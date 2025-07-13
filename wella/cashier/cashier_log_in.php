<?php 
    session_start();
    $incorrect_error = "";
    if (isset($_SESSION['incorrect-msg'])) {
        $incorrect_error = $_SESSION['incorrect-msg'];
    }
    unset($_SESSION['incorrect-msg'])
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
    </head>
    <body>
        <!-- Navbar -->
        <?php include "../reusable_php/navtab.php" ?>

        <!-- Main Content -->
        <div id="MainContent">
            <div class="log-in-container">
                <div class="log-in-box" style="margin-top: 25px">
                    <h1>CASHIER LOGIN</h1>
                    <div class="form-container">
                        <form name="log-in-form" action="check_cashier_log_in.php" method="post">                  
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