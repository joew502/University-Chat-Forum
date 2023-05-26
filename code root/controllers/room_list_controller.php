<?php


require "./models/rooms_view_model.php";

$UID = $_SESSION["UID"];
$RIC_isAdmin = false;

$model = new RoomsModel();

#check that a block doesnt already exists
if(!$model->blockExists($RIL_hall ?? "`not-set`", $RIL_block ?? "`not-set`")){
    if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
    #throw error if block does not exist
    $ERROR_title = "Block does not exist";
    $ERROR_desc = "The block ".htmlspecialchars($RIL_block)." in room ".htmlspecialchars($RIL_hall).".";
    $ERROR_code = "404";
    include "./views/error.php";
    exit();
}
if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

//Get block ID
$RIC_blockID = $model->getBlockID($RIL_hall, $RIL_block);
if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

//Get block name and content
$block_data = $model->getBlock($RIC_blockID);
if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

//Null $_SESSION['UID'] is fine due to use case
//Get users subscription status
$RPL_subscribed = $model->subscriptionStatus($_SESSION['UID'],$RIC_blockID);
if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

//Get ordered list of members
$RPL_members = $model->getMembers($RIC_blockID);
if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

//Member list string
$MBL_member_list = "";

//Add each member to memberlist
foreach($RPL_members as $member){
    $MBL_member_list .= '<li>'.$member["userName"].' : '.$member["communityPoints"].' Points</li>';
}



//Get ordered list of admins
$RPL_admins = $model->getAdmins($RIC_blockID);
if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

//Admin list string
$MBL_admin_list = "";

//Admin ID list
$MBL_admin_IDs = [];

//Add each member to adminlist
foreach($RPL_admins as $admin){
    $MBL_admin_list .= '<li>'.$admin["userName"].'</li>';
    array_push($MBL_admin_IDs, $admin["userID"]);
    if (isset($UID)){
        if ($UID === $admin["userID"]){
            $RIC_isAdmin = true;
        }
    }
}

//Set admin and member count
$MBL_admin_count = sizeof($RPL_admins);
$MBL_member_count = sizeof($RPL_members);
$BB_count = $MBL_admin_count + $MBL_member_count;

//Set about name and content
$ABOUT_name = array_values($block_data)[0];
$ABOUT_content = htmlspecialchars(array_values($block_data)[1]);

require "./views/room_in_list.php";



