<?php
    session_start();
    require_once("../database/wb_db_connect.php");

    if ($conn) {
        $email = $_POST['email'];
        $password = md5($_POST['password']);

        $checkAccounts = "SELECT * FROM users WHERE Email = '$email' and Password = '$password' and User_Type = 'Cashier'";

        $result = $conn->query($checkAccounts);

        if($result->num_rows > 0) {

            $cashier = $result->fetch_assoc();

            $_SESSION['user_id'] = $cashier['ID'];
            $_SESSION['fname'] = $cashier['First_Name'];
            if ($user['Last_Name']) {
                $_SESSION['lname'] = $user['Last_Name'];
            }
            $_SESSION['email'] = $cashier['Email'];
            $_SESSION['user_type'] = "Cashier";

            header("Location: cashier_home.php");
            exit();

        }

        $_SESSION['incorrect-msg'] = 'Incorrect email or password';
    } else {
        $_SESSION['incorrect-msg'] = 'Unable to connect to the database';
    }

    header("location: cashier_log_in.php");
    exit();
?>