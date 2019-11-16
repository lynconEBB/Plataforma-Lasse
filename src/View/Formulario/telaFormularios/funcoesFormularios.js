window.onload = function () {
    verificaMensagem();

    let requisitado = window.location.pathname.split("/").pop();

    requisicao("GET", "/api/formularios/users/"+requisitado, null, function (resposta, codigo) {
        if (resposta.status === "sucesso") {
            let requisitor = resposta.requisitor;
            setLinks(requisitor);

            if (codigo === 202) {
                document.getElementById("aviso-viagem").style.display = "block";
                document.getElementById("aviso-compra").style.display = "block";
            } else {
                let formularios = resposta.dados;
                let formViagens = [];
                let formCompras = [];

                for (let formulario of formularios) {
                    if (formulario.viagem === null) {
                        formCompras.push(formulario);
                    } else {
                        formViagens.push(formulario);
                    }
                }

                if (requisitor.id === requisitado) {
                    exibeFormularios(formViagens,formCompras);
                } else {
                    exibeFormulariosAdmin(formViagens,formCompras);
                }
            }
        } else {
            decideErros(resposta, codigo);
        }
    });
};

function exibeFormularios(formViagens,formCompras) {
    if (formViagens.length > 0) {
        let containerViagens = document.getElementById("container-viagens");
        for (let formViagem of formViagens ) {
            containerViagens.insertAdjacentHTML("beforeend",`
                <div class="formulario">
                    <div class="info-formulario">
                        <h2>${formViagem.nome}</h2>
                        <span><b>Ultima Modificação: </b>${formViagem.dataModificacao}</span>
                    </div>
                    <div class="botoes">
                        <button class="botao alerta md-1" id="excluir${formViagem.id}" title="Excluir Formulário">
                            <i class="material-icons">delete</i>
                        </button>
                        <button class="botao info md-1" id="atualizar${formViagem.id}" title="Atualizar dados do Formulário">
                            <i class="material-icons">refresh</i>
                        </button>
                        <button class="botao especial md-1" id="download${formViagem.id}" title="Fazer download do Formulário">
                            <i class="material-icons">archive</i>
                        </button>
                    </div>
                    <a class="botao-visualizar" title="Visualizar Origem do Formulário" href="/viagem/${formViagem.viagem.id}">
                        <i class="material-icons">remove_red_eye</i>
                    </a>
                </div>
            `);
            setAtualizacaoDados(formViagem.id);
            setExclusaoFormulario(formViagem.id);
            setDownloadFormulario(formViagem.id);
        }
    } else {
        document.getElementById("aviso-viagem").style.display = "block";
    }

    if (formCompras.length >0) {
        let containerCompras = document.getElementById("container-compras");
        for (let formCompra of formCompras ) {
            containerCompras.insertAdjacentHTML("beforeend",`
                <div class="formulario">
                    <div class="info-formulario">
                        <h2>${formCompra.nome}</h2>
                        <span><b>Ultima Modificação: </b>${formCompra.dataModificacao}</span>
                    </div>
                    <div class="botoes">
                        <button class="botao alerta md-1" id="excluir${formCompra.id}" title="Excluir Formulário">
                            <i class="material-icons">delete</i>
                        </button>
                        <button class="botao info md-1" id="atualizar${formCompra.id}" title="Atualizar dados do Formulário">
                            <i class="material-icons">refresh</i>
                        </button>
                        <button class="botao especial md-1" id="download${formCompra.id}" title="Fazer download do Formulário">
                            <i class="material-icons">archive</i>
                        </button>
                    </div>
                    <a class="botao-visualizar" title="Visualizar Origem do Formulário" href="/compra/${formCompra.compra.id}">
                        <i class="material-icons">remove_red_eye</i>
                    </a>
                </div>
            `);
            setAtualizacaoDados(formCompra.id);
            setExclusaoFormulario(formCompra.id);
            setDownloadFormulario(formCompra.id);
        }
    } else {
        document.getElementById("aviso-compra").style.display = "block";
    }
}

function setAtualizacaoDados(idFormulario) {
    document.getElementById("atualizar"+idFormulario).onclick = function (event) {
        requisicao("PUT","/api/formularios/"+idFormulario,null,function (resposta,codigo) {
            if (resposta.status === "sucesso") {
                addMensagem("sucesso=Dados-do-formulario-atualizados-com-sucesso");
            } else {
                exibirMensagem(resposta.mensagem,true,event.target);
            }
        })
    };
}

function setExclusaoFormulario(idFormulario) {
    document.getElementById("excluir"+idFormulario).onclick = function (event) {
        requisicao("DELETE","/api/formularios/"+idFormulario,null,function (resposta,codigo) {
            if (resposta.status === "sucesso") {
                addMensagem("sucesso=Formulario-excluido-com-sucesso");
            } else {
                exibirMensagem(resposta.mensagem,true,event.target);
            }
        })
    };
}

function setDownloadFormulario(idFormulario) {
    document.getElementById("download"+idFormulario).onclick = function () {
        let requisicaoDownload = "/api/formularios/download/"+idFormulario;
        let iframe = document.getElementById("hiddenDownloader");

        if (iframe != null) {
            iframe.remove();
        }

        iframe = document.createElement('iframe');
        iframe.id = "hiddenDownloader";
        iframe.style.visibility = 'none';
        document.body.appendChild(iframe);
        iframe.src = requisicaoDownload;
    }
}

function exibeFormulariosAdmin(formViagens,formCompras) {
    if (formViagens.length > 0) {
        let containerViagens = document.getElementById("container-viagens");
        for (let formViagem of formViagens ) {
            containerViagens.insertAdjacentHTML("beforeend",`
                <div class="formulario">
                    <div class="info-formulario">
                        <h2>${formViagem.nome}</h2>
                        <span><b>Ultima Modificação: </b>${formViagem.dataModificacao}</span>
                    </div>
                    <a class="botao-visualizar" title="Visualizar Origem do Formulário" href="/viagem/${formViagem.viagem.id}">
                        <i class="material-icons">remove_red_eye</i>
                    </a>
                </div>
            `);
        }
    } else {
        document.getElementById("aviso-viagem").style.display = "block";
    }

    if (formCompras.length >0) {
        let containerCompras = document.getElementById("container-compras");
        for (let formCompra of formCompras ) {
            containerCompras.insertAdjacentHTML("beforeend",`
                <div class="formulario">
                    <div class="info-formulario">
                        <h2>${formCompra.nome}</h2>
                        <span><b>Ultima Modificação: </b>${formCompra.dataModificacao}</span>
                    </div>
                    <a class="botao-visualizar" title="Visualizar Origem do Formulário" href="/compra/${formCompra.compra.id}">
                        <i class="material-icons">remove_red_eye</i>
                    </a>
                </div>
            `);
        }
    } else {
        document.getElementById("aviso-compra").style.display = "block";
    }
}
