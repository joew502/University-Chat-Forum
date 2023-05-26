<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>HallAmi <?php echo $BIL_hall ?? "" ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="<?php echo $MC_abspath_cr;?>/assets/icon/HallAmiLogo.png" />
    <link rel="stylesheet" type="text/css" href="<?php echo $MC_abspath_cr;?>/css/block_list_styles.css?v=<?php echo time();?>">
    <?php require "./views/colouring_styling.php" ?>
</head>
<body>
    <!-- Top nav bar-->
    <?php require './controllers/navbar_controller.php'; ?>
    <!-- Sidebar and block links -->
    <?php require './controllers/sidebar_controller.php'; ?>
    <div id="BIL_pagecontent">
        <?php
        #import all views needed
        require "./views/hallbannerview.php";
        require "./views/createblockview.php";
        ?>
        <div id="BLI_blocklist_holder">
            <div id="BLI_scroll"></div><?php //filled by inf scroll js?>
            <div id="BLI_out_of_blocks">Sorry, it looks like we are out of blocks</div>
        </div>

    </div>

    <script>
        const loadblid = <?php echo $BLC_lblid ?? 1; ?>;
        const hall = "<?php echo $BIL_hall ?? "academic"; ?>";
        const magicPath = "<?php echo $MC_abspath_cr; ?>";
        const userAuth = <?php echo ($_SESSION["auth"] ?? false) ? 1 : 0?>;
        const userID = <?php echo $_SESSION["UID"] ?? -1?>;
        //console.log(magicPath);
    </script>
    <!--call js scripts -->
    <script src="<?php echo $MC_abspath_cr;?>/js/BL_inf_scroll.js?v=<?php echo time();?>"></script>
    <script src="<?php echo $MC_abspath_cr;?>/js/hallscripts.js?v=<?php echo time();?>"></script>
    <script src="<?php echo $MC_abspath_cr;?>/js/sani.js?v=<?php echo time();?>"></script>
</body>
</html>
