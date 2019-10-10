window.onload = function () {
    let inputs = document.getElementsByClassName("form-input");
    for (let input of inputs) {
        input.onpaste = function (e) {
            e.preventDefault();
        };
        input.oncopy = function (e) {
            e.preventDefault();
        };
    }

    document.getElementById("alterarSenha").addEventListener("click",function (event) {
        event.preventDefault();

        let url = window.location.href;

        if (url.indexOf("?") !== -1) {
            let token = url.substring(url.indexOf("?") + 1, url.length);
            let senha = document.getElementById("senha").value;
            let senhaConfirm = document.getElementById("senhaConfirm").value;

            if (senha === senhaConfirm) {
                let body = {
                    novaSenha: senha,
                    token: token
                };

                requisicao("PUT", "/api/users/alterarSenha", body, function (resposta) {
                    if (resposta.status === "sucesso") {
                        window.location.href = "/?sucesso=Senha-alterada-com-sucesso";
                    } else {
                        exibirMensagem(resposta.mensagem,true,event.target);
                    }
                });
            } else {
                exibirMensagem("A confirmação da senha deve ser igual a senha",true,event.target);
            }
        } else {
            exibirMensagem("Link para recuperação de senha inválido",true,event.target);
        }
    });

};
