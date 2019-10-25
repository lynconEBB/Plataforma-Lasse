
let selects = document.querySelectorAll(".form-select");


for( let select of selects) {
    if (select.value !== "") {
        select.parentElement.classList.add("selecionado");
        select.previousElementSibling.classList.add("selecionado");
    }
}
function setInputs () {
    let inputs = document.querySelectorAll(".form-input");

    inputs.forEach(function (input) {
        input.addEventListener("focus",function () {
            this.parentElement.classList.add("selecionado");
            this.previousElementSibling.classList.add("selecionado");

        });

        input.addEventListener("blur",function () {
            if (this.value === "") {
                this.parentElement.className = " form-group";
                this.previousElementSibling.classList.remove("selecionado");
            }
        });

        if (input.value !== "") {
            input.parentElement.classList.add("selecionado");
            input.previousElementSibling.classList.add("selecionado");
        }
    });
}

setInputs();


selects.forEach(function (select) {
    select.addEventListener("focus",function () {
        this.parentElement.classList.add("selecionado");
        this.previousElementSibling.classList.add("selecionado");
    })
});

selects.forEach(function (select) {
    select.addEventListener("blur",function () {
        if (this.value === "") {
            this.parentElement.className = " form-group";
            this.previousElementSibling.classList.remove("selecionado");
        }
    })
});






