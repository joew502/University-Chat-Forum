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

#actions can be 'subscribe' or 'unsubscribe#
switch($_POST["action"]){

    case "subscribe":

        if(!$_SESSION["auth"]) {
            http_response_code(401);
            die();
        }

        if(!isset($_POST["block"]) || !isset($_POST["hall"]) || !isset($_SESSION["UID"])){
            http_response_code(400);
            die();
        }

        #extract data
        $userID = $_SESSION["UID"];
        $block = $_POST["block"];
        $hall = $_POST["hall"];

        require "./utils/sani.php";

        #check data is valid
        if (!SANE_sane($block, "block_name")){
            http_response_code(400);
            exit();
        }
        if (!SANE_sane($hall, "hall")){
            http_response_code(400);
            exit();
        }

        require "./models/blocks_view_model.php";

        $model = new BlocksModel();

        #check block doesnt already exist
        if(!$model->blockExists($block, $hall)){
            if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
            http_response_code(400);
            die();
        }

        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
        $blockID = $model->getBlockID($block, $hall);
        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
        $model->subscribeBlock($userID, $blockID);
        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

        break;
    case "unsubscribe":

        if(!$_SESSION["auth"]) {
            http_response_code(401);
            die();
        }

        if(!isset($_POST["block"]) || !isset($_POST["hall"]) || !isset($_SESSION["UID"])){
            http_response_code(400);
            die();
        }

        #extract data
        $userID = $_SESSION["UID"];
        $block = $_POST["block"];
        $hall = $_POST["hall"];

        require "./utils/sani.php";
        #check data is valid
        if (!SANE_sane($block, "block_name")){
            http_response_code(400);
            exit();
        }
        if (!SANE_sane($hall, "hall")){
            http_response_code(400);
            exit();
        }

        require "./models/blocks_view_model.php";

        $model = new BlocksModel();

        if(!$model->blockExists($block, $hall)){
            if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
            http_response_code(400);
            die();
        }

        #get the blocks ID and unsubscribe the user from that block
        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
        $blockID = $model->getBlockID($block, $hall);
        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
        $model->unsubscribeBlock($userID, $blockID);
        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

        break;
    default:
        http_response_code(404);
        exit();
}