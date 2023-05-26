/**
 * Function to copy a rooms contents to clipboard so that it can be shared
 */
function copy(){

    var dummy = document.createElement('input'),
        text = window.location.href;
    document.body.appendChild(dummy);
    dummy.value = text;
    dummy.select();
    document.execCommand('copy');
    document.body.removeChild(dummy);
    var shareButton = document.getElementById("share");
    //console.log(shareButton);
    shareButton.innerText = "Copied!";


}

