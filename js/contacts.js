
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
                contacts = result["users"]["contacts"];
                blocked = result["users"]["blocked"];
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