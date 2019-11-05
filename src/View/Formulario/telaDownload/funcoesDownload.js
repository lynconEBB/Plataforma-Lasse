window.onload = function () {
    verificaMensagem();

    let requisitado = window.location.pathname.split("/").pop();

    let botaoFormulario = document.getElementById("gerarFormulario");
    botaoFormulario.onclick = function () {
        console.log("ola");
        requisicao("GET","/api/formularios/download/"+requisitado,null,function (resposta) {
            var link = document.createElement('a');
            link.href = resposta;
            link.download = "ola.odt";
            link.click();
        })
    };
};
