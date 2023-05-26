<?php
session_start();
chdir(dirname(__DIR__));

//Check that method is GET
if($_SERVER["REQUEST_METHOD"] !== "GET"){
    http_response_code(405);
    die();
}
//Check if user is logged in
if(!$_SESSION["auth"]) {
    http_response_code(401);
    die();
}
//Check username is set in request
if(!isset($_GET["username"])){
    http_response_code(400);
    die();
}

//Get requested username
$userName = $_GET["username"];

require "./utils/sani.php";
if (!SANE_sane($userName, "username")){
    http_response_code(400);
    die();
}

//Setup model
require "./models/account_view_model.php";
$model = new AccountModel();
$response = sizeof($model->userNameExists($userName));
if ($model->isError()) {
    trigger_error("error occurred in DB transaction: " . $model->getError(), E_USER_ERROR);
    die();
}
if ($response === 1){
    echo 1;
} else {
    echo 0;
}