<?php


require_once "../code root/utils/sani.php";


$basic_test_data = [
    "Academic" => True,
    "Community" => True,
    "Society" => True,
    "academic" => False,
    "community" => False,
    "society" => False,
    "" => False,
    "invalidHall" => False
];

//initialise $test_passed as true
$test_passed = True;


//set to fails in event of failure
foreach ($basic_test_data as $tkey => $tvalue){

    $res = SANE_sane($tkey,"hall");

    if($res !== $tvalue) {
        $test_passed = False;
        ob_start();
        var_dump($res);
        $vdres = ob_get_clean();
        ob_start();
        var_dump($tvalue);
        $vdtvalue = ob_get_clean();
        $test_error = "Testing SANE_sani from utils/sani on hall, failed with params:[$tkey] yielding result [$vdres] where [$vdtvalue] expected";
        break;
    }
}
