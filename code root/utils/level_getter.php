<?php

/**
 * get level info from point values
 *
 * @param int $upvote_points - the number of upvote points the user has
 * @param int $community_points - the number of community points the user has
 * @return array - array containing the following: [current level, % to next level, next level]
 */
function get_levelup_info(int $upvote_points, int $community_points) : array{
    $level_val = 1.0+log(1.0+((pow($upvote_points,1.25)+pow($community_points*3.0,1.125))/33.0),1.5);
    return [floor($level_val),$level_val-floor($level_val),ceil($level_val)];
}


