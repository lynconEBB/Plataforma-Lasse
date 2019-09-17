let btnSair = document.querySelector(".container-sair");
btnSair.onclick = function () {
    requisicao("DELETE","/api/users/deslogar",null,true,function (resposta) {
        if (resposta.status === "erro") {
            window.location.href = "/erro/permissao";
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
