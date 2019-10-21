window.onload = function () {
    verificaMensagem();

    let idUserRequisitado = window.location.pathname.split("/").pop();

    requisicao("GET", "/api/atividades/user/"+idUserRequisitado, null, function (resposta, codigo) {
        if (resposta.status === "sucesso") {
            let requisitor = resposta.requisitor;
            setLinks(requisitor);

            if (codigo === 200) {
                let imprevistos = resposta.dados;
                for (let imprevisto of imprevistos) {
                    preparaImprevisto(imprevisto,requisitor.id,idUserRequisitado);

                }
            }
        } else {
            decideErros(resposta, codigo);
        }
    });
};

function preparaImprevisto(imprevisto,idRequisitor,idUser) {
    let container = document.getElementsByClassName("imprevistos-body")[0];

    container.insertAdjacentHTML("beforeend",`
        <div class="imprevisto" role="button" aria-expanded="false" tabindex="0" id="imprevisto${imprevisto.id}">
            <span><span class="escondeVisualmente">Tipo:</span> ${imprevisto.tipo}</span>
            <span><span class="escondeVisualmente">Data: </span> ${imprevisto.dataRealizacao}</span>
            <span class="total"><span class="escondeVisualmente">Total Gasto: </span> R$ ${imprevisto.totalGasto}</span>
            <div class="imprevisto-detalhes">
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
    `);

    let imprevistoElement = document.querySelector("#imprevisto"+imprevisto.id);

    if (idRequisitor == idUser) {
        document.querySelector("#imprevisto"+imprevisto.id+">.imprevisto-detalhes").insertAdjacentHTML("beforeend",`
            <div class="detalhe-botoes">
                <button class="botao info btn-detalhe">Alterar</button>
                <button class="botao alerta btn-detalhe">Excluir</button>
            </div>
        `);

        imprevistoElement.querySelector(".info").onclick = function () {
            imprevistoElement.innerHTML = `
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
                
                <div class="imprevisto-detalhes">
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
                        <button class="botao alerta btn-detalhe">Voltar</button>
                        <button class="botao sucesso btn-detalhe">Salvar</button>
                    </div>
                </div>
            `;

            imprevistoElement.querySelector("#tipo").value = imprevisto.tipo;

            imprevistoElement.querySelector(".alerta").onclick =  function () {
                imprevistoElement.innerHTML = `
                <span><span class="escondeVisualmente">Tipo:</span> ${imprevisto.tipo}</span>
                <span><span class="escondeVisualmente">Data: </span> ${imprevisto.dataRealizacao}</span>
                <span class="total"><span class="escondeVisualmente">Total Gasto: </span> R$ ${imprevisto.totalGasto}</span>
                <div class="imprevisto-detalhes">
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
                    <div class="detalhe-botoes">
                        <button class="botao info btn-detalhe">Alterar</button>
                        <button class="botao alerta btn-detalhe">Excluir</button>
                    </div>
                </div>
                `;
            };

            imprevistoElement.querySelector(".sucesso").onclick =  function (event) {
                let body = {
                    tipo: imprevistoElement.querySelector("#tipo").value,
                    tempoGasto: imprevistoElement.querySelector("#tempoGasto").value,
                    comentario: imprevistoElement.querySelector("#comentario").value,
                    dataRealizacao: imprevistoElement.querySelector("#data").value,
                };

                requisicao("PUT","/api/atividades/"+imprevisto.id,body,function (resposta,codigo) {
                    if (resposta.status === "sucesso") {
                        addMensagem("sucesso=Imprevisto-alterado-com-sucesso");
                    } else {
                        exibirMensagem(resposta.mensagem,true,event.target);
                    }
                });
            };
        };

    }


    imprevistoElement.onclick = function () {
        let detalhes = this.querySelector(".imprevisto-detalhes");

        if (detalhes.classList.contains("expandido")) {
            this.setAttribute("aria-expanded",false);
            detalhes.classList.remove("expandido");
        } else {
            if (document.querySelector(".expandido") !== null) {
                document.querySelector(".expandido").parentElement.setAttribute("aria-expanded",false);
                document.querySelector(".expandido").classList.remove("expandido");
            }
            detalhes.classList.add("expandido");
            this.setAttribute("aria-expanded","true");
        }
    };
}


