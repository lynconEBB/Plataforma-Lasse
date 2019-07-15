$( document ).ready(function () {
    var options = {
        translation:{
            'd': { pattern: /[0-3]/ },
            'm': { pattern: /[0-1]/ },
            'y': { pattern: /[1-2]/ }
        }
    };

    $('#dtNasc').mask('d0/m0/y000', options);
    $('#dtEmissao').mask('d0/m0/y000', options);
    $("#cpf").mask("000.000.000-00");
    $("#rg").mask("999.999.999-W", {
        translation: {
            'W': {
                pattern: /[X0-9]/
            }
        },
        reverse: true
    });

    $("#valorHora").mask("990,00", {reverse: true});

    $('#valorHora').blur(function () {
        let value = $(this).val();

        if(!value.includes(',')){
           value += ',00';
           $(this).val(value);
        }
    });

    $('.form-group input').focus(function () {
        let label = $(this).siblings('.form-label');
        label.addClass('selecionado');
    });

    $('.form-group input').focusout(function () {
        if($(this).val() === ''){
            let label = $(this).siblings('.form-label');
            label.toggleClass('selecionado');
        }
    });

    $('.link-login').click(function () {
        $('.modal').slideDown('fast');
    });

    $('.btn-close').click(function () {
        $('.modal').slideUp('fast');
    });


    $('.modal-group input').focus(function () {
        $(this).siblings('.modal-label').addClass('changed');
        $(this).parent('.modal-group').addClass('changed')

    });

    $('.modal-group input').blur(function () {
        if($(this).val()==""){
            $(this).siblings('.modal-label').removeClass('changed');
        }
        $(this).parent('.modal-group').removeClass('changed');
    });



    $('.modal-select').click(function (e) {
        if($(this).parent().find('.select-option').length === 0){
            $(this).addClass('changed');
            var i = 2.5;
            $('.modal-select select option').each(function () {
                $('.modal-select').parent().append('<div class="select-option"></div>');
                var op_val = $(this).text();
                var option = $('.select-option').last();
                option.animate({
                    top:i+"em"
                },{duration:300,complete: function () {
                        option.append(op_val);}
                });
                option.animate({width:"90%",padding: "5px"},{duration: 200});
                i+=2;
            });
            $('.select-option').last().css("margin-bottom","20px");
        }
        e.stopPropagation();
    });

    $(window).click(function (e) {
        if(e.target.className !== 'select-option'){
            $('.select-option').siblings('.modal-select').removeClass('changed');
            $('.select-option').remove();

        }else if(e.target.className === 'select-option'){
            let opcao = $(e.target).text();
            $('.select-label').text(opcao);
            $("option").filter(function() {
                if($(this).text() === opcao){
                    $('#back-select').val($(this).val());
                }
            });
            $('.modal-select').removeClass('changed');
            $('.select-option').remove();
        }

    })



});