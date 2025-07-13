<?php 
session_start();

$incorrect_error = "";
if (isset($_SESSION['incorrect-msg'])) {
    $incorrect_error = $_SESSION['incorrect-msg'];
    unset($_SESSION['incorrect-msg']);
}

if (isset($_POST["productID"])) {
    $_SESSION["product_ID"] = $_POST["productID"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wella's BBQ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../wb.ico">
    <link href="../bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/Global_Design.css" rel="stylesheet">
    <link href="../css/log_in_design.css" rel="stylesheet">

    <style>
        #bgVideo {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            z-index: -1;
            object-fit: cover;
            filter: brightness(0.4);
        }

        #guest-name {
            font-size: 30px;
        }
        #guest-submit {
            color: white;
            border-color: #aaa;
            background-color: #aaa;
            margin-bottom: -200px;
            font-size: 35px;
            cursor: default;
        }
        @media (max-width: 1200px) {
            #guest-name {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
     <video autoplay muted loop id="bgVideo">
        <source src="../smoky.mp4" type="video/mp4" />
    </video>

<?php include "../reusable_php/navtab.php"; ?>

<div id="MainContent">
    <div class="log-in-container">
        <div class="log-in-box" style="margin-top: 25px">
            <h1 style="font-size: 40px; margin-bottom: 30px;">YOUR NAME PLEASE</h1>
            <div class="form-container">
                <form name="guest-form" method="get" action="../main_pages/sign_up.php">
                    <input name="guest_name" id="guest-name" type="text" placeholder="Enter your name" autocomplete="on">
                    <p class="error email-error"></p><br>
                    <p class="more-paragraph" style="margin-top: -20px; margin-bottom: 30px">
                        Already have an account? <a href="../main_pages/log_in.php">LOG IN HERE</a>
                    </p>
                    <?php if (!empty($incorrect_error)): ?>
                        <p class="incorrect-error-paragraph"><?= htmlspecialchars($incorrect_error) ?></p>
                    <?php endif; ?>
                    <input type="submit" name="guest-submit" id="guest-submit" value="CONTINUE TO SIGN UP">
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../javascript/JQUERY.js"></script>
<script>
const guestName = document.getElementById("guest-name");
const enterButton = document.getElementById("guest-submit");

guestName.addEventListener("input", () => {
    if (guestName.value === "") {
        enterButton.style.backgroundColor = "#aaa";
        enterButton.style.borderColor = "#aaa";
        enterButton.style.cursor = "default";
    } else {
        enterButton.style.backgroundColor = "red";
        enterButton.style.borderColor = "red";
        enterButton.style.cursor = "pointer";
    }
});

document.forms["guest-form"].onsubmit = function(event) {
    if (guestName.value === "") {
        event.preventDefault();
    }
};
</script>

</body>
</html>
