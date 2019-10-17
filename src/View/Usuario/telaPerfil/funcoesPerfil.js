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
var foto = document.querySelector("#img-perfil");
inputFoto.addEventListener("change",function () {
    let files = this.files;
    if (FileReader && files && files.length) {
        console.log(files[0].type);
        if (files[0].type === "image/png" || files[0].type === "image/jpg" || files[0].type === "image/jpeg") {
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
/*** atualizar*******/
let formAlterar = document.querySelector(".perfil-info");
formAlterar.onsubmit = function (event) {
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
        body.foto = foto.src;
    }
    requisicao("PUT","/api/users",body,true,function (resposta) {
        if (resposta.status === "sucesso") {
            addMensagem("sucesso=Dados-Atualizados-com-sucesso");
        } else {
            console.log(resposta);
            exibirMensagem(resposta.mensagem,true);
        }
    })

};

/****modalExcluir*****/
let modalAbrir = document.querySelector("#ativaModal");
modalAbrir.onclick = function () {
    exibeModal("#modalExcluir");
};
let modalFechar = document.querySelector(".modal-header-close");
modalFechar.onclick = function () {
    fecharModal("#modalExcluir");
};

/*****Excluir******/
let btnExcluir = document.querySelector("#excluir");
btnExcluir.onclick = function () {
  if (document.getElementById("inputExcluir").value === "confirmar") {
      requisicao("DELETE","/api/users",null,true,function (resposta) {
          if (resposta.status == "sucesso") {
              window.location.href = "/";
          } else {
              exibirMensagem(resposta.mensagem,true);
          }
      })
  } else {
      exibirMensagem("Digite confirmar na caixa de texto",true);
  }
};




