<?php

//get test results
require "invoke_tests.php";
$test_results = run_tests();

//sort array to have most important errors at the top
usort($test_results, function($a, $b) {
    return $b[1] <=> $a[1];#b before a to reverse sorting order
});

//generate page
$content = "";
$conv_err_dict = [0 => "DEBUG",1 => "INFO",2 => "WARN",3 => "ERROR",4 => "PRIORITY",5 => "CRITICAL"];
$result_count = count($test_results);
for($err_count=0;$err_count<$result_count;$err_count++){
    $row = $test_results[$err_count];
    $test_error_level = $row[1];
    $test_message = $row[2];

    if($row[0]){
        $test_error_level_txt = "PASSED";
    } else {
        $test_error_level_txt = $conv_err_dict[$test_error_level];
    }

    ob_start();
    require "test_list_item.php";
    $content .= ob_get_clean();
}

//display page
require "testing_output_page.php";

