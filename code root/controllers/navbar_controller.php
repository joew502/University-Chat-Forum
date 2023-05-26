<?php

if ($_SESSION["auth"]){
    require_once "./utils/get_points.php";
    $NBC_points = GP_getpoints();

    #default error code
    if(is_string($NBC_points)) {
        $ERROR_title = $NBC_points;
        $ERROR_desc = "";
        $ERROR_code = "---";
        include "./views/error.php";
        exit();
    }

    #get points
    $NBC_community_xp = $NBC_points[1];
    $NBC_upvote_xp = $NBC_points[3] + $NBC_points[4];

    require_once "./utils/level_getter.php";
    $NBC_levels = get_levelup_info($NBC_upvote_xp, $NBC_community_xp);

    if(is_string($NBC_levels)) {
        $ERROR_title = $NBC_levels;
        $ERROR_desc = "";
        $ERROR_code = "---";
        include "./views/error.php";
        exit();
    }

    $NAV_profile_level = $NBC_levels[0];
}

require "./views/navbar.php";