<?php

/**
 * function to work out how long ago relative to now a time was
 * @param int $time             time of post
 * @param string $now           string that is the name of a function to retrieve the current time
 * @param mixed ...$timeParams  parameters to pass through to current time function
 * @return string
 */
function get_ago_time(int $time,string $now = "time",...$timeParams): string{
    $ago = call_user_func($now,...$timeParams)-$time;
    $ending = "ago";
    if($ago < 0){
        $ending = "time";
        $ago = -$ago;
    }

    #get the time in min, hour, days and years
    $min = (int) floor($ago/60);
    $hour = (int) floor($ago/3600);
    $day = (int) floor($ago/86400);
    $year = (int) floor($ago/31536000);

    #work out how to format as a string
    if($min === 0){
        return "$ago seconds $ending";
    }elseif($min === 1 && $ending !== "time"){
        return "$min minute $ending";
    }elseif($min < 100){
        return "$min minutes $ending";
    }elseif($hour === 1 && $ending !== "time"){
        return "$hour hour $ending";
    }elseif($hour <= 23){
        return "$hour hours $ending";
    }elseif($day === 1 && $ending !== "time"){
        return "$day day $ending";
    }elseif($year === 0){
        return "$day days $ending";
    } elseif($year === 1 && $ending !== "time"){
        return "$year year $ending";
    } else {
        return "$year years $ending";
    }
}
