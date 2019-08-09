$(document).ready(function () {

    $('.form-group input').focus(function () {
        $(this).siblings('.form-label').addClass('clicado');
        $(this).parent('.form-group').addClass('changed');

    });

    $('.form-group input').blur(function () {
        if($(this).val()==""){
            $(this).siblings('.form-label').removeClass('clicado');
        }
        $(this).parent('.form-group').removeClass('changed');
    });


    $('.form-select').click(function (e) {
        if($(this).parent().find('.select-option').length === 0){
            $(this).children('.form-label').addClass('clicado');
            $(this).addClass('changed');
            var i = 75;
            $('.form-select select option').each(function () {
                $('.form-select').parent().append('<div class="select-option"></div>');
                var op_val = $(this).val();
                var option = $('.select-option').last();
                option.animate({
                    top:i+"px"
                },{duration:300,complete: function () {
                        option.append(op_val);}
                });
                option.animate({width:"100%",padding: "5px"},{duration: 200});
                i+=50;
            });
            $('.select-option').last().css("margin-bottom","20px");
        }
        e.stopPropagation();
    });

    $('.select-option').click(function (evt) {
        console.log($(this).val());

    });

    $(window).click(function () {
        $('.select-option').siblings('.form-select').removeClass('changed');
        $('.select-option').remove();
    })


});