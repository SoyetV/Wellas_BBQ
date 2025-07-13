<?php
    session_start();
    require_once("wb_db_connect.php");

    if ($conn) {
        $user_id = $_SESSION["user_id"];

        $deleteRow = "DELETE FROM orders WHERE User_ID = $user_id AND Status = 'Pending'";

        if ($conn->query($deleteRow) == FALSE) {
            failedUpdate("Unable to cancel your order, try again");
            exit();
        }
    } else {
        failedUpdate("Unable to connect to the database");
        exit();
    }

    $_SESSION["Success"] = "You have cancelled your order";
    header("Location: ../main_pages/menu.php");
    exit();

    function failedUpdate($msg) {
        $_SESSION["Failed"] = $msg;
        header("Location: ../main_pages/order.php");
    }
?>