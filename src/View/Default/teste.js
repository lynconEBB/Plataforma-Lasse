window.onload = function () {
    document.querySelector(".user-name").insertAdjacentText("beforeend","lyncon.ebb");


    document.querySelector("#btn").onclick = (event) => { exibeModal("mdal",event.target)}

};
