$(document).ready(function () {
    $('#modalAlterar').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var nome = button.data('nome');
        var qtd = button.data('qtd');
        var valor = button.data('valor');
        var id = button.data('id');
        var modal = $(this);
        modal.find('#nome').val(nome);
        modal.find('#valor').val(valor);
        modal.find('#qtd').val(qtd);
        modal.find('#id').val(id);
    })
});