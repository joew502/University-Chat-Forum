/**
 * Verify password matches requirements
 * @returns {boolean}
 */
function verifyPasswords(){
    let statusDiv = document.getElementById("register-status");
    let password = document.getElementById("register-password").value;
    let username = document.getElementById("register-username").value;
    let email = document.getElementById("register-email").value;
    statusDiv.classList.remove("success","failure");

    if (matchingPasswords() && passwordLength() && SANE_sane(email, "exeter_email") && SANE_sane(username, "username") && SANE_sane(password, "password")){
        //If here conditions are met submit form
        return true;
    } else{
        //Display the failure div
        statusDiv.innerHTML = "";
        statusDiv.classList.add("register-failure");
        statusDiv.innerHTML = "Failure</br>";
        if (!matchingPasswords()) {
            //Display that passwords don't match
            statusDiv.innerHTML = statusDiv.innerHTML + "Passwords do not match</br>";
        }
        if (!passwordLength()){
            //Display that password is of the incorrect length
            statusDiv.innerHTML = statusDiv.innerHTML + "Password must be between 8 and 32 characters long</br>";
        }
        if (!SANE_sane(email, "exeter_email")){
            //Display that email does not contain @exeter.ac.uk
            statusDiv.innerHTML = statusDiv.innerHTML + "Email must be an @exeter.ac.uk address and not contain invalid characters</br>";
        }
        if (!SANE_sane(username, "username")){
            //Display username does not fit required format
            statusDiv.innerHTML = statusDiv.innerHTML + "Username must be between 4 and 30 characters and must be alphanumeric. </br>";
        }
        if (!SANE_sane(password, "password")){
            //Display password contains an invalid character
            statusDiv.innerHTML = statusDiv.innerHTML + "Password contains an invalid character. </br>";
        }
    }
    return false;
}

/**
 * Verify passwords match requirements when changing
 * @returns {boolean}
 */
function verifyPasswordChange(){
    let statusDiv = document.getElementById("register-status");
    let password = document.getElementById("register-password").value;
    statusDiv.classList.remove("success","failure");
    if (matchingPasswords() && passwordLength() && SANE_sane(password, "password")){
        //If here conditions are met submit form
        return true;
    } else{
        //Display the failure div
        statusDiv.innerHTML = "";
        statusDiv.classList.add("register-failure");
        statusDiv.innerHTML = "Failure</br>";
        if (!matchingPasswords()) {
            //Display that passwords don't match
            statusDiv.innerHTML = statusDiv.innerHTML + "Passwords do not match</br>";
        }
        if (!passwordLength()){
            //Display that password is of the incorrect length
            statusDiv.innerHTML = statusDiv.innerHTML + "Password must be between 8 and 32 characters long</br>";
        }

        if (!SANE_sane(password, "password")){
            //Display password contains an invalid character
            statusDiv.innerHTML = statusDiv.innerHTML + "Password contains an invalid character. </br>";
        }
    }
    return false;
}


/**
 * verify passwords match
 * @returns {boolean}
 */
function matchingPasswords(){
    let password1 = document.getElementById("register-password").value;
    let password2 = document.getElementById("register-confirm-password").value;
    return password1 === password2;
}

/**
 * verify password matches length requirements
 * @returns {boolean}
 */
function passwordLength(){
    let password = document.getElementById("register-password").value;
    return password.length <= 32 && password.length >= 8;
}
