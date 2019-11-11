window.onload = function () {
    verificaMensagem();

    let requisitado = window.location.pathname.split("/").pop();

    requisicao("GET", "/api/projetos/user/"+requisitado, null, function (resposta, codigo) {
        if (resposta.status === "sucesso") {
            let requisitor = resposta.requisitor;
            let projetos = resposta.dados;

            setLinks(requisitor);

            if (requisitor.admin === "1" || requisitor.id === requisitado) {
                let cores = gerarGraficoTempoGastoTodosProjetos(projetos, requisitor);

                if (cores !== false) {
                    populaSelect();
                    gerarGraficoTempoGastoPorProjeto(projetos,requisitado,cores);
                    document.getElementById("gerarGraficoTempoPorProjeto").onclick = function () {
                        atualizarGraficotempoPorProjeto();
                    };
                } else {
                    document.getElementById("graficoTempoGastoPorProjeto").style.display = "none";
                    document.getElementById("aviso2").style.display = "block";
                }
            }
        } else {
            decideErros(resposta, codigo);
        }
    });
};

function gerarGraficoTempoGastoTodosProjetos(projetos,requisitor) {
    let graficoElement = document.getElementById("graficoTempoGastoTotal").getContext('2d');
    let data = gerarDataTempoGastoTodosProjetos(projetos,requisitor);
    if (data !== false) {
        new Chart(graficoElement, {
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
        return data.datasets[0].backgroundColor;
    } else {
        document.getElementById("graficoTempoGastoTotal").style.display = "none";
        document.getElementById("aviso1").style.display = "block";
        return false;
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

function gerarGraficoTempoGastoPorProjeto(projetos,idUsuario,cores) {
    let graficoElement = document.getElementById("graficoTempoGastoPorProjeto").getContext('2d');

    let ultimoMes = new Date();
    ultimoMes.setDate(1);
    let body = {
        mes: ultimoMes.getMonth(),
        ano: ultimoMes.getFullYear()
    };

    requisicao("POST","/api/users/tempoGastoDiario/"+idUsuario,body,function (respsota,codigo) {
        if (respsota.status === "sucesso") {
            if (codigo === 200) {
                new Chart(graficoElement, {
                    type:'line',
                    data: formataDadosResposta(respsota.dados,cores),
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
                        },
                        tooltips:{
                            callbacks:{
                                label: function(tooltipItem, data) {
                                    return data.datasets[tooltipItem.datasetIndex].label +': ' + tooltipItem.yLabel + 'h';
                                }
                            }
                        },
                        scales: {
                            xAxes:[{
                                ticks:{
                                    fontColor:"white",
                                    fontSize:14
                                },
                                gridLines: {
                                    color:"rgba(255,255,255,0.4)"
                                }
                            }],
                            yAxes:[{
                                ticks:{
                                    callback: function(value, index, values) {
                                        return value + 'h';
                                    },
                                    beginAtZero:true,
                                    fontColor:"white",
                                    fontSize:16
                                },
                                gridLines:{
                                    color:"rgba(255,255,255,0.4)"
                                }
                            }]
                        }
                    }
                });
            } else {
                document.getElementById("graficoTempoGastoPorProjeto").style.display = "none";
                document.getElementById("aviso2").style.display = "block";
            }
        } else {
            exibirMensagem("Erro ao tentar gerar o gráfico",true,document.getElementById("gerarGraficoTempoPorProjeto"));
        }
    });

}
function formataDadosResposta(dados,cores) {
    let labels = [];
    let datasets = [];
    let count = 0;
    for (let projeto in dados) {
        labels = [];
        if (Object.prototype.hasOwnProperty.call(dados,projeto)) {
            let data = [];
            for (let dado in dados[projeto]) {
                labels.push(dado);
                data.push(dados[projeto][dado]);
            }
            let cor =  cores[count];
           datasets.push({
               label: projeto,
               data:data,
               backgroundColor:cor,
               borderColor:cor,
               lineTension:0,
               fill:false,
           })
        }
        count++;
    }

    return {
        labels: labels,
        datasets: datasets
    }

}
function atualizarGraficotempoPorProjeto() {

}

function populaSelect() {
    let hoje = new Date();
    let select = document.getElementById("mesAno");
    let meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    for (let ano = 2005;ano <= hoje.getFullYear(); ano++) {
        let count = 1;
        for (let mes of meses) {
            select.insertAdjacentHTML("afterbegin",`<option value="${mes}-${ano}">${mes} de ${ano}</option>`)
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
