<link rel="stylesheet" type="text/css" href="<?php echo $MC_abspath_cr;?>/css/hallBanner.css?v=<?php echo time();?>">
<div id ="HB-main" class="HB-main">
    <!--Top banner for hall view-->
    <div id="HB-title" class="HB-hall-title"><?php echo $BIL_hall ?? "default"; ?></div>
    <div id="HB-description" class="HB-description"><?php echo $HB_description ?? "??????"; ?></div>
    <div id="HB-buttons-wrapper" class="HB-buttons-wrapper">
        <button id="HB-about" class="HB-button" onclick="onClickBlocks()">Blocks</button>
        <?php if($_SESSION["auth"]){?><button id="HB-create-block" class="HB-button" onclick="onClickCreate()">Create Block</button><?php } ?>

    </div>
    <a href="<?php echo $MC_abspath_cr."/control.php/home"; ?>"><div id="HB-back" class="HB-back">Back to home</div></a>
</div>
