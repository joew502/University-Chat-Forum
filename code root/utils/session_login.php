<?php

/**
 * ensures required variables are set for each connection,
 * Note: will be small initially but needed for potential future content
 *
 * session variable set:
 *
 * auth boolean - True if user is logged in
 *
 */
if(!isset($_SESSION["auth"])){
    $_SESSION["auth"] = false;
    $_SESSION["UID"] = -1;
    $_SESSION["uid"] = -1;
    $_SESSION["username"] = "not-logged-in";
    $_SESSION["global_role"] = "Student";
    $_SESSION["account_status"] = "Banned";
}
