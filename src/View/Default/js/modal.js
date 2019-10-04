var modalBack = document.querySelector(".modal-background");
let modais = document.getElementsByClassName(".modal");

for (let modal in modais) {

}


function mostrarModal(idModal) {
    let modal = document.querySelector(idModal);
    modal.classList.add("ativo");
    modalBack.classList.add("ativo");
}

function fecharModal(idModal) {
    let modal = document.querySelector(idModal);
    modal.classList.remove("ativo");
    modalBack.classList.remove("ativo");
}

