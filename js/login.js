$(function () {
    $('.message a').click(function(){
        $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
    });

    // Input mask for phone number field in registration form
    $("#phone-number").inputmask({
        mask: "\\0\\999 999 9999",
        onincomplete: function() {
            $("#phone-number").css("border", "3px solid red");
        },
        oncomplete: function() {
            $("#phone-number").css("border", "3px solid green");
        }
    });

    // Input mask for handle field in registration form
    $("#handle").inputmask({
        mask: "@*{50}",
        placeholder: "",
        casing: "lower",
    });

    // Input mask for handle field in login form
    $("#login-handle").inputmask({
        mask: "@*{50}",
        placeholder: "",
        casing: "lower",
    });

    $(".login-form button").on('click', function() {
        let handle = document.getElementById("login-handle").value;
        let password = document.getElementById("login-password").value;
        $.ajax({
            url: 'services/login.php',
            type: 'POST',
            async: !1,
            data: {handle: handle, password: password},
            success: function (resultString) {
                result = JSON.parse(resultString);
                if(result["success"])
                {
                    let user = result["user"];
                    location.reload();
                }
                else
                {
                    console.log(result["errorMessage"]);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                console.log("Status: " + textStatus);
                console.log("Error: " + errorThrown); 
            }
        });
    })
});

function ValidateNameInput(elem) {
    let inputText = elem.value;

    let regex = /^[A-Za-z0-9 ]*$/;
    if (inputText.length < 1 || inputText.length > 50 || !regex.test(inputText)) {
        elem.style = "border: solid red;";
    } else {
        elem.style = "border: solid green;";
    }
}

function CheckHandleAvailability(elem) {
    let inputText = elem.value;

    $.ajax({
        url: 'services/handleCheck.php',
        type: 'POST',
        async: !1,
        data: {handle: inputText},
        success: function (resultString) {
            result = JSON.parse(resultString);
            if(result["success"])
            {
                elem.style = "border: solid green;";
            }
            else
            {
                elem.style = "border: solid red;";
                console.log(result["errorMessage"]);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            console.log("Status: " + textStatus);
            console.log("Error: " + errorThrown); 
        }
    });
}

function CheckPassword(elem) {
    let inputText = elem.value;

    regex = /[a-zA-Z0-9\=\*\$\#\!\+\-]{8,24}/;

    if (inputText.length == 0) {
        return;
    }
    if (inputText.length < 8) {
        elem.style = "border: solid red;";
    } else if (inputText.length < 25) {
        if (!regex.test(inputText)) { // Password does not meet the requirements
            elem.style = "border: solid red;";
        } else {
            elem.style = "border: solid green;";
        }
    } else {
        elem.style = "border: solid red;";
    }
}

function CreateAccount() {
    let fullName = document.getElementById("full-name").value;
    let handle = document.getElementById("handle").value;
    let phoneNumber = document.getElementById("phone-number").value;
    let password = document.getElementById("password").value;
    phoneNumber = phoneNumber.replace(/ /g,'')
    $.ajax({
        url: 'services/createAccount.php',
        type: 'POST',
        async: !1,
        data: { fullName: fullName, handle: handle, phoneNumber: phoneNumber, password: password},
        success: function (resultString) {
            let result = JSON.parse(resultString);
            if(result["success"])
            {
                $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
            }
            else
            {
                console.log(result["errorMessage"]);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            console.log("Status: " + textStatus);
            console.log("Error: " + errorThrown); 
        }
    });
}

// function Login() {
//     let handle = document.getElementById("login-handle").value;
//     let password = document.getElementById("login-password").value;
//     $.ajax({
//         url: 'services/login.php',
//         type: 'POST',
//         async: !1,
//         data: {handle: handle, password: password},
//         success: function (resultString) {
//             result = JSON.parse(resultString);
//             if(result["success"])
//             {
//                 user = result["user"];
//                 $('#content-wrapper-div').load("view/shared/content.html");
//             }
//             else
//             {
//                 console.log(result["errorMessage"]);
//             }
//         },
//         error: function(XMLHttpRequest, textStatus, errorThrown) { 
//             console.log("Status: " + textStatus);
//             console.log("Error: " + errorThrown); 
//         }
//     });
// }