window.onload = function () {
    verificaMensagem();

    let idUserRequisitado = window.location.pathname.split("/").pop();

    if (idUserRequisitado === "todos") {
        requisicao("GET","/api/projetos",null,function (resposta,codigo) {
            if (resposta.status === "sucesso") {
                let requisitor = resposta.requisitor;
                setLinks(requisitor);
                document.getElementById("todosProjetos").classList.add("selecionado");

                if (codigo === 200) {
                    document.getElementById("titulo-cabecalho").textContent = "Todos Projetos";
                    let projetos = resposta.dados;
                    let donosProjetos = requisitor.donosReais;
                    let main = document.querySelector("#dash-projetos");
                    for (let projeto of projetos) {
                        let template = ` 
                        <a href="/projeto/${projeto.id}" class="container-projeto" id="projeto${projeto.id}">
                            <span class="projeto-title">${projeto.nome}</span>
                            <hr class="projeto-divisoria">
                            <span class="projeto-criacao"><b>Data de Criação:</b> ${projeto.dataInicio}</span>
                            <span class="projeto-finalizacao"><b>Data de Finalização:</b> ${projeto.dataFinalizacao}</span>
                            <span class="projeto-participantes"><b>Total Gasto:</b> R$ ${projeto.totalGasto}</span>
                            <span class="projeto-participantes"><b>Participantes:</b> ${projeto.participantes.length}</span>
                        </a>`;

                        main.insertAdjacentHTML("beforeend",template);

                        for (let participante of projeto.participantes) {
                            if (participante.id === donosProjetos[projeto.id]) {
                                document.getElementById("projeto"+projeto.id).insertAdjacentHTML("beforeend",` <span class="projeto-participantes"><b>Dono:</b> ${participante.login}</span>`)
                            }
                        }
                        if (requisitor.id === donosProjetos[projeto.id]) {
                            let projetoContainer = document.getElementById("projeto"+projeto.id);
                            projetoContainer.insertAdjacentHTML("beforeend","<div class=\"admin\"><i class=\"material-icons\" title='Sendo dono deste projeto'>supervisor_account</i></div>")
                        }
                    }
                } else {
                    document.getElementById("container-aviso").style.display = "flex";
                }
            } else {
                decideErros(resposta,codigo);
            }
        });
    } else {
        requisicao("GET", "/api/projetos/user/"+idUserRequisitado, null, function (resposta,codigo) {
            if (resposta.status === "sucesso") {

                let requisitor = resposta.requisitor;
                let projetos = resposta.dados;

                document.getElementById("projetos").classList.add("selecionado");
                setLinks(requisitor);

                requisicao("GET","/api/users/"+idUserRequisitado,null,function (resposta,codigo) {
                    if (resposta.status === "sucesso") {
                        let usuario = resposta.dados;
                        document.getElementById("titulo-cabecalho").textContent = "Projetos de "+usuario.login;
                    }
                });

                if (requisitor.id === idUserRequisitado) {
                    document.getElementById("abreModal").style.display = "inline-block";
                }

                if (codigo === 200) {
                    let donosProjetos = requisitor.donosReais;
                    let main = document.querySelector("#dash-projetos");
                    for (let projeto of projetos) {
                        let template = ` 
                        <a href="/projeto/${projeto.id}" class="container-projeto" id="projeto${projeto.id}">
                            <span class="projeto-title">${projeto.nome}</span>
                            <hr class="projeto-divisoria">
                            <span class="projeto-criacao"><b>Data de Criação:</b> ${projeto.dataInicio}</span>
                            <span class="projeto-finalizacao"><b>Data de Finalização:</b> ${projeto.dataFinalizacao}</span>
                            <span class="projeto-participantes"><b>Total Gasto:</b> R$ ${projeto.totalGasto}</span>
                            <span class="projeto-participantes"><b>Participantes:</b> ${projeto.participantes.length}</span>
                        </a>`;
                        main.insertAdjacentHTML("beforeend",template);
                        for (let participante of projeto.participantes) {
                            if (participante.id === donosProjetos[projeto.id]) {
                                document.getElementById("projeto"+projeto.id).insertAdjacentHTML("beforeend",` <span class="projeto-participantes"><b>Dono:</b> ${participante.login}</span>`)
                            }
                        }
                        if (requisitor.id === donosProjetos[projeto.id]) {
                            let projetoContainer = document.getElementById("projeto"+projeto.id);
                            projetoContainer.insertAdjacentHTML("beforeend","<div class=\"admin\"><i class=\"material-icons\" title='Sendo dono deste projeto'>supervisor_account</i></div>")
                        }
                    }
                } else {
                    document.getElementById("container-aviso").style.display = "flex";
                }
            } else {
                decideErros(resposta,codigo);
            }
        });
    }
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


