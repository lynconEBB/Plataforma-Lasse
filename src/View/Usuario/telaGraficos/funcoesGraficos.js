window.onload = function () {
    verificaMensagem();

    let requisitado = window.location.pathname.split("/").pop();

    requisicao("GET", "/api/projetos/user/"+requisitado, null, function (resposta, codigo) {
        if (resposta.status === "sucesso") {
            let requisitor = resposta.requisitor;
            let projetos = resposta.dados;
            setLinks(requisitor);

            if (requisitor.admin === "1" && requisitor.id === requisitado)  {
                gerarGraficosAdmin(projetos);
            } else {
                gerarGraficosUsuario(projetos);
            }
        } else {
            decideErros(resposta, codigo);
        }
    });
};

function gerarGraficosAdmin(projetos) {

}
function gerarGraficosUsuario(projetos) {
    gerarGraficoTempoGastoTodosProjetos(projetos);
}

function gerarGraficoTempoGastoTodosProjetos(projetos) {
    let graficoElement = document.getElementById("graficoTempoGastoTotal").getContext('2d');

    let data = gerarDataTempoGastoTodosProjetos(projetos);

    let graficoTempoGasto = new Chart(graficoElement, {
        type:'pie',
        data: data,
        options: {
            title: {
                display: true,
                text: "Horas gastas por projeto",
                fontSize:20
            }
        }
    });
}

function gerarGraficoTempoGastoPorProjeto(projeto) {

}

function gerarDataTempoGastoTodosProjetos(projetos) {
    let labels = [];
    let qtdTempo = [];

    for (let projeto of projetos) {
        labels.push(projeto.nome);

        let tempoGasto = 0;
        if (projeto.tarefas != null) {
            for (let tarefa of projeto.tarefas) {
                if (tarefa.atividades != null) {
                    for (let atividade of tarefa.atividades) {
                        tempoGasto += parseFloat(atividade.tempoGasto);
                    }
                }
            }
        }
        qtdTempo.push(tempoGasto);
    }
    return {
        labels: labels,
        datasets: [{
            label: "Horas gastas",
            data: qtdTempo,
            backgroundColor: getCores(labels.length)
        }]
    };
}

function getCores(tamanho) {
    let letters = '0123456789ABCDEF';
    let colors = [];
    for (let i = 0; i < tamanho; i++) {
        let color = '';
        do {
            color = "#";
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
        } while (colors.includes(color));
        colors.push(color);
    }
    return colors;
}


