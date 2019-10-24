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
                setBotaoAbreModalViagem();
                setFuncionamentoModalViagem();
            } else {
                mostrarTarefaNaoParticipando(tarefa);
            }

            exibeViagens(tarefa,requisitor);
            //exibeCompras(tarefa,requisitor);
            //exibeAtividades(tarefa,requisitor);
            setBotoesScroll();
        } else {
            decideErros(resposta,codigo);
        }
    });
};

function setBotoesScroll() {
    let botoesDireita = document.getElementsByClassName("direita");
    let botoesEsquerda = document.getElementsByClassName("esquerda");

    for (let botaoDireita of botoesDireita) {
        botaoDireita.addEventListener("mousedown",function () {
            let containerGeral = botaoDireita.parentElement.parentElement;
            let containerItens = containerGeral.querySelector(".container-itens");

            if (containerGeral.offsetWidth !== containerItens.scrollWidth) {
                let posDesejada = containerItens.scrollLeft + 390;
                if (posDesejada < containerItens.scrollWidth) {
                    containerItens.scrollTo({left: posDesejada, behavior: "smooth"});
                }
            }
        });
    }

    for (let botaoEsquerda of botoesEsquerda) {
        botaoEsquerda.addEventListener("mousedown",function () {
            let containerGeral = botaoEsquerda.parentElement.parentElement;
            let containerItens = containerGeral.querySelector(".container-itens");

            if (containerGeral.offsetWidth !== containerItens.scrollWidth) {
                let posDesejada = containerItens.scrollLeft - 390;
                if (posDesejada > 0) {
                    containerItens.scrollTo({left:posDesejada,behavior:"smooth"});
                } else {
                    containerItens.scrollTo({left:0,behavior:"smooth"});
                }
            }
        });
    }
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
