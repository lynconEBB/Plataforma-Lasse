

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
            exibirMensagem(mensagem,false);
        } else if (partes.length == 2 && partes[0] === "erro") {
            let mensagem = partes[1].replace(/-/g," ");
            exibirMensagem(mensagem,true);
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
    if (erro.codigo == 405) {
        window.location.href = "/?erro=Logue-no-sistema-para-ter-acesso!";
    }
    else if (erro.codigo == 401) {
        window.location.href = "/erro/permissao";
    }
    else if (erro.codigo == 400 || erro.codigo == 404) {
        window.location.href = "/erro/naoEncontrado";
    }
}
function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)===' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function eraseCookie(name) {
    document.cookie = name+'=; Max-Age=-99999999;';
}

function requisicao(metodo,url,body,autorizacao,response) {
    let xhr = new XMLHttpRequest();
    xhr.open(metodo, url, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    if (autorizacao) {
        xhr.setRequestHeader("Authorization","Bearer "+ getCookie('token'));
    }
    if (body !== null) {
        xhr.send(JSON.stringify(body));
    } else {
        xhr.send();
    }

    xhr.onload = function() {
        console.log(xhr.response);
        response(JSON.parse(xhr.response));
    };

    xhr.onerror = function () {
        exibirMensagem("Servidor não respondendo",true);
    };

    return xhr.response;
}



