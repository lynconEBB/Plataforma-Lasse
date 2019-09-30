var botaoMensagem = document.querySelector(".botao-fechar-mensagem");
var mensagem = document.querySelector(".mensagem");
var textoMensagem = document.querySelector(".mensagem-texto");

function exibirMensagem(texto,erro) {
    textoMensagem.textContent = texto;
    if (erro === true) {
        mensagem.className = "mensagem erro ativa";
    } else {
        mensagem.className = "mensagem sucesso ativa";
    }
}

botaoMensagem.onclick = () => mensagem.classList.remove("ativa");


