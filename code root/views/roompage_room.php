<!-- Container -->
<div class="RP_room">
    <div>
        <!-- Text Content-->
        <div class = "RP_roomInfo RP_roomComment">
            <p class = "RP_path">
                <span><?php echo ($RPR_username ?? "User")?> &gt; </span>
                <a href="<?php echo $MC_abspath_cr."/control.php/halls/".($RPC_hall ?? "Academic")."/".$RPC_block ?? "Block"; ?>"><?php echo ($RPC_block ?? "Block")?></a><span> &gt; </span>
                <a href="<?php echo $MC_abspath_cr."/control.php/halls/".($RPC_hall ?? "Academic"); ?>"><?php echo ($RPC_hall ?? "Hall")?></a>
            </p>
            <p class = "RP_time"><?php echo $RPC_ago ?? "? hours ago";?></p>
            <p class = "RP_title"><?php echo $RPC_title ?? "";?></p>
            <p class = "RP_content" id="RP_content"><?php echo $RPC_content ?? "I am some content";?></p>
            <p style="display: none"><?php echo $RPC_cid ?? "null";?></p>

            <?php if ($_SESSION["auth"] === true){?>
            <button onclick="IRCC_room_makeComment(this)">Comment</button>
            <?php } ?>

            <div></div>

            <button class="share" ID="share" onclick="copy()">Share</button>
            <?php if ($RPC_authorised ?? false){?>
            <button onclick="IRRV_editroomactivate(this)">Edit</button>
            <form action="<?php echo $MC_abspath_cr."/control.php/editroom"; ?>" method="post" id="RP_editform" onsubmit="return IRRV_editroomsubmit()">
                <input type="hidden" name="roomID" value="<?php echo $RPC_cid ?? "null";?>">
                <input type="hidden" name="content" id="RP_content_form" value="">
                <input type="hidden" name="block" value="<?php echo ($RPC_block ?? "Undefined");?>">
                <input type="hidden" name="hall" value="<?php echo ($RPC_hall ?? "Undefined");?>">
                <input type="hidden" name="title" value="<?php echo ($RPC_title ?? "Undefined");?>">
            </form>
            <?php } ?>

        </div>
        <!-- Voting buttons -->

        <div class="RP_Votes">
            <?php if($_SESSION["auth"]){ ?>
            <button onclick="IRCV_room_vote_up(this)" <?php if(($RPR_vote_type ?? "null") === "Up"){ echo 'class=RP_voted';} ?>>
                <img src="<?php echo $MC_abspath_cr;?>/assets/icon/up_vote_icon.png" alt="Up-vote">
            </button>
            <?php } ?>
            <p><?php echo $RPR_votes ?? "??????";?></p>
            <?php if($_SESSION["auth"]){ ?>
            <button onclick="IRCV_room_vote_down(this)" <?php if(($RPR_vote_type ?? "null") === "Down"){ echo 'class=RP_voted';} ?>>
                <img src="<?php echo $MC_abspath_cr;?>/assets/icon/down_vote_icon.png" alt="Down-vote" >
            </button>
            <?php } ?>
        </div>
        <script src="<?php echo $MC_abspath_cr;?>/js/copyToClipboard.js?>"></script>
    </div>
</div>

