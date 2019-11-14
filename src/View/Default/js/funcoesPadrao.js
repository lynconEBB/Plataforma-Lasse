function setLinks(requisitor) {
    let idUsuario = requisitor.id;
    let btnProjetos = document.querySelector("#projetos");
    btnProjetos.href = "/projetos/user/"+idUsuario;
    let btnGraficos = document.querySelector("#graficos");
    btnGraficos.href = "/graficos/user/"+idUsuario;
    let btnFormularios = document.querySelector("#formularios");
    btnFormularios.href = "/formularios/user/"+idUsuario;
    let btnPerfil = document.querySelector("#perfil");
    btnPerfil.href = "/perfil/user/"+idUsuario;
    let btnDashboard = document.querySelector("#dashboards");
    btnDashboard.href = "/dashboard/user/"+idUsuario;
    let btnImprevistos = document.querySelector("#imprevistos");
    btnImprevistos.href = "/imprevistos/user/"+idUsuario;

    document.querySelector(".container-user-img").style.backgroundImage = "url('/"+requisitor.foto+"')";
    document.querySelector(".user-name").textContent = requisitor.login;
}

function verificaMensagem() {
    if (window.performance) {
        if (window.performance.navigation.type === 0) {
            criaMensagemURL();
        }
    } else {
        criaMensagemURL();
    }
}

function criaMensagemURL() {
    let url = window.location.href;

    if (url.indexOf("?") !== -1) {
        let variavel = url.substring(url.indexOf("?")+1,url.length);
        let partes = variavel.split("=");
        if (partes.length === 2 && partes[0] === "sucesso") {
            let mensagem = partes[1].replace(/-/g," ");
            exibirMensagemInicio(mensagem,false);
        } else if (partes.length === 2 && partes[0] === "erro") {
            let mensagem = partes[1].replace(/-/g," ");
            exibirMensagemInicio(mensagem,true,null);
        }
    }
}

function addMensagem(mensagem) {
    let url = location.href;
    if (url.indexOf("?") !== -1) {
        url = url.substring(0,url.indexOf("?"));
    }
    url = url+"?"+mensagem;
    window.location.href = url;
}

function decideErros(resposta,codigo) {
    let erro = resposta.dados;

    if (codigo === 405) {
        window.location.href = "/?erro=Logue-no-sistema-para-ter-acesso!";
    }
    else if (codigo === 401) {
        setLinks(resposta.requistor);
        document.getElementsByClassName("main-content")[0].innerHTML = criaTemplateSemPermissao(erro);
    }
    else if (codigo === 404) {
        setLinks(resposta.requistor);
        document.getElementsByClassName("main-content")[0].innerHTML = criaTemplateNaoEncontrado(erro);
    }
    else if (codigo === 500) {
        if (resposta.hasOwnProperty("requisitor")) {
            setLinks(resposta.requistor);
        }
        document.getElementsByClassName("main-content")[0].innerHTML = criaTemplateErroInterno(erro);
    }
}

function requisicao(metodo,url,body,response) {
    let xhr = new XMLHttpRequest();
    xhr.open(metodo, url, true);
    xhr.setRequestHeader("Content-Type", "application/json");

    if (body !== null) {
        xhr.send(JSON.stringify(body));
    } else {
        xhr.send();
    }

    xhr.onload = function() {
        response(JSON.parse(xhr.response),xhr.status);
    };

    xhr.onerror = function () {
        exibirMensagem("Servidor não respondendo",true);
    };
}

function criaTemplateNaoEncontrado(erro) {
    let templateErroNaoEncontrado;
    return templateErroNaoEncontrado = `
        <div class="container-erro">
            <figure class="container-img-erro">
                <img alt="Icone indicando um erro de que algo não foi encontrado. Ilustração" src="/assets/images/mascote/RaioSozinho.png">
            </figure>

            <h1>Ops! Não encontramos o que você estava procurando</h1>
            <h2>${erro.mensagem}</h2>
        </div>
    `;
}

function criaTemplateSemPermissao(erro) {
    return templateErroPermisso = `
        <div class="container-erro">
            <figure class="container-img-erro-permissao">
                <img alt="Icone indicando um erro de que o usuário não possui acesso à está página. Ilustração" src="/assets/images/mascote/RaioBarrado.png">
            </figure>

            <h1>Parece que você está entrando em lugares que não deveria!</h1>
            <h2>${erro.mensagem}</h2>
        </div>
`;
}

function criaTemplateErroInterno(erro) {
    return templateErroInterno = `
        <div class="container-erro">
            <figure class="container-img-erro">
                <img alt="Icone indicando um erro de que o usuário não possui acesso à está página. Ilustração" src="/assets/images/mascote/RaioDuvida.png">
            </figure>

            <h1>Opa! Parece que houve um erro inesperado em nossos servidores</h1>
            <h2>Tente novamente mais tarde</h2>
        </div>
`;
}
window.onbeforeunload = function () {
    if (window.performance) {
        if (window.performance.navigation.type !== 1 ) {
            let cookie = document.cookie;
            if (cookie !== "") {
                deslogar(cookie);
            }
        }
    }
};

function deslogar() {
    let xhr = new XMLHttpRequest();
    xhr.open("DELETE","/api/users/deslogar");
    xhr.send();
}

