<?php
    session_start();
    require_once("wb_db_connect.php");

    if ($conn) {
        if (isset($_POST["deleteOrder"])) {
            
            $id = $_POST["deleteOrder"];

            $deleteRow = "DELETE FROM orders WHERE ID = $id";

            if ($conn->query($deleteRow) == FALSE) {
                failedUpdate("Order not found");
                exit();
            }
        }
    } else {
        failedUpdate("Unable to connect to the database");
        exit();
    }

    $_SESSION["Success"] = "Item is removed from your order";
    header("Location: ../main_pages/order.php");
    exit();

    function failedUpdate($msg) {
        $_SESSION["Failed"] = $msg;
        header("Location: ../user/edit_order.php");
    }
?>