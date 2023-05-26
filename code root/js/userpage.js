/**
 * opens the chosen tab so that it can be viewed
 * @param UP_buttonName The name of button passed through
 * @param UP_tabName    The tab you wish to open
 */
function openTab(UP_buttonName, UP_tabName) {
    let i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("UP_tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("UP_tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(UP_tabName).style.display = "block";
    document.getElementById(UP_buttonName).className += " active";
}

/**
 * checks if the settings tab should be open or not
 * @param UP_settingsactive if it is 'active' or not
 */
function activeTab(UP_settingsactive) {
    if (UP_settingsactive === 'active') {
        document.getElementById("UP_settings").style.display = "block";
    } else {
        document.getElementById("UP_account").style.display = "block";
    }
}

/**
 * sanitise check to make sure a username is valid
 */
function verifySendXP(){
    if(SANE_sane(document.getElementById("UP-receiver-username").value,"username")){
        return true;
    } else {
        alert("Username invalid")
        return false;
    }
}

/**
 * function to check if a user exists or not
 */
function checkUserExists(){
    let username = document.getElementById("UP-receiver-username").value;
    let xhttp = new XMLHttpRequest();
    let status = document.getElementById("AP_user_status");
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4) {
            if (xhttp.status === 200) {
                if (xhttp.response === "1"){
                    status.innerText = "User Found";
                } else {
                    status.innerText = "User Missing";
                }
            } else {
                console.log("Failed checking for user");
                console.log(xhttp)
            }
        }
    };
    if(SANE_sane(username,"username")){
        xhttp.open("GET", UE_api_path+"/user_existence_api.php?username="+username);
        xhttp.send();
    } else {
        alert("Username invalid")
    }
}