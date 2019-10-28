function setBotaoAbreModalCompra() {
    let botaoModalCompra =   document.getElementById("abreModalCadastroCompra");
    botaoModalCompra.style.display = "inline-block";
    botaoModalCompra.onclick = function (event) {
        exibeModal("modalCadastroCompra",event.target);
    };

    document.getElementById("excluiItem").addEventListener("click",function () {
        this.parentElement.remove();
    });
    let containerItens = document.getElementById("itens");
    let count = 0;
    document.getElementById("novoItem").onclick = function (event) {
        event.preventDefault();
        containerItens.insertAdjacentHTML("afterbegin",`
            <div class="container-item">
                <div class="form-group">
                    <label for="nomeItem${count}" class="form-label ">Item</label>
                    <input type="text" id="nomeItem${count}" class="form-input itemNome">
                </div>
                <div class="form-group">
                    <label for="item${count}" class="form-label ">Quantidade</label>
                    <input type="text" id="item${count}" class="form-input itemQtd">
                </div>
                <div class="form-group">
                    <label for="qtdItem${count}" class="form-label ">Valor</label>
                    <input type="text" id="qtdItem${count}" class="form-input itemValor">
                </div>
                <button class="botao alerta" id="excluiItem${count}">
                    <i class="material-icons">delete</i>
                </button>
            </div>
        `);
        document.getElementById("excluiItem"+count).addEventListener("click",function () {
            this.parentElement.remove();
        });
        count++;
        setInputs();
    }
}

function setCadastroCompra(tarefa) {
    let containerItens = document.getElementById("itens");

    document.getElementById("cadastrarCompra").onclick = function (event) {
        if (containerItens.childElementCount) {
            let body = {
                proposito : document.getElementById("proposito").value,
                fonte : document.getElementById("fonteCompra").value,
                natOrcamentaria : document.getElementById("natOrcamentaria").value,
                idTarefa: tarefa.id,
                itens: getItens()
            };

            requisicao("POST","/api/compras",body,function (resposta,codigo) {
               if (resposta.status === "sucesso") {
                   addMensagem("sucesso=Compra-cadastrada-com-sucesso");
               } else {
                   exibirMensagem(resposta.mensagem,true,event.target);
               }
            });
        } else {
            exibirMensagem("Não é possível cadastrar um compra sem itens",true,event.target);
        }
    };
}

function getItens() {
    let itens = document.getElementsByClassName("container-item");
    let arrayItens = [];
    for (let item of  itens) {
        arrayItens.push({
            nome: item.getElementsByClassName("itemNome")[0].value,
            valor: item.getElementsByClassName("itemValor")[0].value,
            quantidade: item.getElementsByClassName("itemQtd")[0].value
        });
    }

    return arrayItens;
}

function exibeCompras(tarefa,requisitor) {
    let compras = tarefa.compras;
    if (compras != null ){
        for (let compra of compras) {
            let totalItens = 0;
            for (let item of compra.itens) {
                totalItens += item.quantidade;
            }
            let templateCompra = ` 
                <a href="#" class="compra" id="compra${compra.id}">
                    <h2>Compra com ${totalItens} itens</h2>
                    <hr>
                    <span class="viagem-label">Comprador: ${compra.comprador.nomeCompleto}</span>
                    <span class="viagem-label">Total Gasto: R$ ${compra.totalGasto}</span>
                </a>
            `;
            document.getElementById("compras").insertAdjacentHTML("afterbegin",templateCompra);
            let compraExibida = document.getElementById("compra"+compra.id);
            if (requisitor.admin === "1" || requisitor.id === compra.comprador.id) {
                compraExibida.setAttribute("href","/compra/"+compra.id);
            } else {
                compraExibida.onclick = function(event) {
                    event.preventDefault();
                    exibirMensagem("Você não possui permissão para visualizar esta compra",true,event.target);
                }
            }
        }
    } else {
        document.getElementById("aviso-compra").style.display = "block";
    }
}