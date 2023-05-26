const blockScrollDiv = document.querySelector('#BLI_scroll');

const BL_api_target = magicPath+"/api-controllers/block_list_api.php";

const blocks_expected = 10;
var block_items_exhausted = false;
var block_added_count = 0;
var ongoing_request = false;

/**
 * Function to add items(blocks) to the block list that the user can scroll through
 */
function add_items_if_needed(){
    if(!block_items_exhausted && !ongoing_request && window.scrollY + window.innerHeight >= document.documentElement.scrollHeight - 20)
    {requestAdditionalBlocks();}
}
requestAdditionalBlocks();


window.addEventListener('scroll',() => {
    add_items_if_needed();
});


/**
 * Function to request More blocks from the models so that they can be displayed on the block list
 */
function requestAdditionalBlocks(){
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function () {
        if(xhttp.readyState === 4){
            if(xhttp.status === 200){
                console.log("got blocks!");
                var resp = xhttp.response;
                var regi = /((\r\n)|(\n))/gm;
                resp = resp.replace(regi,"<br>")
                regi = /[\\]+/gm;
                resp = resp.replace(regi,"")
                //console.log(resp);
                createBlocks(JSON.parse(resp)["blocks"]);
            } else if(xhttp.status === 204){
                console.log("Out of blocks");
                block_items_exhausted = true;
            } else {
                console.log("something went wrong ("+xhttp.status+")");
                console.log(xhttp);
            }
            ongoing_request = false;
        }
    }

    ongoing_request = true;
    xhttp.open("GET",BL_api_target+"?action=get_next_block&bindex="+block_added_count+"&hall="+hall+"&loadblid="+loadblid+"&bnum="+blocks_expected);
    xhttp.send();
}

/**
 * function to createt the template in which the blocks should be displayed
 * @returns {HTMLDivElement}    Blocks to return once formatted into html template
 */
function blockTemplate(){
    let block = document.createElement('div')
    block.className = "BLI_block";
    //html template
    block.innerHTML = `<div>
            <div class="BLI_blockInfo">
                <p class="BLI_title"><a href="">None</a></p>
                <p class="BLI_content">NO TITLE FOR YOU</p>
            </div>
        </div>`;
    return block;
}

/**
 * Function to extract block data and put it into the template form. This is then appended to the scroll list
 * @param blockData The data of the given block
 */
function createBlocks(blockData){
    console.log("Here with: ");
    console.log(blockData);
    if(blockData.length<blocks_expected){
        block_items_exhausted = true;
    }

    var block;
    var mn;

    for(var i=0;i<blockData.length;i++){
        block = blockTemplate()
        //put block data into the template
        mn = block.firstElementChild.firstElementChild.children;
        mn[0].firstElementChild.innerText = blockData[i]["name"]+" > "+hall;
        mn[0].firstElementChild.href = (window.location.href.replace(/\/$/, '')+"/"+blockData[i]["name"]);
        mn[1].innerHTML = blockData[i]["description"];
        block_added_count++;
        blockScrollDiv.appendChild(block);
    }
}

