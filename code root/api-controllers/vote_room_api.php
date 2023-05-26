<?php

session_start();
chdir(dirname(__DIR__));

if($_SERVER["REQUEST_METHOD"] !== "POST"){
    http_response_code(405);
    die();
}
if(!isset($_POST["action"])){
    http_response_code(400);
    die();
}

switch($_POST["action"]){

    case "vote_room":

        if(!$_SESSION["auth"]) {
            http_response_code(401);
            die();
        }

        if(!isset($_POST["room"]) || !isset($_POST["block"]) || !isset($_POST["hall"]) || !isset($_SESSION["UID"]) || !isset($_POST["type"])){
            http_response_code(400);
            die();
        }

        #extract data
        $roomName = $_POST["room"];
        $blockName = $_POST["block"];
        $hallName = $_POST["hall"];
        $userID = $_SESSION["UID"];
        $type = $_POST["type"];

        require "./models/inner_room_model.php";

        $model = new InnerRoomModel();

        #check data is valid
        require "./utils/sani.php";
        if (!SANE_sane($roomName, "room_name")) {
            http_response_code(400);
            die();
        }
        if (!SANE_sane($blockName, "block_name")) {
            http_response_code(400);
            die();
        }
        if (!SANE_sane($hallName, "hall")) {
            http_response_code(400);
            die();
        }

        #check if room already exists
        if(!$model->roomExists($roomName, $blockName,$hallName)) {
            if ($model->isError()) { trigger_error("error occurred in DB transaction: " . $model->getError(), E_USER_ERROR); die();}
            http_response_code(400);
            die();
        }
        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}


        $roomID = $model->getRoomID($hallName, $blockName, $roomName);
        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

        $VRA_db_vote_type = $model->hasRoomVoted($userID, $roomID);
        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

        $VRA_db_vote_type = count($VRA_db_vote_type) === 0 ? "" : $VRA_db_vote_type[0]["voteType"];

        if($VRA_db_vote_type !== ""){
            //voted before

            if($VRA_db_vote_type === $type){
                //you vote the same as before: unvote!
                $model->deleteRoomVote($userID, $roomID);
                if ($model->isError()) {trigger_error("error occurred in DB transaction: " . $model->getError(), E_USER_ERROR); die();}

            } else {
                //you voted differently: change vote
                $model->updateRoomVote($userID, $roomID, $type);
                if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

            }
        }
        elseif ($type === "Up"){
            //haven't voted before and wish to upvote
            if ($model->isError()) {
                trigger_error("error occurred in DB transaction: " . $model->getError(), E_USER_ERROR);
                die();
            }
            $model->upRoomVote($roomID, $userID);
            if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

        }
        elseif ($type === "Down"){
            //haven't voted before and wish to downvote
            if ($model->isError()) {
                trigger_error("error occurred in DB transaction: " . $model->getError(), E_USER_ERROR);
                die();
            }
            $model->downRoomVote($roomID, $userID);
            if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

        }
        else{
            trigger_error("In valid vote type: ", E_USER_ERROR);
            die();
        }

        break;

    default:
        http_response_code(404);
        exit();
}


