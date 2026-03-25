<?php

function readCodeFile($messageId, $directory) {
    $messageId = trim((string) $messageId);
    $filename = $directory . $messageId . '.txt';

    if (is_file($filename)) {
        $file = fopen($filename, 'r');
        if ($file !== false) {
            $code = fread($file, filesize($filename));
            fclose($file);
            @unlink($filename);
            echo $code;
        }
    }
}

$script = $_SERVER['SCRIPT_FILENAME'] ?? '';
$isDirect = ($script !== '' && realpath($script) === realpath(__FILE__));

if ($isDirect && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $directory = __DIR__ . '/../client-area/codes/';
    if (isset($_GET['id'])) {
        readCodeFile(trim($_GET['id']), $directory);
    }
    exit;
}

if ($isDirect) {
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'Error: Only POST requests are allowed.';
}
