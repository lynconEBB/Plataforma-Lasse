let sidebar = document.querySelector(".sidebar");
document.getElementById("botao-menu").onclick = () =>{

    if (sidebar.className === "sidebar ativa") {
        sidebar.classList.remove("ativa");
    } else {
        sidebar.classList.add("ativa");
    }
};

let menuUser = document.querySelector(".user-menu");
document.querySelector(".user-info").onclick = () => {

};


