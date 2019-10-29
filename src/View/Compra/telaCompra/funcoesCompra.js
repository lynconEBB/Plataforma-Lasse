window.onload = function () {
    verificaMensagem();

    let idCompraRequisitada = window.location.pathname.split("/").pop();

    requisicao("GET", "/api/compras/"+idCompraRequisitada, null, function (resposta, codigo) {
        if (resposta.status === "sucesso") {
            let requisitor = resposta.requisitor;
            let compra = resposta.dados;
            let comprador = compra.comprador;

            setLinks(requisitor);
            document.getElementById("titulo").textContent = "Compra realizada por "+compra.comprador.login;

            if (requisitor.id === comprador.id) {

            } else {
                exibeDetalhesNaoProprietario();
                document.getElementById("novoItem").style.display = "none";
                document.getElementById("alterarCompra").style.display = "none";
            }
        } else {
            decideErros(resposta, codigo);
        }
    });
};

function exibeDetalhesNaoProprietario() {

}

function exibeDetalhesProprietario() {

}
