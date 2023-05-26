<?php

chdir(dirname(__DIR__));

if($_SERVER["REQUEST_METHOD"] !== "GET"){
    http_response_code(405);
    die();
}
if(!isset($_GET["action"])){
    http_response_code(400);
    die();
}
switch($_GET["action"]){

    case "get_next_block":

        #check data exists
        if(!isset($_GET["bindex"]) || !isset($_GET["hall"]) || !isset($_GET["loadblid"]) || !isset($_GET["bnum"])){
            http_response_code(400);
            die();
        }
        $hallName = $_GET["hall"];


        require "./models/blocks_view_model.php";

        $model = new BlocksModel();
        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

        #extract data
        $bindex = $_GET["bindex"];
        $loadblid = $_GET["loadblid"];
        $bnum = $_GET["bnum"];

        require "./utils/sani.php";
        if (!SANE_sane($hallName, "hall")) {
            http_response_code(400);
            die();
        }
        $blocks = $model->getBlockFromStampAtIndex($hallName,$loadblid,$bindex,$bnum);
        if($model->isError()){trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);die();}

        if(count($blocks) === 0){
            http_response_code(204);
            exit();
        }
        $output = "{ \"blocks\":[";

        #loop through each block and add to a JSON file format
        foreach($blocks as $block){
            $block = array_values($block);
            $bname = $block[1];
            $bdescription = htmlspecialchars($block[2]);

            $output .= "{ \"name\":\"$bname\", \"description\":\"$bdescription\"},";
        }

        $output = rtrim($output,",");

        $output .= "]}";

        echo $output;
        break;

    default:
        http_response_code(404);
        exit();
}


