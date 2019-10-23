window.onload = function () {
    verificaMensagem();

    let idTarefaRequisitada = window.location.pathname.split("/").pop();

    requisicao("GET", "/api/tarefas/"+idTarefaRequisitada, null, function (resposta,codigo) {
        if (resposta.status === "sucesso") {

            let requisitor = resposta.requisitor;
            let tarefa = resposta.dados;

            setLinks(requisitor);

            /******Coloca informações da tarefa**********/
            if (requisitor.participa) {
                mostrarTarefaParticipando(tarefa);
                setAlteracaoTarefa(tarefa);
                setExclusaoTarefa(tarefa,requisitor);
            } else {
                mostrarTarefaNaoParticipando(tarefa);
            }

            exibeViagens(tarefa);


            /*****Mostrar Veiculos e condutores**********/
            /*if (requisitor.participa) {
                let selectCondutor = document.getElementById("idCondutor");
                let groupCondutor = document.getElementById("group-condutor");
                let containerCondutor = document.getElementById("container-condutor");
                let botaoAbreCondutor = document.getElementById("abreCondutor");
                let botaoFechaCondutor = document.getElementById("fechaCondutor");
                requisicao("GET","/api/condutores",null,true,function (resposta) {
                    if (resposta.status === "sucesso") {
                        if (resposta.hasOwnProperty("dados")) {
                            let condutores = resposta.dados;
                            condutores.forEach(function (condutor) {
                                selectCondutor.insertAdjacentHTML("beforeend",`<option value="${condutor.id}">${condutor.nome}</option>`);
                            })
                        } else {
                            groupCondutor.style.display = "none";
                            botaoAbreCondutor.style.display = "none";
                            containerCondutor.style.display = "block";
                        }
                    } else {
                        exibirMensagem(resposta.mensagem,true);
                    }
                });
                botaoAbreCondutor.onclick = function () {
                    if (groupCondutor.style.display === "block") {
                        selectCondutor.value = "novo";
                        groupCondutor.style.display = "none";
                        botaoAbreCondutor.style.display = "none";
                        containerCondutor.style.display = "block";
                    }
                };
                botaoFechaCondutor.onclick = function () {
                    if (groupCondutor.style.display === "none") {
                        selectCondutor.value = "selecione";
                        groupCondutor.style.display = "block";
                        botaoAbreCondutor.style.display = "block";
                        containerCondutor.style.display = "none";
                    }
                };


                let selectVeiculo = document.getElementById("idVeiculo");
                let groupVeiculo = document.getElementById("group-veiculo");
                let containerVeiculo = document.getElementById("container-veiculo");
                let botaoAbreVeiculo = document.getElementById("abreVeiculo");
                let botaoFechaVeiculo = document.getElementById("fechaVeiculo");
                requisicao("GET","/api/veiculos",null,true,function (resposta) {
                    if (resposta.status === "sucesso") {
                        if (resposta.hasOwnProperty("dados")) {
                            let veiculos = resposta.dados;
                            veiculos.forEach(function (veiculo) {
                                selectVeiculo.insertAdjacentHTML("beforeend",`<option value="${veiculo.id}">${veiculo.nome}</option>`);
                            })
                        } else {
                            groupVeiculo.style.display = "none";
                            botaoAbreVeiculo.style.display = "none";
                            containerVeiculo.style.display = "block";
                        }
                    } else {
                        exibirMensagem(resposta.mensagem,true);
                    }
                });
                botaoAbreVeiculo.onclick = function () {
                    if (groupVeiculo.style.display === "block") {
                        selectVeiculo.value = "novo";
                        groupVeiculo.style.display = "none";
                        botaoAbreVeiculo.style.display = "none";
                        containerVeiculo.style.display = "block";
                    }
                };
                botaoFechaVeiculo.onclick = function () {
                    if (groupVeiculo.style.display === "none") {
                        selectVeiculo.value = "selecione";
                        groupVeiculo.style.display = "block";
                        botaoAbreVeiculo.style.display = "block";
                        containerVeiculo.style.display = "none";
                    }
                };


            }*/


        } else {
            decideErros(resposta,codigo);
        }
    });
};

function setBotoesScroll() {
    let containerViagens = document.getElementById("viagens");
    let botaoDireta = document.getElementById("direita");
    let botaoEsquerda = document.getElementById("esquerda");

    botaoDireta.addEventListener("mousedown",function () {
        if (containerViagens.parentElement.offsetWidth !== containerViagens.scrollWidth) {
            let posDesejada = containerViagens.scrollLeft + 390;
            if (posDesejada < containerViagens.scrollWidth) {
                containerViagens.scrollTo({left: posDesejada, behavior: "smooth"});
            }
        }
    });

    botaoEsquerda.addEventListener("mousedown",function () {
        if (containerViagens.parentElement.offsetWidth !== containerViagens.scrollWidth) {
            let posDesejada = containerViagens.scrollLeft - 390;
            if (posDesejada > 0) {
                containerViagens.scrollTo({left:posDesejada,behavior:"smooth"});
            } else {
                containerViagens.scrollTo({left:0,behavior:"smooth"});
            }
        }
    });
}

function setExclusaoTarefa(tarefa,requisitor) {
    document.getElementById("botaoAbreExcluirTarefa").onclick = (event) => exibeModal("modalExcluirTarefa",event.target);

    document.getElementById("excluirTarefa").onclick = function (event) {
        requisicao("DELETE","/api/tarefas/"+tarefa.id,null,function (resposta) {
            if (resposta.status === "sucesso") {
                window.location.href = "/projetos/user/"+requisitor.id+"?sucesso=Tarefa-excluida-com-sucesso";
            } else {
                exibirMensagem(resposta.mensagem,true,event.target);
            }
        });
    };
}

function setAlteracaoTarefa(tarefa) {
    document.getElementById("botaoAlterarTarefa").onclick =  function (event) {
        event.preventDefault();
        let bodyTarefa = {
            nome: document.getElementById("nome").value,
            descricao: document.getElementById("descricao").value,
            estado: document.getElementById("estado").value,
            dataInicio: document.getElementById("dtInicio").value,
            dataConclusao: document.getElementById("dtConclusao").value,
        };
        requisicao("PUT","/api/tarefas/"+tarefa.id,bodyTarefa,function (resposta,codigo) {
            if (resposta.status === "sucesso") {
                addMensagem("sucesso=Tarefa-alterada-com-sucesso!");
            } else {
                exibirMensagem(resposta.mensagem,true,event.target);
            }
        });
    }
}

function mostrarTarefaParticipando(tarefa) {
    let containerTarefa = document.getElementById("detalhe-conteudo");
    let templateTarefa = `
        <input id="nome" type="text" class="alterar-titulo" value="${tarefa.nome}">
        <textarea id="descricao" class="alterar-area">${tarefa.descricao}</textarea>
        <div class="tarefa-group">
            <label class="alterar-label" for="dtInicio">Data de Início</label>
            <input class="alterar-input" value="${tarefa.dataInicio}" type="text" id="dtInicio">
        </div>
        <div class="tarefa-group">
            <label class="alterar-label" for="dtConclusao">Data de Conlusão</label>
            <input class="alterar-input" type="text" value="${tarefa.dataConclusao}" id="dtConclusao">
        </div>
        <div class="tarefa-group">
            <label class="alterar-label" for="estado">Estado</label>
            <select class="alterar-select" id="estado">
                <option value="Á fazer">Á fazer</option>
                <option value="Em andamento">Em andamento</option>
                <option value="Concluída">Concluída</option>
            </select>
        </div>
        <div class="tarefa-group">
            <label class="alterar-label" for="totalGasto">Total Gasto</label>
            <label class="alterar-label" id="totalGasto">R$ ${tarefa.totalGasto}</label>
        </div>
        <button id="botaoAlterarTarefa" class="botao info">Alterar</button>
        <button id="botaoAbreExcluirTarefa" type="button" class="botao alerta">Excluir</button>
    `;
    containerTarefa.insertAdjacentHTML("afterbegin",templateTarefa);
    document.getElementById("estado").value = tarefa.estado;
}

function mostrarTarefaNaoParticipando(tarefa) {
    let containerTarefa = document.getElementById("detalhe-conteudo");
    let templateTarefa = `
        <label id="nome">${tarefa.nome}</label>
        <span id="descricao">${tarefa.descricao}</span>
        <div class="tarefa-group">
            <label class="tarefa-label" for="dtInicio">Data de Início</label>
            <span class="detalhe-input" id="dtInicio">${tarefa.dataInicio}</span>
        </div>
        <div class="tarefa-group">
            <label class="tarefa-label" for="dtConclusao">Data de Conlusão</label>
            <span class="detalhe-input" id="dtConclusao">${tarefa.dataConclusao}</span>
        </div>
        <div class="tarefa-group">
            <label class="tarefa-label" for="estado">Estado</label>
            <span class="detalhe-input" id="estado">${tarefa.estado}</span>
        </div>
        <div class="tarefa-group">
            <label class="tarefa-label" for="totalGasto">Total Gasto</label>
            <label class="detalhe-input" id="totalGasto">${tarefa.totalGasto}</label>
        </div>
    `;
    containerTarefa.insertAdjacentHTML("afterbegin",templateTarefa);
    document.getElementById("estado").value = tarefa.estado;
}
