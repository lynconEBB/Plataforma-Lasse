/*$('input').focus(function(){
    $(this).parents('.form-group').addClass('focused');
});

$('input').blur(function(){
    var inputValue = $(this).val();
    if ( inputValue == "" ) {
        $(this).removeClass('filled');
        $(this).parents('.form-group').removeClass('focused');
    } else {
        $(this).addClass('filled');
    }
})*/

$(document).ready(function () {

    $('#cpf').keyup(function () {
        if($(this).val().match(/^[0-9]+$/) != null){
            $(this).css('border','2px green solid');
            alert(String($(this).val()));

        }else if($(this).val() !== ""){
            $(this).css('border','2px red solid');
        }else{
            $(this).css('border','none');
        }
    });
});