/**Coloca Informações no lugar****/
window.onload = function () {
    verificaMensagem();

    let idUserListado = window.location.pathname.split("/").pop();

    requisicao("GET","/api/users/"+idUserListado,null,function (resposta,codigo) {
        if (resposta.status === "sucesso") {
            let requisitor = resposta.requisitor;
            let usuario = resposta.dados;

            setLinks(requisitor);

            if (requisitor.id !== usuario.id) {
                document.querySelector("#alteraUsuario").style.display = "none";
                document.querySelector("#ativaModal").style.display = "none";
                setTemplateAdmin(usuario);
            } else {
                if (usuario.admin === "1") {
                    document.querySelector("#tipo").textContent = "Administrador";
                } else {
                    document.querySelector("#tipo").textContent = "Funcionário";
                }
                document.querySelector("#container-img-perfil").style.backgroundImage = "url('/"+usuario.foto+"')";
                document.querySelector("#login").value = usuario.login;
                document.querySelector("#atuacao").value = usuario.atuacao;
                document.querySelector("#nome").value = usuario.nomeCompleto;
                document.querySelector("#dtNasc").value = usuario.dtNasc;
                document.querySelector("#formacao").value = usuario.formacao;
                document.querySelector("#email").value = usuario.email;
                document.querySelector("#cpf").value = usuario.cpf;
                document.querySelector("#rg").value = usuario.rg;
                document.querySelector("#dtEmissao").value = usuario.dtEmissao;
                document.querySelector("#valorHora").value = usuario.valorHora;
            }
        } else {
            decideErros(resposta,codigo);
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

function setTemplateAdmin(usuario) {
    let template = `
        <div id="container-img-perfil"></div>
        <div class="perfil-info">
            <div class="container-apre">
                <label class="escondeVisualmente">Nome de Usuário (login):</label>
                <h1 class="perfil-titulo">${usuario.login}</h1>
                <label class="escondeVisualmente">Tipo: </label>
                <span id="tipo"></span>
                <label class="escondeVisualmente" for="atuacao">Atuação:</label>
                <span class="perfil-item">${usuario.atuacao}</span>
            </div>
            <hr>
            <div class="form-perfil">
                <div class="part-form">
                    <div class="form-group">
                        <label class="alterar-label">Nome Completo: </label>
                        <span class="perfil-item">${usuario.nomeCompleto}</span>
                    </div>
                    <div class="form-group">
                        <label for="dtNasc" class="alterar-label">Data de Nascimento: </label>
                        <span class="perfil-item">${usuario.dtNasc}</span>
                    </div>
                    <div class="form-group">
                        <label for="formacao" class="alterar-label">Formação: </label>
                        <span class="perfil-item">${usuario.formacao}</span>
                    </div>
                    <div class="form-group">
                        <label for="valorHora" class="alterar-label">Valor Hora: </label>
                        <span class="perfil-item">${usuario.valorHora}</span>
                    </div>
                </div>
                <div class="part-form">
                    <div class="form-group">
                        <label for="email" class="alterar-label">E-mail: </label>
                        <span class="perfil-item">${usuario.email}</span>
                    </div>
                    <div class="form-group">
                        <label for="cpf" class="alterar-label">CPF: </label>
                        <span class="perfil-item">${usuario.cpf}</span>
                    </div>
                    <div class="form-group">
                        <label for="rg" class="alterar-label">RG: </label>
                        <span class="perfil-item">${usuario.rg}</span>
                    </div>
                    <div class="form-group">
                        <label for="dtEmissao" class="alterar-label">Data de Emissão:</label>
                        <span class="perfil-item">${usuario.dtEmissao}</span>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.querySelector(".main-content").innerHTML = template;
    document.querySelector("#container-img-perfil").style.backgroundImage = "url('/"+usuario.foto+"')";
    if (usuario.admin === "1") {
        document.querySelector("#tipo").textContent = "Administrador";
    } else {
        document.querySelector("#tipo").textContent = "Funcionário";
    }

}




