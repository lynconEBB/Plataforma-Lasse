window.onload = function() {
    verificaMensagem();

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
    let fotoMudou = false;
    let inputFoto = document.querySelector("#foto");
    let foto = document.querySelector(".container-foto");
    inputFoto.addEventListener("change",function (event) {
        let files = this.files;
        if (FileReader && files && files.length) {
            if (files[0].type === "image/png" || files[0].type === "image/jpg" || files[0].type === "image/jpeg") {
                let reader = new FileReader();
                reader.onload = function () {
                    fotoMudou = true;
                    foto.style.backgroundImage = "url('"+reader.result+"')";
                };
                reader.readAsDataURL(files[0]);
            } else {
                exibirMensagem("Formato de arquivo não suportado",true,event.target)
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


    /* Deixa campos vermelhos ou verdes */
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

    /* Login */

    let botaoEnviar =  document.querySelector("#botao-enviar");
    botaoEnviar.addEventListener("click",requisitalogin);


    function requisitalogin(event) {
        event.preventDefault();
        let request = {
            senha: document.getElementById("senha").value,
            login: document.getElementById("usuario").value
        };

        if (request.senha.length >= 6 && request.login.length >= 6) {
            requisicao("POST","/api/users/login",request,function (resposta,codigo) {
                if (resposta.status === "sucesso") {
                    window.location.href = "/dashboard/user/"+resposta.requisitor.id;
                } else {
                    exibirMensagem(resposta.mensagem,true,event.target);
                }
            });
        } else {
            exibirMensagem("Os campos de Usuario e Senha precisam possuir mais de 6 caracteres",true,event.target);
        }
    }

    /* Recupera Senha */
    let botaoSenha = document.getElementById("envia-email");
    botaoSenha.addEventListener("click",function (event) {
        event.preventDefault();
        let email = document.getElementById("emailRecSenha").value;
        if (validaEmail(email)) {
            let body = {
                "email": email
            };
            document.querySelector("#modalRecSenha .modal-conteudo").insertAdjacentHTML("beforeend","<div class='lds-ring' aria-label='Carregando... Aguarde' role='alert' tabindex='-1'><div></div><div></div><div></div><div></div></div>");
            document.getElementsByClassName("lds-ring")[0].focus();
            document.getElementById("emailRecSenha").inert = true;
            this.inert = true;
            document.getElementsByClassName("modal-close")[1].inert = true;

            requisicao("POST", "/api/users/geraRecuperacao",body,function (resposta,codigo) {
                document.getElementById("emailRecSenha").inert = false;
                document.getElementById("envia-email").inert = false;
                document.getElementsByClassName("lds-ring")[0].remove();
                document.getElementsByClassName("modal-close")[1].inert = false;

                if (resposta.status === "sucesso") {
                    exibirMensagem(resposta.mensagem,false,document.querySelector("#botao-senha"));
                } else {
                    exibirMensagem(resposta.mensagem,true,event.target);
                }
            });
        } else {
            exibirMensagem("E-mail inválido",true,event.target);
        }
    });

    function validaEmail(email) {
        let re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    /* Cadastro */
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
            valorHora: document.getElementById("valorHora").value
        };
        if (checkAdmin.checked === true) {
            request.senhaAdmin = document.getElementById("senhaAdmin").value
        }
        if(fotoMudou) {
            request.foto = foto.style.backgroundImage.slice(4, -1).replace(/"/g, "");
        }
        if (senhaConfirm === request.senha) {
            requisicao("POST","/api/users",request,function (resposta,codigo) {
                if (resposta.status === "erro") {
                    exibirMensagem(resposta.mensagem,true,event.target);
                } else {
                    fecharModal("#modalCadastro");
                    exibirMensagem(resposta.mensagem,false,event.target);
                }
            });
        } else {
            exibirMensagem("As senhas devem coincidir",true,event.target);
        }
    };


    /*** modal***/
    document.querySelector("#botao-cadastro").onclick = function (event) {
        event.preventDefault();
        exibeModal("modalCadastro",this)
    };

    document.querySelector("#botao-senha").onclick = function (event) {
        event.preventDefault();
        exibeModal("modalRecSenha",this)
    };

};








