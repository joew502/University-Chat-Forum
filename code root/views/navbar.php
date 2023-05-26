<link rel="stylesheet" type="text/css" href="<?php echo $MC_abspath_cr;?>/css/navbar.css?v=<?php echo time();?>">
<div id="navbar" class="navbar">
    <!-- Open button -->
    <button class="SB-openbtn" onclick="openSidebar()">&#9776;</button><?php
    //Logic to display profile or log in button as appropriate.
    if ($_SESSION["auth"]){
        ?><div id="NB-name-wrapper" class="navbar-name-wrapper">
            <div class="navbar-name"><?php echo $_SESSION["username"] ?? "TEST-USERNAME-HERE" ?></div>
           <!--display user name-->
        </div>
            <div id = "NB-profile" class="navbar-level-wrapper" >
                <div class="navbar-level"><?php echo $NAV_profile_level ?? 0; ?></div>
            </div>
            <div id = "profileDropdown">
                <div class="NB-dropdown-content" id="NB-dropdown-menu">
                    <!--Display drop down options-->
                    <a href="<?php echo $MC_abspath_cr;?>/control.php/userpage">Profile</a>
                    <a href="<?php echo $MC_abspath_cr;?>/control.php/userpage?page=settings">Settings</a>
                    <a href="<?php echo $MC_abspath_cr;?>/control.php/logout">Sign out</a>
                </div>
            </div>
    <?php } else { ?>
        <a href="<?php echo $MC_abspath_cr;?>/control.php/login"><div class="NB-login">Login</div></a>
    <?php } ?>
    <a href="<?php echo $MC_abspath_cr;?>/control.php"><img src="<?php echo $MC_abspath_cr;?>/assets/exeterlogo.png" alt="NB-logo" class="navbar-logo"></a>
</div>
<script src="<?php echo $MC_abspath_cr;?>/js/navbar.js?v=<?php echo time();?>"></script>
<script>
    let NBPLNK = document.getElementById("NB-profile");
    if(NBPLNK != null){
        document.getElementById("NB-profile").addEventListener("click", logo)
    }
</script>