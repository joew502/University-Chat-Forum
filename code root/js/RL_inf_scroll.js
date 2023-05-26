const roomScrollDiv = document.querySelector('#RLI_scroll');

const RL_api_target = magicPath+"/api-controllers/room_list_api.php";

const rooms_expected = 10;
var room_items_exhausted = false;
var room_added_count = 0;
var ongoing_request = false;


/**
 * add items to a room list should the user scroll be at the bottom
 */
function add_items_if_needed(){
    if(!room_items_exhausted && !ongoing_request && window.scrollY + window.innerHeight >= document.documentElement.scrollHeight - 20)
    {requestAdditionalRooms();}
}

requestAdditionalRooms();



window.addEventListener('scroll',() => {
    add_items_if_needed();
});

/**
 * request that more rooms be requested so they can be added to the list
 */
function requestAdditionalRooms(){
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function () {
        if(xhttp.readyState === 4){
            if(xhttp.status === 200){
                console.log("got rooms!");
                createRooms(JSON.parse(xhttp.response)["rooms"]);
            } else if(xhttp.status === 204){
                console.log("Out of rooms");
                room_items_exhausted = true;
            } else {
                console.log("something went wrong ("+xhttp.status+")");
                console.log(xhttp);
            }

            ongoing_request = false;
        }
    };

    ongoing_request = true;
    xhttp.open("GET",RL_api_target+"?action=get_next_room&rindex="+room_added_count+"&hall="+hall+"&block="+block+"&loadtimestamp="+loadtime+"&rnum="+rooms_expected);
    xhttp.send();
}

/**
 * Turn room data into an html template so that it can be displayed
 * @returns {HTMLDivElement}
 */
function roomTemplate(){
    let room = document.createElement('div')
    room.className = "RLI_room";
    if (userAuth){
        //room display template
        room.innerHTML = `<div>
            <div class="RLI_roomInfo RLI_roomComment">
                <p class="RLI_path"><a href="#">None</a><span> &gt; </span><a href="#">None</a></p>
                <p class="RLI_time"><abbr title="Unknown">? hours ago</abbr></p>
                <p class="RLI_title">NO TITLE FOR YOU</p>
                <p class="RLI_content">I AM A POSTS MAIN CONTENT mwahahhaahahaha...</p>
            </div>
            <div class="RLI_Votes">
                <button onClick="upvote(this)" class="">
                    <img src="`+magicPath+`/assets/icon/up_vote_icon.png" alt="Up-vote">
                </button>
                  <p class="votes"></p>  
                <button onClick="downvote(this)" class="">
                    <img src="`+magicPath+`/assets/icon/down_vote_icon.png" alt="Down-vote">
                </button>
            </div>
        </div>`;
    } else {
        //default values
        room.innerHTML = `<div>
            <div class="RLI_roomInfo RLI_roomComment">
                <p class="RLI_path"><a href="#">None</a><span> &gt; </span><a href="#">None</a></p>
                <p class="RLI_time"><abbr title="Unknown">? hours ago</abbr></p>
                <p class="RLI_title">NO TITLE FOR YOU</p>
                <p class="RLI_content">I AM A POSTS MAIN CONTENT mwahahhaahahaha...</p>
            </div>
            <div class="RLI_Votes">
                  <p class="votes"></p>  
            </div>
        </div>`;
    }

    return room;
}

/**
 * take room data, send to be made into template and add to room list
 * @param roomData
 */
function createRooms(roomData){
    console.log("Here with: ");
    console.log(roomData);
    if(roomData.length<rooms_expected){
        room_items_exhausted = true;
    }

    var room;
    var mn;
    let votecount;
    let upbutton;
    let downbutton;

    for(var i=0;i<roomData.length;i++){
        room = roomTemplate();
        mn = room.firstElementChild.firstElementChild.children;

        //put room data into room template
        mn[0].firstElementChild.innerText = roomData[i]["username"]+" > "+block;
        mn[0].children[2].innerText = hall;
        mn[0].firstElementChild.href = window.location.href.replace(/\/$/, '')+"/"+roomData[i]["name"];
        mn[0].children[2].href = magicPath+"/control.php/halls/"+hall;
        mn[1].firstElementChild.innerText = roomData[i]["ago"];
        mn[2].innerText = roomData[i]["name"];
        mn[3].innerText = roomData[i]["content"];

        if(userAuth){
            upbutton = room.firstElementChild.children[1].firstElementChild;
            downbutton = room.firstElementChild.children[1].children[2];
            votecount = room.firstElementChild.children[1].children[1];
            if (roomData[i]["votedup"]){
                upbutton.classList.add("RLI_voted");
            }
            if (roomData[i]["voteddown"]){
                downbutton.classList.add("RLI_voted");
            }
        } else {
            votecount = room.firstElementChild.children[1].firstElementChild;
        }
        votecount.innerText = roomData[i]["votecount"];

        room_added_count++;
        roomScrollDiv.appendChild(room);
    }

}
