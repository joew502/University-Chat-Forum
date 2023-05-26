<!DOCTYPE html>
<html lang="en">
<head>
    <title>HallAmi Register</title>
    <link rel="stylesheet" type="text/css" href="<?php echo $MC_abspath_cr;?>/css/login.css?v=<?php echo time();?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $MC_abspath_cr;?>/css/register.css?v=<?php echo time();?>">
    <link rel="icon" href="<?php echo $MC_abspath_cr;?>/assets/icon/HallAmiLogo.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <table class="login-content-table">
        <form id="register-form" action="<?php echo $MC_abspath_cr;?>/control.php/register/attempt"
              method="POST" onsubmit="return verifyPasswords();">
            <!--Form to let user register-->
            <tr>
                <td>
                    Email:
                </td>
                <td>
                    <input type="text" name="email" id="register-email" class="login-input-field" required>
                </td>
            </tr>
            <tr>
                <td>
                    Username:
                </td>
                <td>
                    <input type="text" name="uname" id="register-username" class="login-input-field" required>
                </td>
            </tr>
            <tr>
                <td>
                    Password:
                </td>
                <td>
                    <input type="password" name="pwd" id="register-password" class="login-input-field" required>
                </td>
            </tr>
            <tr>
                <td>
                    Confirm Password:
                </td>
                <td>
                    <input type="password" id="register-confirm-password"  class="login-input-field" required>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="<?php echo $MC_abspath_cr;?>/control.php/privacy">Privacy policy</a>
                </td>
                <td>
                    <a href="<?php echo $MC_abspath_cr;?>/control.php/tos">Terms of Service</a>
                </td>
            </tr>
            <tr>
                <td>
                    <label><input type="checkbox" id="register-pp" required>I have read the privacy policy</label>
                </td>
            </tr>
            <tr>
                <td>
                    <label><input type="checkbox" id="register-tos" required>I have read the terms of service</label>
                </td>
            </tr>
            <tr>
                <td>
                    <button type="button" class="login-input-button" onclick="goBack()">&#8592 Back</button>
                </td>
                <td>
                    <input type="submit" value="Register" class="login-input-button">
                </td>
            </tr>
        </form>
    </table>
    <div class="register-status-container"><div id="register-status"></div></div>
</body>
<script src="<?php echo $MC_abspath_cr;?>/js/register.js?v=<?php echo time();?>"></script>
<script src="<?php echo $MC_abspath_cr;?>/js/sani.js?v=<?php echo time();?>"></script>
<script src="<?php echo $MC_abspath_cr;?>/js/back.js"></script>
</html>
