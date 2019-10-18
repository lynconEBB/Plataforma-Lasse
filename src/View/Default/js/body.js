let sidebar = document.querySelector(".sidebar");
document.getElementById("botao-menu").onclick = () =>{
    if (sidebar.className === "sidebar ativa") {
        sidebar.classList.remove("ativa");
    } else {
        sidebar.classList.add("ativa");
    }
};

let menuUser = document.querySelector(".user-menu");
let linkPerfil = document.getElementById("perfil");
let linkSair = document.getElementById("sair");

document.querySelector(".user-info").addEventListener("click",function () {
    if (menuUser.className === "user-menu") {
        menuUser.classList.add("ativo");
        linkPerfil.focus();
        this.setAttribute("aria-expanded",true);
        linkPerfil.tabIndex = 0;
        linkSair.tabIndex = 0;
    } else {
        menuUser.classList.remove("ativo");
        this.setAttribute("aria-expanded",false);
        linkPerfil.tabIndex = -1;
        linkSair.tabIndex = -1;
    }
});

document.getElementById("sair").onclick = function () {
    requisicao("DELETE","/api/users/deslogar",null,function (resposta,codigo) {
        if (resposta.status === "sucesso") {
            window.location.href = "/";
        } else {
            decideErros();
        }
    });
};







