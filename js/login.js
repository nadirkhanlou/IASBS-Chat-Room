var infoValidation = {name: false, handle: false, phoneNum: false, password: false};

$(function () {
    $('.user-settings-wrapper').hide();
    $('#user-overview-settings-button').on('click', function() {
        $('.user-contacts-wrapper, .user-settings-wrapper').slideToggle();
        infoValidation = {name: false, handle: false, phoneNum: true, password: true};
    });

    $('.message a').click(function(){
        $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
    });

    // Input mask for phone number field in registration form
    $("#phone-number").inputmask({
        mask: "\\0\\999 999 9999",
        onincomplete: function() {
            $("#phone-number").css('border', '3px solid red');
            infoValidation['phoneNum'] = false;
        },
        oncomplete: function() {
            $("#phone-number").css('border', '3px solid green');
            infoValidation['phoneNum'] = true;
        }
    });

    // Input mask for handle field in registration form
    $("#handle").inputmask({
        mask: "@*{50}",
        placeholder: "",
        casing: "lower",
    });

    $("#user-settings-handle").inputmask({
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
        infoValidation['name'] = false;
    } else {
        elem.style = "border: solid green;";
        infoValidation['name'] = true;
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
                $('#' + elem.id).css('border', 'solid green');
                infoValidation['handle'] = true;
                $('#' + elem.id + '-availability-message').html("");
                $('#' + elem.id + '-availability-message').css('display', 'none');
            }
            else
            {
                $('#' + elem.id).css('border', 'solid red');
                console.log(result["errorMessage"]);
                infoValidation['handle'] = false;
                $('#' + elem.id + '-availability-message').html("This handle is already taken.");
                $('#' + elem.id + '-availability-message').css('display', 'inline');
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

    regex = /^[a-zA-Z0-9\=\*\$\#\!\+\-]{8,24}$/;

    if (elem.id == "new-password" && inputText.length == 0) {
        console.log("new passssssssssss");
        infoValidation['password'] = true;
    }
    if (!regex.test(inputText)) {
        $('#' + elem.id).css('border', `solid red`);
        $('#' + elem.id + '-error-message').html("Password must be 8 to 24 characters long and contain only a-zA-Z0-9=*$#!+-");
        $('#' + elem.id + '-error-message').css('display', 'inline');
        infoValidation['password'] = false;
    } else {
        $('#' + elem.id).css('border', `solid green`);
        $('#' + elem.id + '-error-message').html("");
        $('#' + elem.id + '-error-message').css('display', 'none');
        infoValidation['password'] = true;
    }
}

function CreateAccount() {
    let fullName = document.getElementById("full-name").value;
    let handle = document.getElementById("handle").value;
    let phoneNumber = document.getElementById("phone-number").value;
    let password = document.getElementById("password").value;
    phoneNumber = phoneNumber.replace(/ /g,'')

    if (infoValidation['name'] && infoValidation['handle'] && infoValidation['phoneNum'] && infoValidation['password']) {
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
                    $('#login-box-message').html("You successfully created an account. You can now log in.");
                    $('#login-box-message').css('display', 'inline');
                }
                else
                {
                    console.log(result["errorMessage"]);
                    $('#create-account-box-message').html("Failed to create account. Check your connection and try again.");
                    $('#create-account-box-message').css('display', 'inline');
                    $('#login-box-message').html("");
                    $('#login-box-message').css('display', 'none');
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                console.log("Status: " + textStatus);
                console.log("Error: " + errorThrown); 
            }
        });
    } else {
        $('#create-account-box-message').html("Failed to create account. Check your information and try again.");
        $('#create-account-box-message').css('display', 'inline');
        $('#login-box-message').html("");
        $('#login-box-message').css('display', 'none');
    }
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