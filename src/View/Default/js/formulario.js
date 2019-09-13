let inputs = document.querySelectorAll(".form-input");

inputs.forEach(function (input) {
    input.addEventListener("focus",function () {
        this.parentElement.classList.add("clicado");
        console.log(this.previousSibling);
    });
});

inputs.forEach(function (input) {
   input.addEventListener("blur",function () {
       this.parentElement.classList.remove("clicado");
   })
});


