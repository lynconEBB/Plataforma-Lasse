window.onload = function () {
    verificaMensagem();
    var idViagemRequisitada = window.location.pathname.split("/").pop();

    requisicao("GET", "/api/viagens/"+idViagemRequisitada, null, true, function (resposta) {
        if (resposta.status === "sucesso") {
            var requisitor = resposta.requisitor;
            setLinks(requisitor.id);
            document.querySelector(".user-img").src = "/" + requisitor.foto;
            document.querySelector(".user-name").textContent = requisitor.login;

            var viagem = resposta.dados;

            if (requisitor.id === viagem.viajante.id) {
                templateDono(viagem);
                let botaoFormulario = document.getElementById("gerarFormulario");
                botaoFormulario.onclick = function () {
                    requisicao("POST","/api/formularios/requisicaoViagem/"+idViagemRequisitada,null,true,function (resposta) {
                        console.log(resposta);
                        if (resposta.status === "sucesso") {
                            xhr = new XMLHttpRequest();
                            console.log("/"+resposta.dados.caminhoDocumento);
                            xhr.open("GET","/"+resposta.dados.caminhoDocumento);
                            xhr.send();
                            xhr.onload = function () {
                                console.log(xhr.response);
                            }
                        } else {
                            exibirMensagem(resposta.mensagem,true);
                        }
                    })
                };
            } else {
                templateAdmin(viagem);
                document.querySelector(".container-botoes").style.display = "none";
            }

        } else {
            exibirMensagem(resposta.mensagem,true)
        }
    });
};

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
    document.getElementById("veiculo").value = viagem.veiculo.nome;
    document.getElementById("tipoVeiculo").value = viagem.veiculo.tipo;
    document.getElementById("dtRetirada").value = viagem.veiculo.dataRetirada;
    document.getElementById("dtDevolucao").value = viagem.veiculo.dataDevolucao;
    document.getElementById("horaDevolucao").value = viagem.veiculo.horarioDevolucao;
    document.getElementById("horaRetirada").value = viagem.veiculo.horarioRetirada;
    document.getElementById("condutor").value = viagem.veiculo.condutor.nome;
    document.getElementById("cnh").value = viagem.veiculo.condutor.cnh;
    document.getElementById("validadeCNH").value = viagem.veiculo.condutor.validadeCNH;
    document.getElementById("totalGasto").value = "R$ "+viagem.totalGasto;

    let containerGastos = document.getElementById("container-gastos");
    viagem.gastos.forEach(function (gasto) {
        containerGastos.insertAdjacentHTML("beforeend",`<input class='viagem-input' type='text' value='${gasto.tipo}'>`);
        containerGastos.insertAdjacentHTML("beforeend",`<input class='viagem-input' type='text' value='R$ ${gasto.valor}'>`);
    });

}

function templateAdmin(viagem) {
    let saidaHosp = viagem.saidaHosp.split(" ");
    let entradaHosp = viagem.entradaHosp.split(" ");

    document.getElementById("span-origem").textContent = viagem.origem;
    document.getElementById("span-destino").textContent = viagem.destino;
    document.getElementById("span-dataIda").textContent = viagem.dtIda;
    document.getElementById("span-dataVolta").textContent = viagem.dtVolta;
    document.getElementById("span-passagem").textContent = viagem.passagem;
    document.getElementById("span-justificativa").textContent = viagem.justificativa;
    document.getElementById("span-observacoes").textContent = viagem.obeservacoes;
    document.getElementById("span-dtEntradaHosp").textContent = entradaHosp[0];
    document.getElementById("span-dtSaidaHosp").textContent = saidaHosp[0];
    document.getElementById("span-horaEntradaHosp").textContent = entradaHosp[1];
    document.getElementById("span-horaSaidaHosp").textContent = saidaHosp[1];
    document.getElementById("span-fonte").textContent = viagem.fonte;
    document.getElementById("span-atividade").textContent = viagem.atividade;
    document.getElementById("span-tipo").textContent = viagem.tipo;
    document.getElementById("span-tipoPassagem").textContent = viagem.tipoPassagem;
    document.getElementById("span-veiculo").textContent = viagem.veiculo.nome;
    document.getElementById("span-tipoVeiculo").textContent = viagem.veiculo.tipo;
    document.getElementById("span-dtRetirada").textContent = viagem.veiculo.dataRetirada;
    document.getElementById("span-dtDevolucao").textContent = viagem.veiculo.dataDevolucao;
    document.getElementById("span-horaDevolucao").textContent = viagem.veiculo.horarioDevolucao;
    document.getElementById("span-horaRetirada").textContent = viagem.veiculo.horarioRetirada;
    document.getElementById("span-condutor").textContent = viagem.veiculo.condutor.nome;
    document.getElementById("span-cnh").textContent = viagem.veiculo.condutor.cnh;
    document.getElementById("span-validadeCNH").textContent = viagem.veiculo.condutor.validadeCNH;
    document.getElementById("span-totalGasto").textContent = viagem.totalGasto;

    let containerGastos = document.getElementById("container-gastos");
    viagem.gastos.forEach(function (gasto) {
        containerGastos.insertAdjacentHTML("beforeend",`<span class='viagem-span'>${gasto.tipo}</span>`);
        containerGastos.insertAdjacentHTML("beforeend",`<span class='viagem-span' >R$ ${gasto.valor}</span>`);
    });

    let inputs = document.getElementsByClassName("viagem-input");
    for (let input of inputs) {
        input.style.display = "none"
    }
    let selects = document.getElementsByClassName("viagem-select");
    for (let select of selects) {
        select.style.display = "none"
    }
    let areas = document.getElementsByClassName("viagem-textarea");
    for (let area of areas) {
        area.style.display = "none"
    }
    let spans = document.getElementsByClassName("viagem-span");
    for (let span of spans) {
        span.style.display = "block"
    }
}



