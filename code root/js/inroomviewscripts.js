const IRCC_roomDiv = document.querySelector(".RP_room");
const IRCC_api_target = magicPath+"/control.php/createcomment";
const IRCC_address= window.location.href;

/**
 * Checks if a comment is sanitized
 * @param form The form which contains the comment to sanitize
 * @returns {boolean}
 */
function commentVerify(form){
    let comment = form.firstElementChild.value;
    if(!SANE_sane(comment, "comment_text")) {
        alert("Comment contains invalid characters")
        return false;
    }
    return true;
}

/**
 * creates a form to create a new room
 * @returns {HTMLDivElement}
 */
function IRCC_createTemplateForm(){
    //Create div for form and form variables
    const newdiv = document.createElement('div');
    const c = document.createElement("FORM");
    c.setAttribute("onsubmit","return commentVerify(this);")
    c.setAttribute("action", IRCC_api_target);
    c.setAttribute("method","POST");


    //Create input fields both visible and invisible
    const content = document.createElement("input");
    const submit = document.createElement("input");
    const post_item = document.createElement("input");
    const block_item = document.createElement("input");
    const hall_item = document.createElement("input");

    //Get post block and hall name
    let IRCC_address_array = IRCC_address.split("/");
    let post = IRCC_address_array[IRCC_address_array.length-1];
    let block = IRCC_address_array[IRCC_address_array.length-2];
    let hall = IRCC_address_array[IRCC_address_array.length-3];

    //Set invisible field values
    post_item.setAttribute("type", "hidden");
    post_item.setAttribute("name","room");
    post_item.value= post;
    block_item.setAttribute("type", "hidden");
    block_item.setAttribute("name","block");
    block_item.value=block;
    hall_item.setAttribute("type", "hidden");
    hall_item.setAttribute("name","hall");
    hall_item.value=hall;

    //Set up visible fields
    submit.setAttribute("type", "submit");
    content.setAttribute("type","text");
    content.setAttribute("name","comment");

    //Append fields to form
    newdiv.appendChild(c);
    c.appendChild(content);
    c.appendChild(submit);
    c.appendChild(hall_item);
    c.appendChild(block_item);
    c.appendChild(post_item);

    return newdiv;
}

/**
 * function to create a form to allow a comment to be created
 * This is for a comment that is not a root comment (it is a comment on a comment)
 * @param e
 */
function IRCC_makeComment(button){


    //Handle user not being logged in
    if(!IRCC_Loggedin){
        console.log("Not logged in user making a comment");
        return;
    }

    //Block double open of comment view
    if(button.parentNode.children[6].childElementCount !== 0){
        console.log("Blocked attempted double open");
        return;
    }

    //Get form div
    const newdiv = IRCC_createTemplateForm();

    //Set parent value (needs to be done in this context
    let parentField = document.createElement("input");
    parentField.setAttribute("type", "hidden");
    parentField.setAttribute("name","parent");
    newdiv.firstElementChild.append(parentField);

    button.parentNode.children[6].appendChild(newdiv);
    parentField.value=newdiv.parentNode.parentNode.parentNode.parentNode.firstElementChild.firstElementChild.children[4].innerText;
}

const IRCV_api_target = magicPath+"/api-controllers/vote_comment_api.php";

/**
 * Function to handle upvote button being clicked
 * @param button    upvote button
 */
function IRCV_vote_up(button){
    let votecount = button.parentNode.children[1];
    let downvote_button = button.parentNode.children[2];
    //set up xhttp request
    var xhttp = new XMLHttpRequest();

    //get container div and comment ID
    const containerDiv = button.parentNode.parentNode;
    const commentID = containerDiv.firstElementChild.children[4].innerText;

    //Function to handle state change of xhttp request
    xhttp.onreadystatechange = function () {
        if(xhttp.readyState === 4){
            if(xhttp.status === 200){
                //Successfully voted
                //console.log("Voted");
                //console.log(button.classList.contains("RP_voted"))
                if (!button.classList.contains("RP_voted")) {
                    votecount.innerText = parseInt(votecount.innerText) + 1;
                    //console.log("here")
                    if (downvote_button.classList.contains("RP_voted")) {
                        votecount.innerText = parseInt(votecount.innerText) + 1;
                        downvote_button.classList.toggle("RP_voted");
                    }
                } else if(button.classList.contains("RP_voted")) {
                    votecount.innerText = parseInt(votecount.innerText) - 1;
                }
                button.classList.toggle("RP_voted");
            } else {
                console.log("something went wrong ("+xhttp.status+")");
                console.log(xhttp);
            }
            ongoing_request = false;
        }
    };

    //Send request
    ongoing_request = true;
    xhttp.open("POST",IRCV_api_target);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("action=vote_comment&comment="+commentID+"&type=Up");
}

/**
 * Function to handle downvote button being clicked
 * @param button    down vote button
 */
function IRCV_vote_down(button){
    let votecount = button.parentNode.children[1];
    let upvote_button = button.parentNode.firstElementChild;
    //set up xhttp request
    var xhttp = new XMLHttpRequest();

    //get container div and comment ID
    const containerDiv = button.parentNode.parentNode;
    const commentID = containerDiv.firstElementChild.children[4].innerText;

    //Function to handle state change of xhttp request
    xhttp.onreadystatechange = function () {
        if(xhttp.readyState === 4){
            if(xhttp.status === 200) {
                //Succesfully voted
                //console.log("Voted");
                if (!button.classList.contains("RP_voted")) {
                    votecount.innerText = parseInt(votecount.innerText) - 1;
                    if (upvote_button.classList.contains("RP_voted")) {
                        votecount.innerText = parseInt(votecount.innerText) - 1;
                        upvote_button.classList.toggle("RP_voted");
                    }
                } else if(button.classList.contains("RP_voted")) {
                    votecount.innerText = parseInt(votecount.innerText) + 1;
                }
                button.classList.toggle("RP_voted");
            } else {
                console.log("something went wrong ("+xhttp.status+")");
                console.log(xhttp);
            }
            ongoing_request = false;
        }
    };

    //Send request
    ongoing_request = true;
    xhttp.open("POST",IRCV_api_target);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("action=vote_comment&comment="+commentID+"&type=Down");
}

/**
 * Function to create a form to create a root comment
 * AKA a comment that is on the room not another comment
 * @param e
 */
function IRCC_room_makeComment(button){


    //Handle user not being logged in
    if(!IRCC_Loggedin){
        console.log("Not logged in user making a comment");
        return;
    }

    //Block double open of comment view
    if(button.parentNode.children[6].childElementCount !== 0){
        console.log("Blocked attempted double open");
        return;
    }

    //Get form div
    const newdiv = IRCC_createTemplateForm();

    //Set parent value (needs to be done in this context
    let parentField = document.createElement("input");
    parentField.setAttribute("type", "hidden");
    parentField.setAttribute("name","parent");
    newdiv.firstElementChild.append(parentField);
    button.parentNode.children[6].appendChild(newdiv);
    parentField.value="null";
}

const IRRV_api_target = magicPath+"/api-controllers/vote_room_api.php";

/**
 * function to vote up
 * @param button - the button that clicked this
 */
function IRCV_room_vote_up(button){
    let votecount = button.parentNode.children[1];
    let downvote_button = button.parentNode.children[2];
    //set up xhttp request
    var xhttp = new XMLHttpRequest();

    //Function to handle state change of xhttp request
    xhttp.onreadystatechange = function () {
        if(xhttp.readyState === 4){
            if(xhttp.status === 200){
                //Successfully voted
                //console.log("Voted");
                //console.log(xhttp);
                if (!button.classList.contains("RP_voted")) {
                    votecount.innerText = parseInt(votecount.innerText) + 1;
                    if (downvote_button.classList.contains("RP_voted")) {
                        votecount.innerText = parseInt(votecount.innerText) + 1;
                        downvote_button.classList.toggle("RP_voted");
                    }
                } else if(button.classList.contains("RP_voted")) {
                    votecount.innerText = parseInt(votecount.innerText) - 1;
                }
                button.classList.toggle("RP_voted");
            } else {
                console.log("something went wrong ("+xhttp.status+")");
                console.log(xhttp);
            }
            ongoing_request = false;
        }
    };

    //Send request
    ongoing_request = true;
    xhttp.open("POST",IRRV_api_target);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("block="+IRCC_block+"&room="+IRCC_room+"&type=Up&hall="+IRCC_hall+"&action=vote_room");
}

/**
 * Function to handle downvote button being clicked
 * @param button - the button that clicked this
 */
function IRCV_room_vote_down(button){
    let votecount = button.parentNode.children[1];
    let upvote_button = button.parentNode.firstElementChild;
    //set up xhttp request
    var xhttp = new XMLHttpRequest();

    //Function to handle state change of xhttp request
    xhttp.onreadystatechange = function () {
        if(xhttp.readyState === 4){
            if(xhttp.status === 200) {
                //Successfully voted
                //console.log("Voted");
                //console.log(xhttp);
                if (!button.classList.contains("RP_voted")) {
                    votecount.innerText = parseInt(votecount.innerText) - 1;
                    if (upvote_button.classList.contains("RP_voted")) {
                        votecount.innerText = parseInt(votecount.innerText) - 1;
                        upvote_button.classList.toggle("RP_voted");
                    }
                } else if(button.classList.contains("RP_voted")) {
                    votecount.innerText = parseInt(votecount.innerText) + 1;
                }
                button.classList.toggle("RP_voted");
            } else {
                console.log("something went wrong ("+xhttp.status+")");
                console.log(xhttp);
            }
            ongoing_request = false;
        }
    };

    //Send request
    ongoing_request = true;
    xhttp.open("POST",IRRV_api_target);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("block="+IRCC_block+"&room="+IRCC_room+"&type=Down&hall="+IRCC_hall+"&action=vote_room");
}

/**
 * Shows the form to edit a room
 * @param button - the button that clicked this
 */
function IRRV_editroomactivate(button) {
    let form = document.getElementById("RP_editform");
    let editarea = document.getElementById("RP_content");
    editarea.setAttribute("contenteditable", true);
    let submit = document.createElement("input");
    submit.setAttribute("type", "submit");
    submit.setAttribute("value", "save");
    form.appendChild(submit);
    button.remove();
}

/**
 * Gets edited content for edited room
 * @returns {boolean}
 */
function IRRV_editroomsubmit() {
    var content = document.getElementById("RP_content").innerHTML;
    content = content.replaceAll("&nbsp;"," ");
    document.getElementById("RP_content_form").setAttribute("value", content)
    if(SANE_sane(content,"room_text")){
        return true;
    }else{
        alert("You content contains invalid characters or is too long")
        return false;
    }
}
