window.onload = function () {
    verificaMensagem();
    requisitaDadosUsuario();
};

function requisitaDadosUsuario() {
    let idUserRequisitado = window.location.pathname.split("/").pop();

    // /api/users/{idUsuario}
    requisicao("GET","/api/users/"+idUserRequisitado,null,function (resposta,codigo) {

        if (resposta.status === "sucesso") {

            let usuario = resposta.dados;
            let requisitor = resposta.requisitor;

            setLinks(requisitor);

            document.getElementsByClassName('titulo')[0].textContent = "Dashboard de "+usuario.login;
            /****Mostra quantidade projetos******/
            requisicao("GET","/api/projetos/user/"+usuario.id,null,function (resposta,codigo) {

                if (resposta.status === "sucesso") {
                    if (!resposta.hasOwnProperty("dados")) {
                        document.querySelector("#qtdProjetos").textContent = resposta.mensagem;
                    } else {
                        let projetos = resposta.dados;
                        let ehDono = resposta.requisitor.infoAdd;
                        let qtdDono = 0;
                        for( let key in ehDono) {
                            if(ehDono[key] === true) {
                                qtdDono += 1;
                            }
                        }
                        document.querySelector("#qtdProjetos").innerHTML = "Participando de: "+projetos.length+" projeto(s)<br>" + "Sendo dono de: "+qtdDono+" projeto(s)";
                    }
                } else {
                    exibirMensagemInicio(resposta.mensagem,true);
                }
            });

            /****Mostra quantidade de Projetos****/
            requisicao("GET", "/api/formularios/users/"+usuario.id,null,function (resposta,codigo) {
                if (resposta.status === "sucesso") {
                    if (!resposta.hasOwnProperty("dados")) {
                        document.querySelector("#qtdFormularios").textContent = resposta.mensagem;
                    } else {
                        let formularios = resposta.dados;
                        document.querySelector("#qtdFormularios").textContent = "Foram encontrados "+formularios.length+" formulários no sistema";
                    }
                } else {
                    exibirMensagemInicio(resposta.mensagem,true);
                }
            });

            /*****Mostra quantidade de imprevistos******/
            requisicao("GET", "/api/atividades/user/"+usuario.id,null,function (resposta,codigo) {
                if (resposta.status === "sucesso") {
                    if (!resposta.hasOwnProperty("dados")) {
                        document.querySelector("#qtdImprevistos").textContent = resposta.mensagem;
                    } else {
                        let imprevistos = resposta.dados;
                        document.querySelector("#qtdImprevistos").textContent = "Foram encontrados "+imprevistos.length+" imprevistos no sistema";
                    }
                } else {
                    exibirMensagemInicio(resposta.mensagem,true);
                }
            });

            let telaFormularios = document.querySelector("#telaFormularios");
            telaFormularios.onclick = () => window.location.href = "/formularios/user/"+usuario.id;
            let telaProjetos = document.querySelector("#telaProjetos");
            telaProjetos.onclick = () => window.location.href = "/projetos/user/"+usuario.id;
            let telaImprevistos = document.querySelector("#telaImprevistos");
            telaImprevistos.onclick = () => window.location.href = "/imprevistos/user/"+usuario.id;

        } else {
            decideErros(resposta,codigo);
        }
    });
}






