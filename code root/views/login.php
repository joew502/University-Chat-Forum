<!DOCTYPE html>
<html lang="en">
<head>
    <title>HallAmi Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="<?php echo $MC_abspath_cr;?>/css/login.css?v=<?php echo time();?>">
    <link rel="icon" href="<?php echo $MC_abspath_cr;?>/assets/icon/HallAmiLogo.png" />
</head>
<body>
    <table class="login-content-table">
        <!--login form-->
        <form id="login-form" action="<?php echo $MC_abspath_cr;?>/control.php/login/attempt"
              method="POST" onsubmit="return verifyUser();">

            <tr>
                <td>
                    Username:
                </td>
                <td>
                    <input type="text" name="username" id="login-username" class="login-input-field" required>
                </td>
            </tr>
            <tr>
                <td>
                    <text>Password:</text>
                </td>
                <td>
                    <input type="password" name="password" class="login-input-field" id="login-password" required>
                </td>
            </tr>
            <tr>
                <td>
                    <button type="button" class="login-input-button" onclick="goBack()">&#8592 Back</button>
                </td>
                <td>
                    <input type="submit" value="Login" class="login-input-button">
                    <button type="button" class="login-input-button"
                            onclick="location.href='<?php echo $MC_abspath_cr;?>/control.php/register'">Register
                    </button>
                </td>
            </tr>
        </form>
    </table>
    <script src="<?php echo $MC_abspath_cr;?>/js/login.js?v=<?php echo time();?>"></script>
    <script src="<?php echo $MC_abspath_cr;?>/js/sani.js?v=<?php echo time();?>"></script>
    <script src="<?php echo $MC_abspath_cr;?>/js/back.js"></script>
</body>
</html>
