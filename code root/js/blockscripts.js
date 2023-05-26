//api address
const BS_api_target = magicPath+"/api-controllers";

let aboutView = document.getElementById("ABOUT-main");
let memberView = document.getElementById("MBL-main");
let createView = document.getElementById("create-room-container");
let roomListView = document.getElementById("RLI_roomlist_holder");

/**
 * function to hide all views. The selected view is then made visible elsewhere
 */
function hide_all_views(){
    //if an item can be hidden, hide it
    if (!aboutView.classList.contains("BB-hidden")){
        aboutView.classList.toggle("BB-hidden");
    }
    if (!memberView.classList.contains("BB-hidden")){
        memberView.classList.toggle("BB-hidden");
    }
    if (!createView.classList.contains("BB-hidden")){
        createView.classList.toggle("BB-hidden");
    }
    if (!roomListView.classList.contains("BB-hidden")){
        roomListView.classList.toggle("BB-hidden");
    }
}

/**
 * Show about view and hide others
 */
function onClickAbout(){
    hide_all_views();
    aboutView.classList.toggle("BB-hidden");
}

/**
 * Show member list view and hide others
 */
function onClickMember(){
    hide_all_views();
    memberView.classList.toggle("BB-hidden");
}

/**
 * Show create room view and hide others
 */
function onClickCreate(){
    hide_all_views();
    createView.classList.toggle("BB-hidden");
}

/**
 * Show rooms view and hide others
 */
function onClickRooms(){
    hide_all_views();
    roomListView.classList.toggle("BB-hidden");
}

/**
 * function to upvote a room via api
 * @param button    The vote button
 */
function upvote(button){
    let votecount = button.parentNode.children[1];
    let downvote_button = button.parentNode.children[2];
    let post = button.parentNode.parentNode;
    let title = post.children[0].children[2].innerText;

    //Create xhttp request and set params
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4) {
            if (xhttp.status === 200) {
                if (!button.classList.contains("RLI_voted")) {
                    //the user has not voted this way before: add an upvote
                    votecount.innerText = parseInt(votecount.innerText) + 1;

                    if (downvote_button.classList.contains("RLI_voted")) {
                        //if the user has downvoted undownvote
                        votecount.innerText = parseInt(votecount.innerText) + 1;
                        downvote_button.classList.toggle("RLI_voted");
                    }


                } else if(button.classList.contains("RLI_voted")) {
                    //if the user has voted before unvote
                    votecount.innerText = parseInt(votecount.innerText) - 1;

                }

                button.classList.toggle("RLI_voted")
            } else {
                console.log("Failed voting");
                console.log(xhttp)
            }
        }
    };
    xhttp.open("POST", BS_api_target+"/vote_room_api.php");
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("block="+block+"&room="+title+"&type=Up&UID="+userID+"&hall="+hall+"&action=vote_room");
}

/**
 * function to downvote a room via api
 * @param button    Down vote button
 */
function downvote(button){
    let votecount = button.parentNode.children[1];
    let upvote_button = button.parentNode.firstElementChild;
    let post = button.parentNode.parentNode;
    let title = post.children[0].children[2].innerText;

    //Create xhttp request and set params
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4) {
            if (xhttp.status === 200) {
                if (!button.classList.contains("RLI_voted")) {
                    //the user has not voted this way before: ad down vote
                    votecount.innerText = parseInt(votecount.innerText) - 1;

                    if (upvote_button.classList.contains("RLI_voted")) {
                        //if the user has upvoted: unvote
                        votecount.innerText = parseInt(votecount.innerText) - 1;
                        upvote_button.classList.toggle("RLI_voted");
                    }


                } else if(button.classList.contains("RLI_voted")) {
                    //if the user has downvoted before: undownvote
                    votecount.innerText = parseInt(votecount.innerText) + 1;
                }
                button.classList.toggle("RLI_voted")
            } else {
                console.log("Failed voting");
                console.log(xhttp)
            }
        }
    };
    xhttp.open("POST", BS_api_target+"/vote_room_api.php");
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("block="+block+"&room="+title+"&type=Down&UID="+userID+"&hall="+hall+"&action=vote_room");
}

/**
 * Function to subscribe a user to a block
 */
function onClickSubscribe(){
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function (){
        if (this.readyState === 4) {
            if (xhttp.status === 200) {
                location.reload();
            } else {
                console.log("Failed to subscribe user ");
                console.log(xhttp)
            }
        }

    };
    xhttp.open("POST", BS_api_target+"/subscribe_api.php");
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("block="+block+"&hall="+hall+"&action=subscribe");
}

/**
 * function to unsubscribe a user from a block
 */
function onClickUnsubscribe (){
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function (){
        if (this.readyState === 4) {
            if (xhttp.status === 200) {
                location.reload();
            } else {
                console.log("Failed to unsubscribe user ");
                console.log(xhttp)
            }
        }
    };
    xhttp.open("POST", BS_api_target+"/subscribe_api.php");
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("block="+block+"&hall="+hall+"&action=unsubscribe");
}

/**
 * Sanitising function to make sure that a block description in valid
 * @returns {boolean}
 * @constructor
 */
function BA_getcontent() {
    document.getElementById("ABOUT-body-value").setAttribute("value", document.getElementById("ABOUT-content").innerText);
    if(!SANE_sane( document.getElementById("ABOUT-body-value").value, "block_desc")){
        alert("Invalid characters in description");
        return false;
    }
}

/**
 * function to allow a blocks description to be editable
 * @param button    Button to allow the description to be editable
 * @constructor
 */
function BA_showeditform(button) {
    document.getElementById("ABOUT-content").setAttribute("contenteditable", true);
    document.getElementById("ABOUT-content").setAttribute("role", "textbox");
    submit = document.createElement("input");
    submit.setAttribute("type","submit");
    submit.setAttribute("value", "submit");
    document.getElementById("BA-edit-form").appendChild(submit);
    button.remove();
}

