var isOpen = false;

/* ------------------------------------------------ */

const navScreenWidth = window.matchMedia("(max-width: 1200px)");

function mediaScreen(width) {
    if (!width.matches) {
        isOpen = false;

        $("#MainContent").stop();
        $("#expandTab").stop();

        $("#MainContent").show(0);
        $("#expandTab").hide(0);

        const more_icon = document.getElementById("more-icon");
        more_icon.style.backgroundImage = "url('../images/icons/MoreIcon.png')";
    }
}

navScreenWidth.addEventListener("change", () => {
    mediaScreen(navScreenWidth);
});

/* ------------------------------------------------ */

document.addEventListener("DOMContentLoaded", () => {
    $("#expandTab").hide(0);
    document.getElementById("expandTab").style.visibility = "visible";
});

document.getElementById("review-order-button-click").addEventListener("click", () => {
    window.location.replace("../main_pages/order.php");
})

document.getElementById("expanded-review-order-button-click").addEventListener("click", () => {
    window.location.replace("../main_pages/order.php");
})

function expandNavigation(button) {
    $("#MainContent").stop();
    $("#expandTab").stop();
    
    if (!isOpen) {
        isOpen = true;

        $("#MainContent").show(0);
        $("#expandTab").hide(0);

        $("#MainContent").fadeOut("fast", "swing");
        $("#expandTab").slideDown("slow", "swing");
        button.style.backgroundImage = "url('../images/icons/CloseMoreIcon.png')";
    } else {
        isOpen = false;

        $("#MainContent").hide(0);
        $("#expandTab").show(0);
        
        $("#expandTab").fadeOut("fast", "swing");
        $("#MainContent").fadeIn("fast", "swing");
        button.style.backgroundImage = "url('../images/icons/MoreIcon.png')";
    }
}