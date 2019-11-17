window.onload = function () {
    verificaMensagem();

    requisicao("GET", "/api/projetos", null, function (resposta, codigo) {
        if (resposta.status === "sucesso") {
            let requisitor = resposta.requisitor;
            setLinks(requisitor);

            if (codigo === 200) {
                let projetos = resposta.dados;
                let cores = setGraficoTempoTotal(projetos);
                setGraficoGastoTotal(projetos,cores);

                populaSelectProjetos(projetos);
                document.getElementById("gerarGraficoTipoAtividades").onclick = function () {
                    setGraficoTipoAtividade(projetos,document.getElementById("projetoTipo").value);
                };
                setGraficoTipoAtividade(projetos,projetos[projetos.length-1].id);

                populaSelectDatas();
                let data = new Date();
                data.setDate(1);
                setGraficoAtividadesUsuario(data.getMonth()+"-"+data.getFullYear(),projetos[projetos.length-1].id,false);
                document.getElementById("gerarGraficoAtividadesUsuario").onclick = function () {
                    let mesAno = document.getElementById("mesAno").value;
                    let idProjeto = document.getElementById("projeto").value;
                    setGraficoAtividadesUsuario(mesAno,idProjeto);
                };
            } else {
                document.getElementById("aviso1").style.display = "flex";
                document.getElementById("aviso2").style.display = "flex";
                document.getElementById("aviso3").style.display = "flex";
                document.getElementById("aviso4").style.display = "flex";
            }
        } else {
            decideErros(resposta, codigo);
        }
    });
};

function setGraficoAtividadesUsuario(mesAno,idProjeto) {
    let partes = mesAno.split("-");
    let body = {
        mes: partes[0],
        ano: partes[1],
        idProjeto: idProjeto
    };

    requisicao("PUT","/api/projetos/gerarGrafico",body,function (resposta,codigo) {
        if (resposta.status === "sucesso") {
            if (codigo === 200) {
                document.getElementById("grafico4").style.display = "block";
                document.getElementById("aviso4").style.display = "none";
                let labels = resposta.dados[0];
                let datasets = resposta.dados[1];

                let novasCores = getCores(datasets.length);
                let count = 0;
                for (let dataset of datasets) {
                    dataset["backgroundColor"] = novasCores[count];
                    dataset["borderColor"] = novasCores[count];
                    dataset["lineTension"] = 0;
                    dataset["fill"] =  false;
                    count++;
                }
                montaGraficoAtividadesUsuario(labels,datasets);

            } else {
                exibirMensagem("Dados insuficientes para gerar gráfico",true,document.getElementById("gerarGraficoAtividadesUsuario"));
                document.getElementById("grafico4").style.display = "none";
                document.getElementById("aviso4").style.display = "block";
            }
        } else {
            exibirMensagem(resposta.mensagem,true,document.getElementById("gerarGraficoAtividadesUsuario"));
        }
    })
}

function montaGraficoAtividadesUsuario(labels,datasets) {
    document.getElementById("grafico4").remove();
    let canvas = document.createElement("canvas");
    canvas.setAttribute("id","grafico4");
    document.getElementById("container-canvas4").appendChild(canvas);

    let graficoCanvas = document.getElementById("grafico4").getContext('2d');
    new Chart(graficoCanvas, {
        type:'line',
        data: {
            labels: labels,
            datasets: datasets
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
}

function setGraficoTipoAtividade(projetos,idProjeto) {
    let labels = ["Compras","Viagens","Atividades"];
    let valorGasto = [0,0,0];

    for (let projeto of projetos) {
        if (projeto.id === idProjeto) {

            if (projeto.tarefas != null) {
                for (let tarefa of projeto.tarefas) {
                    if (tarefa.atividades != null) {
                        for (let atividade of tarefa.atividades) {
                            valorGasto[2] += parseFloat(atividade.totalGasto);
                        }
                    }
                    if (tarefa.compras != null) {
                        for (let compra of tarefa.compras) {
                            valorGasto[0] += parseFloat(compra.totalGasto);
                        }
                    }
                    if (tarefa.viagens != null) {
                        for (let viagem of tarefa.viagens) {
                            valorGasto[1] += parseFloat(viagem.totalGasto);
                        }
                    }
                }
            }
        }
    }
    let data = {
        labels: labels,
        datasets: [{
            data: valorGasto,
            backgroundColor: ["#57b498","#49aee3","#b45323"]
        }]
    };

    document.getElementById("grafico3").remove();
    let canvas = document.createElement("canvas");
    canvas.setAttribute("id","grafico3");
    document.getElementById("container-canvas3").appendChild(canvas);

    let graficoCanvas = document.getElementById("grafico3").getContext('2d');
    new Chart(graficoCanvas, {
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
            },
            tooltips: {
                callbacks: {
                    title: function (tooltipItem, data) {
                        return data['labels'][tooltipItem[0]['index']];
                    },
                    label: function (tooltipItem, data) {
                        return "Valor gasto: R$ " + data['datasets'][0]['data'][tooltipItem['index']];
                    },
                },
            }
        }
    });
}

function setGraficoGastoTotal(projetos,cores) {
    let labels = [];
    let valorGasto = [];

    for (let projeto of projetos) {
        labels.push(projeto.nome);
        valorGasto.push(projeto.totalGasto);
    }
    let data =  {
        labels: labels,
        datasets: [{
            labels: "Horas gastas",
            data: valorGasto,
            backgroundColor: cores
        }]
    };

    let graficoCanvas = document.getElementById("grafico2").getContext('2d');
    new Chart(graficoCanvas, {
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
            },
            tooltips: {
                callbacks: {
                    title: function (tooltipItem, data) {
                        return data['labels'][tooltipItem[0]['index']];
                    },
                    label: function (tooltipItem, data) {
                        return "Valor Gasto: R$ " + data['datasets'][0]['data'][tooltipItem['index']];
                    },
                },
            }
        }
    });
}

function setGraficoTempoTotal(projetos) {
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
    let data =  {
        labels: labels,
        datasets: [{
            labels: "Horas gastas",
            data: qtdTempo,
            backgroundColor: getCores(labels.length)
        }]
    };

    let graficoCanvas = document.getElementById("grafico1").getContext('2d');
    new Chart(graficoCanvas, {
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
            },
            tooltips: {
                callbacks: {
                    title: function (tooltipItem, data) {
                        return data['labels'][tooltipItem[0]['index']];
                    },
                    label: function (tooltipItem, data) {
                        return "Horas Gastas: " + data['datasets'][0]['data'][tooltipItem['index']] + "h";
                    },
                },
            }
        }
    });
    return data.datasets[0].backgroundColor;
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

function populaSelectProjetos(projetos) {
    let select1 = document.getElementById("projetoTipo");
    let select2 = document.getElementById("projeto");

    for (let projeto of projetos) {
        select1.insertAdjacentHTML("beforeend",`
            <option value="${projeto.id}">${projeto.nome}</option>
        `);
        select2.insertAdjacentHTML("beforeend",`
            <option value="${projeto.id}">${projeto.nome}</option>
        `);
    }
    document.getElementById("projetoTipo").value = projetos[projetos.length-1].id;
    document.getElementById("projeto").value = projetos[projetos.length-1].id;
}

function populaSelectDatas() {
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