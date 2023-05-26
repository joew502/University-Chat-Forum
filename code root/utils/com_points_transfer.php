<?php

//username point amount

/**
 * function to transfer community points
 * @return bool|string  returns true if success. if failer returns string of reason
 */
function CPT_com_points_trans(){
    if($_SERVER["REQUEST_METHOD"] !== "POST"){
        return "Invalid method";
    }

    if(!$_SESSION["auth"] || !isset($_SESSION["uid"])){
        return "Not logged in";
    }

    if(!isset($_POST["username"]) || !isset($_POST["points"])){
        return "Missing query element";
    }

    #extract data from post
    $uname = $_POST["username"];
    $upoints = $_POST["points"];

    //check valid data sent
    require_once "./utils/sani.php";
    if(!SANE_sane($uname,"username")){
        return "Invalid Username";
    }

    if(!is_numeric($upoints)){
        return "Non-number points supplied";
    }

    $points = intval($upoints);
    if("$points" !== $upoints){
        return "Non-int points supplied";
    }

    if($points < 0){
        return "Non-positive point value";
    }

    require_once "./models/com_points_model.php";

    $model = new ComPointsModel();

    #check username exists
    if(!$model->userNameExists($uname)){
        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
        return "target user does not exist";
    }
    if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

    #get users ID
    $recuid = $model->getUserID($uname);
    if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

    if($model->getComPointTotal($_SESSION["uid"]) < $points){
        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
        return "You do not have enough points";
    }
    if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

    #transfer points
    $model->transferPoints($_SESSION["uid"],$recuid,$points);
    if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

    return True;
}