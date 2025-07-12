<?php
    session_start();
    require_once "wb_db_connect.php";

    if ($conn) {
        $name = $_POST["name"];
        $userType = "Guest";

        $insertQuery = "INSERT INTO users (First_Name, Last_Name, Email, Password, User_Type)
                        VALUES ('$name', NULL, NULL, NULL, '$userType')";

        if ($conn->query($insertQuery) == TRUE) {

            $userID = $conn->insert_id;

            $checkAccounts = "SELECT * FROM users WHERE ID = '$userID'";

            $result = $conn->query($checkAccounts);

            $user = $result->fetch_assoc();

            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['fname'] = null;
            $_SESSION['lname'] = null;
            $_SESSION['email'] = null;
            $_SESSION['user_type'] = $user['User_Type'];
            
            header("location: ../user/order_product.php");
            exit();
        } else {
            $_SESSION['incorrect-msg'] = 'Unable to login as guest, try again';
        }
    } else {
        $_SESSION['incorrect-msg'] = 'Unable to connect to the database';
    }
    header("location: ../main_pages/guest_log_in.php");
    exit();

?>