$(document).ready(function () {
    var cont = 1;
    $('#adicionar-item').click(function () {
        input = criarInputItem(cont);
        $('#compras').append(input);
        cont++;
    });
});

function criarInputItem(i) {
    let input = "<div class='col-sm-8'>";
    input += "<label for='nome-item"+ i +"' class='col-form-label'>Nome</label>";
    input += '<input class="form-control" id="nome-item'+i+'" name="itens['+i+'][nome]">';
    input += '</div>';
    input += '<div class="col-sm-2">';
    input += '<label for="valor-item'+i+'" class="col-form-label">Valor</label>';
    input += '<input class="form-control" id="valor-item'+i+'" name="itens['+i+'][valor]">'
    input += '</div>';
    input += '<div class="col-sm-2">';
    input += '<label for="qtd-item'+i+'" class="col-form-label">Quantidade</label>'
    input += '<input class="form-control" id="qtd-item'+i+'" name="itens['+i+'][qtd]">'
    input += '</div>';

    return input;
}


