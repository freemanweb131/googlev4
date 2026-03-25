<?php 

$js_enabled = false;

$server = array(
    'disabled',
    'disabled',
    'disabled',
    'disabled',
    'disabled',
    'disabled',
);

$correct = 0;

if (function_exists('session_start')) {
    $server[0] = 'enabled';
    $correct++;
}
if (extension_loaded('curl')) {
    $server[1] = 'enabled';
    $correct++;
}

if (function_exists('uniqid') && function_exists('md5')) {
    $server[2] = 'enabled';
    $correct++;
}

if (function_exists('ob_start')) {
    $server[3] = 'enabled';
    $correct++;
}

$images_functions = [
    'imagecreatefromjpeg', 
    'imagesx', 
    'imagesy', 
    'imagecreatetruecolor', 
    'imagecolorallocatealpha', 
    'imagefill', 
    'imagecopyresized',
    'imagecolorallocate',
    'imagettftext',
    'imagepng',
    'imagedestroy'];

foreach ($images_functions as $function) {
    if (function_exists($function)) {
        $server[4] = 'enabled';
        $correct++;
    }else{
    }
}



?>  
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Live Telegram Panel v2</title>
    <link rel="preload" href="css/t5t6IRMbNJ6TQG7Il_EKPqP9zTnvqouPWhojrg.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="css/t5t6IRMbNJ6TQG7Il_EKPqP9zTnvqouBWho.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="css/gNMHW3x8Qoy5_mf8uWMFMIo.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="css/gNMHW3x8Qoy5_mf8uWMLMIqK_Q.woff2.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="css/4iCs6KVjbNBYlgoKcQ72j00.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="css/4iCs6KVjbNBYlgoKfw72.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link href="css/aos.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link href="css/fonts.css" rel="stylesheet">
    <script src="js/aos.js"></script>
    <script>
        <?php $js_enabled = true; ?>
    </script>
    <?php 
    if ($js_enabled == true) {
        $server[5] = 'enabled';
        $correct++;
    }
    ?>
</head>
<body>
    <div class="app">
        <div class="background">
            <div style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAQAAAAECAYAAACp8Z5+AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyFpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTQyIDc5LjE2MDkyNCwgMjAxNy8wNy8xMy0wMTowNjozOSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo2REVERkRGMjg4RkIxMUU4OEVGRkEzRDZCN0QyQTEyNyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDo2REVERkRGMzg4RkIxMUU4OEVGRkEzRDZCN0QyQTEyNyI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjZERURGREYwODhGQjExRTg4RUZGQTNENkI3RDJBMTI3IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjZERURGREYxODhGQjExRTg4RUZGQTNENkI3RDJBMTI3Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+tCXrZwAAACFJREFUeNpiZGBg+M8AIv7/ZwTRTAxogBEogyKAoQIgwABOKwcBZKwNbgAAAABJRU5ErkJggg==&quot;); background-repeat: repeat; height: 100%; width: 100%; position: fixed; z-index: 0;"></div>
        </div>
        <div class="box" style="display: none;">
            <div class="padding-element" data-aos="fade-right">
                <div class="txt-left">
                <p class="txt-color header-text font-doto txt-center head-text-border" data-aos="fade-right">Smart Live Telegram Panel v3</p>
                    <div>
                        <br>
                        <p class="txt-color header-text" style="font-size: 18px"><b>Server Compatibility</b></p>
                        <form id="continueForm" autocomplete="off" action="" method="post">
                            <div class="server">
                                <img src="img/<?php echo $server[0] ?>.png" alt="">
                                <p class="que-color que-text"><b>Session <?php echo $server[0] ?></b></p>
                            </div>
                            <div class="server">
                                <img src="img/<?php echo $server[1] ?>.png" alt="">
                                <p class="que-color que-text"><b>cURL <?php echo $server[1] ?></b></p>
                            </div> 
                            <div class="server">
                                <img src="img/<?php echo $server[2] ?>.png" alt="">
                                <p class="que-color que-text"><b>Unique ID <?php echo $server[2] ?></b></p>
                            </div> 
                            <div class="server">
                                <img src="img/<?php echo $server[3] ?>.png" alt="">
                                <p class="que-color que-text"><b>Output buffering <?php echo $server[3] ?></b></p>
                            </div> 
                            <div class="server">
                                <img src="img/<?php echo $server[4] ?>.png" alt="">
                                <p class="que-color que-text"><b>Image functions <?php echo $server[4] ?></b></p>
                            </div> 
                            <div class="server">
                                <img src="img/<?php echo $server[5] ?>.png" alt="">
                                <p class="que-color que-text"><b>JavaScript <?php echo $server[5] ?></b></p>
                            </div>
                            <?php if($correct == 16){ ?>
                            <div style="padding-top: 10px">
                                <a href="install.php">
                                <button type="button" class="btnRegular border-bottom-left border-bottom-right">Connect Now</button>
                                </a>
                            </div>
                            <?php }else{ ?>
                            <div class="server">
                                <p class="que-color que-text"><b></b></p>
                            </div> 
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="loading-screen">
            <div class="floating" style="text-align: center;">
                <div id="floatBarsG_1" class="floatBarsG"></div>
                <div id="floatBarsG_2" class="floatBarsG"></div>
                <div id="floatBarsG_3" class="floatBarsG"></div>
                <div id="floatBarsG_4" class="floatBarsG"></div>
                <div id="floatBarsG_5" class="floatBarsG"></div>
                <div id="floatBarsG_6" class="floatBarsG"></div>
                <div id="floatBarsG_7" class="floatBarsG"></div>
                <div id="floatBarsG_8" class="floatBarsG"></div>
            </div>
        </div>
        <noscript>
            <div>
                <p class="txt-color que-text"><span class="que-color" style="font-weight: 900 !important">*</span>Please enable JavaScript to continue</p>
            </div>
        </noscript>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelector(".loading-screen").style.display = "none";
            document.querySelector('.box').style.display = null;
            AOS.init();
        });
    </script>
</body>
</html>