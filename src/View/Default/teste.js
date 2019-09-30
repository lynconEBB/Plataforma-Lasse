window.onload = function () {
    document.getElementById("btn1").onclick = () => exibirMensagem("fjkhsdkjfdggffffffffffffffffdgfd gdfgdfgdfffffffffffffff fdgdfgdfg",false);
    verificaMensagem();
    requisicao("", "/api/", null, true, function (resposta) {
        if (resposta.status === "sucesso") {
            var requisitor = resposta.requisitor;
            setLinks(requisitor.id);
            document.querySelector(".user-img").src = "/" + requisitor.foto;
            document.querySelector(".user-name").textContent = requisitor.login;
        } else {

        }
    });
};
