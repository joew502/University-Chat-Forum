<?php
$test_passed = True;

require_once "../code root/utils/rarray_values.php";


$array_test_1 = array("size" => "XL", "color" => "gold");
$rarray_test_1 = rarray_values($array_test_1);
if($rarray_test_1[0] !== "XL"){
    $test_passed = False;
    $test_error = "Expected XL got: ".$rarray_test_1[0];
} elseif ($rarray_test_1[1] !== "gold"){
    $test_passed = False;
    $test_error = "Expected gold got: ".$rarray_test_1[1];
} elseif (count($rarray_test_1) !== 2){
    $test_passed = False;
    $test_error = "Expected two value array got: ".count($rarray_test_1);
}

$array_test_2 = array();
$rarray_test_2 = rarray_values($array_test_2);
if(count($rarray_test_2) !== 0){
    $test_passed = False;
    $test_error = "Expected empty array got: ".count($rarray_test_2);
}

$array_test_3 = array("num1" => ["tw" => "res","tw2" => "res2"], "num2" => "gold");
$rarray_test_3 = rarray_values($array_test_3);
if($rarray_test_3[0][0] !== "res" || $rarray_test_3[0][1] !== "res2"){
    $test_passed = False;
    ob_start();
    var_dump($rarray_test_3);
    $rarray_test_3vd = ob_get_clean();
    $test_error = "Unexpected values returned: $rarray_test_3vd";
}

