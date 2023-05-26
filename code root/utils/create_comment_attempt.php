<?php
chdir(dirname(__DIR__));
$RV_CCA_ret_loc = $MC_control_path;
/**
 * Function to attempt to create a comment
 * @return bool|string      return true if success, string or error if not
 */
function RV_CCA_create_comment()
{
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        return "Incorrect method";
    }
    if (!$_SESSION["auth"]) {
        return "Not authorised";

    }
    if (!isset($_POST["room"]) || !isset($_POST["comment"]) || !isset($_POST["parent"]) || !isset($_SESSION["UID"]) || !isset($_POST["block"]) || !isset($_POST["hall"])) {
        return "Missing input";
    }

    #extract data
    $roomName =urldecode($_POST["room"]);
    $text = $_POST["comment"];
    $userID = $_SESSION["UID"];
    if ($_POST["parent"] === 'null') {
        $parent = 0;
    } else {
        $parent = intval($_POST["parent"]);
    }
    $block = urldecode($_POST["block"]);
    $hall = urldecode($_POST["hall"]);

    require "./models/inner_room_model.php";
    require "./utils/sani.php";

    if(!SANE_sane($roomName, "room_name")){
        return "Room name contains incorrect characters";
    }
    if(!SANE_sane($text, "comment_text")){
        return "Text contains invalid characters";
    }
    $model = new InnerRoomModel();

    #check if parent comment exists
    if (!$model->commentExists($parent) && $parent !== 0) {
        if ($model->isError()) {
            trigger_error("error occurred in DB transaction: " . $model->getError(), E_USER_ERROR);
            die();
        }
        return "Parent comment missing?";
    }
    if ($model->isError()) {
        trigger_error("error occurred in DB transaction: " . $model->getError(), E_USER_ERROR);
        die();
    }
    #get room ID
    $roomID = $model->getRoomID($hall, $block, $roomName);
    if ($model->isError()) {
        trigger_error("error occurred in DB transaction: " . $model->getError(), E_USER_ERROR);
        die();
    }
    #create comment
    $model->comment($roomID, $userID, $text, $parent);
    if ($model->isError()) {
        trigger_error("error occurred in DB transaction: " . $model->getError(), E_USER_ERROR);
        die();
    }
    global $RV_CCA_ret_loc;
    $RV_CCA_ret_loc .= "/halls/$hall/$block/$roomName";
    return true;
}
