const BS_api_target = magicPath+"/api-controllers";

let createView = document.getElementById("create-block-container");
let blockListView = document.getElementById("BLI_blocklist_holder");

/**
 * function to hide all views so that only the selected ones are displayed
 */
function hide_all_views(){
    if (!createView.classList.contains("HB-hidden")){
        createView.classList.toggle("HB-hidden");
    }
    if (!blockListView.classList.contains("HB-hidden")){
        blockListView.classList.toggle("HB-hidden");
    }
}

/**
 * Function to make only the block list be displayed
 */
function onClickBlocks(){
    hide_all_views()
    blockListView.classList.toggle("HB-hidden");
}

/**
 * function to make only the create block view be displayed
 */
function onClickCreate(){
    hide_all_views()
    createView.classList.toggle("HB-hidden");
}

/**
 * function to sanitise the inputs for creating a block
 * @returns {boolean}   returns true if successful, false if not
 */
function onClickCreateBlock(){
    let body = document.getElementById("CRF-create-block-body").value;
    console.log(body)
    let title = document.getElementById("CBF-create-block-title").value;
    if (!SANE_sane(body, "block_desc")){
        alert("Invalid characters in description");
        return false
    }
    if (!SANE_sane(title, "block_name")){
        alert("Invalid characters in title");
        return false
    }
    return true
}