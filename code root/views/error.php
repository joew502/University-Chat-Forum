<!DOCTYPE html>
<!--Error page. This should be shown whenever an error arises with the description passed through-->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HallAmi <?php echo $ERROR_title ?? "Error 404 - Page Not Found";?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $MC_abspath_cr;?>/css/error.css">
    <link rel="icon" href="<?php echo $MC_abspath_cr;?>/assets/icon/HallAmiLogo.png" />
</head>
<body>
    <!-- Top nav bar-->
    <?php require './controllers/navbar_controller.php'; ?>
    <!-- Sidebar and block links -->
    <?php require './controllers/sidebar_controller.php'; ?>
    <div id="ERROR_main">
        <h1 class=""><?php echo $ERROR_title ?? "Error 404 - Page Not Found";?></h1>
        <p class="ERROR_desc"><?php echo $ERROR_desc ?? "We cannot find the page you are looking for.";?></p>
        <p class="ERROR_code"><?php
            if(isset($ERROR_code)) {
                if ($ERROR_code !== '---') {
                    echo "Error Code: " + $ERROR_code;
                }
            } else {
                echo "Error Code: 404";
            }
            ?></p>
        <button class="UP_tablinks" 
                onclick="location.href='<?php echo $MC_abspath_cr;?>/control.php/home'">Home</button>
        <button class="UP_tablinks" onclick="goBack()">&#8592 Back</button>
    </div>
    <script src="<?php echo $MC_abspath_cr;?>/js/back.js"></script>
</body>
</html>