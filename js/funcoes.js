$(document.body).click(function(evt){
    var clicked = evt.target;
    var currentID = clicked.id || "No ID!";
    console.log(currentID);
})