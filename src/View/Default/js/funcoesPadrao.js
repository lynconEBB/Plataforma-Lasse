function setLinks(idUsuario) {
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
}

function verificaMensagem() {
    let url = window.location.href;

    if (url.indexOf("?") !== -1) {
        let variavel = url.substring(url.indexOf("?")+1,url.length);
        let partes = variavel.split("=");
        if (partes.length == 2 && partes[0] === "sucesso") {
            let mensagem = partes[1].replace(/-/g," ");
            exibirMensagemInicio(mensagem,false);
        } else if (partes.length == 2 && partes[0] === "erro") {
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

function decideErros(resposta) {
    let erro = resposta.dados;
    if (erro.codigo === 405) {
        window.location.href = "/?erro=Logue-no-sistema-para-ter-acesso!";
    }
    else if (erro.codigo === 401) {
        window.location.href = "/erro/permissao";
    }
    else if (erro.codigo === 400 || erro.codigo === 404) {
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

        response(JSON.parse(xhr.response));
    };

    xhr.onerror = function () {
        exibirMensagem("Servidor não respondendo",true);
    };

    return xhr.response;
}



