let inputs = document.querySelectorAll(".form-input");

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

let selects = document.querySelectorAll(".form-select");

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





