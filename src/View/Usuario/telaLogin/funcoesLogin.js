/*****mostrar input quando checkbox estiver selecionada******/
var checkAdmin = document.querySelector("#admin");
checkAdmin.onclick = function () {
    if (checkAdmin.checked === true) {
        document.getElementById("form-admin").style.display = "block";
    } else {
        document.getElementById("form-admin").style.display = "none";
    }
};

/****Mostrar foto fornecida**/
var fotoMudou = false;
let inputFoto = document.querySelector("#foto");
var foto = document.querySelector(".foto");
inputFoto.addEventListener("change",function () {
    let files = this.files;
    if (FileReader && files && files.length) {
        if (files[0].type === "image/png" || files[0].type === "image.jpg") {
            var reader = new FileReader();
            reader.onload = function () {
                fotoMudou = true;
                foto.src = reader.result;
            };
            reader.readAsDataURL(files[0]);
        } else {
            exibirMensagem("Formato de arquivo não suportado",true)
        }
    }
});

/* reseta campos quando pagina carrega*/
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
let loginInputs = document.querySelectorAll("#form-login input");
loginInputs.forEach(function (input) {
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
        requisicao("POST","/api/users/login",request,false,function (resposta) {
            if (resposta.status === "sucesso") {
                setCookie('token',resposta.dados,1);
                window.location.href = "/dashboard/user/"+resposta.requisitor.id;
            } else {
                exibirMensagem(resposta.mensagem,true);
            }
        });
    } else {
        exibirMensagem("Os campos de Usuario e Senha precisam possuir mais de 6 caracteres",true);
    }
}


/*
Cadastro
 */
let botaoCadastrar = document.querySelector("#botao-cadastrar");
botaoCadastrar.onclick = function(event) {
    event.preventDefault();
    let senhaConfirm = document.getElementById("passConfirm").value;
    let request = {
        nomeCompleto: document.getElementById("nomeCompleto").value,
        login: document.getElementById("user").value,
        senha: document.getElementById("password").value,
        dtNasc: document.getElementById("dtNasc").value,
        cpf: document.getElementById("cpf").value,
        rg: document.getElementById("rg").value,
        dtEmissao: document.getElementById("dtEmissao").value,
        email: document.getElementById("email").value,
        atuacao: document.getElementById("atuacao").value,
        formacao: document.getElementById("formacao").value,
        valorHora: document.getElementById("valorHora").value,
    };
    if (checkAdmin.checked === true) {
      request.senhaAdmin = document.getElementById("senhaAdmin").value
    }
    if(fotoMudou) {
        request.foto = foto.src;
    }
    if (senhaConfirm === request.senha) {
        requisicao("POST","/api/users",request,false,function (resposta) {
            if (resposta.status === "erro") {
                exibirMensagem(resposta.mensagem,true);
            } else {
                fecharModal("#modalCadastro");
                exibirMensagem(resposta.mensagem,false);
            }
        });
    } else {
        exibirMensagem("As senhas devem coincidir",true);
    }
};


/*** modal***/
let botaoCadastro = document.querySelector("#botao-cadastro");
botaoCadastro.onclick = function () {
    mostrarModal("#modalCadastro")
};
let btnFechaModal = document.querySelector(".modal-header-close");
btnFechaModal.onclick = function () {
    fecharModal("#modalCadastro");
};







