<?php

/**
 * function to attempt to login
 * @return bool|string  true if success, string if false
 */
function LOGIN_pwdlogin(){
    if($_SERVER["REQUEST_METHOD"] !== "POST"){
        return "Invalid method";
    }

    if(!isset($_POST["username"]) || !isset($_POST["password"])){
        return "Missing query element";//.": ".(isset($_POST["email"]) ? "" : "Email,").(isset($_POST["email"]) ? "" : "Password,");
    }

    $uname = $_POST["username"];
    $pwd = $_POST["password"];

    //check valid data sent
    require_once "./utils/sani.php";
    if(!SANE_sane($uname,"username")){
        return "Invalid Username";
    }
    if(!SANE_sane($pwd,"password")){
        return "Invalid Password";
    }

    require_once "./models/login_model.php";

    $model = new LoginModel();

    //check username exists
    if(!$model->userNameExists($uname)){
        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
        return false;
        //note a timing attack is possible against username however
        // because username is public anyway this is a minor concern
    }
    if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}


    $uid = $model->getUserID($uname);
    if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

    if(!$model->isUserActive($uid)){
        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
        return "Account not Active";
    }
    if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

    $correct_pwd_hash = $model->getUserPasswordHash($uid);
    if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

    require_once "/home/xemcyq1zrauf/sec_groupm/pwd_pepper.php";
    $prehash_pwd = PWD_pepper.$pwd;

    if( password_verify($prehash_pwd,$correct_pwd_hash)){
        if (password_needs_rehash($correct_pwd_hash, PASSWORD_DEFAULT)){
            //if this fails don't panic as this is a non-essential operation in this context
            LOGIN_changePassword($uid,$pwd);
        }

        _LOGIN_perform_login($uid);
        return true;
    }
    return false;
}

function LOGIN_changePassword($uid,$new_pwd){
    require_once "./models/login_model.php";
    $model = new LoginModel();

    //add constant pepper
    require_once "/home/xemcyq1zrauf/sec_groupm/pwd_pepper.php";
    $prehash_pwd = PWD_pepper.$new_pwd;

    $hashed_password = password_hash($prehash_pwd, PASSWORD_DEFAULT);

    if($hashed_password === False){
        return false;
    }

    $res = $model->setUserPasswordHash($uid, $hashed_password);
    if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
    return $res;
}


/**
 * Post validation sign in
 *
 * DO NOT CALL WITHOUT DUE CONSIDERATION - there are no checks this just performs the login
 *
 * @param $uid
 */
function _LOGIN_perform_login($uid)
{
    require_once "./models/login_model.php";
    $model = new LoginModel();

    $res = $model->getUserData($uid);
    if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}


    $_SESSION["auth"] = true;
    $_SESSION["UID"] = $uid;
    $_SESSION["uid"] = $uid;
    $_SESSION["username"] = $res[0];
    $_SESSION["global_role"] = $res[1];
    $_SESSION["account_status"] = $res[2];

    require_once "./utils/com_points_util.php";
    grant_comp_if_new_day();
}

