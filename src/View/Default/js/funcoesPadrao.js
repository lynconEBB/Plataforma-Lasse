let btnSair = document.querySelector(".container-sair");
btnSair.onclick = function () {
    requisicao("DELETE","/api/users/deslogar",null,true,function (resposta) {
        if (resposta.status === "erro") {
            decideErros(resposta)
        } else {
            eraseCookie('token');
            window.location.href = "/"
        }
    })
};

function setLinks(idUsuario) {
    let btnProjetos = document.querySelector("#projetos");
    btnProjetos.onclick = () => window.location.href = "/projetos/user/"+idUsuario;
    let btnGraficos = document.querySelector("#graficos");
    btnGraficos.onclick = () => window.location.href = "/graficos/user/"+idUsuario;
    let btnFormularios = document.querySelector("#formularios");
    btnFormularios.onclick = () => window.location.href = "/formularios/user/"+idUsuario;
    let btnPerfil = document.querySelector("#perfil");
    btnPerfil.onclick = () => window.location.href = "/perfil/user/"+idUsuario;
    let btnDashboard = document.querySelector("#dashboards");
    btnDashboard.onclick = () => window.location.href = "/dashboard/user/"+idUsuario;
    let btnImprevistos = document.querySelector("#imprevistos");
    btnImprevistos.onclick = () => window.location.href = "/imprevistos/user/"+idUsuario;

}

function verificaMensagem() {
    let url = window.location.href;
    console.log(url);
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
