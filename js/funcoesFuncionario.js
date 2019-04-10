$(document).ready(function () {

    $('input').focus(function () {
        $(this).siblings('.form-label').addClass('clicado');
        $(this).parent('.form-group').addClass('changed');

    });

    $('input').blur(function () {
        $(this).siblings('.form-label').removeClass('clicado');
        $(this).parent('.form-group').removeClass('changed');
    });


    $('.form-select').click(function () {
        if($(this).parent().find('.select-option').length === 0){
            $(this).children('.form-label').addClass('clicado');
            $(this).addClass('changed');
            var i = 70;
            $('.form-select select option').each(function () {
                $('.form-select').parent().append('<div class="select-option"></div>');
                var op_val = $(this).val();
                var option = $('.select-option').last();
                option.animate({
                    top:i+"px"
                },{complete: function () {
                        option.append(op_val);}
                });
                option.animate({width:"100%",padding: "5px"});
                i+=70;
            });
        }
    });
});