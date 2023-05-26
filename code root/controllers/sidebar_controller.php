<?php

require "./models/sidebar_model.php";

$SC_model = new SidebarModel();

#default for blocks that are already subscribed to
$SB_academic_blocks = "";
$SB_community_blocks = "";
$SB_society_blocks = "";

if($_SESSION["auth"]) {
    $SC_userID = $_SESSION["UID"];
    $SC_blocks = $SC_model->getBlocks($SC_userID);
    if ($SC_model->isError()) {
        trigger_error("error occurred in DB transaction: " . $SC_model->getError(), E_USER_ERROR);
        die();
    }

    #extract blocks
    $SC_hall = array();
    foreach ($SC_blocks as $SC_block) {
        $SC_row = array_values($SC_block);
        array_push($SC_hall, $SC_row);
    }

    #seperate blocks into relevant halls and format into links
    foreach ($SC_hall as $SC_block) {
        $SC_blockName = $SC_block[0];
        if ($SC_block[1] === 'Academic') {
            ob_start();
            $SB_academic_blocks .= "<a class='SB-block-links' href=$MC_abspath_cr/control.php/halls/academic/".urlencode($SC_blockName).">$SC_blockName</a>";
            $SB_academic_blocks .= ob_get_clean();
        } elseif ($SC_block[1] === 'Community') {
            ob_start();
            $SB_community_blocks .= "<a class='SB-block-links' href=$MC_abspath_cr/control.php/halls/community/".urlencode($SC_blockName).">$SC_blockName</a>";
            $SB_community_blocks .= ob_get_clean();
        } elseif ($SC_block[1] === 'Society') {
            ob_start();
            $SB_society_blocks .= "<a class='SB-block-links' href=$MC_abspath_cr/control.php/halls/society/".urlencode($SC_blockName).">$SC_blockName</a>";
            $SB_society_blocks .= ob_get_clean();
        }
    }
}

#defaults for if there are no subscribed to blocks
if($SB_academic_blocks === ""){
    $SB_academic_blocks = "<a class='SB-block-links' href='#'>No subscriptions found</a>";
}
if($SB_community_blocks === ""){
    $SB_community_blocks = "<a class='SB-block-links' href='#'>No subscriptions found</a>";
}
if($SB_society_blocks === ""){
    $SB_society_blocks = "<a class='SB-block-links' href='#'>No subscriptions found</a>";
}


require "./views/sidebar.php";

