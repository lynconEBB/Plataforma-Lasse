window.onload = function () {
    if(performance.navigation.type == 2){
        location.reload(true);
    }
    if (performance.navigation.type !== 1) {
        verificaMensagem();
    }

    var idUserRequisitado = window.location.pathname.split("/").pop();

    requisicao("GET", "/api/projetos/user/"+idUserRequisitado, null, true, function (resposta) {

        if (resposta.status === "sucesso") {
            var requisitor = resposta.requisitor;

            setLinks(requisitor.id);
            document.querySelector(".user-img").src = "/"+requisitor.foto;
            document.querySelector(".user-name").textContent = requisitor.login;

            if (resposta.hasOwnProperty("dados")) {
                let main = document.querySelector("#dash-projetos");
                resposta.dados.forEach(function (projeto) {
                    let template = ` 
                    <div class="container-projeto" id="projeto${projeto.id}">
                        <span class="projeto-title">${projeto.nome}</span>
                        <hr class="projeto-divisoria">
                        <span class="projeto-criacao">Data de Criação: ${projeto.dataInicio}</span>
                        <span class="projeto-finalizacao">Data de Finalização: ${projeto.dataFinalizacao}</span>
                        <span class="projeto-participantes">Participantes: ${projeto.participantes.length}</span>
                    </div>
                    `;

                    main.insertAdjacentHTML("beforeend",template);
                    if (requisitor.infoAdd[projeto.id] === true) {
                        container =document.getElementById("projeto"+projeto.id);
                        divisoria = container.querySelector(".projeto-divisoria");
                        divisoria.insertAdjacentHTML("afterend","<i class='material-icons'>star</i>")
                    }
                    document.getElementById("projeto"+projeto.id).onclick = () => window.location.href = "/projeto/"+projeto.id;
                })
            } else {
                document.getElementById("container-aviso").style.display = "flex";
            }
        } else {
            decideErros(resposta);
        }
    });
};

document.getElementById("cadastrarProjeto").onclick = function(event) {
    event.preventDefault();
    let body = {
        nome :document.getElementById("nome").value,
        descricao :document.getElementById("descricao").value,
        dataInicio :document.getElementById("dataCriacao").value,
        dataFinalizacao :document.getElementById("dataFinalizacao").value,
        centroCusto :document.getElementById("numCentroCusto").value
    };
    requisicao("POST","/api/projetos",body,true,function (response) {
        console.log(response);
        if (response.status == "sucesso") {
            fecharModal("#modalCadastro");
            addMensagem("sucesso=Projeto-cadastrado-com-sucesso!");
        } else {
            exibirMensagem(response.mensagem,true);
        }
    });
};

let btnAbreModal = document.getElementById("abreModal");
btnAbreModal.onclick = function () {
    exibeModal("#modalCadastro");
};

let btnFechaModal = document.querySelector(".modal-header-close");
btnFechaModal.onclick = function () {
    fecharModal("#modalCadastro");
};

