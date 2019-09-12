function exibirMensagem(texto,erro) {
    let mensagem = document.querySelector(".mensagem");
    let textoMensagem = document.querySelector(".mensagem-texto");

    textoMensagem.textContent = texto;
    if (erro === true) {
        mensagem.className = "mensagem mensagem-erro mensagem-ativa";
    } else {
        mensagem.className = "mensagem mensagem-sucesso mensagem-ativa";
    }
}

let botaoMensagem = document.querySelector(".botao-fechar-mensagem");
let mensagem = document.querySelector(".mensagem");

botaoMensagem.addEventListener("click",function () {
    mensagem.classList.remove("mensagem-ativa");
});


