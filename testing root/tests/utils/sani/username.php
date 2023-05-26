<?php


require_once "../code root/utils/sani.php";


$basic_test_data = [
    "us" => False,
    "user" => True,
    "CAPSusername" => True,
    "usernamewithnum2" => True,
    "username with space" => True,
    "username-with-dash" => True,
    "username_with_underscore" => True,
    "userWithSpecial:[]'!£$%^&*()" => False,
    "tooLongUsernameAintNoWayThisCanBeValidItIsLikeSevenMillionCharacters" => False
];

//initialise $test_passed as true
$test_passed = True;


//set to fails in event of failure
foreach ($basic_test_data as $tkey => $tvalue){

    $res = SANE_sane($tkey,"username");

    if($res !== $tvalue) {
        $test_passed = False;
        ob_start();
        var_dump($res);
        $vdres = ob_get_clean();
        ob_start();
        var_dump($tvalue);
        $vdtvalue = ob_get_clean();
        $test_error = "Testing SANE_sani from utils/sani on username, failed with params:[$tkey] yielding result [$vdres] where [$vdtvalue] expected";
        break;
    }
}
