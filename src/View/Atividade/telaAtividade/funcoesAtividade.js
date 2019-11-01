window.onload = function () {
    verificaMensagem();

    let requisitado = window.location.pathname.split("/").pop();

    requisicao("GET", "/api/atividades/"+requisitado, null, function (resposta, codigo) {
        if (resposta.status === "sucesso") {
            let requisitor = resposta.requisitor;
            let atividade = resposta.dados;

            setLinks(requisitor);

            document.getElementById("titulo").textContent = "Atividade realizada por "+atividade.usuario.login;

            if (requisitor.id === atividade.usuario.id) {
                exibeDetalhesProprietario(atividade);
                setAlteracaoAtividade(atividade.id);
                setExclusaoAtividade(atividade.id);
            } else {
                exibeDetalhesNaoProprietario(atividade);
            }
        } else {
            decideErros(resposta, codigo);
        }
    });
};

function setExclusaoAtividade(idAtividade) {
    document.getElementById("excluirAtividade").onclick = function (event) {
        requisicao("DELETE","/api/atividades/"+idAtividade,null,function (resposta,codigo) {
            if (resposta.status === "sucesso") {
                window.location.href = "/tarefa/"+resposta.requisitor.idTarefa+"?sucesso=Atividade-excluida-com-sucesso";
            } else {
                exibirMensagem(resposta.mensagem,true,event.target);
            }
        });
    }
}

function setAlteracaoAtividade(idAtividade) {
    document.getElementById("alterarAtividade").onclick = function (event) {
        event.preventDefault();
        let body = {
            tipo: document.querySelector("#tipo").value,
            tempoGasto: document.querySelector("#tempo").value,
            comentario: document.querySelector("#comentario").value,
            dataRealizacao: document.querySelector("#data").value,
        };
        requisicao("PUT","/api/atividades/"+idAtividade,body,function (resposta,codigo) {
            if (resposta.status === "sucesso") {
                addMensagem("sucesso=Atividade-alterada-com-sucesso");
            } else {
                exibirMensagem(resposta.mensagem,true,event.target);
            }
        });
    }
}

function exibeDetalhesProprietario(atividade) {
    document.getElementById("tipo").value = atividade.tipo;
    document.getElementById("comentario").value = atividade.comentario;
    document.getElementById("data").value = atividade.dataRealizacao;
    document.getElementById("tempo").value = atividade.tempoGasto;
    document.getElementById("totalGasto").textContent = "R$ "+atividade.totalGasto;
}

function exibeDetalhesNaoProprietario(atividade) {
    document.getElementById("excluirAtividade").style.display = "none";
    document.getElementById("alterarAtividade").style.display = "none";
    document.getElementById("data").style.display = "none";
    document.getElementById("tempo").style.display = "none";
    document.getElementById("comentario").style.display = "none";
    document.getElementById("tipo").style.display = "none";

    let spans = document.getElementsByClassName("alterar-span");
    for (let span of spans) {
        span.style.display = "block";
    }
    document.getElementById("span-tipo").textContent = atividade.tipo;
    document.getElementById("span-comentario").textContent = atividade.comentario;
    document.getElementById("span-data").textContent = atividade.dataRealizacao;
    document.getElementById("span-tempo").textContent = atividade.tempoGasto;
    document.getElementById("totalGasto").textContent = "R$ "+atividade.totalGasto;



}
