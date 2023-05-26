<?php

//statements to execute
$statements = [
    ["Select blockID from Block where blockname = ? and parentHall = ?","ss","testblock","Academic"],
    ["select roomID from Room where title = ? and parentBlock = ?;","si","testr3","SET-BY-RES"]
];
//set the 2nd statments 4th feild to the first statements first column and row of returned result
$useresin = ["1,3" => "0,0,0"];

$MC_abspath_cr = "../code root";
require_once "../code root/main_model.php";

$model = new Model();

$res = $model->multiquery($statements,$useresin);

if($model->isError()){
    $test_passed = false;
    $test_error = "model errored with details: (".$model->getError().")";
} elseif($res[0][0][0] !== 11){
    ob_start();
    var_dump($res[0]);
    echo ") from (";
    var_dump($res);
    $resvd = ob_get_clean();
    $test_passed = false;
    $test_error = "model returned incorrect result in first statement: ($resvd)";
} elseif($res[1][0][0] !== 6){
    ob_start();
    var_dump($res[1]);
    echo ") from (";
    var_dump($res);
    $resvd = ob_get_clean();
    $test_passed = false;
    $test_error = "model returned incorrect result in second statement: ($resvd)";
} else {
    $test_passed = true;
}
