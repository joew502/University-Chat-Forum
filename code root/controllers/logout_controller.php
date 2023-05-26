<?php

$_SESSION = array();
$params = session_get_cookie_params();
setcookie(session_name(),'',time()-60*60*24*14,$params["path"],$params["domain"],$params["secure"],$params["httponly"]);
session_destroy();
header("Location: $MC_abspath_cr/control.php");
exit(0);