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
        window.location.href = "/erro/permissao";
    }
    else if (codigo === 404) {
        setLinks(resposta.requistor);
        template = criaTemplateNaoEncontrado(erro);
        //document.getElementsByClassName("main-content")[0].innerHTML = template;
        //window.location.href = "/erro/naoEncontrado";
    }
    else if (codigo ===  500) {
        setLinks(resposta.requistor);
        window.location.href = "/erro/naoEncontrado";
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
        console.log(xhr.response);
        response(JSON.parse(xhr.response),xhr.status);
    };

    xhr.onerror = function () {
        exibirMensagem("Servidor não respondendo",true);
    };

    return xhr.response;
}

function criaTemplateNaoEncontrado(erro) {
    let templateErroNaoEncontrado = `
   
`;
    return templateErroNaoEncontrado;
}



