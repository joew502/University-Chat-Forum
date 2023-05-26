<?php


require_once "../code root/utils/sani.php";


$basic_test_data = [
    "" => False,
    "t" => True,
    "CAPStext" => True,
    "textwithnumbers1234567890" => True,
    "text with spaces" => True,
    "Test With Special Characters !£$%^&*()_+-=[]{};'#:@~<>?,./¬|" => True,
    "Test With New Line Character \n" => True,
    "This is a very long string, it should definitely not be allowed to get past sanitation. In fact it is 611 
    characters long. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. 
    Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam 
    felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, 
    fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. 
    Nullam dictum felis eu pede mollis pretium. Integer tincidunt." => False,
    "Test With Bad Special Character `" => False,
    "Test With Bad Special Character ß" => False,
    "Test With Bad Special Character §" => False,
    "Test With Bad Special Character Ã" => False,
    "Test With Bad Special Character Ÿ" => False,
    "Test with a double  space" => True
];

//initialise $test_passed as true
$test_passed = True;


//set to fails in event of failure
foreach ($basic_test_data as $tkey => $tvalue){

    $res = SANE_sane($tkey,"block_desc");

    if($res !== $tvalue) {
        $test_passed = False;
        ob_start();
        var_dump($res);
        $vdres = ob_get_clean();
        ob_start();
        var_dump($tvalue);
        $vdtvalue = ob_get_clean();
        $test_error = "Testing SANE_sani from utils/sani on block_desc, failed with params:[$tkey] yielding result [$vdres] where [$vdtvalue] expected";
        break;
    }
}
