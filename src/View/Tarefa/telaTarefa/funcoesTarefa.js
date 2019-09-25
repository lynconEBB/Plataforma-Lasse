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


            /*****Mostrar Veiculos e condutores**********/
            if (requisitor.participa) {
                let selectCondutor = document.getElementById("idCondutor");
                let groupCondutor = document.getElementById("group-condutor");
                let containerCondutor = document.getElementById("container-condutor");
                let botaoAbreCondutor = document.getElementById("abreCondutor");
                let botaoFechaCondutor = document.getElementById("fechaCondutor");
                requisicao("GET","/api/condutores",null,true,function (resposta) {
                    if (resposta.status == "sucesso") {
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
                    if (groupCondutor.style.display == "block") {
                        selectCondutor.value = "novo";
                        groupCondutor.style.display = "none";
                        botaoAbreCondutor.style.display = "none";
                        containerCondutor.style.display = "block";
                    }
                };
                botaoFechaCondutor.onclick = function () {
                    if (groupCondutor.style.display == "none") {
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
                    if (resposta.status == "sucesso") {
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
                    if (groupVeiculo.style.display == "block") {
                        selectVeiculo.value = "novo";
                        groupVeiculo.style.display = "none";
                        botaoAbreVeiculo.style.display = "none";
                        containerVeiculo.style.display = "block";
                    }
                };
                botaoFechaVeiculo.onclick = function () {
                    if (groupVeiculo.style.display == "none") {
                        selectVeiculo.value = "selecione";
                        groupVeiculo.style.display = "block";
                        botaoAbreVeiculo.style.display = "block";
                        containerVeiculo.style.display = "none";
                    }
                };


            }


            /*****Cadastrar Viagem*********/
            if (requisitor.participa) {
                document.getElementById("tabViagens").insertAdjacentHTML("afterend","<button class='botao-adicionar' id='abreModalCadastrarViagem'>Novo</button>");
                document.getElementById('abreModalCadastrarViagem').onclick = () => mostrarModal("#modalCadastrarViagem");
                document.getElementById("fechaModalCadastrarViagem").onclick = () => fecharModal("#modalCadastrarViagem");

                let pagViagem = document.getElementById("info-viagem");
                let pagHospVeiculo = document.getElementById("info-hospVeiculo");
                let pagGastos = document.getElementById("info-gastos");

                document.getElementById("irPaginaHospVeiculo").onclick = () => {
                    pagViagem.className = "info";
                    pagHospVeiculo.className = "info ativado";
                };
                document.getElementById("voltarPaginaViagem").onclick = () => {
                    pagHospVeiculo.className = "info";
                    pagViagem.className = "info ativado";
                };
                document.getElementById("irPaginaGastos").onclick = () => {
                    pagHospVeiculo.className = "info";
                    pagGastos.className = "info ativado";
                };
                document.getElementById("voltarPaginaHospVeiculo").onclick = () => {
                    pagGastos.className = "info";
                    pagHospVeiculo.className = "info ativado";
                };

                let botaoCadastraViagem = document.getElementById("cadastrarViagem");
                botaoCadastraViagem.onclick = () => {
                    let bodyViagem = {
                        origem: document.getElementById("origem").value,
                        destino: document.getElementById("destino").value,
                        dataIda: document.getElementById("dataIda").value,
                        dataVolta: document.getElementById("dataVolta").value,
                        passagem: document.getElementById("passagem").value,
                        justificativa: document.getElementById("justificativa").value,
                        observacoes: document.getElementById("observacoes").value,
                        dtEntradaHosp: document.getElementById("dtEntradaHosp").value,
                        dtSaidaHosp: document.getElementById("dtSaidaHosp").value,
                        horaEntradaHosp: document.getElementById("horaEntradaHosp").value,
                        horaSaidaHosp: document.getElementById("horaSaidaHosp").value,
                        fonte: document.getElementById("fonte").value,
                        atividade: document.getElementById("atividade").value,
                        tipo: document.getElementById("tipo").value,
                        tipoPassagem: document.getElementById("tipoPassagem").value,
                        idTarefa: tarefa.id,
                        gastos: [
                            {
                                tipo: document.getElementById("aluguel").previousElementSibling.textContent,
                                valor: document.getElementById("aluguel").value
                            }, {
                                tipo: document.getElementById("combustivel").previousElementSibling.textContent,
                                valor: document.getElementById("combustivel").value
                            }, {
                                tipo: document.getElementById("estacionamento").previousElementSibling.textContent,
                                valor: document.getElementById("estacionamento").value
                            }, {
                                tipo: document.getElementById("passagemRodMetro").previousElementSibling.textContent,
                                valor: document.getElementById("passagemRodMetro").value
                            }, {
                                tipo: document.getElementById("passagemRodInter").previousElementSibling.textContent,
                                valor: document.getElementById("passagemRodInter").value
                            }, {
                                tipo: document.getElementById("pedagio").previousElementSibling.textContent,
                                valor: document.getElementById("pedagio").value
                            }, {
                                tipo: document.getElementById("seguro").previousElementSibling.textContent,
                                valor: document.getElementById("seguro").value
                            }, {
                                tipo: document.getElementById("taxi").previousElementSibling.textContent,
                                valor: document.getElementById("taxi").value
                            }, {
                                tipo: document.getElementById("outros").previousElementSibling.textContent,
                                valor: document.getElementById("outros").value
                            },
                        ]
                    };
                    var veiculo;
                    if (document.getElementById("idVeiculo").value !== "novo") {
                        veiculo =  document.getElementById("idVeiculo").value;
                    } else {
                        veiculo = {
                            nome: document.getElementById("veiculo").value,
                            tipo: document.getElementById("tipoVeiculo").value,
                            dtRetirada: document.getElementById("dataRetirada").value,
                            horaRetirada: document.getElementById("horaRetirada").value,
                            dtDevolucao: document.getElementById("dataDevolucao").value,
                            horaDevolucao: document.getElementById("horaDevolucao").value
                        };
                        var condutor;
                        if (document.getElementById("idCondutor").value !== "novo") {
                            condutor = document.getElementById("idCondutor").value;
                        } else {
                            condutor = {
                                nome: document.getElementById("nomeCondutor").value,
                                cnh: document.getElementById("cnh").value,
                                validadeCNH: document.getElementById("validadeCNH").value,
                            }
                        }
                        veiculo["condutor"] = condutor;
                    }
                    bodyViagem["veiculo"] =  veiculo;
                    console.log(bodyViagem);
                    requisicao("POST","/api/viagens",bodyViagem,true,function (resposta) {
                        if (resposta.status == "sucesso") {
                            addMensagem("sucesso=Viagem-cadastrada-com-sucesso!");
                        } else {
                            exibirMensagem(resposta.mensagem,true);
                        }
                    });
                };
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
