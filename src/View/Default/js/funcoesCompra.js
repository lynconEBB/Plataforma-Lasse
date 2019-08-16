$(document).ready(function () {
    var cont = 1;
    $('#adicionar-item').click(function () {
        input = criarInputItemCadastro(cont);
        $('#container-itens').append(input);
        cont++;
    });

    $('#itensCadastro').on('click',"button",function (event) {
        let button = $(event.target);
        let item = button.parent();
        $(item).remove();
    });

    $('#modalCadastro').on('hidden.bs.modal', function () {
        $('#container-itens').remove();
        $('#itensCadastro').append('<div id="container-itens"></div>');
        cont = 1;
    });

    $('#modalAlterar').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);

        let proposito = button.data('proposito');
        let idTarefa = button.data('idtarefa');
        let id = button.data('id');

        let modal = $(this);
        modal.find('#proposito').val(proposito);
        modal.find('#idTarefa').val(idTarefa);
        modal.find('#idTarefaAntiga').val(idTarefa);
        modal.find('#id').val(id);
        console.log(idTarefa);
    })


});


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
    input += '<button type="button" class="btn btn-danger">&minus;</button>';
    input += '</div>';
    return input;
}


