function isNumber(number) {
    if (/^[0-9\-]+$/.test(number)) {
        return true;
    }
    return false;
}

function isPassword(pass) {
    if (/^[a-zA-Z0-9_+!#$*=]*$/.test(pass)){
        return true;
    }
    return false;
}

function isUsername(pass) {
    if (/^[a-zA-Z0-9_]*$/.test(pass)){
        return true;
    }
    return false;
}

function isEnglish(name) {
    if (/^[A-Za-z0-9\s]*$/.test(name)){
        return true;
    }
    return false;
}





function check(){

    var error = "<br/>";

    //get and check first name and last name and username
    var name = document.getElementsByName("uiFname")[0].value;
    var last_name = document.getElementsByName("uiLname")[0].value;
    var username = document.getElementsByName("uiUsername")[0].value;
    

    if (name.length <3 || name.length >50)
         error += "*Invalid Name<br/>"
    if (last_name.length <3 || last_name.length >100)
        error += "*Invalid Last Name<br/>"
    if (!isEnglish(name)) 
        error += "*Enter Your Name In English<br/>"
    if (!isEnglish(last_name)) 
        error += "*Enter Your Last Name In English<br/>"
    if (!isUsername(username)) 
        error += "*Only Use English Letters, Numbers, _ In Your Username<br/>"
    if (username == "") {
        error += "*A Unique Username Is Required<br />"
    }
        

    //get and check phone number
    var pnumber = document.getElementsByName("uiPhone")[0].value;

    if (pnumber == "") {
        error += "*Phone Number Is Required<br />"
    }
    else if (pnumber.length != 11 || !isNumber(pnumber)) {
        error += "*Invalid Phone Number<br />"
    }

    // get and check passwords
    var password1 = document.getElementsByName("uiPass")[0].value;
    var password2 = document.getElementsByName("uiPass_rep")[0].value;

    if (password1.length <8 || password1.length >24){
        error += "*Password Should Be btween 8 & 24 Characters<br />"
    }
    else if (!isPassword(password1)) {
        error += "*Password Format Is Not Supported <br />"
    }
    else if (password1 != password2){
        error += "*Passwords Does Not Match <br />"
    }
    
    var submit = document.getElementsByName("uiSubmit")[0].checked;
    document.getElementById("uiMessage").innerHTML = error;
    return false;
}