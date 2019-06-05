$(document).ready(function () {
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
});