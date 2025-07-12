
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

let signUpForm = document.forms["sign-up-form"];
signUpForm.onsubmit = function(event) {

    clearError();
    window.scrollTo(0, 0);
    document.getElementById("incorrect-error-paragraph").innerHTML = "";

    if (signUpForm.lname.value == "") {
        event.preventDefault();
        displayError("lname-error", "Enter your last name");
        return false;
    }

    if (signUpForm.fname.value == "") {
        event.preventDefault();
        document.getElementById("sign-up-fname").focus();
        displayError("fname-error", "Enter your first name");
        return false;
    }

    if (signUpForm.email.value === "") {
        event.preventDefault();
        document.getElementById("sign-up-email").focus();
        displayError("email-error", "You have to enter your email");
        return false;
    }

    if (!isValidEmail(signUpForm.email.value)) {
        event.preventDefault();
        displayError("email-error", "Invalid email");
        return false;
    }

    const passwordString = signUpForm.password.value;
    var lowercaseLetters = /[a-z]/g;
    var uppercaseLetters = /[A-Z]/g;
    var digits = /[0-9]/g;

    if (passwordString == "") {
        event.preventDefault();
        document.getElementById("sign-up-password").focus();
        displayError("password-error", "You have to enter your password");
        return false;
    }

    if (!(passwordString.length >= 8)) {
        event.preventDefault();
        displayError("password-error", "Invalid password");
        return false;
    }

    if (!passwordString.match(lowercaseLetters)) {
        event.preventDefault();
        displayError("password-error", "Invalid password: Requires lowercase characters [a-z]");
        return false;
    }
    
    if (!passwordString.match(uppercaseLetters)) {
        event.preventDefault();
        displayError("password-error", "Invalid password: Requires uppercase characters [A-Z]");
        return false;
    }

    if (!passwordString.match(digits)) {
        event.preventDefault();
        displayError("password-error", "Invalid password: Requires digits [0-9]");
        return false;
    }

    if (signUpForm.check_password.value == "") {
        event.preventDefault();
        document.getElementById("sign-up-confirm-password").focus();
        displayError("password-confirm-error", "You have to confirm your password");
        return false;
    }

    if (signUpForm.check_password.value != passwordString) {
        event.preventDefault();
        displayError("password-confirm-error", "Your confirmation password does not match");
        return false;
    }
}