<?php

//Require model and time ago util
require "./models/inner_room_model.php";
require "./utils/agogen.php";

//Create model
$model = new InnerRoomModel();

//Get userID
$userID = $_SESSION['UID'];

//Get rooms ID from hall, block and title
$IR_RID = $model->getRoomID($IR_hall ?? "Community",$IR_block ?? "TEST", $IR_room ?? "TestRoom");
if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

//Get content of room
$room = $model->getRoom($IR_RID);
if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
//Get rooms timestamp
$roomTime = $model->getRoomTime($IR_RID);
if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
//Get top level comments
$comments = $model->getComments($IR_RID);
if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
$RPR_votes = $model->getRoomVotes($IR_RID);
if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
$comments_votes = $model->getCommentVotes($IR_RID);
if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
$RPC_user_votes = $model->getUserCommentVotes($userID);
if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
//Get votes for the room
$InnerRoom_votes = $model->getRoomVotes($IR_RID);
if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
//Check for voting and get vote type
$RoomVoteType = $model->getRoomVoteType($userID, $IR_RID);
if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}
//Get username of room owner
$IR_username = $model->getUsername($room[5]);
if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}


//Parse comments sql
$roots = array();
foreach($comments as $value) {
    $row = array_values($value);
    if ($row[4] === null) {
        array_push($roots, $row);
    }
}

//parse comment votes:
$comment_vote_lookup = [];
foreach ($comments_votes as $comvo_pair) {
    $comvo_pair = array_values($comvo_pair);
    $comment_vote_lookup[$comvo_pair[0]] = $comvo_pair[1];
}

//Set comment content as null by default
$RP_content = "";
ob_start();

//First display room details
?>
        <div>
            <?php $RPC_title = htmlspecialchars($room[1]);
            $RPC_content = htmlspecialchars($room[2]);
            $RPC_ago = get_ago_time($roomTime);
            $RPC_hall = $IR_hall;
            $RPC_block = $IR_block;
            $RPC_cid = $IR_RID;
            $RPR_votes = $InnerRoom_votes;
            $RPR_username = $IR_username;
            if(isset($RoomVoteType)){
                $RPR_vote_type = $RoomVoteType;
            }
            if (!isset($_SESSION['UID'])){
                $RPC_authorised = false;
            }else{
                $RPC_authorised = $room[5] === $_SESSION["UID"];
            }
            require "./views/roompage_room.php";?>
        </div>
        <div id="RP_comments">
            <?php
            //Display comments and recursively display nested comments
            $RP_content .= ob_get_clean();
            function comments($comments, $root, $MC_abspath_cr){
                global $IR_hall;
                global  $IR_block;
                global  $IR_room;
                global $comment_vote_lookup;
                global $RPC_user_votes;
                $RPC_comment = "";
                $RPC_cid = $root[0];
                $RPC_content = htmlspecialchars($root[2]);
                $RPC_ago = get_ago_time($root[3]);
                $RPC_username = $root[1];
                $RPC_hall = $IR_hall;
                $RPC_block = $IR_block;
                $RPC_room = $IR_room;
                $RPC_votes = $comment_vote_lookup[$root[0]] ?? 0;
                $RPC_voted_up = false;
                $RPC_voted_down = false;
                foreach ($RPC_user_votes as $voted_comment){
                    if ($voted_comment["commentID"] === $RPC_cid){
                        if ($voted_comment["voteType"] === "Up"){
                            $RPC_voted_up = true;
                        } elseif ($voted_comment["voteType"] === "Down"){
                            $RPC_voted_down = true;
                        }
                    }
                }
                foreach($comments as $value){
                    $row = array_values($value);
                    if ($row[4]===$root[0]) {
                        $RPC_comment .= comments($comments, $row, $MC_abspath_cr);
                    }
                }
                //Display comment
                ob_start();
                require "./views/roompage_comment.php";
                return ob_get_clean();
            }

            foreach($roots as $value){
                $RP_content .= comments($comments, $value, $MC_abspath_cr);
            }

            ob_start();
            ?>
        </div>
<?php
$RP_content .= ob_get_clean();

//Require view
require "./views/roompage.php";