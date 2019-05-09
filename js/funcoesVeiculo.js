$(document).ready(function () {
    $('#modalAlterar').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);

        let nome = button.data('nome');
        let tipo = button.data('tipo');
        let dtRetirada = button.data('dtret');
        let dtDevolucao = button.data('dtdev');
        let horaRetirada = button.data('horaret');
        let horaDevolucao = button.data('horadev');
        let idCondutor = button.data('idcond');
        let id = button.data('id');

        let modal = $(this);
        modal.find('#nome').val(nome);
        modal.find('#tipo').val(tipo);
        modal.find('#dtRetirada').val(dtRetirada);
        modal.find('#dtDevolucao').val(dtDevolucao);
        modal.find('#horarioRetirada').val(horaRetirada);
        modal.find('#horarioDevolucao').val(horaDevolucao);
        modal.find('#idCondutor').val(idCondutor);
        modal.find('#id').val(id);


    })
});