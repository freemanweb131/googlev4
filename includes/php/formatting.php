<?php
function numberToEmoji($number) {
    $digitToEmoji = [
        '0' => '0️⃣',
        '1' => '1️⃣',
        '2' => '2️⃣',
        '3' => '3️⃣',
        '4' => '4️⃣',
        '5' => '5️⃣',
        '6' => '6️⃣',
        '7' => '7️⃣',
        '8' => '8️⃣',
        '9' => '9️⃣'
    ];

    $numberStr = strval($number);
    $emojiStr = '';
    foreach (str_split($numberStr) as $digit) {
        $emojiStr .= isset($digitToEmoji[$digit]) ? $digitToEmoji[$digit] : $digit;
    }

    return $emojiStr;
}

function countryCodeToFlagEmoji($countryCode) {
$uppercase = strtoupper($someVar ?? '');
    $emoji = '';

    for ($i = 0; $i < strlen($countryCode); $i++) {
        $emoji .= html_entity_decode('&#' . (ord($countryCode[$i]) + 0x1F1A5) . ';', ENT_NOQUOTES, 'UTF-8');
    }

    return $emoji;
}

function deviceNameToEmoji($deviceName) {
    $deviceToEmoji = [
        'Mobile' => '📱',
        'Tablet' => '📲',
        'Desktop' => '🖥️',
    ];

    return $deviceToEmoji[$deviceName] ?? $deviceName;
}
?>