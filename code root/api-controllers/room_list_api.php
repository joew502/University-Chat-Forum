<?php
session_start();
chdir(dirname(__DIR__));

if($_SERVER["REQUEST_METHOD"] !== "GET"){
    http_response_code(405);
    die();
}
if(!isset($_GET["action"])){
    http_response_code(400);
    die();
}


switch($_GET["action"]) {

    case "get_next_room":
        #check for data
        if (!isset($_GET["rindex"]) || !isset($_GET["hall"]) || !isset($_GET["block"]) || !isset($_GET["loadtimestamp"]) || !isset($_GET["rnum"])) {
            http_response_code(400);
            die();
        }
        if (isset($_SESSION["UID"])) {
            $UID = $_SESSION["UID"];
        }
        $hallName = $_GET["hall"];
        $blockName = $_GET["block"];

        require "./models/rooms_view_model.php";


        require "./utils/sani.php";

        if (!SANE_sane($hallName, "hall")){
            http_response_code(400);
            die();
        }

        if (!SANE_sane($blockName, "block_name")){
            http_response_code(400);
            die();
        }

        $model = new RoomsModel();
        #check block doesnt already exist
        if (!$model->blockExists($hallName, $blockName)) {
            if ($model->isError()) {
                trigger_error("error occurred in DB transaction: " . $model->getError(), E_USER_ERROR);
                die();
            }
            http_response_code(400);
            die();
        }
        if ($model->isError()) {
            trigger_error("error occurred in DB transaction: " . $model->getError(), E_USER_ERROR);
            die();
        }

        #get the blocks ID
        $blockID = $model->getBlockId($hallName, $blockName);
        if ($model->isError()) {
            trigger_error("error occurred in DB transaction: " . $model->getError(), E_USER_ERROR);
            die();
        }

        #extract data
        $rindex = $_GET["rindex"];
        $loadts = $_GET["loadtimestamp"];
        $rnum = $_GET["rnum"];

        $rooms = $model->getRoomFromStampAtIndex($blockID, $loadts, $rindex, $rnum);
        if ($model->isError()) {
            trigger_error("error occurred in DB transaction: " . $model->getError(), E_USER_ERROR);
            die();
        }

        if (isset($_SESSION["UID"])) {
            $subscribedRooms = $model->getUserRoomVotes($UID);
            if ($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

        }

        if (count($rooms) === 0) {
            http_response_code(204);
            exit();
        }

        //parse personal votes:
        $per_vote_lookup_array = [];
        if (isset($subscribedRooms)) {
            foreach ($subscribedRooms as $subscribedRoom) {
                $per_vote_lookup_array[$subscribedRoom["roomID"]] = $subscribedRoom["voteType"];
            }
        }

        $roomsBlockVotes = $model->getRoomsVotesInBlock($blockID);
        if ($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

        //parse general votes:
        $gen_vote_lookup_array = [];
        if (isset($roomsBlockVotes)) {
            foreach ($roomsBlockVotes as $roomBlockVotes) {
                $gen_vote_lookup_array[$roomBlockVotes["roomID"]] = $roomBlockVotes["votes"];
            }
        }

        $output = "{ \"rooms\":[";

        require "./utils/agogen.php";

        #loop through rooms and format each into an an item to be added to json dict
        foreach($rooms as $room){
            $room = array_values($room);
            $roomID = intval($room[0]);
            $roomvotes = $gen_vote_lookup_array[$roomID] ?? 0;

            //has the user voted
            $voteddown = false;
            $votedup = false;
            if(isset($per_vote_lookup_array[$roomID])){
                if($per_vote_lookup_array[$roomID] == "Up"){
                    $votedup = true;
                } else {
                    $voteddown = true;
                }
            }

            $rname = $room[1];
            $rcont = $room[2];
            $rtime = $room[3];
            $uname = $room[5];
            $rago = get_ago_time($room[3]);

            $output .= "{ \"name\":\"$rname\", \"content\":\"$rcont\", \"timestamp\":\"$rtime\", \"ago\":\"$rago\", \"votecount\": $roomvotes, \"username\" : \"$uname\", \"votedup\" : \"$votedup\",\"voteddown\" : \"$voteddown\" },";
        }

        $output = rtrim($output,",");

        $output .= "]}";


        echo $output;
        break;

    default:
        http_response_code(404);
        exit();
}


