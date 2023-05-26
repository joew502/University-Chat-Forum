<?php

require "./models/blocks_view_model.php";

$model = new BlocksModel();

#blid = block list id
$BLC_lblid = $model->getMxBlid();
if($model->isError()){
    trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);
    die();
}

$HB_description = $model->getHallDesc($BIL_hall);
if($model->isError()){
    trigger_error("error occurred in DB transaction: ".$model->getError(),E_USER_ERROR);
    die();
}


require "./views/block_in_list.php";



