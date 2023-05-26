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

    case "vote_comment":

        if(!$_SESSION["auth"]) {
            http_response_code(401);
            die();
        }

        #check data exists
        if(!isset($_POST["comment"]) || !isset($_SESSION["UID"]) || !isset($_POST["type"])){
            http_response_code(400);
            die();
        }

        #extract data
        $commentID = $_POST["comment"];
        $userID = $_SESSION["UID"];
        $type = $_POST["type"];

        require "./models/inner_room_model.php";

        $model = new InnerRoomModel();

        if(!$model->commentExists($commentID)) {
            if ($model->isError()) {
                trigger_error("error occurred in DB transaction: " . $model->getError(), E_USER_ERROR);
                die();
            }
            http_response_code(400);
            die();
        }
        if ($model->isError()) {
            trigger_error("error occurred in DB transaction: " . $model->getError(), E_USER_ERROR);
            die();
        }
        #check if there is already a comment vote and delete or update as relevant
        $VCA_db_vote_type = $model->hasCommentVoted($userID, $commentID);
        if($VCA_db_vote_type !== ""){
            if ($model->isError()) {
                trigger_error("error occurred in DB transaction: " . $model->getError(), E_USER_ERROR);
                die();
            }
            if($VCA_db_vote_type === $type){
                $model->deleteCommentVote($userID, $commentID);
                if ($model->isError()) {
                    trigger_error("error occurred in DB transaction: " . $model->getError(), E_USER_ERROR);
                    die();
                }
            } else {
                $model->updateCommentVote($userID, $commentID, $type);
            }

        }
        #otherwise add a new comment vote depending on the type given
        elseif ($type === "Up"){
            if ($model->isError()) {
                trigger_error("error occurred in DB transaction: " . $model->getError(), E_USER_ERROR);
                die();
            }
            $model->upCommentVote($commentID, $userID);
        }
        elseif ($type === "Down"){
            if ($model->isError()) {
                trigger_error("error occurred in DB transaction: " . $model->getError(), E_USER_ERROR);
                die();
            }
            $model->downCommentVote($commentID, $userID);
        }
        else{
            trigger_error("In valid vote type: ", E_USER_ERROR);
            die();
        }
        if ($model->isError()) {
            trigger_error("error occurred in DB transaction: " . $model->getError(), E_USER_ERROR);
            die();
        }

        break;

    default:
        http_response_code(404);
        exit();
}


