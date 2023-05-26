<?php

chdir(dirname(__DIR__));

$RL_CRA_ret_loc = $MC_control_path;

/**
 * create room attempt function
 * @return bool|string  true if success, string or error if not
 */
function RL_CRA_create_room(){
    if($_SERVER["REQUEST_METHOD"] !== "POST"){
        return "Invalid method";
    }

    #check user is logged in
    if(!$_SESSION["auth"]) {
        return "Not logged in";
    }

    #check data exists
    if(!isset($_POST["room"]) || !isset($_POST["cont"]) || !isset($_POST["block"]) || !isset($_POST["hall"]) || !isset($_SESSION["UID"])){
        return "Missing query element".": ".(isset($_POST["room"]) ? "" : "room,").(isset($_POST["cont"]) ? "" : "cont,").(isset($_POST["block"]) ? "" : "block,").(isset($_POST["hall"]) ? "" : "hall");
    }

    #extract data
    $roomName = $_POST["room"];
    $content = $_POST["cont"];
    $blockName = $_POST["block"];
    $hallName = $_POST["hall"];
    $userID = $_SESSION["UID"];

    require "./models/rooms_view_model.php";
    require "sani.php";
    #check data is valid
    if (!SANE_sane($blockName, "block_name")){
        return "Invalid characters in Block name";
    }
    if (!SANE_sane($hallName, "hall")){
        return "Invalid characters in Hall name";
    }
    if (!SANE_sane($content, "room_text")){
        return "Invalid characters in room description";
    }
    if (!SANE_sane($roomName, "room_name")){
        return "Invalid characters in room name";
    }


    $model = new RoomsModel();

    #check if room exists
    if($model->roomExists($roomName,$blockName, $hallName)){
        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
        return "Room already exists";
    }

    #get blocks ID and create the room
    if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
    $blockID = $model->getBlockId($hallName, $blockName);
    if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
    $model->createRoom($roomName, $content, $blockID, $userID);
    if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

    global $RL_CRA_ret_loc;
    $RL_CRA_ret_loc .= "/halls/$hallName/$blockName/$roomName";

    return true;
}
