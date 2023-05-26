<?php

/**
 * Gets the users point values.
 *
 * @return array of point values or a string on error
 */
function GP_getpoints() {
    if(!$_SESSION["auth"]){
        return "Not Logged In";
    }
    $userID = $_SESSION["UID"];

    require_once "./models/account_view_model.php";

    $model = new AccountModel();

    #Check that the user exists
    if(!$model->userExists($userID)){
        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);
    die();}
        return "User does not exist";
    }
    #get the users points, roomvotes and comment votes
    if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
    $points = $model->getPoints($userID);
    if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
    $roomVotes = $model->getRoomVotes($userID);
    if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
    $commentVotes = $model->getCommentVotes($userID);
    if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

    return [$points[0], $points[1], $points[2], $roomVotes, $commentVotes];
}
