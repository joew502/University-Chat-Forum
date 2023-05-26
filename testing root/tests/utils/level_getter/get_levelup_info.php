<?php

require "../code root/utils/level_getter.php";

$ret = get_levelup_info(50,60);

if($ret[0] == 7 && $ret[1] - 0.75468 < 0.01 && $ret[2] == 8){
    $test_passed = True;
} else {
    $test_passed = False;
    $test_error = "unexpected results, [6,0.75,7] expected, [".$ret[0].",".$ret[1].",".$ret[2]."]";
}