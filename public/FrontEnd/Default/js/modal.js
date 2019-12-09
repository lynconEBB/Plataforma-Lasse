var modalBack = document.querySelector(".modal-background");
let modais = document.getElementsByClassName("modal");
let foraModal = document.querySelector(".fora-modal");

let modaisTarget = [];

for (let modal of modais) {
    modal.inert = true;
}

function preparaMeiosParaFechar (modal,target) {
    modaisTarget.push({
        modal: modal,
        target: target
        }
    );

    modal.querySelector(".modal-close").addEventListener("click",function () {
        fechaModal(modal,target);
    });

    document.addEventListener("keydown",function (event) {
        if (event.key === "Escape") {
            for (let modalTarget of modaisTarget) {
                if (modalTarget.modal.className === "modal ativo") {
                    fechaModal(modalTarget.modal,modalTarget.target);
                }
            }
        }
    });

    modalBack.addEventListener("click",function () {
        for (let modalTarget of modaisTarget) {
            if (modalTarget.modal.className === "modal ativo") {
                fechaModal(modalTarget.modal,modalTarget.target);
            }
        }
    });
}

function fechaModal(modal,target) {
    modal.inert = true;
    foraModal.inert = false;
    target.focus();
    modal.classList.remove("ativo");
    modalBack.classList.remove("ativo");
}

function exibeModal(idModal,target) {
    let modal = document.getElementById(idModal);
    if (modaisTarget.length === 0) {
        preparaMeiosParaFechar(modal,target);
    } else {
        let count = 0;
        for (let modalTarget of modaisTarget) {
            if (modalTarget.modal === modal) {
                count++;
            }
        }
        if (count === 0) {
            preparaMeiosParaFechar(modal,target);
        }
    }

    foraModal.inert = true;
    modal.inert = false;
    modal.classList.add("ativo");
    modalBack.classList.add("ativo");
    modal.focus();
}

