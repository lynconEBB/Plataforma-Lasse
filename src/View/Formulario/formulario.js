var base = document.querySelector( '#base64' );
var inp = document.querySelector( 'input[type=file]' );
let xhr = new XMLHttpRequest();
xhr.open('PUT',"http://localhost/api/users");
xhr.setRequestHeader("Authorization","eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJMYXNzZS1Qcm9qZWN0LU1hbmFnZXIiLCJhdWQiOiJpbnNvbW5pYVwvNi42LjIiLCJpYXQiOjE1NjcxMDMzMjksIm5iZiI6MTU2NzEwMzMyOSwiZXhwIjoxNTY3MTg5NzI5LCJkYXRhIjp7ImlkIjoiMTQiLCJsb2dpbiI6ImlzbWFlbCIsImZvdG8iOiJcL2hvbWVcL2xhc3NlXC9MYXNzZS1Qcm9qZWN0LU1hbmFnZXJcL2Fzc2V0c1wvZmlsZXNcL2RlZmF1bHRcL3BlcmZpbC5wbmciLCJhZG1pbiI6IjAifX0.yHdexacVq74-4njoiY_YS9KebmjW9nYSMxoPqDvofBs");

xhr.onload = function() {
    base.appendChild(  document.createTextNode( xhr.response ) );
};

inp.addEventListener( 'change', function() {
    let file = inp.files[0];
    let reader  = new FileReader();

    reader.addEventListener('load', function () {

            let json = {
                foto: reader.result
            };
           // xhr.send(JSON.stringify(json));
            console.log(reader.result);
        });
    if ( file ) {
        reader.readAsDataURL( file );
    }
});


