<?php

    session_start();
    require_once "wb_db_connect.php";

    if($conn) {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $password = md5($_POST['password']);
        $userType = 'Member';

        $checkAccounts = "SELECT * FROM users WHERE email = '$email'";

        $result = $conn->query($checkAccounts);

        if($result->num_rows == 0) {

            $insertQuery = "INSERT INTO users (First_Name, Last_Name, Email, Password, User_Type)
                            VALUES ('$fname', '$lname', '$email', '$password', '$userType')";

            if ($conn->query($insertQuery) == TRUE) {
                $checkAccounts = "SELECT * FROM users WHERE Email = '$email' and Password = '$password'";

                $result = $conn->query($checkAccounts);

                $user = $result->fetch_assoc();

                $_SESSION['user_id'] = $user['ID'];
                $_SESSION['fname'] = $user['First_Name'];
                $_SESSION['lname'] = $user['Last_Name'];
                $_SESSION['email'] = $user['Email'];
                $_SESSION['user_type'] = $user['User_Type'];
                
                header("location: ../index.php");
                exit();
            } else {
                echo "Error:".$conn->error;
            }
        } else {
            $_SESSION['already-exist-msg'] = 'Email already exists';
            header("location: ../main_pages/sign_up.php");
            exit();
        }
    }
    
    $_SESSION['already-exist-msg'] = 'Unable to connect to the database';
    header("location: ../main_pages/sign_up.php");
    exit();
?>