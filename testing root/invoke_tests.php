<?php

/**
 * Get list of paths starting a parent path to all php files in subdirectorys.
 *
 * Will create notices if non-php file is found in subdirectory.
 *
 * @param string $parent_path - The path to look in
 * @param bool $suppress_warnings - Suppress notices produced by this function
 * @return array - An array of strings containing the paths to found php files
 */
function _testing_generate_filelist(string $parent_path="./tests",bool $suppress_warnings=False){
    //get files in passed directory and remove . and ..
    $files = scandir($parent_path);
    $files = array_values(array_diff($files,[".",".."]));

    //parse files opening directoys and adding found php files
    $phpFiles = [];
    $len = count($files);
    for($i=0;$i<$len;$i++){
        $file_name = $files[$i];
        //needed as only variables can be passed my reference (which end does)
        $expo = explode(".", $file_name);
        //expand folders, add php files to the known files list and trigger a notice of non-php objects in the testing directory
        if(is_dir("$parent_path/$file_name")){
            $phpFiles = array_merge($phpFiles,_testing_generate_filelist("$parent_path/$file_name"));
        } elseif(end($expo) === "php"){
            array_push($phpFiles,"$parent_path/$file_name");
        } else {
            if(!$suppress_warnings){
                trigger_error("Non-php file in testing directory: $file_name\n    Path: $parent_path/$file_name", E_USER_NOTICE);
            }
        }
    }

    return $phpFiles;
}

/**
 * Runs a file found at $path and runs it as a test returning the result as a 3-tuple array.
 *
 * @param string $path - The path of the test .php file
 * @return array - A 3 tuple containing [bool: test passed,int: error level(see readme),string: test message]
 */
function _run_test(string $path){
    $test_passed = False;
    $test_error_level = 3;//See README for meanings
    $test_error = "Unset";


    try {
        require $path;
    } catch (Exception $e) {
        $test_passed = False;
        $test_error_level = 4;//See README for meanings
        $test_error = "Exception thrown from test at [$path], msg: ".$e->getMessage();
    } catch (Error $e) {
        $test_passed = False;
        $test_error_level = 4;//See README for meanings
        $test_error = "Error thrown from test at [$path], msg: ".$e->getMessage();
    }


        //detect a failed test where no values were updated
    if (!$test_passed && $test_error_level <= 3 && $test_error === "Unset"){
        $test_error_level = 4;//increase priority for testing failure
        $test_error = "Test Structure Failure: Test does not account for all cases - No result set!";
    }
    if ($test_passed){
        $test_error_level = -1;
        if($test_error === "Unset"){
            $test_error = "Test Passed";
        }
    }

    return [$test_passed,$test_error_level,$test_error];
}

/**
 * Run all tests found in the ./tests directory in the current folder.
 *
 * @return array - An array of 3-tuple arrays containing the test results
 *                 each test result has the structure: [bool: test passed,int: error level(see readme),string: test message]
 */
function run_tests(){
    $files = _testing_generate_filelist();
    $results = [];
    $len = count($files);
    for($i=0;$i<$len;$i++){
        array_push($results,_run_test($files[$i]));
    }
    return $results;
}


