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

                exibeFormularios(formViagens,formCompras);

            }
        } else {
            decideErros(resposta, codigo);
        }
    });
};

function exibeFormularios(formViagens,formCompras) {
    if (formViagens.length > 0) {
        let containerCompras = document.getElementById("container-compras");
        for (let formCompra of formCompras ) {
            containerCompras.insertAdjacentHTML("beforeend",`
                <div class="formulario">
                <div class="info-formulario">
                    <h2>${formCompra.nome}</h2>
                    <span><b>Ultima Modificação:</b></span>
                </div>
                <div class="botoes">
                    <button class="botao alerta md-1" title="Excluir Formulário">
                        <i class="material-icons">delete</i>
                    </button>
                    <button class="botao info md-1" title="Atualizar dados do Formulário">
                        <i class="material-icons">refresh</i>
                    </button>
                    <button class="botao especial md-1" title="Fazer download do Formulário">
                        <i class="material-icons">archive</i>
                    </button>
                </div>
                <a class="botao-visualizar" title="Visualizar Origem do Formulário" href="#">
                    <i class="material-icons">remove_red_eye</i>
                </a>
            </div>
            `);
        }
    } else {
        document.getElementById("aviso-viagem").style.display = "block";
    }

    if (formCompras.length >0) {

    } else {
        document.getElementById("aviso-compra").style.display = "block";
    }
}
