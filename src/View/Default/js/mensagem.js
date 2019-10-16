let botaoMensagem = document.querySelector(".botao-fechar-mensagem");
let mensagem = document.querySelector(".mensagem");
let textoMensagem = document.querySelector("#mensagem-texto");

function exibirMensagem(texto,erro,target) {
    mensagem.focus();
    target.focus();

    if (mensagem.classList.contains("ativa")) {
        mensagem.classList.remove("ativa");
        setTimeout(function () {
            textoMensagem.textContent = texto;
            if (erro === true) {
                mensagem.className = "mensagem erro ativa";
            } else {
                mensagem.className = "mensagem sucesso ativa";
            }
        },300);
    } else {
        textoMensagem.textContent = texto;
        if (erro === true) {
            mensagem.className = "mensagem erro ativa";
        } else {
            mensagem.className = "mensagem sucesso ativa";
        }
    }

    setTimeout(function () {
        mensagem.classList.remove("ativa");
    },10000);
}

function exibirMensagemInicio(texto,erro) {

    textoMensagem.textContent = texto;
    mensagem.focus();

    if (erro === true) {
        mensagem.className = "mensagem erro ativa";
    } else {
        mensagem.className = "mensagem sucesso ativa";
    }
}

botaoMensagem.onclick = () => {
    mensagem.classList.remove("ativa");
};
/*
<div class="mensagem" role="alert" tabindex="-1">
    <span id="mensagem-texto">
    </span>
    <button class="botao-fechar-mensagem" aria-hidden="true" tabindex="-1">
        <i class="material-icons botao-fechar-mensagem">
            close
        </i>
    </button>
</div>
 */





