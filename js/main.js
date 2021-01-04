
var IsEditing = false;
var EditingMessageId = 0;

$(function() {
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
            type: 'GET',
            async: !1,
            data: {},
            success: function (resultString) {
                let result = JSON.parse(resultString);
                if(result["success"])
                {
                    let contacts = result["users"]["contacts"];
                    let blocked = result["users"]["blocked"];
    
                    let contactsListHTML = "";
                    for(let i = 0; i < contacts.length; ++i)
                    {
                        let handleSubStr = contacts[i]['Handle'];
                        handleSubStr = handleSubStr.substr(1, handleSubStr.length - 1);

                        contactsListHTML += `<li id="${handleSubStr}"><span>${contacts[i]['Handle']}</span><span>${contacts[i]['FullName']}</span></li>\n`;
                        LoadChat(contacts[i]);
                        
                    }
                    for(let i = 0; i < blocked.length; ++i)
                    {
                        let handleSubStr = blocked[i]['Handle'];
                        handleSubStr = handleSubStr.substr(1, handleSubStr.length - 1);

                        contactsListHTML += `<li class="contact-blocked" id="${handleSubStr}"></span><span>${blocked[i]['FullName']}</span></li>\n`;
                        LoadChat(blocked[i]);
                    }
                    $('.user-contacts-list').html(contactsListHTML);

                    window.setInterval(function() {
                        UpdateContacts();
                    }, 1000);
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

    $('.user-contacts-list').on('click', 'li', function() {
        let contactHandle = $(this).find('span:first-of-type').html();

        if(IsEditing){
            IsEditing = false;
            $(`.chat-window-body #${EditingMessageId} .message-bubble`).html("");
        }

        if(contactHandle.charAt(0) == '@') {
            contactHandleSubStr = contactHandle.substr(1, contactHandle.length - 1);
            $(".chat-window-wrapper").hide();
            $(".chat-window-wrapper#chat-window-" + contactHandleSubStr).show();
        }
        else
        {
            contactHandleSubStr = $(this).attr('id');

            $.ajax({
                url: 'services/unblockUser.php',
                type: 'POST',
                async: !1,
                data: {'contactHandle': '@' + contactHandleSubStr},
                success: function (resultString) {
                    let result = JSON.parse(resultString);
                    if(result["success"])
                    {
                        $(`.user-contacts-list #${contactHandleSubStr}`).attr('class', '');
                        contactItemHtml = $(`.user-contacts-list #${contactHandleSubStr}`).html();
                        contactItemHtml = `<span>@${contactHandleSubStr}</span>` + contactItemHtml;
                        $(`.user-contacts-list #${contactHandleSubStr}`).html(contactItemHtml)
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
        
    });

    $('#user-setting-save-changes-button').on('click', function() {
        let fullName = document.getElementById("user-settings-full-name").value;
        let handle = document.getElementById("user-settings-handle").value;
        let currentPassword = document.getElementById("current-password").value;
        let newPassword = document.getElementById("new-password").value;
        if(newPassword == "")
            newPassword = currentPassword;

        if (infoValidation['name'] && infoValidation['handle'] && infoValidation['phoneNum'] && infoValidation['password']) {
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
        }
    });

    $(".user-info").ready(function() {
        ShowUserInfo();
    });

    $(".chat-window-input").submit(function (e) {
        e.preventDefault();
    });
    
    $(".chat-window-input button").on('click', function() {
        
        handleSubStr = $(this).attr('id');

        let receiverHandle = "@" + handleSubStr;

        let messageText = $('#chat-window-' + handleSubStr + ' .chat-window-input input').val();


        if(IsEditing)
        {
            $.ajax({
                url: 'services/editMessage.php',
                type: 'POST',
                async: !1,
                data: {'receiverHandle': receiverHandle, 'message': messageText, 'messageId': EditingMessageId},
                success: function (resultString) {
                    let result = JSON.parse(resultString);
                    if(result["success"])
                    {
                        $(`.chat-window-body #${messageId} .message-bubble`).html(messageText);
                        IsEditing = false;
                        $('#chat-window-' + handleSubStr + ' .chat-window-input input').val("");
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
        else
        {

            $.ajax({
                url: 'services/sendMessage.php',
                type: 'POST',
                async: !1,
                data: {'receiverHandle': receiverHandle, 'messageText': messageText},
                success: function (resultString) {
                    let result = JSON.parse(resultString);
                    if(result["success"])
                    {
                        let message = result["message"];
                        let chatWrapper = $('#chat-window-' + handleSubStr);
                        let msgHTML = "";
                        msgHTML += ShowMessage(message, true, false);
                        chatWrapper.find(".chat-window-body").append(msgHTML);
                        $('#chat-window-' + handleSubStr + ' .chat-window-input input').val("");
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

    });
});

function ShowMessage(message, isUserSender, isDelivered)
{
    /*
    message properties:
	    SenderHandle, ReceiverHandle, Message, MessageType, DateTime, MessageId,
    */
    let divClass = isUserSender ? "\"sent-message\"" : "\"received-message\"";

    let receiverHandle = message.ReceiverHandle;
    receiverHandle = receiverHandle.substr(1, receiverHandle.length - 1);

    messageElement = 
                   `<div id=${message.MessageId}>
                       <div class=${divClass}>
                           <span class="message-id" style="display: none">${message.MessageId}</span>
                           <span class="message-bubble">${message.Message}</span>
                           <span class="message-container">
                               <span class="message-date-time">${message.DateTime}</span>
                               ${isUserSender ? `<i class="fas fa-trash-alt ${message.MessageId}" title="delete" id="${receiverHandle}" onclick="DeleteMessage(this.id, this.className)"></i>‌` : ``}
                               ${isUserSender ? `<i class="fas fa-pencil-alt ${message.MessageId}" title="edit" id="${receiverHandle}" onclick="EditMessage(this.id, this.className)"></i>` : ``}
                               ${isUserSender ? `${isDelivered ? `<i class="fas fa-check-double" title="delivered"></i>` : `<i class="fas fa-check" title="sent"></i>`}` : ``}
                           </span>
                       </div>
                   </div>`

    return messageElement;
}

function LoadChat(contact)
{
    let handle = contact["Handle"];
    let fullName = contact["FullName"];
    let handleSubStr = handle.substr(1, handle.length - 1);
    $(".chat-window-wrapper#chat-window-empty").clone(true).appendTo(".right-side-wrapper");
    let newChatWrapper = $(".chat-window-wrapper:last-child");
    newChatWrapper.attr("id","chat-window-" + handleSubStr);
    newChatWrapper.hide();
    //newChatWrapper.find(".chat-window-input input").attr('id', handleSubStr);
    newChatWrapper.find('.chat-window-header').html(
        `<span class="chat-window-contact-full-name">${fullName}</span>
        <span class="chat-window-contact-handle">${handle}</span>
        <span><i title="block" class="fas fa-ban fa-1x" onclick="BlockUser(this.id)" id="chat-user-block" title="block"></i></span>`
    );
    newChatWrapper.find('.chat-window-header i').attr('id', handleSubStr); //block
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

                receivedMsg.sort(SortByDate);
                sentMsg.sort(SortByDate);
                undeliveredMsg.sort(SortByDate);

                for(let i = 0; i < receivedMsg.length + sentMsg.length; ++i) {
                    if(receivedIndex >= receivedMsg.length){
                        receivedMsgHTML += ShowMessage(sentMsg[sentIndex], true, true);
                        sentIndex++;
                    }
                    else if(sentIndex >= sentMsg.length)
                    {
                        receivedMsgHTML += ShowMessage(receivedMsg[receivedIndex], false, true);
                        receivedIndex++;
                    }
                    else
                    {
                        recDate = new Date(receivedMsg[receivedIndex].DateTime);
                        senDate = new Date(sentMsg[sentIndex].DateTime);
                        if(recDate < senDate)
                        {
                            receivedMsgHTML += ShowMessage(receivedMsg[receivedIndex], false, true);
                            receivedIndex++;
                        }
                        else
                        {
                            receivedMsgHTML += ShowMessage(sentMsg[sentIndex], true, true);
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

function SortByDate(a, b){
    aDate = new Date(a.DateTime);
    bDate = new Date(b.DateTime);
    return aDate - bDate;
}

function DeleteMessage(receiverHandleSubStr, className) {
    classNames = className.split(' ');
    messageId = classNames[classNames.length - 1];
    $.ajax({
        url: 'services/deleteMessage.php',
        type: 'POST',
        async: !1,
        data: {'messageId': messageId, 'receiverHandle': '@' + receiverHandleSubStr},
        success: function (resultString) {
            let result = JSON.parse(resultString);
            if(result["success"])
            {
                $(`.chat-window-body #${messageId}`).remove();
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

function BlockUser(handleSubStr) {
    $.ajax({
        url: 'services/blockUser.php',
        type: 'POST',
        async: !1,
        data: {'contactHandle': '@' + handleSubStr},
        success: function (resultString) {
            let result = JSON.parse(resultString);
            if(result["success"])
            {
                $(`#chat-window-empty`).show();
                $(`#chat-window-${handleSubStr}`).hide();
                $(`.user-contacts-list #${handleSubStr}`).attr('class', 'contact-blocked');
                $(`.user-contacts-list #${handleSubStr} span:first-child`).remove();
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

function GetAndEditMessage(messageId) {
    $.ajax({
        url: 'services/getMessageById.php',
        type: 'POST',
        async: !1,
        data: {'messageId': messageId},
        success: function (resultString) {
            let result = JSON.parse(resultString);
            if(result["success"])
            {
                $(`.chat-window-body #${messageId} .message-bubble`).html(result['message'].Message);
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

function EditMessage(receiverHandleSubStr, className) {
    classNames = className.split(' ');
    messageId = classNames[classNames.length - 1];
    
    message = $(`.chat-window-body #${messageId} .message-bubble`).html();
    $(`#chat-window-${receiverHandleSubStr} .chat-window-input input`).val(message);
    IsEditing = true;
    EditingMessageId = messageId;
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

window.setInterval(function() {
    GetNewMessages();
    GetEditedList();
}, 1000);

function GetNewMessages() {
    $.ajax({
        url: 'services/getNewMessages.php',
        type: 'GET',
        async: !1,
        data: {},
        success: function (resultString) {
            let result = JSON.parse(resultString);
            if(result["success"])
            {
                let messages = result["messages"];
                messages.sort(SortByDate);

                for(let i = 0; i < messages.length; ++i)
                {       
                    let senderHandle = messages[i].SenderHandle;
                    let handleSubStr = senderHandle.substr(1, senderHandle.length - 1);
                    let senderChatWrapper = $('#chat-window-' + handleSubStr);
                    let msgHTML = "";
                    msgHTML += ShowMessage(messages[i], false, true);
                    senderChatWrapper.find(".chat-window-body").append(msgHTML);
                }
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

function GetEditedList() {
    $.ajax({
        url: 'services/getEditedMessagesList.php',
        type: 'GET',
        async: !1,
        data: {},
        success: function (resultString) {
            let result = JSON.parse(resultString);
            if(result["success"])
            {
                editedList = result["editedList"];
                for(let i = 0; i < editedList.length; ++i) {
                    if(editedList[i].EditType == 'delete')
                    {
                        $(`.chat-window-body #${editedList[i].MessageId}`).remove();
                    }
                    else if(editedList[i].EditType == 'edit')
                    {
                        GetAndEditMessage(editedList[i].MessageId);
                    }
                }
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

function UpdateContacts()
{
    let searchedText = $(`.user-contacts-search-bar input`).val();


    $.ajax({
        url: 'services/contacts.php',
        type: 'GET',
        async: !1,
        data: {},
        success: function (resultString) {
            let result = JSON.parse(resultString);
            if(result["success"])
                {

                let contacts = result["users"]["contacts"];
                let blocked = result["users"]["blocked"];
                let blockedBy = result["users"]["blockedBy"];
    
                let contactsListHTML = "";
                for(let i = 0; i < contacts.length; ++i)
                {
                    let handleSubStr = contacts[i]['Handle'];
                        handleSubStr = handleSubStr.substr(1, handleSubStr.length - 1);
                    if ($(`.user-contacts-list #${handleSubStr}`).length <= 0) {
                        contactsListHTML += `<li id="${handleSubStr}"><span>${contacts[i]['Handle']}</span><span>${contacts[i]['FullName']}</span></li>\n`;
                        LoadChat(contacts[i]);
                    }

                    if(handleSubStr.search(searchedText) != -1)
                    {
                        $(`.user-contacts-list #${handleSubStr}`).show();
                    }
                    else
                    {
                        $(`.user-contacts-list #${handleSubStr}`).hide();
                    }
                }
                for(let i = 0; i < blocked.length; ++i)
                {
                    let handleSubStr = blocked[i]['Handle'];
                    handleSubStr = handleSubStr.substr(1, handleSubStr.length - 1);
                    if ($(`.user-contacts-list #${handleSubStr}`).length <= 0) {
                        contactsListHTML += `<li class="contact-blocked" id="${handleSubStr}"></span><span>${blocked[i]['FullName']}</span></li>\n`;
                        LoadChat(blocked[i]);
                    }

                    if(handleSubStr.search(searchedText) != -1)
                    {
                        $(`.user-contacts-list #${handleSubStr}`).show();
                    }
                    else
                    {
                        $(`.user-contacts-list #${handleSubStr}`).hide();
                    }
                }
                for(let i = 0; i < blockedBy.length; ++i)
                {
                    let handleSubStr = blockedBy[i]['Handle'];
                    handleSubStr = handleSubStr.substr(1, handleSubStr.length - 1);
                    if ($(`.user-contacts-list #${handleSubStr}`).length > 0) {
                        $(`.user-contacts-list #${handleSubStr}`).remove();
                    }
                        
                }
                $('.user-contacts-list').append(contactsListHTML);
    
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
