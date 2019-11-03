window.onload = function () {
    verificaMensagem();

    let idViagemRequisitada = window.location.pathname.split("/").pop();

    requisicao("GET", "/api/viagens/"+idViagemRequisitada, null, function (resposta,codigo) {
        if (resposta.status === "sucesso") {
            let requisitor = resposta.requisitor;

            setLinks(requisitor);

            let viagem = resposta.dados;

            if (requisitor.id === viagem.viajante.id) {
                templateDono(viagem);
                setAlteracaoViagem(viagem);
                setExclusaoViagem(viagem);
                setDetalhesTransporte(viagem.veiculo);
                setAlteracaoTransporte(viagem);
            } else {
                templateAdmin(viagem);
                document.querySelector(".container-botoes").style.display = "none";
            }
        } else {
            decideErros(resposta,codigo);
        }
    });
};

function setAlteracaoTransporte(viagem) {
    document.getElementById("abreModalAlterarTransporte").onclick = function (event) {
        exibeModal("modalAlteracaoTransporte",event.target);
    };
    exibeVeiculos(viagem.veiculo);
    exibeCondutores(viagem.veiculo.condutor);
    document.getElementById("irPaginaCondutor").onclick = function () {
        document.getElementById("info-veiculo").classList.remove("ativado");
        document.getElementById("info-condutor").classList.add("ativado");
    };
    document.getElementById("voltarPaginaVeiculo").onclick = function () {
        document.getElementById("info-condutor").classList.remove("ativado");
        document.getElementById("info-veiculo").classList.add("ativado");
    };

    document.getElementById("atualizarTransporte").onclick = function (event) {
        let novoVeiculo = {
            nome: document.getElementById("veiculo").value,
            tipo: document.getElementById("tipoVeiculo").value,
            dtRetirada: document.getElementById("dtRetirada").value,
            horaRetirada: document.getElementById("horaRetirada").value,
            dtDevolucao: document.getElementById("dtDevolucao").value,
            horaDevolucao: document.getElementById("horaDevolucao").value,
            condutor:  {
                nome: document.getElementById("nomeCondutor").value,
                cnh: document.getElementById("cnh").value,
                validadeCNH: document.getElementById("validadeCNH").value,
            }
        };
        let body = viagem;
        delete body.id;
        delete body.veiculo;
        body["veiculo"] = novoVeiculo;

        requisicao("PUT","/api/viagens"+viagem.id,body,function (resposta,codigo) {
            if (resposta.status === "sucesso") {

            } else {
                exibirMensagem(resposta.mensagem,true,event.target);
            }
        })

    }
}


function exibeCondutores(condutorUsado) {
    requisicao("GET","/api/condutores",null,function (resposta,codigo) {
        if (resposta.status === "sucesso") {
            let condutores = resposta.dados;

            let containerCondutores = document.getElementById("condutores");
            let condutoresDif = [];
            for (let condutor of condutores) {
                if (condutor.id !== condutorUsado.id) {
                    condutoresDif.push(condutor);
                }
            }

            if (condutoresDif.length > 0) {
                for (let condutor of condutoresDif) {
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
                    };
                }
            } else {
                document.getElementById("novoCondutor").style.display = "none";
                document.querySelector("#info-condutor>h2").textContent = "Altere os dados do condutor";
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
            btnNovo.textContent = "Escolher";
            document.getElementById("atualizarTransporte").style.display = "block";
        } else {
            document.getElementById("atualizarTransporte").style.display = "none";
            btnNovo.textContent = "Atualizar";
            condutores.style.display = "grid";
            formCondutor.style.display = "none";
        }
    };
}

function exibeVeiculos(veiculoUsado) {
    requisicao("GET","/api/veiculos",null,function (resposta) {
        if (resposta.status === "sucesso") {
            let veiculos = resposta.dados;
            let veiculosDif = [];
            for (let veiculo of veiculos) {
                if (veiculo.id !== veiculoUsado.id) {
                    veiculosDif.push(veiculo);
                }
            }
            if (veiculosDif.length > 0) {
                for (let veiculo of veiculosDif) {
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
                    };
                }
            } else {
                document.getElementById("novoVeiculo").style.display = "none";
                document.querySelector("#info-veiculo>h2").textContent = "Altere os dados do veiculo";
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
            document.getElementById("irPaginaCondutor").style.display = "inline-block";
        } else {
            document.getElementById("irPaginaCondutor").style.display = "none";
            btnNovo.textContent = "Atualizar";
            veiculos.style.display = "grid";
            formVeiculo.style.display = "none";
        }
    };
}


function setExclusaoViagem(viagem) {
    document.getElementById("abreModalExclusaoViagem").onclick = function (event) {
        event.preventDefault();
        exibeModal("modalExclusaoViagem",event.target);
    };

    document.getElementById("excluirViagem").onclick = function (event) {
        requisicao("DELETE","/api/viagens/"+viagem.id,null,function (resposta,codigo) {
            if (resposta.status === "sucesso") {
                window.location.href = "/tarefa/"+resposta.requisitor.idTarefa+"?sucesso=Viagem-excluida-com-sucesso";
            } else {
                exibirMensagem(resposta.mensagem,true,event.target);
            }
        })
    }
}

function setAlteracaoViagem(viagem) {
    document.getElementById("alterarViagem").onclick = function (event) {
        event.preventDefault();
        let body = criaViagemAtualizada(viagem);

        requisicao("PUT","/api/viagens/"+viagem.id,body,function (resposta,codigo) {
            if (resposta.status === "sucesso") {
                addMensagem("sucesso=Viagem-atualizada-com-sucesso");
            } else {
                exibirMensagem(resposta.mensagem,true,event.target);
            }
        });
    };
}

function templateDono(viagem) {
    let saidaHosp = viagem.saidaHosp.split(" ");
    let entradaHosp = viagem.entradaHosp.split(" ");
    document.getElementById("origem").value = viagem.origem;
    document.getElementById("destino").value = viagem.destino;
    document.getElementById("dataIda").value = viagem.dtIda;
    document.getElementById("dataVolta").value = viagem.dtVolta;
    document.getElementById("passagem").value = viagem.passagem;
    document.getElementById("justificativa").value = viagem.justificativa;
    document.getElementById("observacoes").value = viagem.obeservacoes;
    document.getElementById("dtEntradaHosp").value = entradaHosp[0];
    document.getElementById("dtSaidaHosp").value = saidaHosp[0];
    document.getElementById("horaEntradaHosp").value = entradaHosp[1];
    document.getElementById("horaSaidaHosp").value = saidaHosp[1];
    document.getElementById("fonte").value = viagem.fonte;
    document.getElementById("atividade").value = viagem.atividade;
    document.getElementById("tipo").value = viagem.tipo;
    document.getElementById("tipoPassagem").value = viagem.tipoPassagem;
    document.getElementById("span-veiculo").textContent = viagem.veiculo.nome;
    document.getElementById("span-tipoVeiculo").textContent = viagem.veiculo.tipo;
    document.getElementById("span-dtRetirada").textContent = viagem.veiculo.dataRetirada;
    document.getElementById("span-dtDevolucao").textContent = viagem.veiculo.dataDevolucao;
    document.getElementById("span-horaDevolucao").textContent = viagem.veiculo.horarioDevolucao;
    document.getElementById("span-horaRetirada").textContent = viagem.veiculo.horarioRetirada;
    document.getElementById("span-condutor").textContent = viagem.veiculo.condutor.nome;
    document.getElementById("span-cnh").textContent = viagem.veiculo.condutor.cnh;
    document.getElementById("span-validadeCNH").textContent = viagem.veiculo.condutor.validadeCNH;
    document.getElementById("totalGasto").textContent = "R$ "+viagem.totalGasto;

}

function criaViagemAtualizada(viagem) {
    return {
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
        veiculo: viagem.veiculo.id
    }
}

function setDetalhesTransporte(veiculo) {
    document.getElementById("veiculo").value = veiculo.nome;
    document.getElementById("tipoVeiculo").value = veiculo.tipo;
    document.getElementById("dtRetirada").value = veiculo.dataRetirada;
    document.getElementById("dtDevolucao").value = veiculo.dataDevolucao;
    document.getElementById("horaDevolucao").value = veiculo.horarioDevolucao;
    document.getElementById("horaRetirada").value = veiculo.horarioRetirada;
    document.getElementById("nomeCondutor").value = veiculo.condutor.nome;
    document.getElementById("cnh").value = veiculo.condutor.cnh;
    document.getElementById("validadeCNH").value = veiculo.condutor.validadeCNH;
}

function templateAdmin(viagem) {

}



