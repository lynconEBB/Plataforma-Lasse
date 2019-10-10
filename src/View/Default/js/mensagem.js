var botaoMensagem = document.querySelector(".botao-fechar-mensagem");
var mensagem = document.querySelector(".mensagem");
var textoMensagem = document.querySelector("#mensagem-texto");

function exibirMensagem(texto,erro,target) {

    textoMensagem.textContent = texto;

    mensagem.focus();
    target.focus();

    if (erro === true) {
        mensagem.className = "mensagem erro ativa";
    } else {
        mensagem.className = "mensagem sucesso ativa";
    }

    botaoMensagem.onclick = () => {
        mensagem.classList.remove("ativa");
    };
}


function exibirMensagemInicio(texto,erro) {

    textoMensagem.textContent = texto;

    mensagem.focus();

    if (erro === true) {
        mensagem.className = "mensagem erro ativa";
    } else {
        mensagem.className = "mensagem sucesso ativa";
    }

    botaoMensagem.onclick = () => {
        mensagem.classList.remove("ativa");
    };
}
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





