$('input').focus(function(){
    $(this).parents('.form-group-login').addClass('focused');
});

$('input').blur(function(){
    var inputValue = $(this).val();
    if ( inputValue == "" ) {
        $(this).removeClass('filled');
        $(this).parents('.form-group-login').removeClass('focused');
    } else {
        $(this).addClass('filled');
    }
});

