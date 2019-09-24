window.onload = function () {
    verificaMensagem();

    var idTarefaRequisitada = window.location.pathname.split("/").pop();

    requisicao("GET", "/api/tarefas/"+idTarefaRequisitada, null, true, function (resposta) {
        if (resposta.status === "sucesso") {
            var requisitor = resposta.requisitor;
            let tarefa = resposta.dados;
            setLinks(requisitor.id);
            document.querySelector(".user-img").src = "/" + requisitor.foto;
            document.querySelector(".user-name").textContent = requisitor.login;

            /******Coloca informações da tarefa**********/
            if (requisitor.participa) {
                mostrarTarefaParticipando(tarefa);
            } else {
                mostrarTarefaNaoParticipando(tarefa);
            }

            /****Alterar Tarefa*********/
            if (requisitor.participa) {
                document.getElementById("botaoAlterarTarefa").onclick =  function (event) {
                    event.preventDefault();
                    let bodyTarefa = {
                        nome: document.getElementById("nome").value,
                        descricao: document.getElementById("descricao").value,
                        estado: document.getElementById("estado").value,
                        dataInicio: document.getElementById("dtInicio").value,
                        dataConclusao: document.getElementById("dtConclusao").value,
                    };
                    requisicao("PUT","/api/tarefas/"+tarefa.id,bodyTarefa,true,function (resposta) {
                        if (resposta.status == "sucesso") {
                            addMensagem("sucesso=Tarefa-alterada-com-sucesso!");
                        } else {
                            exibirMensagem(resposta.mensagem,true);
                        }
                    });
                }
            }

            /***** Excluir tarefa**********/
            if (requisitor.participa) {
                document.getElementById("botaoAbreExcluirTarefa").onclick = () => mostrarModal("#modalExcluirTarefa");
                document.getElementById("fechaModalExcluirTarefa").onclick = () => fecharModal("#modalExcluirTarefa");

                let botaoExcluirTarefa = document.getElementById("excluirTarefa");
                botaoExcluirTarefa.onclick = function () {
                    requisicao("DELETE","/api/tarefas/"+tarefa.id,null,true,function (resposta) {
                        if (resposta.status == "sucesso") {
                            fecharModal("#modalExcluirTarefa");
                            window.location.href = "/projetos/user/"+requisitor.id+"?sucesso=Tarefa-excluida-com-sucesso";
                        } else {
                            fecharModal("#modalExcluirTarefa");
                            exibirMensagem(resposta.mensagem,true);
                        }
                    });
                };
            }


            /*****Coloca Viagens************/
            if (tarefa.viagens.length > 0 ){
                tarefa.viagens.forEach(function (viagem) {
                    let templateViagem = ` 
                        <div class="viagem" id="viagem${viagem.id}">
                            <h2>${viagem.origem} - ${viagem.destino}</h2>
                            <hr>
                            <span class="viagem-label">Viajante: ${viagem.viajante.nomeCompleto}</span>
                            <span class="viagem-label">Total Gasto: ${viagem.totalGasto}</span>
                        </div>`;
                    document.getElementById("viagens").insertAdjacentHTML("beforeend",templateViagem);
                    document.getElementById("viagem"+viagem.id).onclick = () => window.location.href = "/viagem/"+viagem.id;
                });
            } else {
                document.getElementById("aviso-viagem").style.display = "block";
            }

            /****Scroll Viagens*******/
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

            /*****Cadastrar Viagem*********/
            if (requisitor.participa) {
                document.getElementById("tabViagens").insertAdjacentHTML("afterend","<button class='botao-adicionar' id='abreModalCadastrarViagem'>Novo</button>");
                document.getElementById('abreModalCadastrarViagem').onclick = () => mostrarModal("#modalCadastrarViagem");
                document.getElementById("fechaModalCadastrarViagem").onclick = () => fecharModal("#modalCadastrarViagem");
            }

        } else {
            exibirMensagem(resposta.mensagem,true);
        }
    });
};


function mostrarTarefaParticipando(tarefa) {
    let containerTarefa = document.getElementById("detalhe-conteudo");
    let templateTarefa = `<input id="nome" type="text" value="${tarefa.nome}">
                        <textarea id="descricao">${tarefa.descricao}</textarea>
                        <div class="tarefa-group">
                            <label class="tarefa-label" for="dtInicio">Data de Início</label>
                            <input class="detalhe-input" value="${tarefa.dataInicio}" type="text" id="dtInicio">
                        </div>
                        <div class="tarefa-group">
                            <label class="tarefa-label" for="dtConclusao">Data de Conlusão</label>
                            <input class="detalhe-input" type="text" value="${tarefa.dataConclusao}" id="dtConclusao">
                        </div>
                        <div class="tarefa-group">
                            <label class="tarefa-label" for="estado">Estado</label>
                            <select class="tarefa-select" id="estado">
                                <option value="Á fazer">Á fazer</option>
                                <option value="Em andamento">Em andamento</option>
                                <option value="Concluída">Concluída</option>
                            </select>
                        </div>
                        <div class="tarefa-group">
                            <label class="tarefa-label" for="totalGasto">Total Gasto</label>
                            <label class="detalhe-input" id="totalGasto">${tarefa.totalGasto}</label>
                        </div>
                        <button id="botaoAlterarTarefa" class="botao-alterar">Alterar</button>
                        <button id="botaoAbreExcluirTarefa" type="button" class="botao-excluir">Excluir</button>`;
    containerTarefa.insertAdjacentHTML("afterbegin",templateTarefa);
    document.getElementById("estado").value = tarefa.estado;
}

function mostrarTarefaNaoParticipando(tarefa) {
    let containerTarefa = document.getElementById("detalhe-conteudo");
    let templateTarefa = `<label id="nome">${tarefa.nome}</label>
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
                        </div>`;
    containerTarefa.insertAdjacentHTML("afterbegin",templateTarefa);
    document.getElementById("estado").value = tarefa.estado;
}
