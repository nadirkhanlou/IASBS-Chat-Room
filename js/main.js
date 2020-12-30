$(function() {
    $('.user-settings-wrapper').hide();
    $('.user-overview button').on('click',
    function()
    {
        console.log("Hi");
        $('.user-contacts-wrapper, .user-settings-wrapper').toggle();
    }
    );
});
