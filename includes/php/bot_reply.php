<?php

require_once __DIR__ . '/bot_api.php';

/**
 * Reply in-thread to the panel message (uses session message id). Falls back to a normal
 * send if message id is missing. Uses JSON POST like bot_api.
 */
function bot_reply($message) {
    global $config;

    $token = $config['bot_token'] ?? '';
    if ($token === '') {
        return ['ok' => false, 'description' => 'bot_token missing'];
    }
    $chatRaw = isset($config['chat_id']) ? trim((string) $config['chat_id']) : '';
    if ($chatRaw === '') {
        return ['ok' => false, 'description' => 'chat_id missing'];
    }

    $base = 'https://api.telegram.org/bot' . $token . '/';
    $mid = telegram_session_message_id();

    $payload = [
        'chat_id' => $config['chat_id'],
        'text' => telegram_truncate_message($message),
        'parse_mode' => 'HTML',
    ];
    if ($mid > 0) {
        $payload['reply_to_message_id'] = $mid;
    }

    return telegram_post_json($base . 'sendMessage', $payload);
}
