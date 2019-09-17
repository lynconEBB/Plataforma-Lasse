window.onload = function () {
    verificaMensagem();

    var idUserRequisitado = window.location.pathname.split("/").pop();

    requisicao("GET","/api/users/"+idUserRequisitado,null,true,function (resposta) {
        if (resposta.status === "sucesso") {
            var requisitor = resposta.requisitor;
            setLinks(requisitor.id);
            document.querySelector(".user-img").src = "/"+resposta.requisitor.foto;
            document.querySelector(".user-name").textContent = resposta.requisitor.login;

            var usuario = resposta.dados;

            requisicao("GET", "/api/projetos/user/"+usuario.id,null,true,function (resposta) {
                if (resposta.status == "sucesso") {
                    if (resposta.mensagem == "Nenhum projeto encontrado") {
                        document.querySelector("#qtdProjetos").textContent = resposta.mensagem;
                    } else {
                        let projetos = resposta.dados;
                        let ehDono = resposta.requisitor.infoAdd;
                        let sendoDono = 0;
                        ehDono.forEach(function (dono) {
                            if (dono == true) {
                                sendoDono += 1;
                            }
                        });
                        document.querySelector("#qtdProjetos").textContent = "Participando de: "+projetos.length+" projeto(s)<br>" +
                            "Sendo dono de :"+sendoDono+" projeto(s)";
                    }
                } else {
                    exibirMensagem()
                }
            })


        } else {
            window.location.href = "/erro/permissao";
        }
    });

    //requisicao("GET","/api/projetos/user"+idUsuario,bod)
};






