window.onload = function () {

    verificaMensagem();
    var idProjetoRequisitado = window.location.pathname.split("/").pop();

    requisicao("GET", "/api/projetos/"+idProjetoRequisitado, null, true, function (resposta) {

        if (resposta.status === "sucesso") {
            var requisitor = resposta.requisitor;
            setLinks(requisitor.id);
            document.querySelector(".user-img").src = "/" + requisitor.foto;
            document.querySelector(".user-name").textContent = requisitor.login;

            let projeto = resposta.dados;
            if (requisitor.dono === true) {
                let template = `
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
                let projetoDetalhe = document.getElementById("projeto-detalhes");
                projetoDetalhe.insertAdjacentHTML("afterbegin",template);
            }
        } else {
            exibirMensagem(resposta.mensagem,true);
        }
    });
};

