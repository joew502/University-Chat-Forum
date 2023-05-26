<?php

/**
 * recursive array value
 * @param array $arr    input raw data array
 * @return array        processed array
 */
function rarray_values(array $arr) : array{
    $out = [];
    foreach($arr as $child){
        if(gettype($child) == "array"){
            array_push($out,rarray_values($child));
        } else {
            array_push($out,$child);
        }
    }
    return $out;
}

