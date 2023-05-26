<?php
/**
 * Sets colouring variables,  must be called from a control-hall based root!
 */


//data checks:
require "./utils/sani.php";

if(!isset($MC_path_components)){
    trigger_error("colouring_style called without $MC_path_components",E_USER_ERROR);
} elseif(count($MC_path_components) < 2){
    trigger_error("colouring_style called without long enougth $MC_path_components",E_USER_ERROR);
} else {
    $GCS_hall = ucwords($MC_path_components[1]);
}

//sanitation to check that the information is valid
if(!SANE_sane($GCS_hall,"hall")){
    $ERROR_title = "Hall not defined";
    $ERROR_desc = "Hall <b>".htmlspecialchars($GCS_hall)."</b> is not a valid hall";
    $ERROR_code = "---";
    include "./views/error.php";
    exit();
}

switch ($GCS_hall){
    //change the colour scheme to match the hall
    case "Academic":
        ?>
        <style>
            :root{
                --colour: #c81919;
                --hcolour: #720c0c;
        <?php break;
    case "Society":
        ?>
        <style>
            :root{
                --colour: #e7a72a;
                --hcolour: #806010;
        <?php break;
    case "Community":
        ?>
        <style>
            :root{
                --colour: #1a8ee7;
                --hcolour: #2b6aa9;
        <?php break;
}

//The indentation that follows is intentional
?>
                --bcolour: var(--colour);
            }
         </style>
<?php

