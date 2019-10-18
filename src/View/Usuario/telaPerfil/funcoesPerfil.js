/**Coloca Informações no lugar****/
window.onload = function () {
    verificaMensagem();

    let idUserListado = window.location.pathname.split("/").pop();

    requisicao("GET","/api/users/"+idUserListado,null,function (resposta) {
        if (resposta.status === "sucesso") {
            let requisitor = resposta.requisitor;
            let usuario = resposta.dados;

            setLinks(requisitor);

            if (requisitor.id !== usuario.id) {
                document.querySelector(".btn-alterar").style.display = "none";
                document.querySelector("#ativaModal").style.display = "none";
            }

            document.querySelector("#container-img-perfil").style.backgroundImage = "url('/"+usuario.foto+"')";
            document.querySelector("#login").value = usuario.login;

            if (usuario.admin === "1") {
                document.querySelector("#tipo").textContent = "Administrador";
            } else {
                document.querySelector("#tipo").textContent = "Funcionário";
            }
            console.log(usuario.atuacao);

            document.querySelector("#atuacao").value = usuario.atuacao;
            document.querySelector("#nome").value = usuario.nomeCompleto;
            document.querySelector("#dtNasc").value = usuario.dtNasc;
            document.querySelector("#formacao").value = usuario.formacao;
            document.querySelector("#email").value = usuario.email;
            document.querySelector("#cpf").value = usuario.cpf;
            document.querySelector("#rg").value = usuario.rg;
            document.querySelector("#dtEmissao").value = usuario.dtEmissao;
            document.querySelector("#valorHora").value = usuario.valorHora;

        } else {
            decideErros(resposta);
        }
    });
};
/*** mudar foto*****/
var fotoMudou = false;
let inputFoto = document.querySelector("#foto");
var foto = document.querySelector("#container-img-perfil");
inputFoto.addEventListener("change",function () {
    let files = this.files;
    if (FileReader && files && files.length) {
        console.log(files[0].type);
        if (files[0].type === "image/png" || files[0].type === "image/jpg" || files[0].type === "image/jpeg") {
            var reader = new FileReader();
            reader.onload = function () {
                fotoMudou = true;
                foto.style.backgroundImage = "url('"+reader.result+"')";
            };
            reader.readAsDataURL(files[0]);
        } else {
            exibirMensagem("Formato de arquivo não suportado",true)
        }
    }
});
/*** atualizar*******/
let formAlterar = document.querySelector("#alteraUsuario");
formAlterar.onclick = function (event) {
    event.preventDefault();
    let body = {
        nomeCompleto: document.getElementById("nome").value,
        login: document.getElementById("login").value,
        dtNasc: document.getElementById("dtNasc").value,
        cpf: document.getElementById("cpf").value,
        rg: document.getElementById("rg").value,
        dtEmissao: document.getElementById("dtEmissao").value,
        email: document.getElementById("email").value,
        atuacao: document.getElementById("atuacao").value,
        formacao: document.getElementById("formacao").value,
        valorHora: document.getElementById("valorHora").value,
    };
    if (fotoMudou) {
        body.foto = foto.style.backgroundImage.slice(4, -1).replace(/"/g, "");
    }

    requisicao("PUT","/api/users",body,function (resposta,codigo) {
        if (resposta.status === "sucesso") {
            addMensagem("sucesso=Dados-Atualizados-com-sucesso");
        } else {
            exibirMensagem(resposta.mensagem,true,event.target);
        }
    })

};

/****modalExcluir*****/
document.querySelector("#ativaModal").onclick = function (event) {
    exibeModal("modalExcluir",event.target);
};

/*****Excluir******/
let btnExcluir = document.querySelector("#excluir");
btnExcluir.onclick = function (event) {
  if (document.getElementById("inputExcluir").value === "confirmar") {
      requisicao("DELETE","/api/users",null,function (resposta,codigo) {
          if (resposta.status === "sucesso") {
              window.location.href = "/?sucesso=Usuario-deletado-com-sucesso";
          } else {
              exibirMensagem(resposta.mensagem,true,event.target);
          }
      });
  } else {
      exibirMensagem("Digite confirmar na caixa de texto",true,event.target);
  }
};




