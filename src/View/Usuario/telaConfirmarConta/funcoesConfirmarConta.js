window.onload = function () {
    let url = window.location.href;
    document.getElementById("voltarLogin").onclick = () => window.location.href = "/";
    if (url.indexOf("?") !== -1) {
        let token = url.substring(url.indexOf("?") + 1, url.length);
        requisicao("PUT", "/api/users/confirmar/"+token, null, function (resposta, codigo) {
        if (resposta.status === "sucesso") {

        } else {
            exibeErro();
        }
    });
    } else {
        exibeErro();
    }
};
function exibeErro() {
    document.querySelector(".container-header").classList.add("alerta");
    document.getElementById("titulo-aviso").textContent = "Link de confirmação inválido!";
    document.getElementById("desc-aviso").textContent = "Clique no botão abaixo para ir até a tela de login e efetue um novo cadastro.";
    document.getElementById("voltarLogin").className = "botao alerta center";
}
