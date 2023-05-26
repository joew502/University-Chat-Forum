<link rel="stylesheet" type="text/css" href="<?php echo $MC_abspath_cr;?>/css/createroomform.css?v=<?php echo time();?>">
<div id="create-room-container" class="CRF-container BB-hidden">
    <!--create room form-->
    <form id="RL_CRV_form" action="<?php echo $MC_abspath_cr;?>/control.php/createRoom"
          method="POST" onsubmit="return verifyRoom();">
        <table class="CRF-table">
            <tr>
                <td>
                    Title:
                </td>
                <td>
                    <input type="text" id="CRF-create-room-title" name="room" maxlength="45" class="CRF-title">
                </td>
            </tr>
            <tr>
                <td>
                    Content:
                </td>
            </tr>
        </table>

        <div id="CRF-body-wrapper" class="CRF-wrapper">
            <textarea name="cont" id="CRF-create-room-body" class="CRF-body"></textarea>
        </div>

        <input name="hall" value="<?php echo $RIL_hall ?? "Academic"; ?>" type="hidden">
        <input name="block" value="<?php echo $RIL_block ?? "testblock"; ?>" type="hidden">
        <!--hidden non optional values that still need to be passed through-->
        <div id="CRF-submit-wrapper" class="CRF-wrapper">
            <input type="submit" id="CRF-submit" class="CRF-submit" value="Create">
        </div>
    </form>
    <div id="CRF-error" class="CRF-failure , BB-hidden"></div>
    <script src="<?php echo $MC_abspath_cr;?>/js/createroom.js?v=<?php echo time();?>"></script>
    <script src="<?php echo $MC_abspath_cr;?>/js/sani.js?v=<?php echo time();?>"></script>
</div>
