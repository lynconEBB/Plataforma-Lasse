window.onload = function () {
    verificaMensagem();

    let idUserRequisitado = window.location.pathname.split("/").pop();

    requisicao("GET", "/api/users/"+idUserRequisitado, null, function (resposta, codigo) {
        if (resposta.status === "sucesso") {
            let requisitor = resposta.requisitor;

            let btnPerfil = document.querySelector("#perfil");
            btnPerfil.href = "/perfil/user/"+idUserRequisitado;
            document.querySelector(".container-user-img").style.backgroundImage = "url('/"+requisitor.foto+"')";
            document.querySelector(".user-name").textContent = requisitor.login;
            document.getElementById("voltar").setAttribute("href","/dashboard/user/"+idUserRequisitado);

            let botoesSidebar = document.getElementsByClassName("pageLink");
            for (let botao of botoesSidebar) {
                botao.addEventListener("click",function (event) {
                    let idPage = this.dataset.page;
                    document.querySelector(".exibindo").classList.remove("exibindo");
                    document.getElementById(idPage).classList.add("exibindo");
                });
            }

        } else {
            window.location.href = "/dashboard/user/"+idUserRequisitado;
        }
    });
};