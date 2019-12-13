function exibeViagens(tarefa,requisitor) {
    let viagens = tarefa.viagens;
    if (viagens.length > 0 ){
        for (let viagem of viagens) {
            let templateViagem = ` 
                <a href="#" class="viagem" id="viagem${viagem.id}">
                    <h2>${viagem.origem} - ${viagem.destino}</h2>
                    <hr>
                    <span class="viagem-label">Viajante: ${viagem.viajante.nomeCompleto}</span>
                    <span class="viagem-label">Total Gasto: R$ ${viagem.totalGasto}</span>
                </a>
            `;
            document.getElementById("viagens").insertAdjacentHTML("afterbegin",templateViagem);
            let viagemExibida = document.getElementById("viagem"+viagem.id);
            if (requisitor.admin === "1" || requisitor.id === viagem.viajante.id) {
                viagemExibida.setAttribute("href","/viagem/"+viagem.id);
            } else {
                viagemExibida.onclick = function(event) {
                    event.preventDefault();
                    exibirMensagem("Você não possui permissão para visualizar esta viagem",true,event.target);
                }
            }
        }
    } else {
        document.getElementById("aviso-viagem").style.display = "block";
    }
}

function setBotaoAbreModalViagem() {
    let abreModalViagem = document.getElementById("abreModalCadastroViagem");
    abreModalViagem.style.display = "inline-block";
    abreModalViagem.onclick = function (event) {
        exibeModal("modalCadastroViagem",event.target);
    }
}

function setBotoesNavegacaoModal() {
    let botoesProximo = document.getElementsByClassName("proximo");
    let botoesAnterior = document.getElementsByClassName("anterior");

    for (let botaoProximo of botoesProximo) {
        let containerBotao = botaoProximo.parentElement.parentElement;
        let proximoContainer = containerBotao.nextElementSibling;
        botaoProximo.onclick = function () {
            containerBotao.classList.remove("ativado");
            proximoContainer.classList.add("ativado");
        }
    }
    for (let botaoAnterior of botoesAnterior) {
        let containerBotao = botaoAnterior.parentElement.parentElement;
        let anteriorContainer = containerBotao.previousElementSibling;

        botaoAnterior.onclick = function () {
            containerBotao.classList.remove("ativado");
            anteriorContainer.classList.add("ativado");
        }
    }
}

function setFuncionamentoModalViagem() {
    setBotoesNavegacaoModal();
    exibeVeiculos();
    exibeCondutores();
    setBotaoNovoGasto();
    document.getElementById("irPaginaGastos").onclick = function () {
        document.getElementById("info-condutor").classList.remove("ativado");
        document.getElementById("info-gastos").classList.add("ativado");
        setVoltarGastos("info-condutor");
    };
}

function setBotaoNovoGasto() {
    let count = 0;
    let containerGastos = document.getElementById("gastos");
    document.getElementById("novoGasto").onclick = function () {
        containerGastos.insertAdjacentHTML("afterbegin",`
            <div class="container-gasto">
                <div class="form-group">
                    <label class="form-label" for="tipoGasto${count}">Gasto</label>
                    <input class="form-input tipoGasto" id="tipoGasto${count}"  type="text">
                </div>
                <div class="form-group">
                    <label class="form-label" for="valorGasto${count}">Valor</label>
                    <input class="form-input valorGasto" id="valorGasto${count}" type="text">
                </div>
                <button type="button" class="botao alerta" id="gasto${count}"><i class="material-icons">delete</i></button>
            </div>
        `);
        document.getElementById("gasto"+count).addEventListener("click",function () {
           this.parentElement.remove();
        });
        setInputs();
        count++;
    }
}

function exibeCondutores() {
    requisicao("GET","/api/condutores",null,function (resposta,codigo) {
        if (resposta.status === "sucesso") {
            if (resposta.hasOwnProperty("dados")) {
                let condutores = resposta.dados;
                let containerCondutores = document.getElementById("condutores");
                for (let condutor of condutores) {
                    containerCondutores.insertAdjacentHTML("beforeend",`
                        <div class="condutor-container">
                            <div class="veiculo-dados">
                                <h3>${condutor.nome}</h3>
                                <span><b>CNH:</b> ${condutor.cnh}</span>
                                <span><b>Validade CNH:</b> ${condutor.validadeCNH}</span>
                            </div>
                            <button class="selecionaVeiculo" id="selecionaCondutor${condutor.id}" title="Escolher Condutor">
                                <i class="material-icons md-15">keyboard_arrow_right</i>
                            </button>
                        </div>
                    `);
                    document.getElementById("selecionaCondutor"+condutor.id).onclick = function (event) {
                        event.preventDefault();
                        setVoltarGastos("info-condutor");
                        document.getElementById("idCondutor").value = condutor.id;
                        document.getElementById("info-condutor").classList.remove("ativado");
                        document.getElementById("info-gastos").classList.add("ativado");
                    };
                }
            } else {
                document.getElementById("idCondutor").value = "novo";
                document.getElementById("novoCondutor").style.display = "none";
                document.getElementById("form-condutor").style.display = "block";
                document.querySelector("#info-condutor>h2").textContent = "Informações do Condutor";
                document.getElementById("irPaginaGastos").style.display = "inline-block";
            }
        } else {
            exibirMensagemInicio(resposta.mensagem,true);
        }
    });

    let condutores = document.getElementById("condutores");
    let formCondutor = document.getElementById("form-condutor");
    let btnNovo = document.getElementById("novoCondutor");
    btnNovo.onclick = function () {
        if (formCondutor.style.display === "none") {
            condutores.style.display = "none";
            formCondutor.style.display = "block";
            document.getElementById("idCondutor").value = "novo";
            btnNovo.textContent = "Escolher";
            document.getElementById("irPaginaGastos").style.display = "inline-block";
        } else {
            document.getElementById("irPaginaGastos").style.display = "none";
            btnNovo.textContent = "Novo";
            condutores.style.display = "grid";
            formCondutor.style.display = "none";
        }
    };
}

function exibeVeiculos() {
    requisicao("GET","/api/veiculos",null,function (resposta) {
        if (resposta.status === "sucesso") {
            if (resposta.hasOwnProperty("dados")) {
                let veiculos = resposta.dados;
                for (let veiculo of veiculos) {
                    document.getElementById("veiculos").insertAdjacentHTML("beforeend",`
                        <div class="veiculo-container">
                            <div class="veiculo-dados">
                                <h3>${veiculo.nome}</h3>
                                <h4>${veiculo.tipo}</h4>
                                
                                <hr>
                                <span><b>Retirada:</b> ${veiculo.dataRetirada}</span>
                                <span><b>Devolução:</b> ${veiculo.dataDevolucao}</span>
                                <span><b>Condutor:</b> ${veiculo.condutor.nome}</span>
                                <span><b>CNH condutor:</b> ${veiculo.condutor.cnh}</span>
                            </div>
                            <button class="selecionaVeiculo" id="selecionaVeiculo${veiculo.id}" title="Escolher Veiculo">
                                <i class="material-icons md-15">keyboard_arrow_right</i>
                            </button>
                        </div>
                    `);
                    document.getElementById("selecionaVeiculo"+veiculo.id).onclick = function (event) {
                        event.preventDefault();
                        setVoltarGastos("info-veiculo");
                        document.getElementById("idVeiculo").value = veiculo.id;
                        document.getElementById("info-veiculo").classList.remove("ativado");
                        document.getElementById("info-gastos").classList.add("ativado");
                    };
                }
            } else {
                document.getElementById("idVeiculo").value = "novo";
                document.getElementById("novoVeiculo").style.display = "none";
                document.getElementById("form-veiculo").style.display = "block";
                document.querySelector("#info-veiculo>h2").textContent = "Informações do Veículo";
                document.getElementById("irPaginaCondutor").style.display = "inline-block";
            }
        } else {
            exibirMensagemInicio(resposta.mensagem,true);
        }
    });

    let veiculos = document.getElementById("veiculos");
    let formVeiculo = document.getElementById("form-veiculo");
    let btnNovo = document.getElementById("novoVeiculo");
    btnNovo.onclick = function (event) {
        event.preventDefault();
        if (formVeiculo.style.display === "none") {
            veiculos.style.display = "none";
            formVeiculo.style.display = "block";
            btnNovo.textContent = "Escolher";
            document.getElementById("idVeiculo").value = "novo";
            document.getElementById("irPaginaCondutor").style.display = "inline-block";
        } else {
            document.getElementById("irPaginaCondutor").style.display = "none";
            btnNovo.textContent = "Novo";
            veiculos.style.display = "grid";
            formVeiculo.style.display = "none";
        }
    };
}

function setVoltarGastos(pagVolta) {
    document.getElementById("voltarPagina").onclick = function () {
        if (pagVolta === "info-veiculo") {
            document.getElementById("idVeiculo").value = "novo";
        } else {
            document.getElementById("idCondutor").value = "novo";
        }
        document.getElementById("info-gastos").classList.remove("ativado");
        document.getElementById(pagVolta).classList.add("ativado");
    }
}

function setCadastroViagem(tarefa) {

    let botaoCadastraViagem = document.getElementById("cadastrarViagem");
    botaoCadastraViagem.onclick = function(event) {
        document.getElementById("lds-ring").style.display = "block";
        document.getElementById("cadastrarViagem").disabled = true;
        document.getElementById("voltarPagina").disabled = true;
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
            gastos : adicionaGastosBody()
        };

        let veiculo;
        if (document.getElementById("idVeiculo").value !== "novo") {
            veiculo = document.getElementById("idVeiculo").value;
        } else {
            veiculo = {
                nome: document.getElementById("veiculo").value,
                tipo: document.getElementById("tipoVeiculo").value,
                dtRetirada: document.getElementById("dataRetirada").value,
                horaRetirada: document.getElementById("horaRetirada").value,
                dtDevolucao: document.getElementById("dataDevolucao").value,
                horaDevolucao: document.getElementById("horaDevolucao").value
            };

            let condutor;
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
        requisicao("POST","/api/viagens",bodyViagem,function (resposta,codigo) {
            document.getElementById("lds-ring").style.display = "none";
            document.getElementById("cadastrarViagem").disabled = false;
            document.getElementById("voltarPagina").disabled = false;
            if (resposta.status === "sucesso") {
                addMensagem("sucesso=Viagem-cadastrada-com-sucesso!");
            } else {
                exibirMensagem(resposta.mensagem,true,event.target);
            }
        });
    };
}

function adicionaGastosBody() {
    let gastos = [];
    let containerGastos = document.getElementsByClassName("container-gasto");
    for (let containerGasto of containerGastos) {
        gastos.push({
            tipo: containerGasto.getElementsByClassName("tipoGasto")[0].value,
            valor: containerGasto.getElementsByClassName("valorGasto")[0].value
        })
    }

    let container = document.getElementById("gastosPadroes");
    let gastosPadroes = container.getElementsByClassName("form-group");
    for (let gastoPadrao of gastosPadroes) {
        gastos.push({
            tipo: gastoPadrao.getElementsByClassName("form-label")[0].textContent,
            valor: decideGasto(gastoPadrao)
        });
    }

    return gastos;
}

function decideGasto(gastoPadrao) {
    let valor = gastoPadrao.getElementsByClassName("form-input")[0].value;
    if (valor == "") {
        return 0.00
    } else {
        return valor;
    }
}
