window.onload = function () {
    verificaMensagem();

    let idCompraRequisitada = window.location.pathname.split("/").pop();

    requisicao("GET", "/api/compras/"+idCompraRequisitada, null, function (resposta, codigo) {
        if (resposta.status === "sucesso") {
            let requisitor = resposta.requisitor;
            let compra = resposta.dados;
            let comprador = compra.comprador;

            setLinks(requisitor);
            document.getElementById("titulo").textContent = "Compra realizada por "+compra.comprador.login;

            if (requisitor.id === comprador.id) {
                exibeDetalhesProprietario(compra);
                exibeItensProprietario(compra.itens);
                setCadastroItem(idCompraRequisitada);
                setExclusaoCompra(idCompraRequisitada,requisitor.id);
                setAlteracaoCompra(idCompraRequisitada);
            } else {
                exibeItensNaoProprietario(compra.itens);
                exibeDetalhesNaoProprietario(compra);
                document.getElementById("novoItem").style.display = "none";
                document.getElementById("alterarCompra").style.display = "none";
                document.getElementById("abreModalExcluirCompra").style.display = "none";
            }
        } else {
            decideErros(resposta, codigo);
        }
    });
};
function exibeItensNaoProprietario(itens) {
    let containerItens = document.getElementById("itens");
    for (let item of itens) {
        containerItens.insertAdjacentHTML("beforeend",`
            <form class="item">
                <div class="form-group">
                    <label class="alterar-label">Item</label>
                    <span class="span-compra">${item.nome}</span>
                </div>
                <div class="form-group">
                    <label class="alterar-label">Quantidade</label>
                     <span class="span-compra">${item.quantidade}</span>
                </div>
                <div class="form-group">
                    <label class="alterar-label">Valor</label>
                     <span class="span-compra">${item.valor}</span>
                </div>
            </form>
            <hr>
        `);
    }
}

function exibeDetalhesNaoProprietario(compra) {
    let inputs = document.getElementsByClassName("alterar-input");
    for (let input of inputs) {
        input.style.display = "none";
    }
    for (let span of document.getElementsByClassName("span-compra")) {
        span.style.display = "block";
    }
    document.getElementsByClassName("alterar-area")[0].style.display = "none";
    document.getElementById("span-nat").textContent = compra.naturezaOrcamentaria;
    document.getElementById("span-fonte").textContent = compra.fonte;
    document.getElementById("totalGasto").textContent = "R$ "+compra.totalGasto;
    document.getElementById("span-proposito").textContent = compra.proposito;
}

function exibeDetalhesProprietario(compra) {
    document.getElementById("natOrcamentaria").value = compra.naturezaOrcamentaria;
    document.getElementById("fonte").value = compra.fonte;
    document.getElementById("totalGasto").textContent = "R$ "+compra.totalGasto;
    document.getElementById("proposito").value = compra.proposito;
}

function exibeItensProprietario(itens) {
    let containerItens = document.getElementById("itens");
    for (let item of itens) {
        containerItens.insertAdjacentHTML("beforeend",`
            <form class="item">
                <div class="form-group">
                    <label class="alterar-label" for="nomeItem${item.id}">Item</label>
                    <input type="text" id="nomeItem${item.id}" value="${item.nome}" class="alterar-input">
                </div>
                <div class="form-group">
                    <label class="alterar-label" for="qtdItem${item.id}">Quantidade</label>
                    <input type="text" id="qtdItem${item.id}" value="${item.quantidade}" class="alterar-input">
                </div>
                <div class="form-group">
                    <label class="alterar-label" for="valorItem${item.id}">Valor</label>
                    <input type="text" id="valorItem${item.id}" class="alterar-input" value="${item.valor}">
                </div>
                <button class="botao alerta" type="button" id="excluirItem${item.id}"><i class="material-icons">delete</i></button>
                <button class="botao info" type="submit" id="alterarItem${item.id}"><i class="material-icons">edit</i></button>
            </form>
            <hr>
        `);
        setAlteracaoItem(item);
        setExclusaoItem(item);
    }
    if (itens.length === 1) {
        containerItens.getElementsByClassName("alerta")[0].onclick = function (event) {
            exibirMensagem("Não é possível existir uma compra sem itens",true,event.target);
        }
    }
}

function setAlteracaoItem(item) {
    document.getElementById("alterarItem"+item.id).onclick = function (event) {
        event.preventDefault();
        let body = {
            nome: document.getElementById("nomeItem"+item.id).value,
            quantidade: document.getElementById("qtdItem"+item.id).value,
            valor: document.getElementById("valorItem"+item.id).value
        };
        requisicao("PUT","/api/itens/"+item.id,body,function (resposta,codigo) {
            if (resposta.status === "sucesso") {
                addMensagem("sucesso=Item-alterado-com-sucesso");
            } else {
                exibirMensagem(resposta.mensagem,true,event.target);
            }
        });
    }
}

function setExclusaoItem(item) {
    document.getElementById("excluirItem"+item.id).onclick = function (event) {
        requisicao("DELETE","/api/itens/"+item.id,null,function (resposta,codigo) {
            if (resposta.status === "sucesso") {
                addMensagem("sucesso=Item-excluido-com-sucesso");
            } else {
                exibirMensagem(resposta.mensagem,true,event.target);
            }
        });
    }
}

function setCadastroItem(idCompraRequisitada)
{
    document.getElementById("novoItem").onclick = function (event) {
        exibeModal("modalCadastroItem",event.target);
    };

    document.getElementById("cadastrarItem").onclick = function (event) {
        let body =  {
            nome: document.getElementById("nomeCadastro").value,
            quantidade: document.getElementById("qtdCadastro").value,
            valor: document.getElementById("valorCadastro").value,
            idCompra : idCompraRequisitada
        };
        requisicao("POST","/api/itens",body,function (resposta,codigo) {
            if (resposta.status === "sucesso") {
                addMensagem("sucesso=Item-cadastrado-com-sucesso");
            } else {
                exibirMensagem(resposta.mensagem,true,event.target);
            }
        });
    };
}

function setExclusaoCompra(idCompra,idRequisitor)
{
    document.getElementById("abreModalExcluirCompra").onclick = function (event) {
        event.preventDefault();
        exibeModal("modalExclusaoCompra",event.target);
    };

    document.getElementById("excluirCompra").onclick = function (event) {
        requisicao("DELETE","/api/compras/"+idCompra,null,function (resposta,codigo) {
            if (resposta.status === "sucesso") {
                window.location.href = "/projetos/user/"+idRequisitor+"?sucesso=Compra-excluida-com-sucesso";
            } else {
                exibirMensagem(resposta.mensagem,true,event.target);
            }
        });
    };
}

function setAlteracaoCompra(idCompra)
{
    document.getElementById("alterarCompra").onclick = function (event) {
        event.preventDefault();
        let body = {
            proposito: document.getElementById("proposito").value,
            natOrcamentaria: document.getElementById("natOrcamentaria").value,
            fonte: document.getElementById("fonte").value,
        };

        requisicao("PUT","/api/compras/"+idCompra,body,function (resposta,codigo) {
            if (resposta.status === "sucesso") {
                addMensagem("sucesso=Compra-alterada-com-sucesso");
            } else {
                exibirMensagem(resposta.mensagem,true,event.target);
            }
        });
    }
}
