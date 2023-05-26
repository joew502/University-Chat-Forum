<?php

/**
 * function to attempt to register a new user
 * @return bool|string
 */
function REG_register(){
    #check methid
    if($_SERVER["REQUEST_METHOD"] !== "POST"){
        return "Invalid method";
    }

    if(!isset($_POST["email"]) || !isset($_POST["uname"]) || !isset($_POST["pwd"])){
        return "Missing query element";//.": ".(isset($_POST["email"]) ? "" : "Uname,").(isset($_POST["email"]) ? "" : "Email,").(isset($_POST["email"]) ? "" : "Password,");
    }

    #extract data
    $email = $_POST["email"];
    $uname = $_POST["uname"];
    $pwd = $_POST["pwd"];

    //check valid data sent
    require_once "./utils/sani.php";
    if(!SANE_sane($email,"exeter_email")){
        return "Invalid Email";
    }
    if(!SANE_sane($uname,"username")){
        return "Invalid Username";
    }
    if(!SANE_sane($pwd,"password")){
        return "Invalid Password";
    }

    require_once "./models/reg_model.php";

    $model = new RegModel();

    if($model->userNameExists($uname)){
        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
        return "Username already taken";
    }
    if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

    if($model->emailExists($email)){
        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
        return "Email is already taken";
    }
    if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

    //add constant pepper
    require_once "/home/xemcyq1zrauf/sec_groupm/pwd_pepper.php";
    $prehash_pwd = PWD_pepper.$pwd;

    $hashed_password = password_hash($prehash_pwd, PASSWORD_DEFAULT);

    if($hashed_password === false){
        trigger_error("password hash failed for new user: $uname",E_USER_WARNING);
        return "Hash Fault";
    }



    $model->add_new_user($uname,$hashed_password,$email,"Student");
    if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}



    require_once "./utils/login_attempt.php";
    $uid = $model->uidFromName($uname);
    if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}


    _LOGIN_perform_login($uid);

    return True;
}







