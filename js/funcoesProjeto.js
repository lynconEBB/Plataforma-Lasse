$(document).ready(function () {
    $('#modalAlterar').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);

        let nome = button.data('nome');
        let desc = button.data('desc');
        let dtIni = button.data('dtini');
        let dtFim = button.data('dtfim');
        let id = button.data('id');

        let modal = $(this);
        modal.find('#nomeProjeto').val(nome);
        modal.find('#descricao').val(desc);
        modal.find('#dataInicio').val(dtIni);
        modal.find('#dataFinalizacao').val(dtFim);
        modal.find('#id').val(id);
    })
});