<?php

$server_requrl_restore_point = $_SERVER["REQUEST_URI"];


require_once "../code root/utils/url_parsing.php";


$basic_test_data = [
    '/an/example/url' => False,
    '/an/example/url.php' => '/an/example/url.php',
    '/an/example/url.php/page' => '/an/example/url.php',
    '/an/example/url.php/page/subpage/item' => '/an/example/url.php',
    '/an/example/url.php/page/subpage?q=1' => '/an/example/url.php',
    '/an/example/url.php/page/subpage#top' => '/an/example/url.php',
    'control.php' => 'control.php'
];

//initialise $test_passed as true
$test_passed = True;

//set to fails in event of failure
foreach ($basic_test_data as $tkey => $tvalue){
    $_SERVER["REQUEST_URI"] = $tkey;
    $res = get_control_path();
    if($res !== $tvalue) {
        $test_passed = False;
        ob_start();
        var_dump($res);
        $vdres = ob_get_clean();
        ob_start();
        var_dump($tvalue);
        $vdtvalue = ob_get_clean();
        $test_error = "Testing get_control_path from utils/url_parsing failed with url:[$tkey] yielding result [$vdres] where [$vdtvalue] expected";
        break;
    }
}


$_SERVER["REQUEST_URI"] = $server_requrl_restore_point;