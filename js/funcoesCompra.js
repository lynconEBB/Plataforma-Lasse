$(document).ready(function () {
    var cont = 1;
    $('#adicionar-item').click(function () {

        input = criarInputItemCadastro(cont);
        $('#container-itens').append(input);
        cont++;
    });

    $('#container-itens').on('click',"button",function () {
        let button = $(event.relatedTarget);
        console.log(button.val());
    });

    $('#modalCadastro').on('hidden.bs.modal', function () {
        $('#container-itens').remove();
        $('#itensCadastro').append('<div id="container-itens"></div>');
        cont = 1;
    });


    $('#modalAlterar').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);
        let itens = button.data('itens');
        let id = button.data('id');
        let proposito = button.data('proposito');

        console.log(itens);
        for(var i=1; i<=itens.length;i++){
            input = criarInputItem(i,itens[i-1]);
            $('#container-Itens').append(input);
        }

        var modal = $(this);
        modal.find('#id').val(id);
        modal.find('#proposito').val(proposito);
    });

    $('#modalAlterar').on('hidden.bs.modal', function () {
        $('#container-Itens').remove();
        $('#itensAlterar').append('<div id="container-Itens"></div>');
    })
});

function criarInputItem(i,item) {
    let input = "<div class='row item"+i+"'>";
        input += "<div class='col-sm-6'>";
            input += "<label for='nome-item"+ i +"' class='col-form-label'>Nome</label>";
            input += '<input class="form-control" id="nome-item'+i+'" name="itens['+i+'][nome]" value="'+item[1]+'">';
        input += '</div>';
        input += '<div class="col-sm-2">';
            input += '<label for="valor-item'+i+'" class="col-form-label">Valor</label>';
            input += '<input class="form-control" id="valor-item'+i+'" name="itens['+i+'][valor]"  value="'+item[2]+'">';
        input += '</div>';
        input += '<div class="col-sm-2">';
            input += '<label for="qtd-item'+i+'" class="col-form-label">Quantidade</label>';
            input += '<input class="form-control" id="qtd-item'+i+'" name="itens['+i+'][qtd]"  value="'+item[3]+'">';
            input += '<input class="form-control" type="hidden" name="itens['+i+'][id]"  value="'+item[0]+'">';
        input += '</div>';input += '<div class="col-sm-2">';
            input += '<label for="valor-item'+i+'" class="col-form-label">Apagar</label>';
            input += '<input class="form-control" type="checkbox" id="valor-item'+i+'" name="itens['+i+'][apagar]">';
        input += '</div>';
    input += '</div>';

    return input;
}

function criarInputItemCadastro(i) {
    let input = "<div class='row item"+i+"'>";
    input += "<div class='col-sm-6'>";
    input += "<label for='nome-item"+ i +"' class='col-form-label'>Nome</label>";
    input += '<input class="form-control" id="nome-item'+i+'" name="itens['+i+'][nome]">';
    input += '</div>';
    input += '<div class="col-sm-2">';
    input += '<label for="valor-item'+i+'" class="col-form-label">Valor</label>';
    input += '<input class="form-control" id="valor-item'+i+'" name="itens['+i+'][valor]">';
    input += '</div>';
    input += '<div class="col-sm-2">';
    input += '<label for="qtd-item'+i+'" class="col-form-label">Quantidade</label>';
    input += '<input class="form-control" id="qtd-item'+i+'" name="itens['+i+'][qtd]">';
    input += '</div>';
    input += '<div class="col-sm-2">';
    input += '<button type="button" class="btn btn-danger" id="apagar" data-item="item'+i+'">&minus;</button>';
    input += '</div>';

    input += '</div>';


    return input;
}


