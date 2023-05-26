<!--Template for a comment which is filled out by the controller-->
<div class="RP_room">
    <div>
        <div class = "RP_roomInfo RP_roomComment">
            <!--Display room page content-->
            <p class = "RP_path"><?php echo ($RPC_username ?? "User")." > ".($RPC_room ?? "Room");?></p>
            <p class = "RP_time"><?php echo $RPC_ago ?? "? hours ago";?></p>
            <p class = "RP_title"><?php echo $RPC_title ?? "";?></p>
            <p class = "RP_content"><?php echo $RPC_content ?? "I am some content";?></p>
            <p style="display: none"><?php echo $RPC_cid ?? "null";?></p>
            <?php //Logic to only display comment option to logged in users
            if ($_SESSION["auth"] === true){?>
            <button onclick="IRCC_makeComment(this)">Comment</button>
            <div></div>
            <?php } ?>
        </div>
        <div class = "RP_Votes">
            <?php if($_SESSION["auth"]){ ?>
            <button onclick="IRCV_vote_up(this)" <?php if ($RPC_voted_up ?? false) { echo 'class = "RP_voted"';}?>>
                <img src="<?php echo $MC_abspath_cr;?>/assets/icon/up_vote_icon.png" alt="Up-vote">
            </button>
            <?php } ?>
            <p><?php echo $RPC_votes ?? "??????";?></p>
            <?php if($_SESSION["auth"]){ ?>
            <button onclick="IRCV_vote_down(this)" <?php if ($RPC_voted_down ?? false) { echo 'class = "RP_voted"';}?>>
                <img src="<?php echo $MC_abspath_cr;?>/assets/icon/down_vote_icon.png" alt="Down-vote">
            </button>
            <?php } ?>
        </div>
    </div>
    <div class="RP_nested_comments">
        <?php echo $RPC_comment ?? "";?>
    </div>
</div>

