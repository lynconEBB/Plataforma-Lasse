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
    let pagHosp = document.getElementById("info-hosp");
    let pagViagem = document.getElementById("info-viagem");
    let pagGastos =  document.getElementById("info-gastos");
    let pagVeiculo = document.getElementById("info-veiculo");

    document.getElementById("irPaginaHosp").onclick = function() {
        pagViagem.classList.remove("ativado");
        pagHosp.classList.add("ativado");
    };

    document.getElementById("voltaPagViagem").onclick = function() {
        pagHosp.classList.remove("ativado");
        pagViagem.classList.add("ativado");
    };

    document.getElementById("irPagVeiculo").onclick = function() {
        pagHosp.classList.remove("ativado");
        pagVeiculo.classList.add("ativado");
    };
}

function setFuncionamentoModalViagem() {
    setBotoesNavegacaoModal();
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
                        <button class="selecionaVeiculo" title="Escolher Veículo">
                            <i class="material-icons md-15">keyboard_arrow_right</i>
                        </button>
                    </div>
                    `);
                }
            } else {
                // TODO: Implementar aviso sem veiculos
            }
        } else {
            exibirMensagemInicio(resposta.mensagem,true);
        }
    });

    document.getElementById("novoVeiculo").onclick = function (event) {
        event.preventDefault();

        document.getElementById("veiculos").style.display = "none";
        document.getElementById("form-veiculo").style.display = "block";
    };

    /*let selectCondutor = document.getElementById("idCondutor");
    let groupCondutor = document.getElementById("group-condutor");
    let containerCondutor = document.getElementById("container-condutor");
    let botaoAbreCondutor = document.getElementById("abreCondutor");
    let botaoFechaCondutor = document.getElementById("fechaCondutor");

    requisicao("GET","/api/condutores",null,function (resposta) {
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
    };*/

}
function setCadastroViagem() {
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
        requisicao("POST","/api/viagens",bodyViagem,function (resposta) {
            if (resposta.status === "sucesso") {
                addMensagem("sucesso=Viagem-cadastrada-com-sucesso!");
            } else {
                exibirMensagem(resposta.mensagem,true);
            }
        });
    };

}
