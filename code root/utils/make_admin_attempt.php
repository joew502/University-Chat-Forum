<?php

session_start();
chdir(dirname(__DIR__));

$BV_MA_ret_loc = $MC_control_path;
function BV_MA_make_admin()
{
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        return "Incorrect Method";
    }
    if (!$_SESSION["auth"]) {
        return "Not authenitcated";
    }

    if (!isset($_POST["block"]) || !isset($_SESSION["UID"]) || !isset($_POST['hall'])) {
        return "Request variable not set";
    }

    $userID = $_SESSION["UID"];
    $blockName = urldecode($_POST["block"]);
    $hallName = $_POST["hall"];
    $promoteUsername = $_POST["targetname"];

    require "./utils/sani.php";
    if (!SANE_sane($blockName, "block_name")) {
        return "Block name contains unsupported chars";
    }
    if (!SANE_sane($hallName, "hall")) {
        return "Hall name contains unsupported chars";
    }
    if (!SANE_sane($promoteUsername, "username")) {
        return "Target username does not match format";
    }

    require "./models/rooms_view_model.php";

    $roomModel = new RoomsModel();

//Check block exists
    if (!$roomModel->blockExists($hallName, $blockName)) {
        if ($roomModel->isError()) {
            trigger_error("error occurred in DB transaction: " . $roomModel->getError(), E_USER_ERROR);
            die();
        }
        return "Block does not exist";
    }
    if ($roomModel->isError()) {
        trigger_error("error occurred in DB transaction: " . $roomModel->getError(), E_USER_ERROR);
        die();
    }

//Get blockID
    $blockID = $roomModel->getBlockId($hallName, $blockName);
    if ($roomModel->isError()) {
        trigger_error("error occurred in DB transaction: " . $roomModel->getError(), E_USER_ERROR);
        die();
    }

//Check user is authorised to promote
    if (!$roomModel->isAdmin($userID, $blockID)) {
        if ($roomModel->isError()) {
            trigger_error("error occurred in DB transaction: " . $roomModel->getError(), E_USER_ERROR);
            die();
        }
        return "You are note authorised for this request";
    }

//Get target user ID
    $promoteID = $roomModel->getUserID($promoteUsername);
    if ($roomModel->isError()) {
        trigger_error("error occurred in DB transaction: " . $roomModel->getError(), E_USER_ERROR);
        die();
    }

//Check if not already admin
    if (!$roomModel->isAdmin($promoteID, $blockID)) {
        if ($roomModel->isError()) {
            trigger_error("error occurred in DB transaction: " . $roomModel->getError(), E_USER_ERROR);
            die();
        }

        //Check user is subscribed
        if (!$roomModel->isMember($promoteID, $blockID)) {
            if ($roomModel->isError()) {
                trigger_error("error occurred in DB transaction: " . $roomModel->getError(), E_USER_ERROR);
                die();
            }
            return "User is not subscribed";
        }
        //Make them admin
        $roomModel->makeAdmin($promoteID, $blockID);
        if ($roomModel->isError()) {
            trigger_error("error occurred in DB transaction: " . $roomModel->getError(), E_USER_ERROR);
            die();
        }
    } else {
        //User is already admin
        if ($roomModel->isError()) {
            trigger_error("error occurred in DB transaction: " . $roomModel->getError(), E_USER_ERROR);
            die();
        }
        return "User is already an admin";
    }
    if ($roomModel->isError()) {
        trigger_error("error occurred in DB transaction: " . $roomModel->getError(), E_USER_ERROR);
        die();
    }
    global $BV_MA_ret_loc;
    $BV_MA_ret_loc .= "/halls/$hallName/$blockName";
    return true;
}