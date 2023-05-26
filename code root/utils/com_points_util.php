<?php

/**
 * Gives the user a community point if they haven't logged in 23 hours.
 *
 * @return bool|string - A string if error, true if successful and false if failure.
 */
function grant_comp_if_new_day(){
    if(!$_SESSION["auth"]){
        return "not logged in";
    }

    require_once "./models/com_points_model.php";

    $model = new ComPointsModel();

    #get when the user last received points
    $lastP = $model->getLastGotPoints($_SESSION["uid"]);
    if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

    #if a day ago give new points
    if($lastP+(23*3600) < time()){
        $model->incrementComPoints($_SESSION["uid"]);
        if($model->isError()){
            trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_WARNING);
            return "Unable to grant Community points";
        }
        return true;
    } else {
        return false;
    }
}