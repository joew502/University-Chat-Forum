<?php
/**
 * generate an array of subfolders the url is requesting
 *
 * @return array - an array of requested sub folders 0 being immediately after the root file
 */
function get_path_comps() : array{
    #get unprocessed path
    $url_path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

    $regres = [];

    #Regex explanation:
    # ^(?:(?=[^.])[ -~])*   ->   discard up to the first . in the url (as folders never have them)
    # \.php                 ->   read the .php (to move the pointer along)
    # ([^?#]*)              ->   capture result of full path upto the query
    preg_match("%^(?:(?=[^.])[ -~])*\.php([^?#]*)%",$url_path,$regres);
    return explode("/",urldecode(trim($regres[1],"/")));
}

/**
 * get and return the path to the main controller (only works when called from a child of the main controller)
 *
 * @return string|False - the url path to the main controller (control.php) False if none found
 */
function get_control_path(){
    #get unprocessed path
    $url_path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

    $regres = [];

    #Regex explanation:
    # ^(?:(?=[^.])[ -~])*   ->   capture up to the first . in the url (as folders never have them)
    # \.php                 ->   capture the .php
    preg_match("%^((?:(?=[^.])[ -~])*\.php)%",$url_path,$regres);
    if(count($regres) == 2){
        return $regres[1];
    }
    return False;
}

/**
 * return the path to the coderoot from anywhere
 *
 * @return string - http path to the coderoot
 */
function get_abspth_cr(){
    return "http://$_SERVER[HTTP_HOST]".get_control_path()."/..";
}