$(function() {
    $('.user-settings-wrapper').hide();
    $('.user-overview button').on('click', function() {
        $('.user-contacts-wrapper, .user-settings-wrapper').slideToggle();
    });
});
