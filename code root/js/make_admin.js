function MA_check(){
    if(SANE_sane(document.getElementById("MBL_user_name").value,"username")){
        return true;
    } else {
        alert("Username invalid")
        return false;
    }
}
