window.onload = function () {
    var idUsuario = window.location.pathname.split("/").pop();
    requisicao("GET","/api/users/"+idUsuario,null,true,function (resposta) {
        if (resposta.status === "sucesso") {
            document.querySelector(".user-name").textContent = resposta.dados.login;
            document.querySelector(".user-img").src = resposta.requisitor.foto;
            document.querySelector("#img-perfil").src = resposta.requisitor.foto;
            var usuario = resposta.dados;

            document.querySelector("#login").textContent = usuario.login;
            if (usuario.admin === "1") {
                document.querySelector("#tipo").textContent = "Administrador";
            } else {
                document.querySelector("#tipo").textContent = "Funcionário";
            }
            document.querySelector("#atuacao").textContent = usuario.atuacao;

            setLinks(usuario.id);
        } else {
            window.location.href = "/erro/permissao";
        }
    });
};