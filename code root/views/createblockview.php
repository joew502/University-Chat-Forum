<link rel="stylesheet" type="text/css" href="<?php echo $MC_abspath_cr;?>/css/createblockform.css?v=<?php echo time();?>">
<div id="create-block-container" class="CBF-container HB-hidden">
    <!--Form to create a block-->
    <form id="BL_CBV_form" action="<?php echo $MC_abspath_cr;?>/control.php/createblock" method="POST" onsubmit="return onClickCreateBlock();">
        <table class="CBF-table">
            <tr>
                <td>
                    Title:
                </td>
                <td>
                    <input type="text" id="CBF-create-block-title" name="block" maxlength="45" class="CBF-title">
                </td>
            </tr>
            <tr>
                <td>
                    Description:
                </td>
            </tr>
        </table>

        <div id="CBF-body-wrapper" class="CBF-wrapper">
            <textarea name="desc" id="CRF-create-block-body" class="CBF-body"></textarea>
        </div>

        <input name="hall" value="<?php echo $BIL_hall ?? "Academic"; ?>" type="hidden">

        <div id="CBF-submit-wrapper" class="CBF-wrapper">
            <input type="submit" id="CBF-submit" class="CBF-submit" value="Create">
        </div>
    </form>
    <div id="CBF-error" class="CBF-failure HB-hidden"></div>
</div>
