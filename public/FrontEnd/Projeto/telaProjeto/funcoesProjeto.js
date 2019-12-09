window.onload = function () {
    verificaMensagem();

    let idProjetoRequisitado = window.location.pathname.split("/").pop();

    requisicao("GET", "/api/projetos/"+idProjetoRequisitado, null, function (resposta,codigo) {

        if (resposta.status === "sucesso") {
            let requisitor = resposta.requisitor;
            let projeto = resposta.dados;

            setLinks(requisitor);

            let requisitorParticipa = exibeParticipantes(projeto.participantes,requisitor.id);

            if (requisitor.dono === true) {
                setTemplateDono(projeto);
                setExclusaoProjeto(projeto,requisitor.id);
                setAlteracaoProjeto(projeto.id);
                setBotaoTransferirDominio(projeto,requisitor);
                setBotaoAdicionaFuncionario(projeto.id);
            } else {
                setTemplateNaoDono(projeto,requisitor,requisitorParticipa);
            }

            setBotaoExibeParticipantes();



            let tarefaFazer = document.getElementById("tarefas-a-fazer");
            let tarefaAndamento = document.getElementById("tarefas-fazendo");
            let tarefaConluida = document.getElementById("tarefas-concluidas");

            if (requisitorParticipa) {
                tarefaConluida.insertAdjacentHTML("beforeend", "<button  class='botao aviso center btn-tarefa' data-tipo='Concluída'>Adicionar Tarefa</button>");
                tarefaAndamento.insertAdjacentHTML("beforeend", "<button  class='botao aviso center btn-tarefa' data-tipo='Em andamento'>Adicionar Tarefa</button>");
                tarefaFazer.insertAdjacentHTML("beforeend", "<button  class='botao aviso center btn-tarefa' data-tipo='Á fazer'>Adicionar Tarefa</button>");
                setBotaoModalCadastroTarefa();
                setBotaoCadastroTarefa(projeto.id);
            }

            exibeTarefas(projeto.tarefas,tarefaFazer,tarefaAndamento,tarefaConluida);

        } else {
            decideErros(resposta,codigo);
        }
    });
};

function setBotaoTransferirDominio(projeto,requisitor) {
    let participantes = projeto.participantes;
    let select = document.getElementById("novoFuncionario");

    if (participantes.length > 1 ) {
        for (let participante of participantes) {
            if (participante.id !== requisitor.id) {
                select.insertAdjacentHTML("beforeend",`<option value="${participante.id}">${participante.login}</option>`)
            }
        }
    }

    document.getElementById("abreModalTransferir").onclick = function (event) {
        if (participantes.length > 1) {
            exibeModal("modalTransferirDominio",event.target);

        } else {
            exibirMensagem("Você é o único participante do projeto, adicione um novo participante para transferir o domínio",true,event.target);
        }
    };

    document.getElementById("transferirDominio").onclick = function (event) {
        let body = {
            idNovoDono: select.value
        };
        requisicao("PUT","/api/projetos/transferirDominio/"+projeto.id,body,function (resposta,codigo) {
            if (resposta.status === "sucesso") {
                addMensagem("sucesso=Dominio-transferido-com-sucesso");
            } else {
                exibirMensagem(resposta.mensagem,true,event.target);
            }
        });
    };


}

function setBotaoAdicionaFuncionario(idProjeto) {
    requisicao("GET","/api/users/naoProjeto/"+idProjeto,null,function (resposta) {
        if (resposta.status === "sucesso") {
            if (resposta.hasOwnProperty("dados")) {
                let funcionarios = resposta.dados;
                let select = document.getElementById("funcionario");
                funcionarios.forEach(function (funcionario) {
                    select.insertAdjacentHTML("beforeend",`<option value="${funcionario.id}">${funcionario.login}</option>`)
                });

                document.getElementById("adicionarFuncionario").onclick = (event) => {
                    let bodyAdicionar = {
                        idProjeto: idProjeto,
                        idUsuario: select.value
                    };
                    requisicao("POST","/api/projetos/adicionar",bodyAdicionar, function (resposta,codigo) {
                        if (resposta.status === "sucesso") {
                            addMensagem("sucesso=Usuario-adicionado!");
                        } else {
                            exibirMensagem(resposta.mensagem,true,event.target);
                        }
                    });
                };
            } else {
                document.getElementById("abreModalAdicionar").onclick = (event) => exibirMensagem(resposta.mensagem,true,event.target);
            }
        } else {
            exibirMensagem(resposta.mensagem,true);
        }


    });
}


function setBotaoCadastroTarefa(idProjeto) {
    let botaoCadastrarTarefa = document.getElementById("cadastrarTarefa");
    botaoCadastrarTarefa.onclick = function (event) {
        event.preventDefault();
        let bodyTarefa = {
            nome: document.getElementById("nomeTarefa").value,
            descricao: document.getElementById("descricaoTarefa").value,
            estado: document.getElementById("estado").value,
            dataInicio: document.getElementById("dtInicioTarefa").value,
            dataConclusao: document.getElementById("dtConclusaoTarefa").value,
            idProjeto: idProjeto,
        };
        requisicao("POST","/api/tarefas",bodyTarefa,function (resposta) {
            if (resposta.status === "sucesso") {
                addMensagem("sucesso=Tarefa-cadastrada-com-sucesso!");
            } else {
                exibirMensagem(resposta.mensagem,true,event.target);
            }
        });
    };
}


function exibeTarefas(tarefas,tarefaFazer,tarefaAndamento,tarefaConluida) {
    let countTarefas = {
        afazer:0,
        fazendo:0,
        concluida:0
    };
    let templateAviso = `
        <div class="aviso-container">
            <img class="img-vazio" src="/public/FrontEnd/images/vazio.png" alt="Icone sem Tarefas">
            <span class="aviso-vazio"> Nenhuma Tarefa Encontrada! </span>
        </div>
    `;

    if (tarefas != null) {
        for (let tarefa of tarefas) {
            let divColocar = null;
            if (tarefa.estado === "Á fazer") {
                divColocar = tarefaFazer;
                countTarefas.afazer += 1;
            } else if (tarefa.estado === "Em andamento") {
                divColocar = tarefaAndamento;
                countTarefas.fazendo += 1;
            } else {
                divColocar = tarefaConluida;
                countTarefas.concluida += 1;
            }
            let templateTarefa = `
                <a href="/tarefa/${tarefa.id}" class="tarefa" id="tarefa${tarefa.id}">
                    <span class="tarefa-title">${tarefa.nome}</span>
                    <span class="tarefa-gasto">R$ ${tarefa.totalGasto}</span>
                </a>
            `;
            divColocar.insertAdjacentHTML("beforeend",templateTarefa);
        }
    }

    if (countTarefas.concluida === 0) {
        tarefaConluida.insertAdjacentHTML("beforeend", templateAviso);
    }
    if (countTarefas.fazendo === 0) {
        tarefaAndamento.insertAdjacentHTML("beforeend", templateAviso);
    }
    if (countTarefas.afazer === 0 ) {
        tarefaFazer.insertAdjacentHTML("beforeend", templateAviso);
    }
}

function setBotaoModalCadastroTarefa() {
    let botoesAbreModalTarefa = document.getElementsByClassName("btn-tarefa");
    for (let botao of botoesAbreModalTarefa) {
        botao.addEventListener("click",function (event) {
            exibeModal("modalTarefa",event.target);
            document.getElementById("estado").value = botao.dataset.tipo
        })
    }
}

function setExclusaoProjeto(projeto,idRequisitor) {
    document.getElementById("botaoExcluir").onclick = (event) => exibeModal("modalExcluirProjeto",event.target);

    let botaoExcluirProjeto = document.getElementById("excluirProjeto");
    botaoExcluirProjeto.onclick = function (event) {
        requisicao("DELETE","/api/projetos/"+projeto.id,null,function (resposta,codigo) {
            if (resposta.status === "sucesso") {
                window.location.href = "/projetos/user/"+idRequisitor+"?sucesso=Projeto-excluido-com-sucesso";
            } else {
                exibirMensagem(resposta.mensagem,true,event.target);
            }
        });
    };
}

function setAlteracaoProjeto(idProjeto) {
    document.getElementById("botaoAlterar").onclick = (event) => {
        event.preventDefault();
        let bodyAlterar = {
            nome: document.getElementById("nome").value,
            dataInicio: document.getElementById("dtInicio").value,
            dataFinalizacao: document.getElementById("dtFinalizacao").value,
            centroCusto: document.getElementById("centroCusto").value,
            descricao: document.getElementById("descricao").value
        };
        requisicao("PUT","/api/projetos/"+idProjeto,bodyAlterar,function (resposta,codigo) {
            if (resposta.status === "sucesso") {
                addMensagem("sucesso=Projeto-alterado-com-sucesso!");
            } else {
                exibirMensagem(resposta.mensagem,true,event.target);
            }
        });
    }
}


function exibeParticipantes(participantes,idRequisitor) {
    let participanteDropdown = document.getElementById("participantes-dropdown");
    let requisitorParticipa = false;
    for (let participante of participantes) {
        if (participante.id === idRequisitor) {
            requisitorParticipa = true;
        }
        participanteDropdown.insertAdjacentHTML("beforeend",`<span class="participante">${participante.login}</span><hr>`)
    }
    return requisitorParticipa;
}

function setBotaoExibeParticipantes() {
    let botaoAbreDropDown = document.getElementById("seta");
    let iconeBotao = document.querySelector("#seta>.material-icons");
    botaoAbreDropDown.onclick = function () {
        if (document.getElementById("participantes-dropdown").style.display === "none") {
            document.getElementById("participantes-dropdown").style.display = "block";
            iconeBotao.textContent = "keyboard_arrow_up";
        } else {
            document.getElementById("participantes-dropdown").style.display = "none";
            iconeBotao.textContent = "keyboard_arrow_down";
        }
    };
}


function setTemplateDono(projeto) {
    let template = `
    <button id="botaoAlterar" class="botao info" type="submit">Alterar</button>
    <button id="botaoExcluir" class="botao alerta" type="button">Excluir</button>
    <input type="text" id="nome" class="alterar-titulo" value="${projeto.nome}">
    <textarea  spellcheck="false" class="alterar-area" id="descricao">${projeto.descricao}</textarea>
    <div class="group-projeto">
        <label class="alterar-label" for="dtInicio">Data de Inicio </label>
        <input type="text" class="alterar-input" id="dtInicio" value="${projeto.dataInicio}">
    </div>
   <div class="group-projeto">
        <label class="alterar-label">Data de Finalização</label>
        <input type="text" class="alterar-input" id="dtFinalizacao" value="${projeto.dataFinalizacao}">
   </div>
    <div class="group-projeto">
        <label class="alterar-label">N° centro de custo</label>
        <input type="text" class="alterar-input" id="centroCusto" value="${projeto.numCentroCusto}">
    </div>
    <div class="group-projeto">
        <label class="alterar-label">Gasto Total</label>
        <label class="label-projeto">R$ ${projeto.totalGasto}</label>
    </div>
    <button type="button" class="botao aviso center" id="abreModalTransferir">Transferir Domínio</button>
    `;

    document.getElementById("participantes-dropdown").insertAdjacentHTML("afterbegin","<button class='botao aviso center' id='abreModalAdicionar'><i class='material-icons md-12'>library_add</i>Novo</button><hr>");
    document.getElementById("abreModalAdicionar").onclick = (event) => exibeModal("modalAdicionarFuncionario",event.target);
    document.getElementById("projeto-detalhes").insertAdjacentHTML("afterbegin",template);
}

function setTemplateNaoDono(projeto,requisitor,requisitorParticipa) {
    let template = `
        <button title="Sair do Projeto" id="botaoSair" class="botao alerta" type="button">Sair</button>
        <span id="nome" class="alterar-titulo titulo-projeto">${projeto.nome}</span>
        <span id="descricao" class="alterar-area descricao-projeto">${projeto.descricao}</span>
        <div class="group-projeto">
            <label class="label-projeto">Data de Início</label>
            <label class="label-projeto">${projeto.dataInicio}</label>
        </div>
        <div class="group-projeto">
            <label class="label-projeto">Data de Finalizacao</label>
            <label class="label-projeto">${projeto.dataFinalizacao}</label>
        </div>
        <div class="group-projeto">
            <label class="label-projeto">N° centro de custo</label>
            <label class="label-projeto">${projeto.numCentroCusto}</label>
        </div>
        <div class="group-projeto">
            <label class="label-projeto">Gasto Total</label>
            <label class="label-projeto">R$ ${projeto.totalGasto}</label>
        </div>`;

    document.getElementById("projeto-detalhes").insertAdjacentHTML("afterbegin",template);
    let botaoSair = document.getElementById("botaoSair");
    if (requisitorParticipa) {
        botaoSair.style.display = "block";
        botaoSair.onclick = function (event) {
            requisicao("DELETE", "/api/projetos/sair/" + projeto.id, null, function (resposta, codigo) {
                if (resposta.status === "sucesso") {
                    window.location.href = "/projetos/user/" + requisitor.id+"?sucesso=Removido-do-projeto-com-sucesso";
                } else {
                    exibirMensagem(resposta.mensagem,true,event.target);
                }
            });
        };
    } else {
        botaoSair.style.display = "none";
    }
}





