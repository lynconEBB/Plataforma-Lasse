window.onload = function () {
    verificaMensagem();

    let requisitado = window.location.pathname.split("/").pop();

    requisicao("GET", "/api/projetos/user/"+requisitado, null, function (resposta, codigo) {
        if (resposta.status === "sucesso") {
            let requisitor = resposta.requisitor;
            let projetos = resposta.dados;

            setLinks(requisitor);
            requisicao("GET","/api/users/"+requisitado,null,function (resposta,codigo) {
                if (resposta.status === "sucesso") {
                    document.getElementById("titulo").textContent = "Gráficos de "+resposta.dados.login;
                } else {
                    decideErros(resposta,codigo);
                }
            });


            if (requisitor.admin === "1" || requisitor.id === requisitado) {
                let cores = gerarGraficoTempoGastoTodosProjetos(projetos, requisitado);
                
                if (cores !== false) {
                    populaSelect();
                    gerarGraficoTempoGastoPorProjeto(requisitado,cores);
                    document.getElementById("gerarGraficoTempoPorProjeto").onclick = function () {
                        atualizarGraficotempoPorProjeto(requisitado,cores);
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

function gerarGraficoTempoGastoTodosProjetos(projetos,idUsuario) {
    let graficoElement = document.getElementById("graficoTempoGastoTotal").getContext('2d');
    let data = gerarDataTempoGastoTodosProjetos(projetos,idUsuario);
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
function gerarDataTempoGastoTodosProjetos(projetos,idUsuario) {
    let labels = [];
    let qtdTempo = [];

    for (let projeto of projetos) {
        labels.push(projeto.nome);
        let tempoGasto = 0;
        if (projeto.tarefas != null) {
            for (let tarefa of projeto.tarefas) {
                if (tarefa.atividades != null) {
                    for (let atividade of tarefa.atividades) {
                        if (atividade.usuario.id === idUsuario) {
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

function gerarGraficoTempoGastoPorProjeto(idUsuario,cores) {
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
                let grafico = new Chart(graficoElement, {
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
        count++;
    }

    if (cores.length !== count) {
        cores = getCores(count);
    }
    count = 0;
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

function atualizarGraficotempoPorProjeto(idUsuario,cores) {
    document.getElementById("graficoTempoGastoPorProjeto").remove();
    let canvas = document.createElement("canvas");
    canvas.setAttribute("id","graficoTempoGastoPorProjeto");
    document.getElementById("container-canvas").appendChild(canvas);
    let graficoElement = document.getElementById("graficoTempoGastoPorProjeto").getContext('2d');
    let mesAno = document.getElementById("mesAno").value;
    let partes = mesAno.split("-");
    let body = {
        mes:partes[0],
        ano:partes[1]
    };
    requisicao("POST","/api/users/tempoGastoDiario/"+idUsuario,body,function (resposta,codigo) {
        if (resposta.status === "sucesso") {
            if (codigo === 200) {
                document.getElementById("graficoTempoGastoPorProjeto").style.display = "block";
                document.getElementById("aviso2").style.display = "none";
                let grafico = new Chart(graficoElement, {
                    type:'line',
                    data: formataDadosResposta(resposta.dados,cores),
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

function populaSelect() {
    let hoje = new Date();
    let select = document.getElementById("mesAno");
    let meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    for (let ano = 2005;ano <= hoje.getFullYear(); ano++) {
        if (ano === hoje.getFullYear()) {
            let ultimoMes = new Date();
            ultimoMes.setDate(1);
            for (let i = 1; i <= ultimoMes.getMonth(); i++) {
                select.insertAdjacentHTML("afterbegin",`<option value="${i}-${ano}">${meses[i-1]} de ${ano}</option>`)
            }
            select.value = ultimoMes.getMonth()+"-"+ultimoMes.getFullYear();
        } else {
            let count = 1;
            for (let mes of meses) {
                select.insertAdjacentHTML("afterbegin",`<option value="${count}-${ano}">${mes} de ${ano}</option>`)
                count++;
            }
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
