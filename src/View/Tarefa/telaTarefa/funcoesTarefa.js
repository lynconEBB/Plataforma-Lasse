window.onload = function () {
    verificaMensagem();

    var idTarefaRequisitada = window.location.pathname.split("/").pop();

    requisicao("GET", "/api/tarefas/"+idTarefaRequisitada, null, true, function (resposta) {
        console.log(resposta);
        if (resposta.status === "sucesso") {

            var requisitor = resposta.requisitor;
            let tarefa = resposta.dados;
            setLinks(requisitor.id);
            document.querySelector(".user-img").src = "/" + requisitor.foto;
            document.querySelector(".user-name").textContent = requisitor.login;
            console.log(tarefa);
            /******Coloca informações da tarefa**********/
            document.getElementById("nome").value = tarefa.nome;
            document.getElementById("descricao").value = tarefa.descricao;
            document.getElementById("dtInicio").value = tarefa.dataInicio;
            document.getElementById("dtConclusao").value = tarefa.dataConclusao;
            document.getElementById("estado").value = tarefa.estado;
            document.getElementById("totalGasto").value = tarefa.totalGasto;


        } else {

        }
    });
};
