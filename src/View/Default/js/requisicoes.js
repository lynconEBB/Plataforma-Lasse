function getToken () {
    var match = document.cookie.match(new RegExp('(^| )token=([^;]+)'));
    if (match) {
        return match[2];
    } else {
        return "";
    }
}

function requisicao(metodo,url,body,response, autorizacao = true) {
    let xhr = new XMLHttpRequest();
    xhr.open(metodo, url, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    if (autorizacao) {
        xhr.setRequestHeader("Authorization","Bearer "+ getToken());
    }

    xhr.send(JSON.stringify(body));

    xhr.onload = function() {
        response(JSON.parse(xhr.response));
    };

    xhr.onerror = function () {
        exibirMensagem("Servidor não respondendo",true);
    };

    return xhr.response;
}
