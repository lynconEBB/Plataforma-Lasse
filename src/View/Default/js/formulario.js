let inputs = document.querySelectorAll(".form-input");
let selects = document.querySelectorAll(".form-select");

for( let input of inputs) {
    if (input.value !== "") {
        input.parentElement.classList.add("selecionado");
        input.previousElementSibling.classList.add("selecionado");
    }
}
for( let select of selects) {
    if (select.value !== "") {
        select.parentElement.classList.add("selecionado");
        select.previousElementSibling.classList.add("selecionado");
    }
}

inputs.forEach(function (input) {
    input.addEventListener("focus",function () {
        this.parentElement.classList.add("selecionado");
        this.previousElementSibling.classList.add("selecionado");

    });
});

inputs.forEach(function (input) {
   input.addEventListener("blur",function () {
       if (this.value === "") {
           this.parentElement.className = " form-group";
           this.previousElementSibling.classList.remove("selecionado");
       }
   })
});

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





