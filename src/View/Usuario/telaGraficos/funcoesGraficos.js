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
                gerarGraficosUsuario(projetos,requisitor);
            }
        } else {
            decideErros(resposta, codigo);
        }
    });
};

function gerarGraficosAdmin(projetos) {

}
function gerarGraficosUsuario(projetos,requisitor) {
    gerarGraficoTempoGastoTodosProjetos(projetos,requisitor);
    document.getElementById("gerarGraficoTempoPorProjeto").onclick = function() {
        gerarGraficoTempoGastoPorProjeto(projetos,requisitor);
    };
    gerarGraficoTempoGastoPorProjeto(projetos,requisitor);
}

function gerarGraficoTempoGastoTodosProjetos(projetos,requisitor) {
    let graficoElement = document.getElementById("graficoTempoGastoTotal").getContext('2d');
    let data = gerarDataTempoGastoTodosProjetos(projetos,requisitor);
    if (data !== false) {
        let graficoTempoGasto = new Chart(graficoElement, {
            type:'pie',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio:false,
                legend: {
                    position: 'bottom',
                    labels: {
                        fontColor: 'white',
                        fontSize:15,
                        padding:20
                    }
                }
            }
        });
    } else {
        document.getElementById("graficoTempoGastoTotal").style.display = "none";
        document.getElementById("aviso1").style.display = "block";
    }

}
function gerarDataTempoGastoTodosProjetos(projetos,requisitor) {
    let labels = [];
    let qtdTempo = [];

    for (let projeto of projetos) {
        labels.push(projeto.nome);
        let tempoGasto = 0;
        if (projeto.tarefas != null) {
            for (let tarefa of projeto.tarefas) {
                if (tarefa.atividades != null) {
                    for (let atividade of tarefa.atividades) {
                        if (atividade.usuario.id === requisitor.id) {
                            tempoGasto += parseFloat(atividade.tempoGasto);
                        }
                    }
                }
            }
        }
        qtdTempo.push(tempoGasto);
    }
    if (qtdTempo.reduce((a,b) => a+ b, 0) === 0) {
        return false;
    } else {
        return {
            labels: labels,
            datasets: [{
                labels: "Horas gastas",
                data: qtdTempo,
                backgroundColor: getCores(labels.length)
            }]
        };
    }

}

function gerarGraficoTempoGastoPorProjeto(projetos,requisitor) {
    let graficoElement = document.getElementById("graficoTempoGastoPorProjeto").getContext('2d');
    //let data = gerarDadosHorasPorPeriodo(projetos,requisitor);
    let graficoTempoGasto = new Chart(graficoElement, {
        type:'line',
        data: {
            labels:["21/03/2006","12/03/2007","21/03/2006","12/03/2007","21/03/2006"],
            datasets: [{
                label: "Projeto 1",
                data: [12,56,65,34,32],
                backgroundColor: 'red',
                fill:false
            },{
                label: "Projeto 2",
                data: [12,12,42,54,32],
                backgroundColor: 'blue',
                borderColor:'blue',
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio:false,
            legend: {
                position: 'bottom',
                labels: {
                    fontColor: 'white',
                    fontSize:15,
                    padding:20
                }
            }
        }
    });

}

function gerarDadosHorasPorPeriodo(projetos,requisitor) {
    let datasets = [];
    for (let projeto of projetos) {
        let dataset = {
            label:projeto.nome
        }



    }
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


