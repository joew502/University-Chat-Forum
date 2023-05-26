<link rel="stylesheet" type="text/css" href="<?php echo $MC_abspath_cr?>/css/blockabout.css?v=<?php echo time();?>">
<div id="ABOUT-main" class="ABOUT-main BB-hidden">

    <div id="ABOUT-title"><h3><?php echo $ABOUT_name ?? "Block:"; ?></h3></div>
    <div id="ABOUT-content"><?php echo $ABOUT_content ?? "description"; ?></div>
    <?php if ($RIC_isAdmin ?? false){?>
    <button class="ABOUT-form-button" onclick="BA_showeditform(this)">Edit</button>
    <form action="<?php echo $MC_abspath_cr;?>/control.php/editblock" method="POST" onsubmit=" return BA_getcontent()" id="BA-edit-form">
        <input id="ABOUT-body-value" name="desc" type="hidden">
            <input name="block" value="<?php echo $RIL_block ?? "undefined" ?>" type="hidden" >
            <input name="hall" value="<?php echo $RIL_hall ?? "undefined" ?>" type="hidden" >
    </form>
    <?php }?>
    <!-- view for describing a block-->
</div>