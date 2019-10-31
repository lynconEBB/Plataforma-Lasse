window.onload = function () {
    verificaMensagem();

    let requisitado = window.location.pathname.split("/").pop();

    requisicao("", "/api/", null, function (resposta, codigo) {
        if (resposta.status === "sucesso") {
            let requisitor = resposta.requisitor;

            setLinks(requisitor);

        } else {
            decideErros(resposta, codigo);
        }
    });
};
