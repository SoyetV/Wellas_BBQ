<?php 
session_start();

// Pre-fill guest name if redirected from guest_log_in
$prefill_fname = isset($_GET['guest_name']) ? htmlspecialchars($_GET['guest_name']) : "";
$incorrect_error = isset($_SESSION['already-exist-msg']) ? $_SESSION['already-exist-msg'] : "";
unset($_SESSION['already-exist-msg']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wella's BBQ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="ico" href="../wb.ico">
    <link href="../bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/Global_Design.css" rel="stylesheet">
    <link href="../css/sign_up_design.css" rel="stylesheet">

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
<?php include "../reusable_php/navtab.php"; ?>

<div id="MainContent">
    <div class="sign-up-container">
        <div class="sign-up-box">
            <h1>SIGN UP</h1>
            <div class="form-container">
                <form name="sign-up-form" action="../database/sign_up_check.php" method="post">
                    <div class="name-div">
                        <div class="name-div-part">
                            <label for="sign-up-lname">Last Name</label><br>
                            <input name="lname" id="sign-up-lname" type="text" autocomplete="on">
                            <p class="error lname-error"></p>
                        </div>

                        <div class="name-div-part">
                            <label for="sign-up-fname">First Name</label><br>
                            <input name="fname" id="sign-up-fname" type="text" autocomplete="on" value="<?php echo $prefill_fname ?>">
                            <p class="error fname-error"></p>
                        </div>
                    </div>

                    <label for="sign-up-email">Email</label><br>
                    <input name="email" id="sign-up-email" type="text" autocomplete="on">
                    <p class="error email-error"></p><br>

                    <label for="sign-up-password">Password</label><br>
                    <input name="password" id="sign-up-password" type="password" autocomplete="off">
                    <p class="error password-error"></p><br>
                    <input type="checkbox" onclick="showPassword('sign-up-password')"> Show Password<br>

                    <label for="sign-up-confirm-password">Confirm Password</label><br>
                    <input name="check_password" id="sign-up-confirm-password" type="password" autocomplete="off">
                    <p class="error password-confirm-error"></p><br>

                    <p class="incorrect-error-paragraph" id="incorrect-error-paragraph"><?php echo htmlspecialchars($incorrect_error); ?></p>
                    <input type="submit" name="sign-up-submit" value="Sign Up">
                </form>
            </div>
            <hr>
            <p class="more-paragraph">Already have an account? <a href="../main_pages/log_in.php">LOG IN HERE</a></p>
        </div>
    </div>
</div>

<script src="../javascript/JQUERY.js"></script>
<script src="../javascript/globaljavascript.js"></script>
<script src="../javascript/showpassword.js"></script>
<script src="../javascript/sign_up_checker.js"></script>
<script src="../javascript/expandnavtab.js"></script>

</body>
</html>
