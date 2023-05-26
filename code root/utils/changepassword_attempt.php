<?php

/**
 * change password attempt.
 * @return bool|string  return true if success. or string explaining reason for fail
 */
function CPA_change() {
    #check logged in
    if(!$_SESSION["auth"]){
        return "Not Logged In";
    }
    #check method is post
    if($_SERVER["REQUEST_METHOD"] !== "POST"){
        return "Invalid method";
    }
    if(!isset($_POST["CP_pwd"])){
        return "Missing query element";
    }
    $new_pwd = $_POST["CP_pwd"];
    //check valid data sent
    require_once "./utils/sani.php";
    if(!SANE_sane($new_pwd,"password")){
        return "Invalid Password";
    }
    $userID = $_SESSION["UID"];

    require "./models/changepassword_model.php";

    $model = new CPModel();

    //Check that the user exists
    if(!$model->userExists($userID)){
        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),
            E_USER_ERROR);die();}
        return "User does not exist";
    }

    require "./utils/login_attempt.php";

    if(false === LOGIN_changePassword($userID,$new_pwd)) {
        return "Password Change Failed";
    }
    return true;

}