<?php
    session_start();
    require_once("wb_db_connect.php");

    unset($_SESSION["payment_id"]);

    if ($conn) {
        if (isset($_SESSION["user_id"])) {

            $user_ID = $_SESSION["user_id"];

            $checkUserPayment = "SELECT * FROM payment WHERE User_ID = $user_ID AND Remarks = 'Ongoing'";

            if ($conn->query($checkUserPayment)->num_rows > 0) {
                $payment_id = $conn->query($checkUserPayment)->fetch_assoc()["ID"];

                $deletePayment = "UPDATE payment
                                    SET
                                    Remarks = 'Cancelled'
                                    WHERE ID = $payment_id";

                if ($conn->query($deletePayment) == FALSE) {
                    failedUpdate("Unable to cancel order");
                    exit();
                }
            }


            $checkUserOrders = "SELECT * FROM orders WHERE User_ID = $user_ID AND Status = 'Ongoing'";
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
            failedUpdate("Unable to cancel order");
            exit();
        }

    } else {
        failedUpdate("Unable to connect to the database");
        exit();
    }

    header("Location: ../main_pages/order.php");
    exit();

    function failedUpdate($msg) {
        $_SESSION["Failed"] = $msg;
        header("Location: ../user/placed_order.php");
    }
?>