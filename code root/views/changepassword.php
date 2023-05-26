<!DOCTYPE html>
<html lang="en">
<head>
    <title>HallAmi Change Password</title>
    <link rel="stylesheet" type="text/css" href="<?php echo $MC_abspath_cr;?>/css/login.css?v=<?php echo time();?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $MC_abspath_cr;?>/css/register.css?v=<?php echo time();?>">
    <link rel="icon" href="<?php echo $MC_abspath_cr;?>/assets/icon/HallAmiLogo.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <table class="login-content-table">
        <!--input form-->
        <form id="CP-form" action="<?php echo $MC_abspath_cr;?>/control.php/changePassword/attempt"
              method="POST" onsubmit="return verifyPasswordChange();">
            <?php if ($_SESSION["auth"]){ ?>
            <tr>
                <td>
                    New Password:
                </td>
                <td>
                    <input type="password" name="CP_pwd" id="register-password" class="login-input-field" required>
                </td>
            </tr>
            <tr>
                <td>
                    Confirm New Password:
                </td>
                <td>
                    <input type="password" id="register-confirm-password"  class="login-input-field" required>
                </td>
            </tr>
            <?php } else { ?>
                <tr>
                    <td>
                        Not Logged In
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td>
                    <button type="button" class="login-input-button" onclick="goBack()">&#8592 Back</button>
                </td>
                <?php if ($_SESSION["auth"]){ ?>
                <td>
                    <input type="submit" value="Change Password" class="login-input-button">
                </td>
                <?php } ?>
            </tr>
        </form>
    </table>
    <div class="register-status-container"><div id="register-status"></div></div>
</body>
<script src="<?php echo $MC_abspath_cr;?>/js/register.js?v=<?php echo time();?>"></script>
<script src="<?php echo $MC_abspath_cr;?>/js/back.js"></script>
<script src="<?php echo $MC_abspath_cr;?>/js/sani.js?v=<?php echo time();?>"></script>
</html>