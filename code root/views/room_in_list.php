<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>HallAmi <?php echo $RIL_hall ?? "" ?> > <?php echo $RIL_block ?? "" ?></title>
    <!--Display title and block-->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="<?php echo $MC_abspath_cr;?>/assets/icon/HallAmiLogo.png" />
    <link rel="stylesheet" type="text/css" href="<?php echo $MC_abspath_cr;?>/css/room_list_styles.css?v=<?php echo time();?>">
    <?php require "./views/colouring_styling.php" ?>
</head>
<body>
    <!-- Top nav bar-->
    <?php require './controllers/navbar_controller.php'; ?>
    <!-- Sidebar and block links -->
    <?php require './controllers/sidebar_controller.php'; ?>
    <div id="RIL_pagecontent">
        <?php
        require "./views/blockbannerview.php";
        require "./views/blockabout.php";
        require "./views/createroomview.php";
        require "./views/memberlist.php";
        ?>
        <div id="RLI_roomlist_holder">
            <div id="RLI_scroll"></div><?php //filled by inf scroll js?>
            <div id="RLI_out_of_rooms">Sorry, it looks like we are out of rooms</div>
        </div>

    </div>

    <script>
        let loadtime = <?php echo time(); ?>;
        const hall = "<?php echo $RIL_hall ?? "Academic"; ?>";
        const block = "<?php echo $RIL_block ?? "testblock"; ?>";
        const magicPath = "<?php echo $MC_abspath_cr; ?>";
        const userAuth = <?php echo ($_SESSION["auth"] ?? false) ? 1 : 0?>;
        const userID = <?php echo $_SESSION["UID"] ?? -1?>;
        //console.log(magicPath);
    </script>
    <script src="<?php echo $MC_abspath_cr;?>/js/RL_inf_scroll.js?v=<?php echo time();?>"></script>
    <script src="<?php echo $MC_abspath_cr;?>/js/blockscripts.js?v=<?php echo time();?>"></script>
    <script src="<?php echo $MC_abspath_cr;?>/js/sani.js?v=<?php echo time();?>"></script>
    <script src="<?php echo $MC_abspath_cr;?>/js/make_admin.js?v=<?php echo time();?>"></script>

</body>
</html>