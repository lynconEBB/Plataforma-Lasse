window.onload = function () {
    var idUsuario = window.location.pathname.split("/").pop();
    requisicao("GET","/api/users/"+idUsuario,null,true,function (resposta) {
        if (resposta.status === "sucesso") {
            let foto = document.querySelector(".user-img");
            document.querySelector(".user-name").textContent = resposta.dados.login;
            foto.src = resposta.requisitor.foto;
            var usuario = resposta.dados;
            setLinks(usuario.id);
        } else {
            window.location.href = "/erro/permissao";
        }
    });
};



