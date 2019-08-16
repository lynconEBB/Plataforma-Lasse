async function post(url,body) {
    var xhr = new XMLHttpRequest();
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.open("POST", url, true);
    xhr.send(body);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            return  JSON.parse(xhr.responseText);
        }
    };
}