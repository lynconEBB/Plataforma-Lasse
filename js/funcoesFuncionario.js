$(document).ready(function () {

    /*$('input').focus(function(){
        $(this).parents('.form-group').addClass('focused');
    });

    $("input").blur(function(){
        var inputValue = $(this).val();
        if ( inputValue == "" ) {
            $(this).removeClass('filled');
            $(this).parents('.form-group').removeClass('focused');
        } else {
            $(this).addClass('filled');
        }
    })*/

    $('input').focus(function () {
        $(this).siblings('.form-label').addClass('clicado');
        $(this).parent('.form-group').addClass('changed');
        $(this).toggleClass('selecionado');
    });

    $('input').blur(function () {
        $(this).siblings('.form-label').removeClass('clicado');
        $(this).toggleClass('selecionado');
        $(this).parent('.form-group').removeClass('changed');
    });
});