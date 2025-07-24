function clearError() {
    let errors = document.querySelectorAll(".error");
    for (let error of errors) {
        error.classList.remove("display-error");
    }
}

function displayError(errorElement, errorMessage) {
    document.querySelector("."+errorElement).classList.add("display-error");
    document.querySelector("."+errorElement).innerHTML = errorMessage;
}

function isValidEmail(email) {
    let emailRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return emailRegex.test(email);
}

$(document).ready(function() {
    $(window).keyup(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
});

let logInForm = document.forms["log-in-form"];
logInForm.onsubmit = function(event) {

    clearError();
    window.scrollTo(0, 0);
    document.getElementById("incorrect-error-paragraph").innerHTML = "";

    if (logInForm.email.value == "") {
        event.preventDefault();
        displayError("email-error", "You have to enter your email");
        return false;
    }

    if (!isValidEmail(logInForm.email.value)) {
        event.preventDefault();
        displayError("email-error", "Invalid email");
        return false;
    }

    if (logInForm.password.value == "") {
        event.preventDefault();
        document.getElementById("log-in-password").focus();
        displayError("password-error", "You have to enter your password");
        return false;
    }
}