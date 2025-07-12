function showPassword(element) {
    const login_password = document.getElementById(element);

    if (login_password.type == "password") {
        login_password.type = "text";
    } else {
        login_password.type = "password";
    }
}