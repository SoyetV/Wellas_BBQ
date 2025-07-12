<?php 
    session_start();
    $incorrect_error = "";
    if (isset($_SESSION['incorrect-msg'])) {
        $incorrect_error = $_SESSION['incorrect-msg'];
    }
    unset($_SESSION['incorrect-msg']);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Wella's BBQ</title>
        <link rel="icon" type="ico" href="../wb.ico">
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
    </style>
    </head>
    <body>
        <video autoplay muted loop id="bgVideo">
        <source src="../smoky.mp4" type="video/mp4" />
    </video>
        <!-- Navbar -->
        <?php include "../reusable_php/navtab.php" ?>

        <!-- Main Content -->
        <div id="MainContent">
            <div class="log-in-container">
                <div class="log-in-box" style="margin-top: 25px">
                    <h1>LOGIN</h1>
                    <div class="form-container">
                        <form name="log-in-form" action="../database/log_in_check.php" method="post">                  
                            <label for="log-in-email">Email</label><br>
                            <input name="email" id="log-in-email" type="text" autocomplete="on">
                            <p class="error email-error"></p><br>

                            <label for="log-in-password">Password</label><br>
                            <input name="password" id="log-in-password" type="password" autocomplete="off">
                            <p class="error password-error"></p><br>
                            <input type="checkbox" onclick="showPassword('log-in-password')"> Show Password

                            <p class="incorrect-error-paragraph" id="incorrect-error-paragraph"><?php echo htmlspecialchars($incorrect_error) ?></p>
                            <input type="submit" name="log-in-submit" value="Log In">
                        </form>
                    </div>
                    <hr>
                    <p class="more-paragraph">Need an account? <a href="../main_pages/sign_up.php">SIGN UP HERE</a></p>
                </div>
            </div>
        </div>

        <script src="../javascript/JQUERY.js"></script>
        <script src="../javascript/globaljavascript.js"></script>
        <script src="../javascript/log_in_checker.js"></script>
        <script src="../javascript/showpassword.js"></script>
        <script src="../javascript/expandnavtab.js"></script>

    </body>
</html>