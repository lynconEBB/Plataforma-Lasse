window.onload = function () {
    if(performance.navigation.type == 2){
        location.reload(true);
    }
    if (performance.navigation.type !== 1) {
        verificaMensagem();
    }


    var idUserRequisitado = window.location.pathname.split("/").pop();

    requisicao("GET","/api/users/"+idUserRequisitado,null,true,function (resposta) {
        if (resposta.status === "sucesso") {
            var requisitor = resposta.requisitor;
            setLinks(requisitor.id);
            document.querySelector(".user-img").src = "/"+resposta.requisitor.foto;
            document.querySelector(".user-name").textContent = resposta.requisitor.login;

            var usuario = resposta.dados;
            /****Mostra quantidade projetos******/
            requisicao("GET","/api/projetos/user/"+usuario.id,null,true,function (resposta) {

                if (resposta.status == "sucesso") {
                    if (!resposta.hasOwnProperty("dados")) {
                        document.querySelector("#qtdProjetos").textContent = resposta.mensagem;
                    } else {
                        let projetos = resposta.dados;
                        let ehDono = resposta.requisitor.infoAdd;
                        let qtdDono = 0;
                        for( let key in ehDono) {
                            if(ehDono[key] == true) {
                                qtdDono += 1;
                            }
                        }
                        document.querySelector("#qtdProjetos").innerHTML = "Participando de: "+projetos.length+" projeto(s)<br>" + "Sendo dono de: "+qtdDono+" projeto(s)";
                    }
                } else {
                    exibirMensagem(resposta.mensagem,true);
                }
            });

            /****Mostra quantidade de Projetos****/
            requisicao("GET", "/api/formularios/users/"+usuario.id,null,true,function (resposta) {
                if (resposta.status == "sucesso") {
                    if (!resposta.hasOwnProperty("dados")) {
                        document.querySelector("#qtdFormularios").textContent = resposta.mensagem;
                    } else {
                        let formularios = resposta.dados;
                        document.querySelector("#qtdFormularios").textContent = "Foram encontrados "+formularios.length+" formulários no sistema";
                    }
                } else {
                    exibirMensagem(resposta.mensagem,true);
                }
            });

            /*****Mostra quantidade de imprevistos******/
            requisicao("GET", "/api/atividades/user/"+usuario.id,null,true,function (resposta) {
                if (resposta.status == "sucesso") {
                    if (!resposta.hasOwnProperty("dados")) {
                        document.querySelector("#qtdImprevistos").textContent = resposta.mensagem;
                    } else {
                        let imprevistos = resposta.dados;
                        document.querySelector("#qtdImprevistos").textContent = "Foram encontrados "+imprevistos.length+" imprevistos no sistema";
                    }
                } else {
                    exibirMensagem(resposta.mensagem,true);
                }
            });

            let telaFormularios = document.querySelector("#telaFormularios");
            telaFormularios.onclick = () => window.location.href = "/formularios/user/"+usuario.id;
            let telaProjetos = document.querySelector("#telaProjetos");
            telaProjetos.onclick = () => window.location.href = "/projetos/user/"+usuario.id;
            let telaImprevistos = document.querySelector("#telaImprevistos");
            telaImprevistos.onclick = () => window.location.href = "/imprevistos/user/"+usuario.id;

        } else {
            decideErros(resposta);
        }
    });

};






