<?php
chdir(dirname(__DIR__));
$BV_EBA_ret_loc = $MC_control_path;

/**
 * function to attempt to edit a block
 * @return bool|string  true if success, error string if not
 */
function BV_EBA_edit_block(){
    if($_SERVER["REQUEST_METHOD"] !== "POST"){
       return "Incorrect method";
    }

    #check user is authorised
    if(!$_SESSION["auth"]) {
        return "not authenticated";
    }

    if(!isset($_POST["block"]) || !isset($_POST["desc"]) || !isset($_POST["hall"]) || !isset($_SESSION["UID"])){
        return "Bad request";
    }
    #check values are strings
    if(!is_string($_POST["block"]) || !is_string($_POST["desc"]) || !is_string($_POST["hall"])){
        return "Bad request-wrong type";
    }

    #check values are correct lengths
    if(strlen($_POST["block"])>45||strlen($_POST["desc"])>500){
        return "block name or description too long";
    }

    #check halls are valid
    if($_POST["hall"] != ('Academic' || 'Community' || 'Society')){
        return "invalid hallname";
    }

    #extract data
    $blockName = urldecode($_POST["block"]);
    $description = urldecode($_POST["desc"]);
    $hallName = $_POST["hall"];
    $userID = $_SESSION["UID"];

    require "./models/hall_view_model.php";
    require "./models/blocks_view_model.php";
    require "sani.php";
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
    $blockModel = new BlocksModel();

    #Check that the block does already exist
    if(!$hallModel->blockExists($hallName,$blockName)){
        if($hallModel->isError()){trigger_error("error occurred in DB transaction: ".$hallModel->getError(),E_USER_ERROR);die();}
        return "Block does not exist";
    }

    $blockID = array_values($hallModel->getBlockID($blockName, $hallName))[0];
    if($hallModel->isError()){trigger_error("error occurred in DB transaction: ".$hallModel->getError(),E_USER_ERROR);die();}
    $isAdmin = $blockModel->isAdmin($userID, $blockID);
    if($blockModel->isError()){trigger_error("error occurred in DB transaction: ".$blockModel->getError(),E_USER_ERROR);die();}
    //var_dump($isAdmin);
    if ($isAdmin){
        $hallModel->editBlockDesc($blockID, $description);
        if($hallModel->isError()){trigger_error("error occurred in DB transaction: ".$hallModel->getError(),E_USER_ERROR);die();}
    } else {
        return "Not authorised";
    }


    global $BV_EBA_ret_loc;
    $BV_EBA_ret_loc .= "/halls/$hallName/$blockName";
    return True;
}


