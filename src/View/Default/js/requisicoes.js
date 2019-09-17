function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)===' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function eraseCookie(name) {
    document.cookie = name+'=; Max-Age=-99999999;';
}

function requisicao(metodo,url,body,autorizacao,response) {
    let xhr = new XMLHttpRequest();
    xhr.open(metodo, url, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    if (autorizacao) {
        xhr.setRequestHeader("Authorization","Bearer "+ getCookie('token'));
    }
    if (body !== null) {
        xhr.send(JSON.stringify(body));
    } else {
        xhr.send();
    }

    xhr.onload = function() {
        response(JSON.parse(xhr.response));
    };

    xhr.onerror = function () {
        exibirMensagem("Servidor não respondendo",true);
    };

    return xhr.response;
}


