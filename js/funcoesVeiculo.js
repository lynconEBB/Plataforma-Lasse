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
        modal.find('#idCondutorAlteracao').val(idCondutor);
        modal.find('#last-id-condutor').val(idCondutor);
        modal.find('#id').val(id);

    });

    $('#novo-condutor').click(function () {
        $('#form-condutor').toggle();
        $('.campo-idCondutor').toggle();

        if($('.campo-idCondutor').is(":hidden")){
            $('#idCondutorCadastro').val('novo');
        }else{
            $('#idCondutorCadastro').val('escolher');
        }

        console.log($('#idCondutorCadastro').val())
    });

    $('#novo-condutor-alter').click(function () {
        $('#form-condutor-alter').toggle();
        $('.campo-idCondutor-alter').toggle();

        if($('.campo-idCondutor-alter').is(":hidden")){
            $('#idCondutorAlteracao').val('novo');
        }else{
            $('#idCondutorAlteracao').val($('#last-id-condutor').val());
        }

        console.log($('#idCondutorAlteracao').val())
    });

});