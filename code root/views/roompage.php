<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>HallAmi <?php echo $IR_hall ?? "" ?> > <?php echo $IR_block ?? "" ?> > <?php echo $IR_room ?? "" ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="<?php echo $MC_abspath_cr;?>/css/roompage.css">

    <?php require "./views/colouring_styling.php" ?>

    <link rel="icon" href="<?php echo $MC_abspath_cr;?>/assets/icon/HallAmiLogo.png" />
</head>
<body>
    <!-- Top nav bar-->
    <?php require './controllers/navbar_controller.php'; ?>
    <!-- Sidebar and block links -->
    <?php require './controllers/sidebar_controller.php'; ?>

    <button type="button" id="RP_backbutton" class="RP_back"
            onclick="location.href='<?php echo $MC_abspath_cr."/control.php/halls/$IR_hall/$IR_block"; ?>'">
        Back to <?php echo $IR_block?>
    </button>

    <div id="RP_main">
        <?php echo $RP_content ?? "Content unreachable";?>
    </div>
    <script>
        const IRCC_hall = "<?php echo $IR_hall ?? "community"; ?>";
        const IRCC_block = "<?php echo $IR_block ?? "test"; ?>";
        const IRCC_room = "<?php echo $IR_room ?? "TestRoom"; ?>";
        const IRCC_Loggedin = "<?php echo $_SESSION["auth"] ?? False;?>";
        const magicPath = "<?php echo $MC_abspath_cr; ?>";
    </script>

    <script src="<?php echo $MC_abspath_cr;?>/js/sani.js"></script>
    <script src="<?php echo $MC_abspath_cr;?>/js/inroomviewscripts.js"></script>

</body>
</html>