<?php
session_start();

$tauth = isset($_SESSION["auth"]) ? ($_SESSION["auth"] ? "T" : "F") : "Unset";
$tuid = $_SESSION["UID"] ?? "Unset";
$tuname = $_SESSION["username"] ?? "Unset";
$tgr = $_SESSION["global_role"] ?? "Unset";
$tas = $_SESSION["account_status"] ?? "Unset";

$test_passed = True;
$test_error = "Test session data: auth:$tauth, uid:$tuid, uname:$tuname, glob_role:$tgr, account_stat:$tas";