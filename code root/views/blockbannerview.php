<link rel="stylesheet" type="text/css" href="<?php echo $MC_abspath_cr;?>/css/blockbanner.css?v=<?php echo time();?>">
<div id="BB-main" class="BB-main">
    <!--Banner details-->
    <div id="BB-title" class="BB-block-title"><?php echo $RIL_block ?? "testblock"; ?></div>
    <div id="BB-member-count" class="BB-member-count">Members: <?php echo $BB_count ?? "??????"; ?></div>
    <div id="BB-hall" class="BB-hall"><?php echo $RIL_hall ?? "academic"; ?></div>
    <div id="BB-buttons-wrapper" class="BB-buttons-wrapper">
        <!--Banner buttons-->
        <button id="BB-about" class="BB-menu-button" onclick="onClickAbout()">About</button>
        <button id="BB-posts" class="BB-menu-button" onclick="onClickRooms()">Rooms</button>
        <button id="BB-members" class="BB-menu-button" onclick="onClickMember()">Members</button>
        <?php if($_SESSION["auth"]){?><button id="BB-create" class="BB-menu-button" onclick="onClickCreate()">Create Room</button><?php } ?>
    </div>

    <?php if (!($MBL_admin_count===1 and in_array($_SESSION["UID"], $MBL_admin_IDs))){
            if($RPL_subscribed ?? false && $_SESSION["auth"]){ ?>
                <!--subscribe button to not show if you are the sole admin-->
            <button id="BB-subscribed" class="BB-menu-button BB-subscribe" onclick="onClickUnsubscribe()">Unsubscribe</button>
    <?php } else if($_SESSION["auth"]){ ?>
        <button id="BB-unsubscribed" class="BB-menu-button BB-subscribe" onclick="onClickSubscribe()">Subscribe</button>
    <?php }} ?>

    <a href="<?php echo $MC_abspath_cr."/control.php/halls/".$RIL_hall; ?>"><div class="BB-back">Back to <?php echo $RIL_hall ?></div></a>
</div>
