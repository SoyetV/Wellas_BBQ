<?php
    session_start();
    require_once "wb_db_connect.php";

    if ($conn) {
        $email = $_POST['email'];
        $password = md5($_POST['password']);

        $checkAccounts = "SELECT * FROM users WHERE Email = '$email' and Password = '$password' and User_Type != 'Cashier'";

        $result = $conn->query($checkAccounts);

        if($result->num_rows > 0) {

            $user = $result->fetch_assoc();

            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['fname'] = $user['First_Name'];
            if ($user['Last_Name']) {
                $_SESSION['lname'] = $user['Last_Name'];
            }
            $_SESSION['email'] = $user['Email'];
            $_SESSION['user_type'] = $user['User_Type'];

            header("Location: ../index.php");
            exit();

        }

        $_SESSION['incorrect-msg'] = 'Incorrect email or password';
    } else {
        $_SESSION['incorrect-msg'] = 'Unable to connect to the database';
    }

    header("location: ../main_pages/log_in.php");
    exit();
?>