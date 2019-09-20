window.onload = function () {

    verificaMensagem();
    var idProjetoRequisitado = window.location.pathname.split("/").pop();

    requisicao("GET", "/api/projetos/"+idProjetoRequisitado, null, true, function (resposta) {

        if (resposta.status === "sucesso") {
            var requisitor = resposta.requisitor;
            setLinks(requisitor.id);
            document.querySelector(".user-img").src = "/" + requisitor.foto;
            document.querySelector(".user-name").textContent = requisitor.login;

            /******Ajusta detalhes caso funciorio ou dono*************/
            let template = ``;
            let projeto = resposta.dados;
            let participanteDropdown = document.getElementById("participantes-dropdown");
            if (requisitor.dono === true) {
                template = `
                    <input type="text" id="nome" value="${projeto.nome}">
                    <textarea  spellcheck="false" id="descricao">${projeto.descricao}</textarea>
                    <div class="group-projeto">
                        <label class="label-projeto">Data de Inicio </label>
                        <input type="text" class="input-projeto" id="dtInicio" value="${projeto.dataInicio}">
                    </div>
                   <div class="group-projeto">
                        <label class="label-projeto">Data de Finalização</label>
                        <input type="text" class="input-projeto" id="dtFinalizacao" value="${projeto.dataFinalizacao}">
                   </div>
                    <div class="group-projeto">
                        <label class="label-projeto">N° centro de custo</label>
                        <input type="text" class="input-projeto" id="centroCusto" value="${projeto.numCentroCusto}">
                    </div>
                    <div class="group-projeto">
                        <label class="label-projeto">Gasto Total</label>
                        <label class="label-projeto">${projeto.totalGasto}</label>
                    </div>
                    <button id="botaoExcluir" type="button">Excluir</button>
                    <button id="botaoAlterar" type="submit">Alterar</button>`;

                    participanteDropdown.insertAdjacentHTML("afterbegin","<button class='botao-adicionar' id='abreModalAdicionar'><i class='material-icons'>library_add</i>Novo</button><hr>");
            } else {
                template = `
                    <span id="nome">${projeto.nome}</span>
                    <span id="descricao">${projeto.descricao}</span>
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
                        <label class="label-projeto">${projeto.totalGasto}</label>
                    </div>
                    <div class="group-projeto">
                        <label class="label-projeto">Gasto Total</label>
                        <label class="label-projeto">${projeto.totalGasto}</label>
                    </div>`;
            }
            let requisitorParticipa = false;
            projeto.participantes.forEach(function (participante) {
                if (participante.id === requisitor.id) {
                    requisitorParticipa = true;
                }
                participanteDropdown.insertAdjacentHTML("beforeend",`<span class="participante">${participante.login}</span><hr>`)
            });
            document.getElementById("projeto-detalhes").insertAdjacentHTML("afterbegin",template);


            let countTarefas = {
                afazer:0,
                fazendo:0,
                concluida:0
            };
            let tarefaFazer = document.getElementById("tarefas-a-fazer");
            let tarefaAndamento = document.getElementById("tarefas-fazendo");
            let tarefaConluida = document.getElementById("tarefas-concluidas");

            /*********Mostra botoes caso usuario esta inserido********/
            if (requisitorParticipa) {
                tarefaConluida.insertAdjacentHTML("beforeend", "<button  class=\"botao-adicionar btn-tarefa\" data-tipo='Concluída'>Adicionar Tarefa</button>");
                tarefaAndamento.insertAdjacentHTML("beforeend", "<button  class=\"botao-adicionar btn-tarefa\" data-tipo='Em andamento'>Adicionar Tarefa</button>");
                tarefaFazer.insertAdjacentHTML("beforeend", "<button  class='botao-adicionar btn-tarefa' data-tipo='Á fazer'>Adicionar Tarefa</button>");
            }
            let botoesAbreModalTarefa = document.getElementsByClassName("btn-tarefa");
            for (let botao of botoesAbreModalTarefa) {
                botao.addEventListener("click",function () {
                    mostrarModal("#modalTarefa");
                    document.getElementById("estado").value = botao.dataset.tipo
                })
            }
            document.getElementById("fechaModalTarefa").onclick = () => fecharModal("#modalTarefa");


            /*************Exibe tarefas e aviso caso nenhuma tarefa exista****************/
            if (projeto.tarefas != null) {
                projeto.tarefas.forEach(function (tarefa) {
                    let divColocar = null;
                    if (tarefa.estado == "Á fazer") {
                        divColocar = tarefaFazer;
                        countTarefas.afazer += 1;
                    } else if (tarefa.estado == "Em andamento") {
                        divColocar = tarefaAndamento;
                        countTarefas.fazendo += 1;
                    } else {
                        divColocar = tarefaConluida;
                        countTarefas.concluida += 1;
                    }
                    var templateTarefa = `
                    <div class="tarefa" id="tarefa${tarefa.id}">
                        <span class="tarefa-title">${tarefa.nome}</span>
                        <span class="tarefa-gasto">${tarefa.totalGasto}</span>
                    </div>
                `;
                    divColocar.insertAdjacentHTML("beforeend",templateTarefa)
                });
            }
            let templateAviso = `
            <div class="aviso">
                <img class="img-vazio" src="/assets/images/vazio.png" alt="Icone sem Tarefas">
                <span class="aviso-vazio"> Nenhuma Tarefa Encontrada! </span>
            </div>`;
            if (countTarefas.concluida ==0) {
                tarefaConluida.insertAdjacentHTML("beforeend", templateAviso);
            }
            if (countTarefas.fazendo == 0) {
                tarefaAndamento.insertAdjacentHTML("beforeend", templateAviso);
            }
            if (countTarefas.afazer == 0 ) {
                tarefaFazer.insertAdjacentHTML("beforeend", templateAviso);
            }


            /******Cadastrar Tarefa***********/
            let botaoCadastrarTarefa = document.getElementById("cadastrarTarefa");
            botaoCadastrarTarefa.onclick = function (event) {
                event.preventDefault();
                let bodyTarefa = {
                    nome: document.getElementById("nomeTarefa").value,
                    descricao: document.getElementById("descricaoTarefa").value,
                    estado: document.getElementById("estado").value,
                    dataInicio: document.getElementById("dtInicioTarefa").value,
                    dataConclusao: document.getElementById("dtConclusaoTarefa").value,
                    idProjeto: projeto.id,
                };
                requisicao("POST","/api/tarefas",bodyTarefa,true,function (resposta) {
                    if (resposta.status === "sucesso") {
                        addMensagem("sucesso=Tarefa-cadastrada-com-sucesso!");
                    } else {
                        exibirMensagem(resposta.mensagem,true);
                    }
                });
            };

            /********Excluir Projeto*********/
            document.getElementById("botaoExcluir").onclick = () => mostrarModal("#modalExcluirProjeto");
            document.getElementById("fechaModalExcluirProjeto").onclick = () => fecharModal("#modalExcluirProjeto");
            let botaoExcluirProjeto = document.getElementById("excluirProjeto");
            botaoExcluirProjeto.onclick = function () {
                requisicao("DELETE","/api/projetos/"+projeto.id,null,true,function (resposta) {
                    if (resposta.status == "sucesso") {
                        window.location.href = "/projetos/user/"+requisitor.id+"?sucesso=Projeto-excluido-com-sucesso";
                    } else {
                        fecharModal("#modalExcluirProjeto");
                        exibirMensagem(resposta.mensagem,true);
                    }
                });
            };



            /******Adicionar Funcionário*************/

            requisicao("GET","/api/users/naoProjeto/"+projeto.id,null,true,function (resposta) {
                if (resposta.status === "sucesso") {
                    if (resposta.hasOwnProperty("dados")) {
                        let funcionarios = resposta.dados;
                        let select = document.getElementById("funcionario");
                        funcionarios.forEach(function (funcionario) {
                            select.insertAdjacentHTML("beforeend",`<option value="${funcionario.id}">${funcionario.login}</option>`)
                        });

                        document.getElementById("abreModalAdicionar").onclick = () => mostrarModal("#modalAdicionarFuncionario");
                        document.getElementById("fechaModalAdicionarFuncionario").onclick = () => fecharModal("#modalAdicionarFuncionario");
                    } else {
                        document.getElementById("abreModalAdicionar").onclick = () => exibirMensagem(resposta.mensagem,true);
                    }

                } else {
                    fecharModal("#modalAdicionarFuncionario");
                    exibirMensagem(resposta.mensagem,true);
                }


            });
        } else {
            exibirMensagem(resposta.mensagem,true);
        }
    });
};

abreDropDown = document.getElementById("seta");
abreDropDown.onclick = function () {
    if (document.getElementById("participantes-dropdown").style.display === "none") {
        document.getElementById("participantes-dropdown").style.display = "block";
        abreDropDown.textContent = "keyboard_arrow_up";
    } else {
        document.getElementById("participantes-dropdown").style.display = "none";
        abreDropDown.textContent = "keyboard_arrow_down";
    }
};





