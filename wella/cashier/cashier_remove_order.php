<?php
    session_start();
    require_once("../database/wb_db_connect.php");

    if ($conn) {
        if (isset($_POST["paymentID"])) {
            $_SESSION["payment_ID"] = $_POST["paymentID"];
        }

        if (isset($_SESSION["payment_ID"])) {

            $payment_id = $_SESSION["payment_ID"];

            $checkPaymentID = "SELECT * FROM payment WHERE ID = $payment_id AND Remarks != 'Completed'";

            if ($conn->query($checkPaymentID)->num_rows > 0) {

                $deletePayment = "DELETE FROM payment WHERE ID = $payment_id";

                if ($conn->query($deletePayment) == FALSE) {
                    failedUpdate("Unable to remove order");
                }
            }

            $checkUserOrders = "SELECT * FROM orders WHERE Payment_ID = $payment_id AND Status = 'Ongoing'";
            $results = $conn->query($checkUserOrders);
            while ($user_orders = $results->fetch_assoc()) {
                $order_id = $user_orders["ID"];
                $removePaymentID = "UPDATE orders
                                    SET
                                    Status = 'Pending', Payment_ID = NULL
                                    WHERE ID = $order_id";

                if ($conn->query($removePaymentID) == FALSE) {
                    failedUpdate("Unable to cancel order");
                    exit();
                }
            }
            $results->free();

        } else {
            failedUpdate("Unable to remove order");
        }

    } else {
        failedUpdate("Unable to connect to the database");
    }

    $_SESSION["Success"] = "Successfully removed order";
    header("Location: cashier_home.php");
    exit();

    function failedUpdate($msg) {
        $_SESSION["Failed"] = $msg;
    }
?>