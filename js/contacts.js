
function GetContacts() {

    $.ajax({
        url: 'services/contacts.php',
        type: 'POST',
        async: !1,
        data: {'contacts': 1},
        success: function (result) {
            if(result["success"])
            {
                contacts = result["users"]["contacts"];
                blocked = result["users"]["blocked"];
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