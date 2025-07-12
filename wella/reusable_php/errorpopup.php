<link href="../css/reusable_php_css/confirmationpopup.css" rel="stylesheet">

<style>
    .confirmation-buttons {
        margin-top: 30px;
        max-width: 300px;
    }

    #error-ok-button {
        background-color: red;
        border: red solid 2px;
        color: white;
    }

@media all and (min-width: 1200px) {
    #error-ok-button:hover {
        background-color: white;
        color: red;
    }
}

@media all and (max-width: 1200px) {
    #error-ok-button:active {
        background-color: white;
        color: red;
    }
}

#error-title {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-weight: 800;
    font-size: 40px;
    text-align: center;
}

#error-text {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-weight: 400;
    font-size: 19px;
    text-align: center;
}

#errorBox {
    display: none;
}

</style>

<div id="errorBox">
    <div class="darkednedBackground" id="error-dark-background">
        <div id="confirmation-box">
            <div id="confirmation-close"></div>
            <h1 id="error-title">___</h1><br>
            <p id="error-text">___</p>
            <center><button class="confirmation-buttons" id="error-ok-button">OK</button></center>
        </div>
    </div>
</div>

<script>
    $("#errorBox").fadeIn("fast");

    document.getElementById("confirmation-close").addEventListener('click', () => {
        closeErrorPopUp();
    })

    document.getElementById("error-ok-button").addEventListener('click', () => {
        closeErrorPopUp();
    })

    document.getElementById("error-dark-background").addEventListener('click', () => {
        closeErrorPopUp();
    })

    function closeErrorPopUp() {
        document.getElementById("errorBox").style.display = "none";
    }
</script>