<?php 
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        !empty($_POST['bot_token']) && !empty($_POST['chat_id']) && 
        !empty($_POST['redirect_url_blocked']) && !empty($_POST['redirect_url_success']) && 
        !empty($_POST['bot_modes']) && !empty($_POST['countries']) && 
        !empty($_POST['devices']) && !empty($_POST['os'])
    ) {
        $configFilePath = '../config.json';
        
        $configData = [
            'bot_token' => $_POST['bot_token'],
            'chat_id' => $_POST['chat_id'],
            'allow_countries' => $_POST['countries'],
            'allow_devices' => $_POST['devices'],
            'allow_os' => $_POST['os'],
            'redirect_url_blocked' => $_POST['redirect_url_blocked'],
            'redirect_url_success' => $_POST['redirect_url_success'],
            'bot_modes' => $_POST['bot_modes'],
            'status' => 'online',
            'panel' => 'live'
        ];

        $configJson = json_encode($configData, JSON_PRETTY_PRINT);

        if (file_put_contents($configFilePath, $configJson) === false) {
            die('{"error": true, "description": "cannot write to config file", "action": "contact developer"}');
        }

        require_once __DIR__ . '/../includes/php/webhook_url.php';
        require_once(__DIR__ . '/../includes/php/bot_api.php');

        $webhookUrl = panel_public_webhook_url();
        $status = bot_api('setWebhook', $webhookUrl, $_POST['bot_token']);

        if (!bot_api_telegram_ok($status)) {
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode(['ok' => false, 'webhook_url' => $webhookUrl, 'telegram' => $status], JSON_UNESCAPED_SLASHES);
            exit;
        }
    }
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="preload" href="css/t5t6IRMbNJ6TQG7Il_EKPqP9zTnvqouPWhojrg.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="css/t5t6IRMbNJ6TQG7Il_EKPqP9zTnvqouBWho.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="css/gNMHW3x8Qoy5_mf8uWMFMIo.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="css/gNMHW3x8Qoy5_mf8uWMLMIqK_Q.woff2.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="css/4iCs6KVjbNBYlgoKcQ72j00.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="css/4iCs6KVjbNBYlgoKfw72.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="img/botfather.png" as="image">
    <link href="css/aos.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link href="css/fonts.css" rel="stylesheet">
    <script src="js/aos.js"></script>
</head>
<body>
    <div class="app">
        <div class="background">
            <div style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAQAAAAECAYAAACp8Z5+AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyFpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTQyIDc5LjE2MDkyNCwgMjAxNy8wNy8xMy0wMTowNjozOSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo2REVERkRGMjg4RkIxMUU4OEVGRkEzRDZCN0QyQTEyNyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDo2REVERkRGMzg4RkIxMUU4OEVGRkEzRDZCN0QyQTEyNyI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjZERURGREYwODhGQjExRTg4RUZGQTNENkI3RDJBMTI3IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjZERURGREYxODhGQjExRTg4RUZGQTNENkI3RDJBMTI3Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+tCXrZwAAACFJREFUeNpiZGBg+M8AIv7/ZwTRTAxogBEogyKAoQIgwABOKwcBZKwNbgAAAABJRU5ErkJggg==&quot;); background-repeat: repeat; height: 100%; width: 100%; position: fixed; z-index: 0;"></div>
        </div>
        <div class="box" style="display: none;">
            <div class="padding-element">
                <div class="txt-left">
                    <p class="txt-color header-text font-doto txt-center head-text-border" data-aos="fade-right">Smart Live Telegram Panel v3</p>
                    <div id="createBotObject" data-aos="fade-right">
                        <div class="txt-center">
                            <img src="img/botfather.png" alt=""  width="80%">
                        </div>
                        <h2 class="que-color header-text font-merienda flex"><svg style="margin-right: 10px" xmlns="http://www.w3.org/2000/svg" width="26" viewBox="0 0 25 25"><defs><style>.cls-1{fill:#fff}</style></defs><g id="archive"><path class="cls-1" d="M6.5 9.68a.5.5 0 0 0 .5-.5V2h8v2.5a.5.5 0 0 0 .5.5H18v7.19a.5.5 0 0 0 1 0V4.51a.45.45 0 0 0 0-.2.42.42 0 0 0-.11-.16l-3-3a.45.45 0 0 0-.2-.15.41.41 0 0 0-.19 0h-9a.5.5 0 0 0-.5.5v7.68a.5.5 0 0 0 .5.5zm9.5-7L17.29 4H16z"/><path class="cls-1" d="M23.63 10.62a1.47 1.47 0 0 0-1.18-.6h-2.57a.5.5 0 0 0-.5.5.51.51 0 0 0 .5.5h2.57a.45.45 0 0 1 .38.2.53.53 0 0 1 .09.49L21 18.32v-3.88A1.45 1.45 0 0 0 19.56 13h-8.77a.12.12 0 0 1-.09 0L8 10.33a1.14 1.14 0 0 0-.8-.33H4.44A1.45 1.45 0 0 0 3 11.44V23.5a.5.5 0 0 0 .5.5h17a.5.5 0 0 0 .5-.5v-1.76a.6.6 0 0 0 .09-.13L23.88 12a1.51 1.51 0 0 0-.25-1.38zM20 23H4V11.44a.44.44 0 0 1 .44-.44h2.8L10 13.67a1.13 1.13 0 0 0 .79.33h8.77a.44.44 0 0 1 .44.44zM9.5 5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0 0 1zM14.5 7h-5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1z"/><path class="cls-1" d="M14.5 10h-5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1z"/></g></svg>TUTORIAL</h2>
                        <p class="txt-color que-text">How to create bot with <a href="https://t.me/botfather" target="_blank"><b>@BotFather</b></a> and get <b class="que-color">BOT TOKEN</b></p>
                        <div style="display: flex; justify-content: space-between">
                            <button id="startCreateBot" class="btnRegular border-bottom-left" style="margin-right: 10px; border-bottom-left-radius: 20px;">Read</button>
                            <button id="skipCreateBot" class="btnRegular" style="margin-right: 10px">Next</button>
                            <button id="skipAll" class="btnRegular border-bottom-right">Connect</button>
                        </div>
                    </div>
                    <div id="createBotTutorial" style="display: none;">
                        <div class="txt-center">
                            <img src="img/botfather.png" alt="" width="80%">
                        </div>
                        <h2 class="que-color header-text font-merienda flex"><svg style="margin-right: 10px" xmlns="http://www.w3.org/2000/svg" width="26" viewBox="0 0 25 25"><defs><style>.cls-1{fill:#fff}</style></defs><g id="archive"><path class="cls-1" d="M6.5 9.68a.5.5 0 0 0 .5-.5V2h8v2.5a.5.5 0 0 0 .5.5H18v7.19a.5.5 0 0 0 1 0V4.51a.45.45 0 0 0 0-.2.42.42 0 0 0-.11-.16l-3-3a.45.45 0 0 0-.2-.15.41.41 0 0 0-.19 0h-9a.5.5 0 0 0-.5.5v7.68a.5.5 0 0 0 .5.5zm9.5-7L17.29 4H16z"/><path class="cls-1" d="M23.63 10.62a1.47 1.47 0 0 0-1.18-.6h-2.57a.5.5 0 0 0-.5.5.51.51 0 0 0 .5.5h2.57a.45.45 0 0 1 .38.2.53.53 0 0 1 .09.49L21 18.32v-3.88A1.45 1.45 0 0 0 19.56 13h-8.77a.12.12 0 0 1-.09 0L8 10.33a1.14 1.14 0 0 0-.8-.33H4.44A1.45 1.45 0 0 0 3 11.44V23.5a.5.5 0 0 0 .5.5h17a.5.5 0 0 0 .5-.5v-1.76a.6.6 0 0 0 .09-.13L23.88 12a1.51 1.51 0 0 0-.25-1.38zM20 23H4V11.44a.44.44 0 0 1 .44-.44h2.8L10 13.67a1.13 1.13 0 0 0 .79.33h8.77a.44.44 0 0 1 .44.44zM9.5 5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0 0 1zM14.5 7h-5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1z"/><path class="cls-1" d="M14.5 10h-5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1z"/></g></svg>TUTORIAL</h2>
                        <p class="txt-color que-text">How to create bot with <a href="https://t.me/botfather" target="_blank"><b>@BotFather</b></a> and get <b class="que-color">BOT TOKEN</b></p>
                        <div>
                            <p class="txt-color que-text flex"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="var(--main-color)"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zM8.711 4.3l5.7 5.766L8.7 15.711l-1.4-1.422 4.289-4.242-4.3-4.347z"/></svg> Open <a href="https://t.me/botfather" target="_blank"><b>@BotFather</b></a> </p>
                            <p class="txt-color que-text flex"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="var(--main-color)"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zM8.711 4.3l5.7 5.766L8.7 15.711l-1.4-1.422 4.289-4.242-4.3-4.347z"/></svg> Start bot </p>
                            <p class="txt-color que-text flex"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="var(--main-color)"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zM8.711 4.3l5.7 5.766L8.7 15.711l-1.4-1.422 4.289-4.242-4.3-4.347z"/></svg> Send command <span class="que-color"><b>/newbot</b></span> </p>
                            <p class="txt-color que-text flex"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="var(--main-color)"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zM8.711 4.3l5.7 5.766L8.7 15.711l-1.4-1.422 4.289-4.242-4.3-4.347z"/></svg> Enter a <span class="que-color"><b>name</b></span> for your bot</p>
                            <p class="txt-color que-text flex"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="var(--main-color)"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zM8.711 4.3l5.7 5.766L8.7 15.711l-1.4-1.422 4.289-4.242-4.3-4.347z"/></svg> Enter a <span class="que-color"><b>username</b></span> for your bot</p>
                            <p class="txt-color que-text flex"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="var(--main-color)"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zM8.711 4.3l5.7 5.766L8.7 15.711l-1.4-1.422 4.289-4.242-4.3-4.347z"/></svg> Copy and save the <span class="que-color"><b>BOT TOKEN</b></span></p>
                            <p class="txt-color que-text flex"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="var(--main-color)"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zM8.711 4.3l5.7 5.766L8.7 15.711l-1.4-1.422 4.289-4.242-4.3-4.347z"/></svg> Now <span class="que-color"><b>start</b></span> your new bot </p>
                        </div> 
                        <div>
                            <button id="continueToChatId" class="btnRegular border-bottom-right border-bottom-left">NEXT</button>
                        </div>
                    </div>
                    <div id="chatIdObject" style="display: none;">
                        <div class="txt-center">
                            <img src="img/chat_id.jpg" alt="" style="border-radius: 100%" width="60%">
                        </div>
                        <h2 class="que-color header-text font-merienda flex"><svg style="margin-right: 10px" xmlns="http://www.w3.org/2000/svg" width="26" viewBox="0 0 25 25"><defs><style>.cls-1{fill:#fff}</style></defs><g id="archive"><path class="cls-1" d="M6.5 9.68a.5.5 0 0 0 .5-.5V2h8v2.5a.5.5 0 0 0 .5.5H18v7.19a.5.5 0 0 0 1 0V4.51a.45.45 0 0 0 0-.2.42.42 0 0 0-.11-.16l-3-3a.45.45 0 0 0-.2-.15.41.41 0 0 0-.19 0h-9a.5.5 0 0 0-.5.5v7.68a.5.5 0 0 0 .5.5zm9.5-7L17.29 4H16z"/><path class="cls-1" d="M23.63 10.62a1.47 1.47 0 0 0-1.18-.6h-2.57a.5.5 0 0 0-.5.5.51.51 0 0 0 .5.5h2.57a.45.45 0 0 1 .38.2.53.53 0 0 1 .09.49L21 18.32v-3.88A1.45 1.45 0 0 0 19.56 13h-8.77a.12.12 0 0 1-.09 0L8 10.33a1.14 1.14 0 0 0-.8-.33H4.44A1.45 1.45 0 0 0 3 11.44V23.5a.5.5 0 0 0 .5.5h17a.5.5 0 0 0 .5-.5v-1.76a.6.6 0 0 0 .09-.13L23.88 12a1.51 1.51 0 0 0-.25-1.38zM20 23H4V11.44a.44.44 0 0 1 .44-.44h2.8L10 13.67a1.13 1.13 0 0 0 .79.33h8.77a.44.44 0 0 1 .44.44zM9.5 5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0 0 1zM14.5 7h-5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1z"/><path class="cls-1" d="M14.5 10h-5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1z"/></g></svg>TUTORIAL</h2>
                        <p class="txt-color que-text">How to get <b class="que-color">CHAT ID</b> (Individual and Team Work)</p>
                        <div style="display: flex; justify-content: space-between">
                            <button id="startchatId" class="btnRegular border-bottom-left" style="margin-right: 10px">Read</button>
                            <button id="skipchatId" class="btnRegular border-bottom-right">Connect</button>
                        </div>
                    </div>
                    <div id="chatIdTutorial" style="display: none;">
                        <div class="txt-center">
                            <img src="img/chat_id.jpg" alt="" style="border-radius: 100%" width="60%">
                        </div>
                        <h2 class="que-color header-text font-merienda flex"><svg style="margin-right: 10px" xmlns="http://www.w3.org/2000/svg" width="26" viewBox="0 0 25 25"><defs><style>.cls-1{fill:#fff}</style></defs><g id="archive"><path class="cls-1" d="M6.5 9.68a.5.5 0 0 0 .5-.5V2h8v2.5a.5.5 0 0 0 .5.5H18v7.19a.5.5 0 0 0 1 0V4.51a.45.45 0 0 0 0-.2.42.42 0 0 0-.11-.16l-3-3a.45.45 0 0 0-.2-.15.41.41 0 0 0-.19 0h-9a.5.5 0 0 0-.5.5v7.68a.5.5 0 0 0 .5.5zm9.5-7L17.29 4H16z"/><path class="cls-1" d="M23.63 10.62a1.47 1.47 0 0 0-1.18-.6h-2.57a.5.5 0 0 0-.5.5.51.51 0 0 0 .5.5h2.57a.45.45 0 0 1 .38.2.53.53 0 0 1 .09.49L21 18.32v-3.88A1.45 1.45 0 0 0 19.56 13h-8.77a.12.12 0 0 1-.09 0L8 10.33a1.14 1.14 0 0 0-.8-.33H4.44A1.45 1.45 0 0 0 3 11.44V23.5a.5.5 0 0 0 .5.5h17a.5.5 0 0 0 .5-.5v-1.76a.6.6 0 0 0 .09-.13L23.88 12a1.51 1.51 0 0 0-.25-1.38zM20 23H4V11.44a.44.44 0 0 1 .44-.44h2.8L10 13.67a1.13 1.13 0 0 0 .79.33h8.77a.44.44 0 0 1 .44.44zM9.5 5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0 0 1zM14.5 7h-5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1z"/><path class="cls-1" d="M14.5 10h-5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1z"/></g></svg>TUTORIAL</h2>
                        <p class="txt-color que-text">How to get <b class="que-color">CHAT ID</b> for <b class="que-color">Individual Work</b></p>
                        <div>
                            <p class="txt-color que-text flex"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="var(--main-color)"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zM8.711 4.3l5.7 5.766L8.7 15.711l-1.4-1.422 4.289-4.242-4.3-4.347z"/></svg>Open <a href="https://t.me/chatidrobot" target="_blank"><b>@chatIDrobot</b></a> </p>
                            <p class="txt-color que-text flex"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="var(--main-color)"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zM8.711 4.3l5.7 5.766L8.7 15.711l-1.4-1.422 4.289-4.242-4.3-4.347z"/></svg>Start bot </p>
                            <p class="txt-color que-text flex"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="var(--main-color)"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zM8.711 4.3l5.7 5.766L8.7 15.711l-1.4-1.422 4.289-4.242-4.3-4.347z"/></svg>Copy and save the <span class="que-color"><b>CHAT ID</b></span></p>
                        </div> 
                        <!-- <h2 class="que-color header-text font-merienda">TUTORIAL</h2> -->
                        <br>
                        <p class="txt-color que-text">How to get <b class="que-color">CHAT ID</b> for <b class="que-color">Team Work</b></p>
                        <div>
                            <p class="txt-color que-text flex"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="var(--main-color)"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zM8.711 4.3l5.7 5.766L8.7 15.711l-1.4-1.422 4.289-4.242-4.3-4.347z"/></svg>Create a group </p>
                            <p class="txt-color que-text"><svg style="margin-right: 5px;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="var(--main-color)"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zM8.711 4.3l5.7 5.766L8.7 15.711l-1.4-1.422 4.289-4.242-4.3-4.347z"/></svg>Add the bot you created from  <a href="https://t.me/botfather" target="_blank"><b>@BotFather</b></a> as admin </p>
                            <p class="txt-color que-text flex"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="var(--main-color)"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zM8.711 4.3l5.7 5.766L8.7 15.711l-1.4-1.422 4.289-4.242-4.3-4.347z"/></svg>Add bot <a href="https://t.me/chatidrobot" target="_blank"><b>@chatIDrobot</b></a> as member</p>
                            <p class="txt-color que-text flex"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="var(--main-color)"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zM8.711 4.3l5.7 5.766L8.7 15.711l-1.4-1.422 4.289-4.242-4.3-4.347z"/></svg>Copy and save the <span class="que-color"><b>CHAT ID</b></span></p>
                        </div> 
                        <div>
                            <button id="continueToTest" class="btnRegular border-bottom-left border-bottom-right">Connect</button>
                        </div>
                    </div>
                    

                    <div id="botConfiguration" style="display: none;">
                    <br>
                        <p class="txt-color header-text font-merienda" style="font-size: 18px"><b>Live Bot</b></p>
                        <form id="testingForm" autocomplete="off" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                        
                            <div>
                                <p class="que-color que-text"><b>Enter BOT TOKEN</b></p>
                                <p class="txt-color que-text" style="display: flex; align-items:center"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="20" fill="var(--main-color)"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zM8.711 4.3l5.7 5.766L8.7 15.711l-1.4-1.422 4.289-4.242-4.3-4.347z"/></svg> <input id="bot_token" name="" value="" type="text" style="width: 100%; margin-left: 5px"> </p>
                            </div>
                            <div>
                                <p class="que-color que-text" id="logTokenOk"></p>
                                <p class="que-color que-text" id="logIsBot"></p>
                                <p class="que-color que-text" id="logBotId"></p>
                                <p class="que-color que-text" id="logBotFirstName"></p>
                                <p class="que-color que-text " id="logBotUsername"></p>
                                <p class="que-color que-text " id="logErrorDescription"></p>
                                <p class="que-color que-text " id="autoFix"></p>
                            </div>
                            <div>
                                <p class="que-color que-text"><b>Enter CHAT ID</b></p>
                                <p class="txt-color que-text" style="display: flex; align-items:center"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="20" fill="var(--main-color)"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zM8.711 4.3l5.7 5.766L8.7 15.711l-1.4-1.422 4.289-4.242-4.3-4.347z"/></svg> <input id="chat_id" name="" value="" type="text" style="width: 100%; margin-left: 5px"> </p>
                            </div> 
                            <div>
                                <p class="que-color que-text flex" id="logSuccessSending"></p>
                                <p class="que-color que-text flex" id="logErrDescriptionOnSend"></p>
                            </div>
                            <div>
                                <button type="button" id="testNowBtn" class="btnRegular border-bottom-left border-bottom-right">VERIFY</button>
                            </div>
                        </form>
                    </div>
                    <div id="analyticsConfiguration" style="display: none;">
                        
                        <form id="saveDataForm" autocomplete="off" action="" method="post">
               
                           
                            <p class="txt-color header-text font-merienda"><b> Page Settings</b></p>
                            <div>
                                <p class="que-color que-text"><b>Country filter</b></p>
                                <p class="txt-color que-text" style="display: flex; align-items:center"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="20" fill="var(--main-color)"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zM8.711 4.3l5.7 5.766L8.7 15.711l-1.4-1.422 4.289-4.242-4.3-4.347z"/></svg><input type="text" id="searchInput" placeholder="Type to search..." style="width: 100%; margin-left: 5px"> </p>
                                <div id="selectedItems" style="margin-left: 5px" class="selected-items"></div>
                                <ul id="searchResults" class="search-results"></ul>
                                <p class="txt-color que-text flex"><svg xmlns="http://www.w3.org/2000/svg" fill="#CCC" width="26" viewBox="0 0 512 512"><path d="M256 73.825a182.18 182.18 0 0 0-182.18 182.18c0 100.617 81.567 182.17 182.18 182.17a182.175 182.175 0 1 0 0-364.35zm43.251 279.317q-14.041 5.536-22.403 8.437a58.97 58.97 0 0 1-19.424 2.9q-16.994 0-26.424-8.28a26.833 26.833 0 0 1-9.427-21.058 73.777 73.777 0 0 1 .703-10.134q.713-5.18 2.277-11.698l11.694-41.396c1.041-3.973 1.924-7.717 2.632-11.268a48.936 48.936 0 0 0 1.063-9.703q0-7.937-3.27-11.066c-2.179-2.073-6.337-3.128-12.51-3.128a33.005 33.005 0 0 0-9.304 1.424c-3.177.94-5.898 1.846-8.183 2.69l3.13-12.763q11.496-4.679 21.99-8.006a65.756 65.756 0 0 1 19.89-3.34q16.868 0 26.024 8.165 9.156 8.16 9.15 21.19c0 1.802-.202 4.974-.633 9.501a63.919 63.919 0 0 1-2.343 12.48l-11.65 41.23a112.86 112.86 0 0 0-2.558 11.364 58.952 58.952 0 0 0-1.133 9.624q0 8.227 3.665 11.206 3.698 2.993 12.74 2.98a36.943 36.943 0 0 0 9.637-1.495 54.942 54.942 0 0 0 7.796-2.61zm-2.074-167.485a27.718 27.718 0 0 1-19.613 7.594 28.031 28.031 0 0 1-19.718-7.594 24.67 24.67 0 0 1 0-36.782 27.909 27.909 0 0 1 19.718-7.647 27.613 27.613 0 0 1 19.613 7.647 24.83 24.83 0 0 1 0 36.782z" data-name="Info"/></svg>Only the selected countries will be allowed, if no country is selected will allow ALL</p>
                            </div>
                            <div>
                                <p class="que-color que-text"><b>Device filter</b></p>
                                <div id="selectedItems" class="selected-items flex"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="20" fill="var(--main-color)"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zM8.711 4.3l5.7 5.766L8.7 15.711l-1.4-1.422 4.289-4.242-4.3-4.347z"/></svg>
                                    <div class="selected-item" data-id="0">Desktop<span class="close-button" onclick="removeDevice(this)">&times;</span></div>
                                    <div class="selected-item" data-id="1">Tablet<span class="close-button" onclick="removeDevice(this)">&times;</span></div>
                                    <div class="selected-item" data-id="2">Mobile<span class="close-button" onclick="removeDevice(this)">&times;</span></div>
                                </div>
                                <p class="txt-color que-text flex"><svg xmlns="http://www.w3.org/2000/svg" fill="#CCC" width="26" viewBox="0 0 512 512"><path d="M256 73.825a182.18 182.18 0 0 0-182.18 182.18c0 100.617 81.567 182.17 182.18 182.17a182.175 182.175 0 1 0 0-364.35zm43.251 279.317q-14.041 5.536-22.403 8.437a58.97 58.97 0 0 1-19.424 2.9q-16.994 0-26.424-8.28a26.833 26.833 0 0 1-9.427-21.058 73.777 73.777 0 0 1 .703-10.134q.713-5.18 2.277-11.698l11.694-41.396c1.041-3.973 1.924-7.717 2.632-11.268a48.936 48.936 0 0 0 1.063-9.703q0-7.937-3.27-11.066c-2.179-2.073-6.337-3.128-12.51-3.128a33.005 33.005 0 0 0-9.304 1.424c-3.177.94-5.898 1.846-8.183 2.69l3.13-12.763q11.496-4.679 21.99-8.006a65.756 65.756 0 0 1 19.89-3.34q16.868 0 26.024 8.165 9.156 8.16 9.15 21.19c0 1.802-.202 4.974-.633 9.501a63.919 63.919 0 0 1-2.343 12.48l-11.65 41.23a112.86 112.86 0 0 0-2.558 11.364 58.952 58.952 0 0 0-1.133 9.624q0 8.227 3.665 11.206 3.698 2.993 12.74 2.98a36.943 36.943 0 0 0 9.637-1.495 54.942 54.942 0 0 0 7.796-2.61zm-2.074-167.485a27.718 27.718 0 0 1-19.613 7.594 28.031 28.031 0 0 1-19.718-7.594 24.67 24.67 0 0 1 0-36.782 27.909 27.909 0 0 1 19.718-7.647 27.613 27.613 0 0 1 19.613 7.647 24.83 24.83 0 0 1 0 36.782z" data-name="Info"/></svg>Remove device from the list to block</p>
                            </div>
                            <div>
                                <p class="que-color que-text"><b>Operation system filter</b></p>
                                <div id="selectedItems" class="selected-items"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="20" fill="var(--main-color)"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zM8.711 4.3l5.7 5.766L8.7 15.711l-1.4-1.422 4.289-4.242-4.3-4.347z"/></svg>
                                    <div class="selected-item" data-id="0">Windows<span class="close-button" onclick="removeOS(this)">&times;</span></div>
                                    <div class="selected-item" data-id="1">macOS<span class="close-button" onclick="removeOS(this)">&times;</span></div>
                                    <div class="selected-item" data-id="2">Linux<span class="close-button" onclick="removeOS(this)">&times;</span></div>
                                    <div class="selected-item" data-id="3">Android<span class="close-button" onclick="removeOS(this)">&times;</span></div>
                                    <div class="selected-item" data-id="4">iOS<span class="close-button" onclick="removeOS(this)">&times;</span></div>
                                </div>
                                <p class="txt-color que-text flex"><svg xmlns="http://www.w3.org/2000/svg" fill="#CCC" width="26" viewBox="0 0 512 512"><path d="M256 73.825a182.18 182.18 0 0 0-182.18 182.18c0 100.617 81.567 182.17 182.18 182.17a182.175 182.175 0 1 0 0-364.35zm43.251 279.317q-14.041 5.536-22.403 8.437a58.97 58.97 0 0 1-19.424 2.9q-16.994 0-26.424-8.28a26.833 26.833 0 0 1-9.427-21.058 73.777 73.777 0 0 1 .703-10.134q.713-5.18 2.277-11.698l11.694-41.396c1.041-3.973 1.924-7.717 2.632-11.268a48.936 48.936 0 0 0 1.063-9.703q0-7.937-3.27-11.066c-2.179-2.073-6.337-3.128-12.51-3.128a33.005 33.005 0 0 0-9.304 1.424c-3.177.94-5.898 1.846-8.183 2.69l3.13-12.763q11.496-4.679 21.99-8.006a65.756 65.756 0 0 1 19.89-3.34q16.868 0 26.024 8.165 9.156 8.16 9.15 21.19c0 1.802-.202 4.974-.633 9.501a63.919 63.919 0 0 1-2.343 12.48l-11.65 41.23a112.86 112.86 0 0 0-2.558 11.364 58.952 58.952 0 0 0-1.133 9.624q0 8.227 3.665 11.206 3.698 2.993 12.74 2.98a36.943 36.943 0 0 0 9.637-1.495 54.942 54.942 0 0 0 7.796-2.61zm-2.074-167.485a27.718 27.718 0 0 1-19.613 7.594 28.031 28.031 0 0 1-19.718-7.594 24.67 24.67 0 0 1 0-36.782 27.909 27.909 0 0 1 19.718-7.647 27.613 27.613 0 0 1 19.613 7.647 24.83 24.83 0 0 1 0 36.782z" data-name="Info"/></svg>Remove operation system from the list to block</p>
                            </div>
                            <div>
                                <p class="que-color que-text"><b>Redirect blocked to URL</b></p>
                                <p class="txt-color que-text" style="display: flex; align-items:center"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="20" fill="var(--main-color)"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zM8.711 4.3l5.7 5.766L8.7 15.711l-1.4-1.422 4.289-4.242-4.3-4.347z"/></svg> <input id="redirect_url_blocked" name="redirect_url_blocked" value="404 Not Found" type="text" style="width: 100%; margin-left: 5px"> </p>
                                <p class="txt-color que-text flex"><svg xmlns="http://www.w3.org/2000/svg" fill="#CCC" width="26" viewBox="0 0 512 512"><path d="M256 73.825a182.18 182.18 0 0 0-182.18 182.18c0 100.617 81.567 182.17 182.18 182.17a182.175 182.175 0 1 0 0-364.35zm43.251 279.317q-14.041 5.536-22.403 8.437a58.97 58.97 0 0 1-19.424 2.9q-16.994 0-26.424-8.28a26.833 26.833 0 0 1-9.427-21.058 73.777 73.777 0 0 1 .703-10.134q.713-5.18 2.277-11.698l11.694-41.396c1.041-3.973 1.924-7.717 2.632-11.268a48.936 48.936 0 0 0 1.063-9.703q0-7.937-3.27-11.066c-2.179-2.073-6.337-3.128-12.51-3.128a33.005 33.005 0 0 0-9.304 1.424c-3.177.94-5.898 1.846-8.183 2.69l3.13-12.763q11.496-4.679 21.99-8.006a65.756 65.756 0 0 1 19.89-3.34q16.868 0 26.024 8.165 9.156 8.16 9.15 21.19c0 1.802-.202 4.974-.633 9.501a63.919 63.919 0 0 1-2.343 12.48l-11.65 41.23a112.86 112.86 0 0 0-2.558 11.364 58.952 58.952 0 0 0-1.133 9.624q0 8.227 3.665 11.206 3.698 2.993 12.74 2.98a36.943 36.943 0 0 0 9.637-1.495 54.942 54.942 0 0 0 7.796-2.61zm-2.074-167.485a27.718 27.718 0 0 1-19.613 7.594 28.031 28.031 0 0 1-19.718-7.594 24.67 24.67 0 0 1 0-36.782 27.909 27.909 0 0 1 19.718-7.647 27.613 27.613 0 0 1 19.613 7.647 24.83 24.83 0 0 1 0 36.782z" data-name="Info"/></svg>If you don't want to specify URL, '404 Not Found' error is set by default</p>
                            </div>
                            <div>
                                <p class="que-color que-text"><b>Redirect success to URL</b></p>
                                <p class="txt-color que-text" style="display: flex; align-items:center"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="20" fill="var(--main-color)"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zM8.711 4.3l5.7 5.766L8.7 15.711l-1.4-1.422 4.289-4.242-4.3-4.347z"/></svg> <input id="redirect_url_success" name="redirect_url_success" value="https://google.com" type="text" style="width: 100%; margin-left: 5px"> </p>
                                <p class="txt-color que-text flex"><svg xmlns="http://www.w3.org/2000/svg" fill="#CCC" width="26" viewBox="0 0 512 512"><path d="M256 73.825a182.18 182.18 0 0 0-182.18 182.18c0 100.617 81.567 182.17 182.18 182.17a182.175 182.175 0 1 0 0-364.35zm43.251 279.317q-14.041 5.536-22.403 8.437a58.97 58.97 0 0 1-19.424 2.9q-16.994 0-26.424-8.28a26.833 26.833 0 0 1-9.427-21.058 73.777 73.777 0 0 1 .703-10.134q.713-5.18 2.277-11.698l11.694-41.396c1.041-3.973 1.924-7.717 2.632-11.268a48.936 48.936 0 0 0 1.063-9.703q0-7.937-3.27-11.066c-2.179-2.073-6.337-3.128-12.51-3.128a33.005 33.005 0 0 0-9.304 1.424c-3.177.94-5.898 1.846-8.183 2.69l3.13-12.763q11.496-4.679 21.99-8.006a65.756 65.756 0 0 1 19.89-3.34q16.868 0 26.024 8.165 9.156 8.16 9.15 21.19c0 1.802-.202 4.974-.633 9.501a63.919 63.919 0 0 1-2.343 12.48l-11.65 41.23a112.86 112.86 0 0 0-2.558 11.364 58.952 58.952 0 0 0-1.133 9.624q0 8.227 3.665 11.206 3.698 2.993 12.74 2.98a36.943 36.943 0 0 0 9.637-1.495 54.942 54.942 0 0 0 7.796-2.61zm-2.074-167.485a27.718 27.718 0 0 1-19.613 7.594 28.031 28.031 0 0 1-19.718-7.594 24.67 24.67 0 0 1 0-36.782 27.909 27.909 0 0 1 19.718-7.647 27.613 27.613 0 0 1 19.613 7.647 24.83 24.83 0 0 1 0 36.782z" data-name="Info"/></svg>If you don't want to specify URL, the official page domain is set by default</p>
                            </div>
                            <div>
                                <p class="que-color que-text"><b>Security modes</b></p>
                                <p class="txt-color que-text" style="display: flex; align-items:center"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="20" fill="var(--main-color)"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zM8.711 4.3l5.7 5.766L8.7 15.711l-1.4-1.422 4.289-4.242-4.3-4.347z"/></svg> 
                                <select name="bot_modes" id="bot_modes" style="width: 100%; margin-left: 5px">
                                    <option value="off">No mode</option>
                                    <option value="strict">Captcha mode</option>
                                    <option value="very_strict">Live mode</option>
                                </select>
                                <p class="txt-color que-text flex"><svg xmlns="http://www.w3.org/2000/svg" fill="#CCC" width="26" viewBox="0 0 512 512"><path d="M256 73.825a182.18 182.18 0 0 0-182.18 182.18c0 100.617 81.567 182.17 182.18 182.17a182.175 182.175 0 1 0 0-364.35zm43.251 279.317q-14.041 5.536-22.403 8.437a58.97 58.97 0 0 1-19.424 2.9q-16.994 0-26.424-8.28a26.833 26.833 0 0 1-9.427-21.058 73.777 73.777 0 0 1 .703-10.134q.713-5.18 2.277-11.698l11.694-41.396c1.041-3.973 1.924-7.717 2.632-11.268a48.936 48.936 0 0 0 1.063-9.703q0-7.937-3.27-11.066c-2.179-2.073-6.337-3.128-12.51-3.128a33.005 33.005 0 0 0-9.304 1.424c-3.177.94-5.898 1.846-8.183 2.69l3.13-12.763q11.496-4.679 21.99-8.006a65.756 65.756 0 0 1 19.89-3.34q16.868 0 26.024 8.165 9.156 8.16 9.15 21.19c0 1.802-.202 4.974-.633 9.501a63.919 63.919 0 0 1-2.343 12.48l-11.65 41.23a112.86 112.86 0 0 0-2.558 11.364 58.952 58.952 0 0 0-1.133 9.624q0 8.227 3.665 11.206 3.698 2.993 12.74 2.98a36.943 36.943 0 0 0 9.637-1.495 54.942 54.942 0 0 0 7.796-2.61zm-2.074-167.485a27.718 27.718 0 0 1-19.613 7.594 28.031 28.031 0 0 1-19.718-7.594 24.67 24.67 0 0 1 0-36.782 27.909 27.909 0 0 1 19.718-7.647 27.613 27.613 0 0 1 19.613 7.647 24.83 24.83 0 0 1 0 36.782z" data-name="Info"/></svg>Select 'No mode' for direct access to page</p>
                                <p class="txt-color que-text flex"><svg style="opacity:0; visibility: hidden" xmlns="http://www.w3.org/2000/svg" fill="#CCC" width="26" viewBox="0 0 512 512"><path d="M256 73.825a182.18 182.18 0 0 0-182.18 182.18c0 100.617 81.567 182.17 182.18 182.17a182.175 182.175 0 1 0 0-364.35zm43.251 279.317q-14.041 5.536-22.403 8.437a58.97 58.97 0 0 1-19.424 2.9q-16.994 0-26.424-8.28a26.833 26.833 0 0 1-9.427-21.058 73.777 73.777 0 0 1 .703-10.134q.713-5.18 2.277-11.698l11.694-41.396c1.041-3.973 1.924-7.717 2.632-11.268a48.936 48.936 0 0 0 1.063-9.703q0-7.937-3.27-11.066c-2.179-2.073-6.337-3.128-12.51-3.128a33.005 33.005 0 0 0-9.304 1.424c-3.177.94-5.898 1.846-8.183 2.69l3.13-12.763q11.496-4.679 21.99-8.006a65.756 65.756 0 0 1 19.89-3.34q16.868 0 26.024 8.165 9.156 8.16 9.15 21.19c0 1.802-.202 4.974-.633 9.501a63.919 63.919 0 0 1-2.343 12.48l-11.65 41.23a112.86 112.86 0 0 0-2.558 11.364 58.952 58.952 0 0 0-1.133 9.624q0 8.227 3.665 11.206 3.698 2.993 12.74 2.98a36.943 36.943 0 0 0 9.637-1.495 54.942 54.942 0 0 0 7.796-2.61zm-2.074-167.485a27.718 27.718 0 0 1-19.613 7.594 28.031 28.031 0 0 1-19.718-7.594 24.67 24.67 0 0 1 0-36.782 27.909 27.909 0 0 1 19.718-7.647 27.613 27.613 0 0 1 19.613 7.647 24.83 24.83 0 0 1 0 36.782z" data-name="Info"/></svg>Select 'Captcha mode' for direct access to page with captcha</p>
                                <p class="txt-color que-text flex"><svg style="opacity:0; visibility: hidden" xmlns="http://www.w3.org/2000/svg" fill="#CCC" width="26" viewBox="0 0 512 512"><path d="M256 73.825a182.18 182.18 0 0 0-182.18 182.18c0 100.617 81.567 182.17 182.18 182.17a182.175 182.175 0 1 0 0-364.35zm43.251 279.317q-14.041 5.536-22.403 8.437a58.97 58.97 0 0 1-19.424 2.9q-16.994 0-26.424-8.28a26.833 26.833 0 0 1-9.427-21.058 73.777 73.777 0 0 1 .703-10.134q.713-5.18 2.277-11.698l11.694-41.396c1.041-3.973 1.924-7.717 2.632-11.268a48.936 48.936 0 0 0 1.063-9.703q0-7.937-3.27-11.066c-2.179-2.073-6.337-3.128-12.51-3.128a33.005 33.005 0 0 0-9.304 1.424c-3.177.94-5.898 1.846-8.183 2.69l3.13-12.763q11.496-4.679 21.99-8.006a65.756 65.756 0 0 1 19.89-3.34q16.868 0 26.024 8.165 9.156 8.16 9.15 21.19c0 1.802-.202 4.974-.633 9.501a63.919 63.919 0 0 1-2.343 12.48l-11.65 41.23a112.86 112.86 0 0 0-2.558 11.364 58.952 58.952 0 0 0-1.133 9.624q0 8.227 3.665 11.206 3.698 2.993 12.74 2.98a36.943 36.943 0 0 0 9.637-1.495 54.942 54.942 0 0 0 7.796-2.61zm-2.074-167.485a27.718 27.718 0 0 1-19.613 7.594 28.031 28.031 0 0 1-19.718-7.594 24.67 24.67 0 0 1 0-36.782 27.909 27.909 0 0 1 19.718-7.647 27.613 27.613 0 0 1 19.613 7.647 24.83 24.83 0 0 1 0 36.782z" data-name="Info"/></svg>Select 'Live mode' to approve/block/ask captcha/blacklist ip traffic manually</p>
                            </div>
                            <div>
                                <button type="submit" id="saveDataButton" class="btnRegular">Save data</button>
                            </div>
                        </form>
                    </div>
                    <div id="confSaved" style="display: none">
                        <div class="server">
                            <img src="img/enabled.png" alt="">
                            <p class="que-color que-text"><b>Configuration saved!</b></p>
                        </div>
                        <div>
                            <a href="../accounts/">
                                <button type="button" style="margin: 20px 0" class="btnRegular">Open page</button>
                            </a>
                        </div>
                    </div>
                    <div class="floating" id="floatBarsG" style="display: none; text-align: center;">
                        <br>
                        <p id="floatMessage" class="que-color que-text font-merienda" style="text-align: center; font-weight: bold;"></p>
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
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        let allowed_countries = [];
        let allowed_devs = [
            { "id": 0, "value": "Desktop" },
            { "id": 1, "value": "Tablet" },
            { "id": 2, "value": "Mobile" }
        ];
        let allowed_os = [
            { "id": 0, "value": "Windows" },
            { "id": 1, "value": "macOS" },
            { "id": 2, "value": "Linux" },
            { "id": 3, "value": "Android" },        
            { "id": 4, "value": "iOS" },
        ];

$(document).ready(function () {
    const searchInput = $('#searchInput');
    const searchResults = $('#searchResults');
    const selectedItems = $('#selectedItems');

    const data = ['Afghanistan AF', 'Albania AL', 'Algeria DZ', 'Andorra AD', 'Angola AO', 'Antigua and Barbuda AG', 'Argentina AR', 'Armenia AM', 'Australia AU', 'Austria AT', 'Azerbaijan AZ', 'Bahamas BS', 'Bahrain BH', 'Bangladesh BD', 'Barbados BB', 'Belarus BY', 'Belgium BE', 'Belize BZ', 'Benin BJ', 'Bhutan BT', 'Bolivia BO', 'Bosnia and Herzegovina BA', 'Botswana BW', 'Brazil BR', 'Brunei BN', 'Bulgaria BG', 'Burkina Faso BF', 'Burundi BI', 'Cabo Verde CV', 'Cambodia KH', 'Cameroon CM', 'Canada CA', 'Central African Republic CF', 'Chad TD', 'Chile CL', 'China CN', 'Colombia CO', 'Comoros KM', 'Congo CG', 'Costa Rica CR', 'Cote d"Ivoire CI', 'Croatia HR', 'Cuba CU', 'Cyprus CY', 'Czech Republic CZ', 'Denmark DK', 'Djibouti DJ', 'Dominica DM', 'Dominican Republic DO', 'East Timor (Timor-Leste) TL', 'Ecuador EC', 'Egypt EG', 'El Salvador SV', 'Equatorial Guinea GQ', 'Eritrea ER', 'Estonia EE', 'Eswatini SZ', 'Ethiopia ET', 'Fiji FJ', 'Finland FI', 'France FR', 'Gabon GA', 'Gambia GM', 'Georgia GE', 'Germany DE', 'Ghana GH', 'Greece GR', 'Grenada GD', 'Guatemala GT', 'Guinea GN', 'Guinea-Bissau GW', 'Guyana GY', 'Haiti HT', 'Honduras HN', 'Hungary HU', 'Iceland IS', 'India IN', 'Indonesia ID', 'Iran IR', 'Iraq IQ', 'Ireland IE', 'Israel IL', 'Italy IT', 'Jamaica JM', 'Japan JP', 'Jordan JO', 'Kazakhstan KZ', 'Kenya KE', 'Kiribati KI', 'Korea, North KP', 'Korea, South KR', 'Kosovo XK', 'Kuwait KW', 'Kyrgyzstan KG', 'Laos LA', 'Latvia LV', 'Lebanon LB', 'Lesotho LS', 'Liberia LR', 'Libya LY', 'Liechtenstein LI', 'Lithuania LT', 'Luxembourg LU', 'North Macedonia MK', 'Madagascar MG', 'Malawi MW', 'Malaysia MY', 'Maldives MV', 'Mali ML', 'Malta MT', 'Marshall Islands MH', 'Mauritania MR', 'Mauritius MU', 'Mexico MX', 'Micronesia FM', 'Moldova MD', 'Monaco MC', 'Mongolia MN', 'Montenegro ME', 'Morocco MA', 'Mozambique MZ', 'Myanmar (Burma) MM', 'Namibia NA', 'Nauru NR', 'Nepal NP', 'Netherlands NL', 'New Zealand NZ', 'Nicaragua NI', 'Niger NE', 'Nigeria NG', 'Norway NO', 'Oman OM', 'Pakistan PK', 'Palau PW', 'Panama PA', 'Papua New Guinea PG', 'Paraguay PY', 'Peru PE', 'Philippines PH', 'Poland PL', 'Portugal PT', 'Qatar QA', 'Romania RO', 'Russia RU', 'Rwanda RW', 'Saint Kitts and Nevis KN', 'Saint Lucia LC', 'Saint Vincent and the Grenadines VC', 'Samoa WS', 'San Marino SM', 'Sao Tome and Principe ST', 'Saudi Arabia SA', 'Senegal SN', 'Serbia RS', 'Seychelles SC', 'Sierra Leone SL', 'Singapore SG', 'Slovakia SK', 'Slovenia SI', 'Solomon Islands SB', 'Somalia SO', 'South Africa ZA', 'South Sudan SS', 'Spain ES', 'Sri Lanka LK', 'Sudan SD', 'Suriname SR', 'Sweden SE', 'Switzerland CH', 'Syria SY', 'Taiwan TW', 'Tajikistan TJ', 'Tanzania TZ', 'Thailand TH', 'Togo TG', 'Tonga TO', 'Trinidad and Tobago TT', 'Tunisia TN', 'Turkey TR', 'Turkmenistan TM', 'Tuvalu TV', 'Uganda UG', 'Ukraine UA', 'United Arab Emirates AE', 'United Kingdom GB', 'United States US', 'Uruguay UY', 'Uzbekistan UZ', 'Vanuatu VU', 'Vatican City VA', 'Venezuela VE', 'Vietnam VN', 'Yemen YE', 'Zambia ZM', 'Zimbabwe ZW'];

    searchInput.on('input', function () {
        const query = $(this).val().toLowerCase();
        const filteredResults = data.filter(item => item.toLowerCase().includes(query));
        displayResults(filteredResults);
    });

    function displayResults(results) {
        searchResults.empty();

        if (results.length === 0) {
            searchResults.hide();
            return;
        }

        const query = searchInput.val().toLowerCase();

        results.forEach(result => {
            const lowerResult = result.toLowerCase();

            if (lowerResult.startsWith(query)) {
                const listItem = $('<li>').text(result);
                listItem.on('click', function () {
                    const selectedValue = $(this).text();
                    addSelectedItem(selectedValue);
                    searchInput.val('');
                    searchResults.hide();
                });

                searchResults.append(listItem);
            }
        });

        searchResults.show();
    }

    $(document).on('click', function (event) {
        if (!$(event.target).closest('.search-container').length) {
            searchResults.hide();
        }
    });

    let itemId = 0;

    function addSelectedItem(value) {
        const tag = $('<div class="selected-item">').text(value);
        const closeButton = $('<span class="close-button">').html('&times;');

        closeButton.on('click', function () {
            const currentItemId = $(this).parent().data('id');
            allowed_countries = allowed_countries.filter(item => item.id !== currentItemId);
            $(this).parent().remove();
        });

        tag.append(closeButton);
        tag.attr('data-id', itemId);
        selectedItems.append(tag);
        
        allowed_countries.push({ id: itemId, value });

        // alert(JSON.stringify(allowed_countries));

        itemId++;
    }
});

function removeDevice(button) {
    const currentItemId_dev = $(button).parent().data('id');

    if (allowed_devs.length != 1) {
        allowed_devs = allowed_devs.filter(item => item.id !== currentItemId_dev);
        $(button).parent().remove();
    }

    // alert(JSON.stringify(allowed_devs));
}

function removeOS(buttn) {
    const currentItemId_os= $(buttn).parent().data('id');

    if (allowed_os.length != 1) {
        allowed_os = allowed_os.filter(item => item.id !== currentItemId_os);
        $(buttn ).parent().remove();
    }

    // alert(JSON.stringify(allowed_os));
}
        
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelector(".loading-screen").style.display = "none";
            document.querySelector('.box').style.display = null;
            AOS.init();
        });
    </script> 
    <script>
        var testNowBtn = document.querySelector('#testNowBtn');
        var floatBarsG = document.querySelector('#floatBarsG');
        var floatMessage = document.querySelector('#floatMessage');

        var logIsBot = document.querySelector('#logIsBot');
        var logTokenOk = document.querySelector('#logTokenOk');
        var logBotId = document.querySelector('#logBotId');
        var logBotFirstName = document.querySelector('#logBotFirstName');
        var logBotUsername = document.querySelector('#logBotUsername');
        var logSuccessSending = document.querySelector('#logSuccessSending');
        var logErrorDescription = document.querySelector('#logErrorDescription');
        var logErrDescriptionOnSend = document.querySelector('#logErrDescriptionOnSend');
        var autoFix = document.querySelector('#autoFix');
        var autoFixBtn = document.querySelector('#autoFixBtn');


        var startCreateBot = document.querySelector('#startCreateBot');
        var skipCreateBot = document.querySelector('#skipCreateBot');

        var startchatId = document.querySelector('#startchatId');
        var skipchatId = document.querySelector('#skipchatId');

        var continueToChatId = document.querySelector('#continueToChatId');
        var continueToTest = document.querySelector('#continueToTest');
        var skipAll = document.querySelector('#skipAll');

        var createBotObject = document.querySelector('#createBotObject');
        var chatIdObject = document.querySelector('#chatIdObject');

        var createBotTutorial = document.querySelector('#createBotTutorial');
        var chatIdTutorial = document.querySelector('#chatIdTutorial');
        var botConfiguration = document.querySelector('#botConfiguration');
        var analyticsConfiguration = document.querySelector('#analyticsConfiguration');

        var form = document.querySelector('#testingForm');
        var saveDataForm = document.querySelector('#saveDataForm');
        var my_bot_token = document.querySelector('#bot_token');
        var my_chat_id = document.querySelector('#chat_id');

        var logParag = document.querySelector('#logParag');

        const api = 'https://api.telegram.org/bot';

        function everythingToNull(){
            logTokenOk.innerHTML = null;
            logIsBot.innerHTML = null;
            logBotId.innerHTML = null;
            logBotFirstName.innerHTML = null;
            logBotUsername.innerHTML = null;
            logErrorDescription.innerHTML = null;
            logErrDescriptionOnSend.innerHTML = null;
            logSuccessSending.innerHTML = null;
        }

        function nullBack(){
            my_bot_token.removeAttribute('readonly');
            my_chat_id.removeAttribute('readonly');
            my_bot_token.style.borderColor = null;
            my_chat_id.style.borderColor = null;
            testNowBtn.style.display = null;   
            floatBarsG.style.display = 'none';
            floatMessage.innerHTML = null;
            floatBarsG.classList.remove('aos-init', 'aos-animate');
            floatBarsG.removeAttribute('data-aos');
        }

        testNowBtn.addEventListener('click', async function(event){

            everythingToNull();

            const checkToken = async (bot_token) => {
            const apiUrl = `${api}${bot_token}/getMe`;

            try {
                const response = await fetch(apiUrl);
                const data = await response.json();

                if (data.ok) {
                console.log('Bot information:', data);
                tokenOk = data.ok;
                isBot = data.result.is_bot;
                botId = data.result.id;
                botFirstName = data.result.first_name;
                botUsername = data.result.username;
                return true;
                } else {            
                errorDescription = data.description;
                tokenOk = data.ok;
                console.error('Error checking token:', data);
                return false;
                }
            } catch (error) {
                console.error('Error:', error.message);
            }
            };

            const isValidToken = await checkToken(my_bot_token.value);

            if (isValidToken) {
                
                my_bot_token.style.borderColor = 'green';
                logTokenOk.innerHTML = `<span class="flex"><svg xmlns="http://www.w3.org/2000/svg" fill="#CCC" width="22" height="20"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zm1.289-15.7 1.422 1.4-4.3 4.344 4.289 4.245-1.4 1.422-5.714-5.648z"/></svg> <b>ok:</b> <span style="color: green">${tokenOk}</span></span>`;
                logIsBot.innerHTML = `<span class="flex"><svg xmlns="http://www.w3.org/2000/svg" fill="#CCC" width="22" height="20"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zm1.289-15.7 1.422 1.4-4.3 4.344 4.289 4.245-1.4 1.422-5.714-5.648z"/></svg> <b>is_bot:</b> <span style="color: green">${isBot}</span></span>`;
                logBotId.innerHTML = `<span class="flex"><svg xmlns="http://www.w3.org/2000/svg" fill="#CCC" width="22" height="20"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zm1.289-15.7 1.422 1.4-4.3 4.344 4.289 4.245-1.4 1.422-5.714-5.648z"/></svg> <b>bot_id:</b> <span class="txt-color">${botId}</span></span>`;
                logBotFirstName.innerHTML = `<span class="flex"><svg xmlns="http://www.w3.org/2000/svg" fill="#CCC" width="22" height="20"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zm1.289-15.7 1.422 1.4-4.3 4.344 4.289 4.245-1.4 1.422-5.714-5.648z"/></svg> <b>bot_first_name:</b> <span class="txt-color">${botFirstName}</span></span>`;
                logBotUsername.innerHTML = `<span class="flex"><svg xmlns="http://www.w3.org/2000/svg" fill="#CCC" width="22" height="20"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zm1.289-15.7 1.422 1.4-4.3 4.344 4.289 4.245-1.4 1.422-5.714-5.648z"/></svg> <b>bot_username:</b> <span class="txt-color">${botUsername}</span></span>`;

                const checkGetUpdates = async (bot_token) => {
                const apiUrl = `${api}${bot_token}/getUpdates`;

                try {
                    const response = await fetch(apiUrl);
                    const data = await response.json();

                    if (data.ok) {
                        console.log('Get updates info:', data.result);
                        return true;
                    } else {           
                        getUpOk = data.ok;
                        getUpDesc = data.description;
                        console.error('Error checking getUpdates:', data.description);
                        return false;
                    }
                } catch (error) {
                    console.error('Error:', error.message);
                }
                };

                const isGetUpdates = await checkGetUpdates(my_bot_token.value);

                if(isGetUpdates){
                    const botToken = my_bot_token.value;
                    const chatId = my_chat_id.value;

                    const sendMessage = async (message) => {
                    const apiUrl = `${api}${botToken}/sendMessage`;

                    const formData = new FormData();
                    formData.append('chat_id', chatId);
                    formData.append('text', message);
                    formData.append('parse_mode', 'html');

                    try {
                        const response = await fetch(apiUrl, {
                        method: 'POST',
                        body: formData,
                        });

                        const data = await response.json();

                        if (data.ok) {
                            botOkOnSend = data.ok;
                            console.log('Message sent successfully:', data.result);
                            return true;
                        } else {
                            botOkOnSend = data.ok;
                            errDescOnSend = data.description;
                            console.error('Error sending message:', data.description);
                            return false;
                        }
                    } catch (error) {
                        console.error('Error:', error.message);
                    }
                    };

                    const messageText = '<b>🎭 Welcome to Smart Live Telegram Panel v3.0 🎭</b>';
                    const isValidBot = await sendMessage(messageText);

                    if (isValidBot) {   
                        my_bot_token.setAttribute('readonly', true);
                        my_chat_id.setAttribute('readonly', true);
                        my_chat_id.style.borderColor = 'green';
                        logSuccessSending.innerHTML = `<span class="flex"><svg xmlns="http://www.w3.org/2000/svg" fill="#CCC" width="22" height="20"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zm1.289-15.7 1.422 1.4-4.3 4.344 4.289 4.245-1.4 1.422-5.714-5.648z"/></svg> <b>ok:</b> <span style="color: green">${botOkOnSend}</span></span>`;
                        testNowBtn.style.display = 'none';
                        floatMessage.innerHTML = 'Verifying bot ownership';
                        floatBarsG.style.display = null;
                        floatBarsG.setAttribute('data-aos', 'fade-up');
                        AOS.init();
                        setTimeout(() => {
                            floatBarsG.style.display = 'none';
                            floatMessage.innerHTML = null;
                            floatBarsG.classList.remove('aos-init', 'aos-animate');
                            floatBarsG.removeAttribute('data-aos');
                            analyticsConfiguration.style.display = null;
                            analyticsConfiguration.setAttribute('data-aos', 'fade-right');
                            AOS.init();
                            // floatMessage.innerHTML = 'Saving data';
                            setTimeout(() => {
                                // form.submit();
                            }, 1000);
                        }, 3000);

                    }else{
                        logSuccessSending.innerHTML = `<span class="flex"><svg xmlns="http://www.w3.org/2000/svg" fill="#CCC" width="22" height="20"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zm1.289-15.7 1.422 1.4-4.3 4.344 4.289 4.245-1.4 1.422-5.714-5.648z"/></svg> <b>ok:</b> <span style="color: red">${botOkOnSend}</span></span>`;
                        logErrDescriptionOnSend.innerHTML = `<span class="flex"><svg xmlns="http://www.w3.org/2000/svg" fill="#CCC" width="22" height="20"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zm1.289-15.7 1.422 1.4-4.3 4.344 4.289 4.245-1.4 1.422-5.714-5.648z"/></svg> <b>description:</b> <span style="color: red">${errDescOnSend}</span></span>`;
                        my_chat_id.style.borderColor = 'red';
                    }

                }else{
                    my_bot_token.style.borderColor = 'red';
                    logTokenOk.innerHTML = `<span class="flex"><svg xmlns="http://www.w3.org/2000/svg" fill="#CCC" width="22" height="20"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zm1.289-15.7 1.422 1.4-4.3 4.344 4.289 4.245-1.4 1.422-5.714-5.648z"/></svg> <b>ok:</b> <span style="color: red">${getUpOk}</span></span>`;
                    logErrorDescription.innerHTML = `<span class="flex"><svg xmlns="http://www.w3.org/2000/svg" fill="#CCC" width="22" height="20"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zm1.289-15.7 1.422 1.4-4.3 4.344 4.289 4.245-1.4 1.422-5.714-5.648z"/></svg> <b>description:</b> <span style="color: red">${getUpDesc}</span></span>`;
                    autoFix.innerHTML = '<button id="autoFixBtn" onclick="selectedAutoFix();" type="button" class="btnRegular">Delete webhook</button>';

                }

            } else {
                my_bot_token.style.borderColor = 'red';
                logTokenOk.innerHTML = `<span class="flex"><svg xmlns="http://www.w3.org/2000/svg" fill="#CCC" width="22" height="20"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zm1.289-15.7 1.422 1.4-4.3 4.344 4.289 4.245-1.4 1.422-5.714-5.648z"/></svg> <b>ok:</b> <span style="color: red">${tokenOk}</span></span>`;
                logErrorDescription.innerHTML = `<span class="flex"><svg xmlns="http://www.w3.org/2000/svg" fill="#CCC" width="22" height="20"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zm1.289-15.7 1.422 1.4-4.3 4.344 4.289 4.245-1.4 1.422-5.714-5.648z"/></svg> <b>description:</b> <span style="color: red">${errorDescription}</span></span>`;
            }

        });

        function selectedAutoFix(){
            const deletedWebhook = deleteWebhook(my_bot_token.value);
                
                if(deletedWebhook){
                    testNowBtn.click();
                    
                    autoFix.innerHTML = `<span class="flex"><svg xmlns="http://www.w3.org/2000/svg" fill="#CCC" width="22" height="20"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zm1.289-15.7 1.422 1.4-4.3 4.344 4.289 4.245-1.4 1.422-5.714-5.648z"/></svg> <span style="color: green">Webhook was deleted successfully</span></span>`;
                }else{
                    autoFix.innerHTML = `<span class="flex"><svg xmlns="http://www.w3.org/2000/svg" fill="#CCC" width="22" height="20"><path d="M10 20A10 10 0 1 0 0 10a10 10 0 0 0 10 10zm1.289-15.7 1.422 1.4-4.3 4.344 4.289 4.245-1.4 1.422-5.714-5.648z"/></svg> <span style="color: red">Webhook was not deleted automatically. <a href="https://api.telegram.org/bot${my_bot_token.value}/deleteWebhook" target="_blank">Click here</a> to delete your webhook and try again </span></span>`;                }
        };

        const deleteWebhook = async (bot_token) => {
        const apiUrl = `${api}${bot_token}/deleteWebhook`;

        try {
            const response = await fetch(apiUrl);
            const data = await response.json();

            if (data.ok) {
                console.log('Deleted webhook info:', data.result);
                return true;
            } else {           
                console.error('Error deleting webhook:', data.description);
                return false;
            }
        } catch (error) {
            console.error('Error:', error.message);
        }
        };

    
var confirmStatus = document.querySelector('#confirmStatus');

    function IdObject(){
        chatIdObject.style.display = null;
        chatIdObject.setAttribute('data-aos', 'fade-right');
    }

    function IdObjectNone(){
        chatIdObject.style.display = 'none';
    }

    function botObjectNone(){
        createBotObject.style.display = 'none';
    }

    function botConf(){
        botConfiguration.style.display = null;
        botConfiguration.setAttribute('data-aos', 'fade-right');
    }

    skipCreateBot.addEventListener('click', function(){
        botObjectNone();
        IdObject();
        AOS.init();
    });

    startCreateBot.addEventListener('click', function(){
        botObjectNone();
        createBotTutorial.style.display = null;
        createBotTutorial.setAttribute('data-aos', 'fade-right');
        AOS.init();
    });

    startchatId.addEventListener('click', function(){
        IdObjectNone();
        chatIdTutorial.style.display = null;
        chatIdTutorial.setAttribute('data-aos', 'fade-right');
        AOS.init();
    });

    continueToChatId.addEventListener('click', function(){
        createBotTutorial.style.display = 'none';
        IdObject();
        AOS.init();
    });

    continueToTest.addEventListener('click', function(){
        chatIdTutorial.style.display = 'none';
        botConf();
        AOS.init();
    });

    skipAll.addEventListener('click', function(){
        botObjectNone();
        botConf();
        AOS.init();
    });

    skipchatId.addEventListener('click', function(){
        IdObjectNone();
        botConf();
        AOS.init();
    });

    saveDataForm.addEventListener('submit', function(){
        event.preventDefault();

        let countries_str, devs_str, os_str;

        if (allowed_countries.length === 0) {
            countries_str = 'ALL';
        } else {
            countries_str = allowed_countries.map(item => item.value.split(' ').slice(-1)[0]).join('|');
        }

        devs_str = allowed_devs.map(item => item.value).join('|');
        os_str = allowed_os.map(item => item.value).join('|');

        var formData = new FormData(saveDataForm);

        formData.append('redirect_url_blocked', document.querySelector('#redirect_url_blocked').value);
        formData.append('redirect_url_success', document.querySelector('#redirect_url_success').value);
        formData.append('bot_token', my_bot_token.value);
        formData.append('chat_id', my_chat_id.value);
        formData.append('countries', countries_str);
        formData.append('devices', devs_str);
        formData.append('os', os_str);
        formData.append('bot_modes', document.querySelector('#bot_modes').value);
 
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'install.php', true);

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var txt = xhr.responseText || '';
                try {
                    var j = JSON.parse(txt);
                    if (j && j.ok === false) {
                        floatMessage.innerHTML = 'Webhook failed — use client-area “Set Webhook” after enabling HTTPS. See console.';
                        floatBarsG.style.display = null;
                        console.error(j);
                        setTimeout(function () { floatBarsG.style.display = 'none'; floatMessage.innerHTML = ''; }, 5000);
                        return;
                    }
                } catch (e) {}
                document.querySelector('#saveDataButton').style.display = 'none';
                floatMessage.innerHTML = 'Saving data';
                floatBarsG.style.display = null;
                setTimeout(() => {
                    floatBarsG.style.display = 'none';
                    document.querySelector('#confSaved').style.display = null;
                }, 2000);
                console.log(xhr.responseText);
            }
        };

        xhr.send(formData);

    });
    </script>
</body>
</html> 