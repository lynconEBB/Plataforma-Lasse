$(document).ready(function () {
    $('#modalAlterar').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var tipo = button.data('tipo');
        var tempoGasto = button.data('tempogasto');
        var comentario = button.data('comentario');
        var id = button.data('id');
        var dataRealizacao = button.data('datarealizacao');

        var modal = $(this);
        modal.find('#tipo').val(tipo);
        modal.find('#comentario').val(comentario);
        modal.find('#tempoGasto').val(tempoGasto);
        modal.find('#dataRealizacao').val(dataRealizacao);
        modal.find('#id').val(id);
    })
});