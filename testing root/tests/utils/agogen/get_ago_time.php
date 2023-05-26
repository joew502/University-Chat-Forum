<?php


require_once "../code root/utils/agogen.php";


$basic_test_data = [
    "60,120" => "1 minute ago",
    "60,119" => "59 seconds ago",
    "60,220" => "2 minutes ago",
    "60, 6061" => "1 hour ago",
    "60, 7261" => "2 hours ago",
    "60,86461" => "1 day ago",
    "60,172861" => "2 days ago",
    "60,31536061" => "1 year ago",
    "60, 63072061" => "2 years ago"
];

//initialise $test_passed as true
$test_passed = True;

function testingEchoer($data){
    return $data;
}

//set to fails in event of failure
foreach ($basic_test_data as $tkey => $tvalue){
    list($pt,$nw) = explode(",",$tkey);



    $res = get_ago_time($pt,"testingEchoer",$nw);

    if($res !== $tvalue) {
        $test_passed = False;
        ob_start();
        var_dump($res);
        $vdres = ob_get_clean();
        ob_start();
        var_dump($tvalue);
        $vdtvalue = ob_get_clean();
        $test_error = "Testing get_ago_time from utils/agogen failed with params:[$tkey] yielding result [$vdres] where [$vdtvalue] expected";
        break;
    }
}


