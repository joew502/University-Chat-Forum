<link rel="stylesheet" type="text/css" href="<?php echo $MC_abspath_cr?>/css/memberlist.css?v=<?php echo time();?>">

<div id="MBL-main" class="MBL-main BB-hidden">
    <?php if ($RIC_isAdmin ?? false){?>
    <div id="MBL-make-admin-wrapper" class="MBL-make-admin-wrappers">

        <!--Section to allow you to make another member an admin, is hidden if user is not an admin-->
        <form action="<?php echo $MC_abspath_cr;?>/control.php/promotemember" method="POST" onsubmit="MA_check()">
            <div class="MBL-make-admin-wrapper">
            <input type="text" id="MBL_user_name" name="targetname" maxlength="30" class="MBL_username">
            <input name="block" value="<?php echo $RIL_block ?? "testblock"; ?>" type="hidden">
            <input name="hall" value="<?php echo $RIL_hall ?? "testblock"; ?>" type="hidden">
            <div id="MBL-submit-wrapper" class="MBL-wrapper">
                <input type="submit" id="MBL_submit" class="MBL_submit" value="Promote">
            </div>
            </div>
        </form>
    </div>
    <?php }?>
    <div id="MBL-admin-wrapper" class="MBL-wrappers">
        <!--Display block admins-->
        <div id="MBL-admin-count">Admins : <?php echo $MBL_admin_count ?? "??"?></div>
        <div id="MBL-admin-list"><?php echo $MBL_admin_list ?? "<li>Admin1</li><li>Admin2</li>"?></div>
    </div>
    <div id="MBL-members-list-wrapper" class="MBL-wrappers">
        <!--Display block members-->
        <div id="MBL-member-count">Members : <?php echo $MBL_member_count ?? "??????"?></div>
        <div id="MBL-member-list"><?php echo $MBL_member_list ?? "<li>Memeber1</li><li>Memeber2</li>"?></div>
    </div>
</div>
