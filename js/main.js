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
                let result = JSON.parse(resultString);
                if(result["success"])
                {
                    let contacts = result["users"]["contacts"];
                    let blocked = result["users"]["blocked"];
    
                    let contactsListHTML = "";
                    for(let i = 0; i < contacts.length; ++i)
                    {
                        contactsListHTML += `<li><span>${contacts[i]['Handle']}</span><span>${contacts[i]['FullName']}</span></li>\n`;
                        LoadChat(contacts[i]);
                        
                    }
                    for(let i = 0; i < blocked.length; ++i)
                    {
                        contactsListHTML += `<li class="contact-blocked"><span>${blocked[i]['Handle']}</span><span>${blocked[i]['FullName']}</span></li>\n`;
                    }
                    $('.user-contacts-list').html(contactsListHTML);

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

    $('.user-contacts-list').on('click', 'li span:first-of-type', function() {
        let contactHandle = $(this).html();
        contactHandleSubStr = contactHandle.substr(1, contactHandle.length - 1);
        $('.chat-window-header').html(contactHandle);
        
        $(".chat-window-wrapper").hide();
        $(".chat-window-wrapper#chat-window-" + contactHandleSubStr).show();
        
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
                    $('.user-contacts-wrapper, .user-settings-wrapper').slideToggle();
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

    $(".chat-window-input button").on('click', function() {
        
        handleSubStr = $(this).attr('id');

        let receiverHandle = "@" + handleSubStr;
        let messageText = $('#chat-window-' + handleSubStr + ' .chat-window-input input').val();

        console.log(receiverHandle);
        console.log(messageText);

        $.ajax({
            url: 'services/sendMessage.php',
            type: 'POST',
            async: !1,
            data: {'receiverHandle': receiverHandle, 'messageText': messageText},
            success: function (resultString) {
                let result = JSON.parse(resultString);
                if(result["success"])
                {
                    let dateTime = result["dateTime"];
                    console.log(dateTime);
                    
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

    $('.right-side-wrapper').ready(function() {

    });
});

function ShowMessage(message, isUserSender, isDelivered)
{
    /*
    message properties:
	    SenderHandle, ReceiverHandle, Message, MessageType, DateTime, MessageId,
    */
    return "<li><span>" + message.Message + "</span><span>" + DateTime + "</span><li>";
}

function LoadChat(contact)
{
    let handle = contact["Handle"];
    let handleSubStr = handle.substr(1, handle.length - 1);
    $(".chat-window-wrapper#chat-window-empty").clone(true).appendTo(".right-side-wrapper");
    let newChatWrapper = $(".chat-window-wrapper:last-child");
    newChatWrapper.attr("id","chat-window-" + handleSubStr);
    newChatWrapper.hide();
    //newChatWrapper.find(".chat-window-input input").attr('id', handleSubStr);
    newChatWrapper.find(".chat-window-input button").attr('id', handleSubStr);
    $.ajax({
        url: 'services/getOldMessages.php',
        type: 'POST',
        data: {'contactHandle': handle},
        async: !1,
        success: function (resultString) {
            let result = JSON.parse(resultString);
            if(result["success"])
            {
                let receivedMsg = result["messages"]["receivedMessages"];
                let sentMsg = result["messages"]["sentMessages"];
                let undeliveredMsg = result["messages"]["undeliveredMessages"];
                newChatWrapper.find(".chat-window-body");
                receivedMsgHTML = "";
                let receivedIndex = 0;
                let sentIndex = 0;

                function sortByDate(a, b){
                    aDate = new Date(a.dateTime);
                    bDate = new Date(b.dateTime);
                    return new Date(a.date) - new Date(b.date);
                }

                receivedMsg.sort(sortByDate);
                sentMsg.sort(sortByDate);
                undeliveredMsg.sort(sortByDate);

                for(let i = 0; i < receivedMsg.length + sentMsg.length; ++i) {
                    if(receivedIndex >= receivedMsg.length){
                        receivedMsgHTML += ShowMessage(sentIndex[sentIndex], false, true);
                        sentIndex++;
                    }
                    else if(sentIndex >= sentMsg.length)
                    {
                        receivedMsgHTML += ShowMessage(receivedMsg[receivedIndex], true, true);
                        receivedIndex++;
                    }
                    else
                    {
                        recDate = new Date(receivedMsg[receivedIndex].dateTime);
                        senDate = new Date(sentMsg[sentIndex].dateTime);
                        if(recDate < senDate)
                        {
                            receivedMsgHTML += ShowMessage(receivedMsg[receivedIndex], false, true);
                            receivedIndex++;
                        }
                        else
                        {
                            receivedMsgHTML += ShowMessage(sentIndex[sentIndex], true, true);
                            sentIndex++;
                        }
                    }  
                    
                }

                for(let i = 0; i < undeliveredMsg.length; ++i) {
                    receivedMsgHTML += ShowMessage(undeliveredMsg[i], true, false);
                }

                newChatWrapper.find(".chat-window-body").html(receivedMsgHTML);
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

function ShowUserInfo() {
    $.ajax({
        url: 'services/getUserInfo.php',
        type: 'GET',
        async: !1,
        success: function (resultString) {
            let result = JSON.parse(resultString);
            if(result["success"])
            {
                let user = result["user"];
                console.log(user);
                $("#user-info-handle").html(user["Handle"]);
                $("#user-info-full-name").html(user["FullName"]);
                
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

// window.setInterval(function() {
    
    
//     $.ajax({
//         url: 'services/sendMessage.php',
//         type: 'GET',
//         async: !1,
//         data: {},
//         success: function (resultString) {
//             result = JSON.parse(resultString);
//             if(result["success"])
//             {
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
// }, 1000);
