<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HallAmi Profile</title>
    <link rel="stylesheet" type="text/css" href="<?php echo $MC_abspath_cr;?>/css/userpage.css">
    <link rel="icon" href="<?php echo $MC_abspath_cr;?>/assets/icon/HallAmiLogo.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <!-- Top nav bar-->
    <?php require './controllers/navbar_controller.php'; ?>
    <!-- Sidebar and block links -->
    <?php require './controllers/sidebar_controller.php'; ?>
    <!-- Main content-->
    <div id="UP_main">
        <div class="UP_tab">
            <button class="UP_tablinks" onclick="goBack()">&#8592 Back</button>
            <button class="UP_tablinks <?php echo $UP_accounttab ?? "";?>" id="UP_accountbutton"
                    onclick="openTab('UP_accountbutton','UP_account')">Account</button>
            <button class="UP_tablinks <?php echo $UP_settingstab ?? "";?>" id="UP_settingsbutton"
                    onclick="openTab('UP_settingsbutton','UP_settings')">Settings</button>
            <button class="UP_tablinks" id="UP_sendxpbutton"
                    onclick="openTab('UP_sendxpbutton','UP_sendxp')">Send Exe-P</button>
        </div>
        <div class="UP_tabcontent" id="UP_account">
            <?php if ($_SESSION["auth"]){ ?>
                <p class="UP_title"><?php echo $_SESSION["username"] ?? "User";?>'s Account</p>
                <div class="UP_progress">
                    <p>Level <?php echo $UP_level ?? "0";?></p>
                    <progress id="UP_progressbar" value="<?php echo $UP_level_progress ?? "0";?>" max="1"></progress>
                    <p>Level <?php echo $UP_next_level ?? "1";?></p>
                </div>
                <table>
                    <tr>
                        <th>Exe-P Types</th>
                        <th>Exe-P</th>
                    </tr>
                    <tr>
                        <td>Community</td>
                        <td><?php echo $UP_community_xp ?? "0";?></td>
                    </tr>
                    <!--
                    <tr>
                        <td>Academic</td>
                        <td><?php //echo $UP_academic_xp ?? "100";?></td>
                    </tr>
                    -->
                    <tr>
                        <td>Upvote</td>
                        <td><?php echo $UP_upvote_xp ?? "0";?></td>
                    </tr>
                    <!--
                    <tr>
                        <td>Meta</td>
                        <td><?php //echo $UP_meta_xp ?? "100";?></td>
                    </tr>
                    -->
                </table>
                <button onclick="location.href='<?php echo $MC_abspath_cr;?>/control.php/signout'">Log Out</button>
            <?php } else { ?>
                <p class="UP_title">Not Logged In</p>
            <?php } ?>
        </div>
        <div class="UP_tabcontent" id="UP_settings">
            <?php if ($_SESSION["auth"]){ ?>
                <p class="UP_title">Settings</p>
                <button onclick="location.href='<?php echo $MC_abspath_cr;?>/control.php/changePassword'">
                    Change Password</button>
                <button onclick="location.href='<?php echo $MC_abspath_cr;?>/control.php/signout'">Log Out</button>
            <?php } else { ?>
                <p class="UP_title">Not Logged In</p>
            <?php } ?>
        </div>
        <div class="UP_tabcontent" id="UP_sendxp">
            <?php if ($_SESSION["auth"]){ ?>
                <?php if ($UP_community_xp>0){ ?>
                    <p class="UP_title">Send Community Exe-P</p>
                    <form id="UP-sendxp-form" action="<?php echo $MC_abspath_cr;?>/control.php/userpage/sendxp"
                          method="POST" onsubmit="return verifySendXP();">
                        <div>
                        Username of Receiver:
                        <input type="text" name="username" id="UP-receiver-username" required>
                        </div>
                        <div>
                        <text>Amount of Exe-P:</text>
                        <input type="number" id="UP_points" name="points" min="1"
                               max="<?php echo $UP_community_xp ?? 1; ?>">
                        </div>
                        <input type="submit" value="Send Exe-P">
                        <button class="up_find_user_button" onclick="checkUserExists();  return false;">Check Username</button>
                        <p id="AP_user_status"></p>
                    </form>
                <?php } else { ?>
                    <p class="UP_title">No Points to Send</p>
                <?php } ?>
            <?php } else { ?>
                <p class="UP_title">Not Logged In</p>
            <?php } ?>
        </div>
    </div>
    <script> const UE_api_path = "<?php echo $MC_abspath_cr;?>/api-controllers"</script>
    <script src="<?php echo $MC_abspath_cr;?>/js/userpage.js?v=<?php echo time();?>"></script>
    <script src="<?php echo $MC_abspath_cr;?>/js/sani.js?v=<?php echo time();?>"></script>
    <script src="<?php echo $MC_abspath_cr;?>/js/back.js"></script>
    <script>activeTab("<?php echo $UP_settingstab ?? "";?>");</script>
</body>
</html>
