


function checkusername() {
    username = document.getElementById("uiUsername").value;
    document.getElementById("uiMessage").innerHTML = 'Please wait...';

    $.ajax({
        url: 'checkusername.php',
        type: 'POST',
        async: !1,
        //contentType: 'charset=utf-8',
        data: { un: username},
        success: function (data) {
            document.getElementById("uiMessage").innerHTML = data;
        }
    });

}