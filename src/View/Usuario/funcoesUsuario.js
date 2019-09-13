window.onload= function() {
    var elements = document.getElementsByTagName("input");
    for (var ii=0; ii < elements.length; ii++) {
        if (elements[ii].type === "text") {
            elements[ii].value = "";
        }
    }
};

/*
Deixa campos vermelhos ou verdes
 */
inputs.forEach(function (input) {
    input.addEventListener("blur",function () {
        if (this.value.length >= 6) {
            this.parentElement.className = "form-group certo";
        } else if (this.value.length >0 && this.value.length < 6) {
            this.parentElement.className = "form-group errado";
        }
    })
});

/*
Login
 */

let botaoEnviar =  document.querySelector("#form-login");
botaoEnviar.addEventListener("submit",logar);

async function logar(event) {
    event.preventDefault();
    let request = {
        senha: document.getElementById("senha").value,
        login: document.getElementById("usuario").value
    };

    if (request.senha.length >= 6 && request.login.length >= 6) {
        requisicao("POST","http://localhost/api/users/login",request,callbackLogar,false);
    } else {
        exibirMensagem("Os campos de Usuario e Senha precisam possuir mais de 6 caracteres",true);
    }
}

function callbackLogar(resposta) {
    if (resposta.status === "sucesso") {
        window.location.href = "http://localhost/user/dashboard";
    } else {
        exibirMensagem(resposta.mensagem,true);
    }
}

let botaoCadastrar = document.querySelector("#botao-cadastro");
botaoCadastrar.onclick = function () {
    mostrarModal("#modalCadastro")
};
let btnFechaModal = document.querySelector(".modal-header-close");
btnFechaModal.onclick = function () {
    fecharModal("#modalCadastro");
};


