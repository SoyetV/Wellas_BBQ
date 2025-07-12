const imageInput = document.getElementById("product-input-image");
const updateImage = document.getElementById("product-image");

imageInput.addEventListener("change", () => {
    if (imageInput.files[0] != null) {
        const imageFileURL = URL.createObjectURL(imageInput.files[0]);
        updateImage.src = imageFileURL;
        updateImage.alt = imageInput.files[0].name;
    }
});

document.getElementById("back-button").addEventListener("click", () => {
    window.location = "../main_pages/menu.php";
})

$(document).ready(function() {
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
});