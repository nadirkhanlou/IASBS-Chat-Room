$(function() {
    $('.user-settings-wrapper').hide();
    $('#user-overview-settings-button').on('click', function() {
        $('.user-contacts-wrapper, .user-settings-wrapper').slideToggle();
    });

    $('#user-overview-logout-button').on('click', function() {
        $.ajax({
            url: 'services/logout.php',
            type: 'POST',
            async: !1,
            data: {'logout': 1},
            success: function (resultString) {
                let result = JSON.parse(resultString);
                if(result["success"])
                {
                    console.log("Logged out successfully");
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
    });

    $('#user-contacts-wrapper-div').ready(function() {
        $.ajax({
            url: 'services/contacts.php',
            type: 'POST',
            async: !1,
            data: {'contacts': 1},
            success: function (resultString) {
                result = JSON.parse(resultString);
                if(result["success"])
                {
                    let contacts = result["users"]["contacts"];
                    let blocked = result["users"]["blocked"];
    
                    let contactsList = document.getElementsByClassName('user-contacts-list')[0];
                    let contactsListInner = "";
                    for(let i = 0; i < contacts.length; ++i)
                    {
                        contactsListInner += `<li><span>${contacts[i]['Handle']}</span><span>${contacts[i]['FullName']}</span></li>\n`;
                    }
                    for(let i = 0; i < blocked.length; ++i)
                    {
                        contactsListInner += `<li class="contact-blocked"><span>${blocked[i]['Handle']}</span><span>${blocked[i]['FullName']}</span></li>\n`;
                    }
                    contactsList.innerHTML = contactsListInner;
    
                    console.log(contacts);
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
    });

    $('.user-contacts-list li span:first-of-type').on('click', function() {
        console.log("test");
    });

    $('#user-setting-save-changes-button').on('click', function() {
        let fullName = document.getElementById("full-name").value;
        let handle = document.getElementById("handle").value;
        let currentPassword = document.getElementById("current-password").value;
        let newPassword = document.getElementById("new-password").value;
        if(newPassword == "")
            newPassword = currentPassword;
        $.ajax({
            url: 'services/editInfo.php',
            type: 'POST',
            async: !1,
            data: {'currentPassword': currentPassword, 'newFullName': fullName, 'newHandle': handle, 'newPassword': newPassword},
            success: function (resultString) {
                result = JSON.parse(resultString);
                if(result["success"])
                {
                    let user = result["user"];
                    ShowUserInfo();
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
    });

    $(".user-info").ready(function() {
        ShowUserInfo();
    });
});

function ShowUserInfo() {
    $.ajax({
        url: 'services/getUserInfo.php',
        type: 'GET',
        async: !1,
        data: {'GetUser': 1},
        success: function (resultString) {
            result = JSON.parse(resultString);
            if(result["success"])
            {
                let user = result["user"];
                console.log(user);
                document.getElementById('user-info-handle').innerHTML = user["Handle"];
                document.getElementById('user-info-full-name').innerHTML = user["FullName"];
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