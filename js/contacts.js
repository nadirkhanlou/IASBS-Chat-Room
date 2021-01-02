
GetContacts();

function GetContacts() {

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
                    contactsListInner += `<li><a href="#">${contacts[i]['FullName']}</a></li>\n`;
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
}