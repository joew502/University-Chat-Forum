<?php


require_once "../code root/utils/sani.php";


$basic_test_data = [
    "na" => False,
    "nam" => True,
    "CapsName" => True,
    "Numbers123" => True,
    "Name With Space" => True,
    "NameWithSpecial!,.£$()@#~:;" => True,
    "NameWithQuestion?" => False,
    "tooLongBlockNameAintNoWayThisCanBeValidItIsLikeSevenMillionCharacters" => False
];

//initialise $test_passed as true
$test_passed = True;


//set to fails in event of failure
foreach ($basic_test_data as $tkey => $tvalue){

    $res = SANE_sane($tkey,"block_name");

    if($res !== $tvalue) {
        $test_passed = False;
        ob_start();
        var_dump($res);
        $vdres = ob_get_clean();
        ob_start();
        var_dump($tvalue);
        $vdtvalue = ob_get_clean();
        $test_error = "Testing SANE_sani from utils/sani on block_name, failed with params:[$tkey] yielding result [$vdres] where [$vdtvalue] expected";
        break;
    }
}
