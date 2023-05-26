
<link rel="stylesheet" type="text/css" href="<?php echo $MC_abspath_cr;?>/css/sidebar.css?v=<?php echo time();?>">
<div id="sidebar" class="sidebar">
    <!-- Close button-->
    <a href="javascript:void(0)" class="SB-closebtn" onclick="closeSidebar()">&#9776;</a>
    <a href="<?php echo $MC_abspath_cr;?>/control.php/halls/academic">
        <div id="SB-academic-hall" class="SB-academic-hall-link">Academic Halls</div>
    </a>
    <a href="<?php echo $MC_abspath_cr;?>/control.php/halls/society">
        <div id="SB-society-hall" class="SB-society-hall-link">Society Halls</div>
    </a>
    <a href="<?php echo $MC_abspath_cr;?>/control.php/halls/community">
        <div id="SB-community-hall" class="SB-community-hall-link">Community Halls</div>
    </a>
    <?php if($_SESSION["auth"]) {?>
    <div id="SB_srlldv">
        <!--buttons to display subscribed block-->
        <button class="SB-dropdown-btn">Academic Hall</button>
            <!--blocks-->
            <div class="SB-dropdown-container">
                <?php echo $SB_academic_blocks ?? 'broken' ?>
            </div>
        <button class="SB-dropdown-btn">Society Hall</button>
            <div class="SB-dropdown-container">
                <?php echo $SB_society_blocks ?? 'broken'?>
            </div>
        <button class="SB-dropdown-btn">Community Hall</button>
            <div class="SB-dropdown-container">
                <?php echo $SB_community_blocks ?? 'broken'?>
            </div>
    </div>
    <?php } ?>
</div>
<script src="<?php echo $MC_abspath_cr;?>/js/sidebar.js?v=<?php echo time();?>"></script>
