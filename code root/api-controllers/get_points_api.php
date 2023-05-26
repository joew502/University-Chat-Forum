<?php

session_start();
chdir(dirname(__DIR__));

if($_SERVER["REQUEST_METHOD"] !== "GET"){
    http_response_code(405);
    die();
}
if(!isset($_GET["action"])){
    http_response_code(400);
    die();
}

switch($_GET["action"]){

    case "get_points":

        if(!$_SESSION["auth"]) {
            http_response_code(401);
            die();
        }

        if(!isset($_SESSION["UID"])){
            http_response_code(400);
            die();
        }

        $userID = $_SESSION["UID"];

        require "./models/account_view_model.php";

        $model = new AccountModel();

        #Check that the block does not already exist
        if(!$model->userExists($userID)){
            if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
            http_response_code(400);
            die();
        }
        #get a users points, roomvotes and comment votes so their score can be later made
        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
        $points = $model->getPoints($userID);
        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
        $roomVotes = $model->getRoomVotes($userID);
        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
        $commentVotes = $model->getCommentVotes($userID);
        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

        $output = "{ \"academic\":$points[0], \"community\":$points[1], \"meta\":$points[2], \"room\":$roomVotes, \"comment\":$commentVotes}";

        echo $output;
        break;

    default:
        http_response_code(404);
        exit();
}


