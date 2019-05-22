$(document).ready(function () {
    $("#cadastra-veiculo").click(function(){
        if ($("#form-veiculo").is(":hidden") && $("#form-condutor").is(":hidden"))  {
            $("#form-veiculo").show();
        } else {
            $("#form-veiculo").hide();
        }

        $(".idVeiculo").toggle();

        if ($("#form-condutor").is(":visible")) {
            $(".idCondutor").show();
            $("#form-condutor").toggle();
        }
    });

    $("#cadastra-condutor").click(function(){
        $(".idCondutor").toggle();
        $("#form-condutor").toggle();

    });
});