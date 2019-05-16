$(document).ready(function () {
    $('#modalAlterar').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);

        let nome = button.data('nome');
        let desc = button.data('desc');
        let estado = button.data('estado');
        let dtInicio = button.data('dtinicio');
        let dtConclusao = button.data('dtconclusao');
        let id = button.data('id');

        let modal = $(this);
        modal.find('#nomeTarefa').val(nome);
        modal.find('#descricao').val(desc);
        modal.find('#estado').val(estado);
        modal.find('#dtInicio').val(dtInicio);
        modal.find('#dtConclusao').val(dtConclusao);
        modal.find('#id').val(id);
    })
});