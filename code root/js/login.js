/**
 * sanitise function to check a username and password are valid
 * @returns {boolean}   true if success, false if not
 */
function verifyUser(){
    if(!SANE_sane(document.getElementById("login-username").value,"username")){
        alert("Username invalid")
        return false;
    }
    if(!SANE_sane(document.getElementById("login-password").value,"password")){
        alert("password invalid")
        return false;
    }
    return true;
}