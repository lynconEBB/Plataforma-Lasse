function exibeViagens(tarefa) {
    let viagens = tarefa.viagens;

    if (viagens.length > 0 ){
        for (let viagem of viagens) {
            let templateViagem = ` 
                <a href="/viagem/${viagem.id}" class="viagem"">
                    <h2>${viagem.origem} - ${viagem.destino}</h2>
                    <hr>
                    <span class="viagem-label">Viajante: ${viagem.viajante.nomeCompleto}</span>
                    <span class="viagem-label">Total Gasto: ${viagem.totalGasto}</span>
                </a>
            `;
            document.getElementById("viagens").insertAdjacentHTML("beforeend",templateViagem);
        }
    } else {
        document.getElementById("aviso-viagem").style.display = "block";
    }
}


function setCadastroViagem() {
    if (requisitor.participa) {
        document.getElementById("tabViagens").insertAdjacentHTML("afterend","<button class='botao-adicionar' id='abreModalCadastrarViagem'>Novo</button>");
        document.getElementById('abreModalCadastrarViagem').onclick = () => exibeModal("#modalCadastrarViagem");

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
}
