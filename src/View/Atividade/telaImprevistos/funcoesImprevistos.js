window.onload = function () {
    verificaMensagem();

    let idUserRequisitado = window.location.pathname.split("/").pop();

    requisicao("GET", "/api/atividades/user/"+idUserRequisitado, null, function (resposta, codigo) {
        if (resposta.status === "sucesso") {
            let requisitor = resposta.requisitor;

            setLinks(requisitor);

        } else {
            decideErros(resposta, codigo);
        }
    });
};
