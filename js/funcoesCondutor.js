$(document).ready(function () {
    $('#modalAlterar').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var nome = button.data('nome');
        var cnh = button.data('cnh');
        var validade = button.data('val');
        var id = button.data('id');
        var modal = $(this);
        modal.find('#nomeCondutor').val(nome);
        modal.find('#cnh').val(cnh);
        modal.find('#validadeCNH').val(validade);
        modal.find('#id').val(id);
    })
});