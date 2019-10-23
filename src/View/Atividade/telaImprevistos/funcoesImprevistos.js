window.onload = function () {
    verificaMensagem();

    let idUserRequisitado = window.location.pathname.split("/").pop();

    requisicao("GET", "/api/atividades/user/"+idUserRequisitado, null, function (resposta, codigo) {
        if (resposta.status === "sucesso") {
            let requisitor = resposta.requisitor;

            setLinks(requisitor);
            if (requisitor.id == idUserRequisitado) {
                document.getElementById("abreModalCadastro").style.display = "inline-block";
                document.getElementById("abreModalCadastro").onclick = function (event) {
                    exibeModal("modalCadastroImprevisto",event.target);
                };
                setBotaoCadastro();
            }

            if (codigo === 200) {

                document.getElementById("titulo").textContent = "Imprevistos de "+resposta.dados[0].usuario.login;
                let imprevistos = resposta.dados;
                for (let imprevistoDados of imprevistos) {

                    let imprevistoElemento = criaImprevistoContainer(imprevistoDados.id);
                    insereDadosImprevisto(imprevistoElemento,imprevistoDados,"");
                    setBotaoExpande(imprevistoElemento);
                    if (requisitor.id == idUserRequisitado) {
                        setBotoesAcoes(imprevistoElemento,imprevistoDados);
                    }
                }
            } else {
                requisicao("GET","/api/users/"+idUserRequisitado,null,function (resposta,codigo) {
                    if (resposta.status === "sucesso") {
                        document.getElementById("titulo").textContent = "Imprevistos de "+resposta.dados.login;
                    }
                });
                document.getElementsByClassName("imprevistos-body")[0].innerHTML = `
                    <div class="erro-container">
                        <figure>
                            <img alt="Icone de Vazio" src="/assets/images/vazio.png">
                        </figure>
                        <h2 class="erro-texto">Nenhum Imprevisto cadastrado por este usuário</h2>
                    </div>
                `;
            }
        } else {
            decideErros(resposta, codigo);
        }
    });
};

function criaImprevistoContainer(IdImprevisto) {
    let container = document.getElementsByClassName("imprevistos-body")[0];
    container.insertAdjacentHTML("beforeend", `
        <div class="imprevisto" aria-expanded="false" id="imprevisto${IdImprevisto}" tabindex="-1"></div>
    `);
    return document.getElementById("imprevisto"+IdImprevisto);
}

function insereDadosImprevisto(imprevistoElemento,imprevisto,expandido) {
    imprevistoElemento.innerHTML = `
        <div class="imprevisto-info">
            <span><span class="escondeVisualmente">Tipo:</span> ${imprevisto.tipo}</span>
            <span><span class="escondeVisualmente">Data: </span> ${imprevisto.dataRealizacao}</span>
            <span class="total"><span class="escondeVisualmente">Total Gasto: </span> R$ ${imprevisto.totalGasto}</span>
            <div class="imprevisto-detalhes ${expandido}">
                <div class="detalhe-group">
                    <span class="detalhe-nome">Comentario</span>
                    <span class="detalhe-valor">${imprevisto.comentario}</span>
                </div>
                <div class="detalhe-group">
                    <span class="detalhe-nome">Tempo Gasto</span>
                    <span class="detalhe-valor">${imprevisto.tempoGasto}</span>
                </div>
                <div class="detalhe-group total">
                    <span class="detalhe-nome">Total Gasto</span>
                    <span class="detalhe-valor">R$ ${imprevisto.totalGasto}</span>
                </div>
            </div>
        </div>
        <button class="expande" aria-label="Expande detalhes do Imprevisto ${imprevisto.tipo} ocorrido em ${imprevisto.dataRealizacao} com total gasto de R$ ${imprevisto.totalGasto}">
              <i class="material-icons">keyboard_arrow_down</i>          
        </button>
    `;
}

function setBotaoExpande(imprevisto) {
    let imprevistoDetalhes = imprevisto.querySelector(".imprevisto-detalhes");
    let btnExpande =  imprevisto.querySelector(".expande");

    if (imprevistoDetalhes.classList.contains("expandido")) {
        btnExpande.innerHTML = "<i class='material-icons'>keyboard_arrow_up</i> ";
    } else {
        btnExpande.innerHTML = "<i class='material-icons'>keyboard_arrow_down</i> ";
    }

    imprevisto.querySelector(".expande").onclick = function () {
        let label = btnExpande.getAttribute("aria-label");

        if (imprevistoDetalhes.classList.contains("expandido")) {
            imprevistoDetalhes.classList.remove("expandido");
            let newLabel = label.replace("Reduz","Expande");
            btnExpande.setAttribute("aria-label",newLabel);
            btnExpande.innerHTML = "<i class='material-icons'>keyboard_arrow_down</i> ";
        } else {
            imprevistoDetalhes.classList.add("expandido");
            let newLabel = label.replace("Expande","Reduz");
            btnExpande.setAttribute("aria-label",newLabel);
            imprevisto.focus();
            btnExpande.innerHTML = "<i class='material-icons'>keyboard_arrow_up</i> ";
        }
    };
}

function setBotoesAcoes(imprevisto,imprevistoDados) {

    let imprevistoDetalhes = imprevisto.querySelector(".imprevisto-detalhes");

    imprevistoDetalhes.insertAdjacentHTML("beforeend",`
        <div class="detalhe-botoes">
            <button class="botao info btn-detalhe">Alterar</button>
            <button class="botao alerta btn-detalhe">Excluir</button>
        </div>
    `);

    let botaoExibeInputs = imprevistoDetalhes.querySelector(".info");
    botaoExibeInputs.onclick = function () {
        exibeInputs(imprevisto,imprevistoDados);
        setBotoesAlterar(imprevisto,imprevistoDados);
        imprevisto.querySelector("#tipo").focus();
    };

    let botaoExibeModalExcluir = imprevistoDetalhes.querySelector(".alerta");
    botaoExibeModalExcluir.onclick =  function (event) {
        requisicao("DELETE","/api/atividades/"+imprevistoDados.id,null,function (resposta,codigo) {
            if (resposta.status === "sucesso") {
                addMensagem("sucesso=Imprevisto-excluido-com-sucesso");
            } else {
                exibirMensagem(resposta.mensagem,true,event.target);
            }
        });
    };
}

function exibeInputs(imprevistoElemento,imprevisto) {

    let imprevistoInfo = imprevistoElemento.querySelector(".imprevisto-info");

    imprevistoInfo.innerHTML = `
        <div class="imprevisto-group">
            <label class="escondeVisualmente" for="tipo">Tipo</label>
            <select class="alterar-select" id="tipo">
                <option value="Atraso">Atraso</option>
                <option value="Consulta">Consulta</option>
                <option value="Viagem">Viagem</option>
                <option value="Acidente">Acidente</option>
            </select>
        </div>
        <div class="imprevisto-group" >
            <label class="escondeVisualmente" for="data">Data de Realização</label>
            <input class="alterar-input" id="data" type="text" value="${imprevisto.dataRealizacao}">
        </div>
        
        <span class="total"><span class="escondeVisualmente">Total Gasto: </span> R$ ${imprevisto.totalGasto}</span>
        
        <div class="imprevisto-detalhes expandido">
            <div class="detalhe-group">
                <label class="alterar-label" for="comentario">Comentário</label>
                <textarea class="alterar-area" id="comentario" rows="3">${imprevisto.comentario}</textarea>
            </div>
            <div class="detalhe-group">
                <label class="alterar-label" for="tempoGasto">Tempo Gasto</label>
                <input class="alterar-input" type="text" id="tempoGasto" value="${imprevisto.tempoGasto}">
            </div>
            <div class="detalhe-group total">
                <span class="detalhe-nome">Total Gasto</span>
                <span class="detalhe-valor">R$ ${imprevisto.totalGasto}</span>
            </div>
            <div class="detalhe-botoes">
                <button class="botao alerta btn-detalhe">Cancelar</button>
                <button class="botao sucesso btn-detalhe">Salvar</button>
            </div>
        </div>`;
    imprevistoInfo.querySelector("#tipo").value = imprevisto.tipo;
    setBotaoExpande(imprevistoElemento);
    return imprevistoInfo;
}

function setBotoesAlterar(imprevisto,imprevistoDados) {
    let botaoCancelar  = imprevisto.querySelector(".alerta");

    botaoCancelar.onclick = function () {
        insereDadosImprevisto(imprevisto,imprevistoDados,"expandido");
        setBotaoExpande(imprevisto);
        setBotoesAcoes(imprevisto,imprevistoDados);
    };

    imprevisto.querySelector(".sucesso").onclick =  function (event) {
        let body = {
            tipo: imprevisto.querySelector("#tipo").value,
            tempoGasto: imprevisto.querySelector("#tempoGasto").value,
            comentario: imprevisto.querySelector("#comentario").value,
            dataRealizacao: imprevisto.querySelector("#data").value,
        };

        requisicao("PUT", "/api/atividades/"+imprevistoDados.id, body, function (resposta, codigo) {
            if (resposta.status === "sucesso") {
                addMensagem("sucesso=Imprevisto-alterado-com-sucesso");
            } else {
                exibirMensagem(resposta.mensagem, true, event.target);
            }
        });
    }
}

function setBotaoCadastro() {
    let botaoCadastro = document.getElementById("cadastraImprevisto");
    botaoCadastro.onclick = function (event) {
        event.preventDefault();
        let body = {
            tipo: document.querySelector("#tipoCadastro").value,
            tempoGasto: document.querySelector("#tempoCadastro").value,
            comentario: document.querySelector("#comentarioCadastro").value,
            dataRealizacao: document.querySelector("#dataCadastro").value,
        };

        requisicao("POST","/api/atividades/",body,function (resposta,codigo) {
            if (resposta.status === "sucesso") {
                addMensagem("sucesso=Imprevisto-cadastrado-com-sucesso");
            } else {
                exibirMensagem(resposta.mensagem,true,event.target);
            }
        });
    }
}

