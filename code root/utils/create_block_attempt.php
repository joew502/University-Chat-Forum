<?php

chdir(dirname(__DIR__));
$BV_CBA_ret_loc = $MC_control_path;

function BV_CBA_create_block(){
    if($_SERVER["REQUEST_METHOD"] !== "POST"){
       return "Incorrect method";
    }

    if(!$_SESSION["auth"]) {
        return "not authenticated";
    }

    if(!isset($_POST["block"]) || !isset($_POST["desc"]) || !isset($_POST["hall"]) || !isset($_SESSION["UID"])){
        return "Bad request";
    }
    if(!is_string($_POST["block"]) || !is_string($_POST["hall"]) || !is_string($_POST["desc"])){
        return "Parameters not set correctly";
    }

    #extract data
    $blockName = $_POST["block"];
    $description = $_POST["desc"];
    $hallName = $_POST["hall"];
    $userID = $_SESSION["UID"];

    require "./models/hall_view_model.php";
    require "./utils/sani.php";
    #check data is valid
    if (!SANE_sane($blockName, "block_name")){
        return "Invalid characters in Block name";
    }
    if (!SANE_sane($hallName, "hall")){
        return "Invalid characters in Hall name";
    }
    if (!SANE_sane($description, "block_desc")){
        return "Invalid characters in Block description";
    }

    $hallModel = new HallsModel();

    #Check that the block does not already exist
    if($hallModel->blockExists($hallName,$blockName)){
        if($hallModel->isError()){trigger_error("error occurred in DB transaction: ".$hallModel->getError(),E_USER_ERROR);die();}
        return "Block already exists";
    }

    if($hallModel->isError()){trigger_error("error occurred in DB transaction: ".$hallModel->getError(),E_USER_ERROR);die();}
    #create the block
    $hallModel->createBlock($blockName, $description, $hallName, $userID);
    if($hallModel->isError()){trigger_error("error occurred in DB transaction: ".$hallModel->getError(),E_USER_ERROR);die();}

    global $BV_CBA_ret_loc;
    $BV_CBA_ret_loc .= "/halls/$hallName/$blockName";
    return true;
}


