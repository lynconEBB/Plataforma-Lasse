$(document).ready(function () {
    $("#cadastra-veiculo").click(function(){

        if ($("#form-veiculo").is(":hidden") && $("#form-condutor").is(":hidden")) {
            $("#form-veiculo").show();
            $('#idVeiculo').val("novo");
        } else {
            $('#idVeiculo').val('escolher');
            $("#form-veiculo").hide();
        }

        $("#group-idVeiculo").toggle();

        if ($("#form-condutor").is(":visible")) {
            $("#group-idCondutor").show();
            $("#form-condutor").toggle();

        }
        $('#idCondutor').val('escolher');
    });

    $("#cadastra-condutor").click(function(){

        if ( $("#form-condutor").is(":hidden"))  {
            $('#idCondutor').val('novo');
        } else {
            $('#idCondutor').val('escolher');
        }

        $("#group-idCondutor").toggle();
        $("#form-condutor").toggle();

    });


    $('#modalAlterar').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);

        let origem = button.data('origem');
        let destino = button.data('destino');
        let dtIda = button.data('dtida');
        let dtVolta = button.data('dtvolta');
        let justif = button.data('justificativa');
        let obser = button.data('observacoes');
        let passagem = button.data('passagem');
        let idVeiculo = button.data('idveiculo');
        let horaEntHosp = button.data('horaentrahosp');
        let horaSaidaHosp = button.data('horasaidahosp');
        let dataEntHosp = button.data('dataentrahosp');
        let dataSaidaHosp = button.data('datasaidahosp');
        let id = button.data('id');

        let modal = $(this);
        modal.find('#origem').val(origem);
        modal.find('#destino').val(destino);
        modal.find('#dtIda').val(dtIda);
        modal.find('#dtVolta').val(dtVolta);
        modal.find('#justificativa').val(justif);
        modal.find('#observacoes').val(obser);
        modal.find('#passagem').val(passagem);
        modal.find('#idVeiculo').val(idVeiculo);
        modal.find('#dtEntradaHosp').val(dataEntHosp);
        modal.find('#dtSaidaHosp').val(dataSaidaHosp);
        modal.find('#horaEntradaHosp').val(horaEntHosp);
        modal.find('#horaSaidaHosp').val(horaSaidaHosp);
        modal.find('#idViagem').val(id);
    })

});