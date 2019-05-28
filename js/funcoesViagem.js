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
});