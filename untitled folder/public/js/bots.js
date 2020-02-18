function getInputValue(){
    // Selecting the input element and get its value 
    var inputVal = document.getElementById("textArea1").value;
    dragDropWindow2.textContent = inputVal;
}

// function createCard() {
//     var canvas = document.getElementByClassName('jtk-demo-canvas');
//     $(canvas).append();
// }


$(document).ready(() => {
    $('#saveBtn').on('click', () => {
        dragDropWindow2.display = "block";
        $("#dragDropWindow2").clone().appendTo(".jtk-demo-canvas");
    });
});


// const showDiv = () => {
//     var textAreaVal = document.getElementById('textArea1').val();
//     if (typeof(textAreaVal) === "string") {
//         #dragDropWindow2.style.display ='block';
//     } 
//     else {
//         #dragDropWindow2.style.display='none';
//     }
// }