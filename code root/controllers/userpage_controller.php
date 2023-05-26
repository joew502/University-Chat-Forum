<?php

if(isset($_GET['page'])) {
    if ($_GET['page'] == 'settings') {
        $UP_settingstab = 'active';
    }
} else {
    $UP_accounttab = 'active';
}

if ($_SESSION["auth"]){
    require_once "./utils/get_points.php";
    $UPC_points = GP_getpoints();

    #defaut error codes
    if(is_string($UPC_points)) {
        $ERROR_title = $UPC_points;
        $ERROR_desc = "";
        $ERROR_code = "---";
        include "./views/error.php";
        exit();
    }

    $UP_community_xp = $UPC_points[1];
    //$UP_academic_xp = $UPC_out[0];
    $UP_upvote_xp = $UPC_points[3] + $UPC_points[4];
    //$UP_meta_xp = $UPC_out[2];

    require_once "./utils/level_getter.php";
    $UPC_levels = get_levelup_info($UP_upvote_xp, $UP_community_xp);

    if(is_string($UPC_levels)) {
        $ERROR_title = $UPC_levels;
        $ERROR_desc = "";
        $ERROR_code = "---";
        include "./views/error.php";
        exit();
    }

    $UP_level = $UPC_levels[0];
    $UP_next_level = $UP_level + 1;
    $UP_level_progress = $UPC_levels[1];

}

require "./views/userpage.php";