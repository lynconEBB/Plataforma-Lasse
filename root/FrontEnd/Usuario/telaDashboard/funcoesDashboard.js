window.onload = function () {
    verificaMensagem();
    requisitaDadosUsuario();

};

function requisitaDadosUsuario() {
    let idUserRequisitado = window.location.pathname.split("/").pop();

    // /api/users/{idUsuario}
    requisicao("GET","/api/users/"+idUserRequisitado,null,function (resposta,codigo) {

        if (resposta.status === "sucesso") {
            let usuario = resposta.dados;
            let requisitor = resposta.requisitor;

            setLinks(requisitor);

            document.getElementsByClassName('titulo')[0].textContent = "Dashboard de "+usuario.login;
            /****Mostra quantidade projetos******/
            requisicao("GET","/api/projetos/user/"+usuario.id,null,function (resposta,codigo) {
                if (resposta.status === "sucesso") {
                    if (!resposta.hasOwnProperty("dados")) {
                        document.querySelector("#qtdProjetos").textContent = resposta.mensagem;
                    } else {
                        let projetos = resposta.dados;
                        let cores = gerarGraficoTempoGastoTodosProjetos(projetos,idUserRequisitado);
                        if (cores !== false) {
                            gerarGraficoTempoGastoPorProjeto(idUserRequisitado,cores);
                        } else {
                            document.getElementById("grafico2").style.display = "none";
                            document.getElementById("aviso2").style.display = "block";
                        }
                        let donosProjetos = resposta.requisitor.donosReais;
                        let qtdDono = 0;
                        for( let idProjeto in donosProjetos) {
                            if(idUserRequisitado === donosProjetos[idProjeto]) {
                                qtdDono += 1;
                            }
                        }
                        document.querySelector("#qtdProjetos").innerHTML = "Participando de: "+projetos.length+" projeto(s)<br>" + "Sendo dono de: "+qtdDono+" projeto(s)";
                    }

                } else {
                    exibirMensagemInicio(resposta.mensagem,true);
                }
            });

            /****Mostra quantidade de Projetos****/
            requisicao("GET", "/api/formularios/users/"+usuario.id,null,function (resposta,codigo) {
                if (resposta.status === "sucesso") {
                    if (!resposta.hasOwnProperty("dados")) {
                        document.querySelector("#qtdFormularios").textContent = resposta.mensagem;
                    } else {
                        let formularios = resposta.dados;
                        document.querySelector("#qtdFormularios").textContent = "Foram encontrado(s) "+formularios.length+" formulários no sistema";
                    }
                } else {
                    exibirMensagemInicio(resposta.mensagem,true);
                }
            });

            /*****Mostra quantidade de imprevistos******/
            requisicao("GET", "/api/atividades/user/"+usuario.id,null,function (resposta,codigo) {
                if (resposta.status === "sucesso") {
                    if (!resposta.hasOwnProperty("dados")) {
                        document.querySelector("#qtdImprevistos").textContent = resposta.mensagem;
                    } else {
                        let imprevistos = resposta.dados;
                        document.querySelector("#qtdImprevistos").textContent = "Foram encontrado(s) "+imprevistos.length+" imprevistos no sistema";
                    }
                } else {
                    exibirMensagemInicio(resposta.mensagem,true);
                }
            });

            let telaFormularios = document.querySelector("#telaFormularios");
            telaFormularios.setAttribute("href", "/formularios/user/"+usuario.id);
            let telaProjetos = document.querySelector("#telaProjetos");
            telaProjetos.setAttribute("href","/projetos/user/"+usuario.id);
            let telaImprevistos = document.querySelector("#telaImprevistos");
            telaImprevistos.setAttribute("href","/imprevistos/user/"+usuario.id);

        } else {
            decideErros(resposta,codigo);
        }
    });
}
function gerarGraficoTempoGastoPorProjeto(idUsuario,cores) {
    let graficoElement = document.getElementById("grafico2").getContext('2d');

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
                document.getElementById("grafico2").style.display = "none";
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
function gerarGraficoTempoGastoTodosProjetos(projetos,idUsuario) {
    let graficoElement = document.getElementById("grafico1").getContext('2d');
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
        document.getElementById("grafico1").style.display = "none";
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





