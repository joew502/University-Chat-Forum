<?php

chdir(dirname(__DIR__));

$RV_ERA_ret_loc = $MC_control_path;

/**
 * function to edit a room description
 * @return bool|string  true if success, string of error if false
 */
function RV_ERA_edit_room(){
    if($_SERVER["REQUEST_METHOD"] !== "POST"){
        return "Invalid method";
    }

    #check logged in
    if(!$_SESSION["auth"]) {
        return "Not logged in";
    }

    if(!isset($_POST["roomID"]) || !isset($_POST["content"]) || !isset($_SESSION["UID"]) || !isset($_POST["block"]) || !isset($_POST["hall"]) ){
        return "Missing query element";//.": ".(isset($_POST["roomID"]) ? "" : "roomID,").(isset($_POST["content"]) ? "" : "content,").(isset($_POST["hall"]) ? "" : "hall,").(isset($_POST["block"]) ? "" : "block").(isset($_POST["title"]) ? "" : "title");
    }

    #extract data
    $roomID = $_POST["roomID"];
    $content = urldecode($_POST["content"]);
    $userID = $_SESSION["UID"];
    $hall = $_POST["hall"];
    $block = $_POST["block"];
    $roomName =  $_POST["title"];

    require "./utils/sani.php";
    #check data is valid
    if (!SANE_sane($block, "block_name")){
        return "Invalid characters in Block name";
    }
    if (!SANE_sane($content, "room_text")){
        return "Invalid characters in room description";
    }
    if (!SANE_sane($roomName, "room_name")){
        return "Invalid characters in room name";
    }


    require "./models/inner_room_model.php";

    $model = new InnerRoomModel();

    if(!$model->roomExists($roomName,$block,$hall)){
        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
        return "Room does not exist";
    }

    #edit the rooms content
    $model->edit_room($roomID, $content, $userID);
    if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

    global $RV_ERA_ret_loc;
    $RV_ERA_ret_loc .= "/halls/$hall/$block/$roomName";

    return true;
}
