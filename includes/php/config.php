<?php
$config = json_decode(file_get_contents(__DIR__ . '/../../config.json'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die("Error parsing JSON: " . json_last_error_msg());
}

if (!defined('BOT_API_DEBUG')) {
    define('BOT_API_DEBUG', true);
}
?>