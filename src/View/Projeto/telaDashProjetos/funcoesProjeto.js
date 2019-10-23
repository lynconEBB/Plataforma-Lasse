window.onload = function () {
    verificaMensagem();

    let idUserRequisitado = window.location.pathname.split("/").pop();

    requisicao("GET", "/api/projetos/user/"+idUserRequisitado, null, function (resposta,codigo) {

        if (resposta.status === "sucesso") {
            let requisitor = resposta.requisitor;
            let projetos = resposta.dados;

            setLinks(requisitor);

            requisicao("GET","/api/users/"+idUserRequisitado,null,function (resposta,codigo) {
                if (resposta.status === "sucesso") {
                    let usuario = resposta.dados;
                    document.getElementById("titulo-cabecalho").textContent = "Projetos de "+usuario.login;
                }
            });

            if (requisitor.id == idUserRequisitado) {
                document.getElementById("abreModal").style.display = "inline-block";
            }

            if (codigo === 200) {

                let main = document.querySelector("#dash-projetos");
                for (let projeto of projetos) {
                    let template = ` 
                        <a href="/projeto/${projeto.id}" class="container-projeto" id="projeto${projeto.id}">
                            <span class="projeto-title">${projeto.nome}</span>
                            <hr class="projeto-divisoria">
                            <span class="projeto-criacao">Data de Criação: ${projeto.dataInicio}</span>
                            <span class="projeto-finalizacao">Data de Finalização: ${projeto.dataFinalizacao}</span>
                            <span class="projeto-participantes">Participantes: ${projeto.participantes.length}</span>
                        </a>`;

                    main.insertAdjacentHTML("beforeend",template);

                    if (requisitor.infoAdd[projeto.id] === true) {
                        let divisoria = document.getElementById("projeto"+projeto.id).querySelector(".projeto-divisoria");
                        divisoria.insertAdjacentHTML("afterend","<i class='material-icons'>star</i>")
                    }
                }
            } else {
                document.getElementById("container-aviso").style.display = "flex";
            }
        } else {
            decideErros(resposta,codigo);
        }
    });
};

document.getElementById("cadastrarProjeto").onclick = function(event) {
    event.preventDefault();
    let body = {
        nome :document.getElementById("nome").value,
        descricao :document.getElementById("descricao").value,
        dataInicio :document.getElementById("dataCriacao").value,
        dataFinalizacao :document.getElementById("dataFinalizacao").value,
        centroCusto :document.getElementById("numCentroCusto").value
    };
    requisicao("POST","/api/projetos",body,function (response,codigo) {

        if (response.status === "sucesso") {
            addMensagem("sucesso=Projeto-cadastrado-com-sucesso!");
        } else {
            exibirMensagem(response.mensagem,true,event.target);
        }
    });
};

document.getElementById("abreModal").onclick = function (event) {
    exibeModal("modalCadastro",event.target);
};


