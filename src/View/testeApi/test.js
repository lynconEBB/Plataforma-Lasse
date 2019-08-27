function request(metodo,url,body) {
    let xhr = new XMLHttpRequest();

    xhr.open(metodo, url,true);

    xhr.send(JSON.stringify(body));

    xhr.onload = function() {
        let responseObj = xhr.response;
        let body = document.querySelector("body");
        body.innerHTML = responseObj;
    };
}

let bodyCadastro = {
    login: "lyncon123",
    senha: "123456",
    nomeCompleto: "Lyncon Baez",
    dtNasc: "24/03/2001",
    cpf: "121.128.809-93",
    rg: "12.611.282-3",
    dtEmissao: "23/06/2005",
    formacao: "desenvolvedor",
    atuacao: "bolsista",
    email: "lynconlyn@gmail.com",
    valorHora: "23.45"
}

request('POST','http://localhost:8000/api/users',body)
