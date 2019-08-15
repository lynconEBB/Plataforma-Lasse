var botaoEntrar = document.querySelector("#botao-entrar");

botaoEntrar.addEventListener("click",function (event) {
    var inputs = document.querySelectorAll("input");
    var data = {
        login: inputs[0].value,
        senha: inputs[1].value
    }

    var request = JSON.stringify(data);

    var xhr = new XMLHttpRequest();
    var url = "http://localhost/api/users/login";
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.send(request);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var json = JSON.parse(xhr.responseText);
            console.log(json);
            console.log(document.cookie);
        }
    };
});


/*$(document).ready(function () {
    $('#modalAlterar').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);

        let nome = button.data('nome');
        let login = button.data('login');
        let email = button.data('email');
        let dtNasc = button.data('dtnasc');
        let rg = button.data('rg');
        let cpf = button.data('cpf');
        let dtEmissao = button.data('dtemissao');
        let formacao = button.data('formacao');
        let atuacao = button.data('atuacao');
        let valorHora = button.data('valorhora');

        let modal = $(this);
        modal.find('#nome').val(nome);
        modal.find('#usuario').val(login);
        modal.find('#email').val(email);
        modal.find('#dtNasc').val(dtNasc);
        modal.find('#rg').val(rg);
        modal.find('#cpf').val(cpf);
        modal.find('#dtEmissao').val(dtEmissao);
        modal.find('#formacao').val(formacao);
        modal.find('#atuacao').val(atuacao);
        modal.find('#valorHora').val(valorHora);
    })
});*/