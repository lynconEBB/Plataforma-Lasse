$(document).ready(function () {
    $('#modalAlterar').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);

        let tipo = button.data('tipo');
        let valor = button.data('valor');
        let id = button.data('id');

        let modal = $(this);
        modal.find('#tipoGasto').val(tipo);
        modal.find('#valor').val(valor);
        modal.find('#id').val(id);
    })
});