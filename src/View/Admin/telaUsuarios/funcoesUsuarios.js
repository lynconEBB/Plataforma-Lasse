window.onload = function () {
    verificaMensagem();

    requisicao("GET", "/api/users", null, function (resposta, codigo) {
        if (resposta.status === "sucesso") {
            let requisitor = resposta.requisitor;
            let usuarios = resposta.dados;
            setLinks(requisitor);

            let usuariosAtivados = document.querySelector(".usuarios-ativos");
            for (let usuario of usuarios) {
                usuariosAtivados.insertAdjacentHTML("afterbegin",`
                    <div class="usuario">
                        <div class="container-img" id="img${usuario.id}"></div>
                        <span class="nomeUsuario">${usuario.login}</span>
                        <span class="atuacao">${usuario.formacao}</span>
                        <div class="botoes">
                            <a href="/dashboard/user/${usuario.id}"><button class="botao info" title="Dashboard do Usuário"><i class="material-icons">dashboard</i></button></a>
                            <a href="/graficos/user/${usuario.id}"><button class="botao info" title="Gráficos do Usuário"><i class="material-icons">assessment</i></button></a>
                            <a href="/imprevistos/user/${usuario.id}"><button class="botao info" title="Imprevistos do Usuário"><i class="material-icons">book</i></button></a>
                        </div>
                    </div>
                `);
                document.getElementById("img"+usuario.id).style.backgroundImage =  "url('/"+usuario.foto+"')";
            }

            exibeUsuariosDesativados();
        } else {
            decideErros(resposta, codigo);
        }
    });
};

function exibeUsuariosDesativados() {
    requisicao("GET","/api/users/desativados",null,function (resposta,codigo) {
        if (resposta.status === "sucesso") {
            if (codigo === 200) {
                let usuarios = resposta.dados;
                let usuariosDesativados = document.querySelector(".usuarios-inativos");

                for (let usuario of usuarios) {
                    usuariosDesativados.insertAdjacentHTML("afterbegin",`
                        <div class="usuario">
                            <div class="container-img" id="img${usuario.id}"></div>
                            <span class="nomeUsuario">${usuario.login}</span>
                            <span class="atuacao">${usuario.formacao}</span>
                            <div class="botoes">
                                <a href="/dashboard/user/${usuario.id}"><button class="botao info" title="Dashboard do Usuário"><i class="material-icons">dashboard</i></button></a>
                                <a href="/graficos/user/${usuario.id}"><button class="botao info" title="Gráficos do Usuário"><i class="material-icons">assessment</i></button></a>
                                <a href="/imprevistos/user/${usuario.id}"><button class="botao info" title="Imprevistos do Usuário"><i class="material-icons">book</i></button></a>
                                <button class="botao alerta" id="reativar${usuario.id}" title="Reativar Usuário"><i class="material-icons">undo</i></button>
                            </div>
                        </div>
                    `);
                    document.getElementById("img"+usuario.id).style.backgroundImage =  "url('/"+usuario.foto+"')";
                    document.getElementById("reativar"+usuario.id).onclick = function (event) {
                        requisicao("PUT","/api/users/reativar/"+usuario.id,null,function (resposta,codigo) {
                            if (resposta.status === "sucesso") {
                                addMensagem("sucesso=Usuario-reativado-com-sucesso!");
                             } else {
                                exibirMensagem(resposta.mensagem,true,event.target);
                            }
                        })
                    };
                }
            } else {
                document.getElementById("container-aviso2").style.display = "flex";
            }
        } else {
            decideErros(resposta,codigo)
        }
    });
}