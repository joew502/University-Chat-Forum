<?php


require_once "../code root/utils/sani.php";


$basic_test_data = [
    "" => False,
    "@exeter.ac.uk" => False,
    "test123@exeter.ac.uk@exeter.ac.uk" => False,
    "test123@gmail.com" => False,
    "test_123@exeter.ac.uk" => False,
    "test 123@exeter.ac.uk" => False,
    "test123 @exeter.ac.uk" => False,
    "test123?@exeter.ac.uk" => False,
    "test-123@exeter.ac.uk" => False,
    "test@exeter.ac.uk" => True,
    "test123@exeter.ac.uk" => True,
    "Test@exeter.ac.uk" => True,
    "test.123@exeter.ac.uk" => True
];

//initialise $test_passed as true
$test_passed = True;


//set to fails in event of failure
foreach ($basic_test_data as $tkey => $tvalue){

    $res = SANE_sane($tkey,"exeter_email");

    if($res !== $tvalue) {
        $test_passed = False;
        ob_start();
        var_dump($res);
        $vdres = ob_get_clean();
        ob_start();
        var_dump($tvalue);
        $vdtvalue = ob_get_clean();
        $test_error = "Testing SANE_sani from utils/sani on exeter_email, failed with params:[$tkey] yielding result [$vdres] where [$vdtvalue] expected";
        break;
    }
}
