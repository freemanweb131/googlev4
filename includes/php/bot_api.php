<?php

/**
 * Telegram Bot API — JSON POST + fallbacks for reliable inline keyboards.
 *
 * Common failure modes addressed:
 * - Oversized message text (>4096) — truncated
 * - callback_data > 64 bytes — truncated (byte-safe UTF-8)
 * - Invalid HTML in parse_mode — retry without parse_mode / plain text
 * - Combined editMessageText + reply_markup rejection — split: edit text, then editMessageReplyMarkup
 * - "message is not modified" — treated as success (non-fatal)
 * - cURL timeouts — connect + total timeouts set
 *
 * Debug: set env BOT_API_DEBUG=1 or define('BOT_API_DEBUG', true)
 */

function telegram_truncate_utf8_bytes($str, $maxBytes) {
    $str = (string) $str;
    if (strlen($str) <= $maxBytes) {
        return $str;
    }
    $s = substr($str, 0, $maxBytes);
    while (strlen($s) > 0 && (ord($s[strlen($s) - 1]) & 0xC0) === 0x80) {
        $s = substr($s, 0, -1);
    }
    return $s;
}

function telegram_truncate_message($text, $max = 4096) {
    if (!is_string($text)) {
        return '';
    }
    if (function_exists('mb_strlen') && mb_strlen($text, 'UTF-8') <= $max) {
        return $text;
    }
    if (!function_exists('mb_strlen') || strlen($text) <= $max) {
        return strlen($text) > $max ? substr($text, 0, $max) : $text;
    }
    return mb_substr($text, 0, $max - 1, 'UTF-8') . '…';
}

function telegram_sanitize_reply_markup($keyboard) {
    if (!is_array($keyboard) || !isset($keyboard['inline_keyboard'])) {
        return $keyboard;
    }
    foreach ($keyboard['inline_keyboard'] as &$row) {
        if (!is_array($row)) {
            continue;
        }
        foreach ($row as &$btn) {
            if (!is_array($btn) || !isset($btn['callback_data'])) {
                continue;
            }
            $cb = (string) $btn['callback_data'];
            $cb = str_replace("\0", '', $cb);
            if (strlen($cb) > 64) {
                // Preserve the "requestId + space + command" structure as much as possible.
                // webhook.php relies on splitting by whitespace into exactly 2 parts.
                $spacePos = strpos($cb, ' ');
                if ($spacePos !== false && $spacePos > 0 && $spacePos < 64) {
                    $left = telegram_truncate_utf8_bytes(substr($cb, 0, $spacePos), $spacePos);
                    $right = substr($cb, $spacePos + 1);
                    $leftBytes = strlen($left);
                    $rightMax = 64 - $leftBytes - 1; // -1 for the space
                    if ($rightMax > 0) {
                        $right = telegram_truncate_utf8_bytes($right, $rightMax);
                        $btn['callback_data'] = $left . ' ' . $right;
                    } else {
                        $btn['callback_data'] = telegram_truncate_utf8_bytes($cb, 64);
                    }
                } else {
                    $btn['callback_data'] = telegram_truncate_utf8_bytes($cb, 64);
                }
            } else {
                $btn['callback_data'] = $cb;
            }
        }
    }
    unset($row, $btn);
    return $keyboard;
}

function telegram_plain_text_from_html($html) {
    $html = str_ireplace(['<br>', '<br/>', '<br />'], "\n", $html);
    $plain = strip_tags($html);
    return telegram_truncate_message(trim(preg_replace('/[ \t]+/', ' ', preg_replace('/\s+/u', "\n", $plain))));
}

/**
 * Telegram returns ok:false with description "Bad Request: message is not modified" when
 * the new content matches the old — treat as success for callers.
 */
function telegram_normalize_telegram_response($r) {
    if (!is_array($r)) {
        return ['ok' => false, 'description' => 'invalid telegram response'];
    }
    if (!empty($r['ok'])) {
        return $r;
    }
    $desc = isset($r['description']) ? (string) $r['description'] : '';
    if (stripos($desc, 'message is not modified') !== false) {
        return array_merge($r, ['ok' => true, 'telegram_noop' => true]);
    }
    return $r;
}

/**
 * Use after any bot_api / telegram_post_json result. true means success or harmless noop.
 */
function bot_api_telegram_ok($r) {
    $r = telegram_normalize_telegram_response(is_array($r) ? $r : []);
    return !empty($r['ok']);
}

/** Session stores panel message id as messageid (legacy: msg_id). */
function telegram_session_message_id() {
    if (!empty($_SESSION['messageid'])) {
        return (int) $_SESSION['messageid'];
    }
    if (!empty($_SESSION['msg_id'])) {
        return (int) $_SESSION['msg_id'];
    }
    return 0;
}

function telegram_post_json($url, array $payload) {
    $flags = JSON_UNESCAPED_UNICODE;
    if (defined('JSON_INVALID_UTF8_SUBSTITUTE')) {
        $flags |= JSON_INVALID_UTF8_SUBSTITUTE;
    }
    $json = json_encode($payload, $flags);
    if ($json === false) {
        return telegram_normalize_telegram_response(['ok' => false, 'description' => 'json_encode: ' . json_last_error_msg()]);
    }

    if (getenv('BOT_API_DEBUG') === '1' || (defined('BOT_API_DEBUG') && BOT_API_DEBUG)) {
        error_log('[telegram_post_json] ' . $url . ' ' . $json);
    }

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json; charset=UTF-8'],
        CURLOPT_POSTFIELDS => $json,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CONNECTTIMEOUT => 25,
        CURLOPT_TIMEOUT => 60,
    ]);
    $response = curl_exec($ch);
    $errno = curl_errno($ch);
    $cerr = curl_error($ch);
    curl_close($ch);
    if ($errno) {
        return telegram_normalize_telegram_response(['ok' => false, 'description' => 'curl ' . $errno . ': ' . $cerr]);
    }
    $decoded = json_decode($response, true);
    if (!is_array($decoded)) {
        return telegram_normalize_telegram_response(['ok' => false, 'description' => 'invalid JSON from Telegram']);
    }
    return telegram_normalize_telegram_response($decoded);
}

function bot_api_edit_message_text_robust($config, $base, $message, $keyboard) {
    $chatRaw = isset($config['chat_id']) ? trim((string) $config['chat_id']) : '';
    if ($chatRaw === '') {
        return ['ok' => false, 'description' => 'config chat_id missing'];
    }
    $chatId = $config['chat_id'];

    $textHtml = telegram_truncate_message($message);
    $markup = null;
    if (is_array($keyboard) && isset($keyboard['inline_keyboard'])) {
        $markup = telegram_sanitize_reply_markup($keyboard);
    } elseif (is_string($keyboard) && $keyboard !== '') {
        $decoded = json_decode($keyboard, true);
        if (is_array($decoded) && isset($decoded['inline_keyboard'])) {
            $markup = telegram_sanitize_reply_markup($decoded);
        }
    }

    $mid = telegram_session_message_id();
    // If we lost the message_id (session cleared / redirect mismatch), don't leave the UI without buttons.
    // Fall back to sendMessage and update $_SESSION['messageid'].
    if ($mid < 1) {
        $payload = [
            'chat_id' => $chatId,
            'text' => $textHtml,
            'parse_mode' => 'HTML',
        ];
        if ($markup !== null) {
            $payload['reply_markup'] = $markup;
        }
        $send = telegram_post_json($base . 'sendMessage', $payload);
        if (bot_api_telegram_ok($send) && !empty($send['result']['message_id'])) {
            $_SESSION['messageid'] = (int) $send['result']['message_id'];
            return $send;
        }

        // HTML parse failed? retry with plain text.
        $plain = telegram_plain_text_from_html($message);
        $payloadPlain = [
            'chat_id' => $chatId,
            'text' => $plain,
        ];
        if ($markup !== null) {
            $payloadPlain['reply_markup'] = $markup;
        }
        $sendPlain = telegram_post_json($base . 'sendMessage', $payloadPlain);
        if (bot_api_telegram_ok($sendPlain) && !empty($sendPlain['result']['message_id'])) {
            $_SESSION['messageid'] = (int) $sendPlain['result']['message_id'];
            return $sendPlain;
        }

        return bot_api_telegram_ok($sendPlain) ? $sendPlain : $send;
    }

    // A) HTML + optional markup
    $payloadA = [
        'chat_id' => $chatId,
        'message_id' => $mid,
        'text' => $textHtml,
        'parse_mode' => 'HTML',
    ];
    if ($markup !== null) {
        $payloadA['reply_markup'] = $markup;
    }
    $r = telegram_post_json($base . 'editMessageText', $payloadA);
    if (bot_api_telegram_ok($r)) {
        return $r;
    }
    error_log('[bot_api] editMessageText A failed: ' . json_encode($r));

    // B) Plain text (no parse_mode) + optional markup — fixes bad HTML entities
    $plain = telegram_plain_text_from_html($message);
    $payloadB = [
        'chat_id' => $chatId,
        'message_id' => $mid,
        'text' => $plain,
    ];
    if ($markup !== null) {
        $payloadB['reply_markup'] = $markup;
    }
    $rB = telegram_post_json($base . 'editMessageText', $payloadB);
    if (bot_api_telegram_ok($rB)) {
        return $rB;
    }
    error_log('[bot_api] editMessageText B failed: ' . json_encode($rB));

    // C) Text only, then reply markup in a second call (most reliable for stubborn clients)
    $rC = telegram_post_json($base . 'editMessageText', [
        'chat_id' => $chatId,
        'message_id' => $mid,
        'text' => $plain,
    ]);
    if (bot_api_telegram_ok($rC) && $markup !== null) {
        $rD = telegram_post_json($base . 'editMessageReplyMarkup', [
            'chat_id' => $chatId,
            'message_id' => $mid,
            'reply_markup' => $markup,
        ]);
        if (bot_api_telegram_ok($rD)) {
            return $rD;
        }
        error_log('[bot_api] editMessageReplyMarkup failed: ' . json_encode($rD));
        return $rC;
    }

    return bot_api_telegram_ok($rC) ? $rC : $rB;
}

function bot_api($method, $message, $keyboard) {
    global $config;

    $token = $config['bot_token'] ?? '';
    $base = 'https://api.telegram.org/bot' . $token . '/';

    switch ($method) {
        case 'sendMessage': {
            $chatRaw = isset($config['chat_id']) ? trim((string) $config['chat_id']) : '';
            if ($chatRaw === '') {
                return ['ok' => false, 'description' => 'config chat_id missing'];
            }
            $payload = [
                'chat_id' => $config['chat_id'],
                'text' => telegram_truncate_message($message),
                'parse_mode' => 'HTML',
            ];
            if (is_array($keyboard) && isset($keyboard['inline_keyboard'])) {
                $payload['reply_markup'] = telegram_sanitize_reply_markup($keyboard);
            }
            return telegram_post_json($base . 'sendMessage', $payload);
        }

        case 'editMessageText':
            return bot_api_edit_message_text_robust($config, $base, $message, $keyboard);

        case 'pinChatMessage': {
            $mid = telegram_session_message_id();
            if ($mid < 1) {
                return ['ok' => false, 'description' => 'session message_id missing'];
            }
            $chatRaw = isset($config['chat_id']) ? trim((string) $config['chat_id']) : '';
            if ($chatRaw === '') {
                return ['ok' => false, 'description' => 'config chat_id missing'];
            }
            return telegram_post_json($base . 'pinChatMessage', [
                'chat_id' => $config['chat_id'],
                'message_id' => $mid,
                'disable_notification' => true,
            ]);
        }

        case 'setWebhook': {
            $token = is_string($keyboard) && $keyboard !== '' ? $keyboard : (string) ($config['bot_token'] ?? '');
            if ($token === '') {
                return ['ok' => false, 'description' => 'bot_token missing'];
            }
            $url = 'https://api.telegram.org/bot' . $token . '/setWebhook';
            return telegram_post_json($url, [
                'url' => $message,
                'allowed_updates' => ['callback_query', 'message'],
            ]);
        }

        default:
            return ['ok' => false, 'description' => 'Unknown bot_api method: ' . $method];
    }
}
