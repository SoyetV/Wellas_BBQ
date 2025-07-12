<?php
    session_start();
    require_once("wb_db_connect.php");

    if ($conn) {
        if (isset($_SESSION["user_id"])) {

            $user_ID = $_SESSION["user_id"];

            $checkUserOrders = "SELECT * FROM orders WHERE User_ID = $user_ID AND Status = 'Pending'";
            $results = $conn->query($checkUserOrders);

            if ($results->num_rows > 0) {

                $total_price = 0.00;
                while ($user_orders = $results->fetch_assoc()) {
                    $total_price += $user_orders["Price"];
                }
                $results->free();

                $results = $conn->query($checkUserOrders);
                $total_qty = 0;
                while ($user_orders = $results->fetch_assoc()) {
                    $total_qty += $user_orders["Quantity"];
                }
                $results->free();

                $createPayment = "INSERT INTO payment (User_ID, Total_Payment, Total_Quantity, PaymentStartedDate)
                                VALUES ($user_ID, $total_price, $total_qty, CURDATE())";
                
                if ($conn->query($createPayment) == TRUE) {
                    $payment_id = $conn->insert_id;

                    $results = $conn->query($checkUserOrders);
                    while ($user_orders = $results->fetch_assoc()) {
                        $order_id = $user_orders["ID"];
                        $insertPaymentID = "UPDATE orders
                                            SET
                                            Status = 'Ongoing', Payment_ID = $payment_id
                                            WHERE ID = $order_id";

                        if ($conn->query($insertPaymentID) == FALSE) {
                            failedUpdate("Unable to place order");
                            exit();
                        }
                    }
                    $results->free();

                } else {
                    failedUpdate("Unable to place order");
                    exit();
                }

            } else {
                failedUpdate("You don't have any order");
                exit();
            }

        } else {
            failedUpdate("You are currently not logged in");
            exit();
        }
    } else {
        failedUpdate("Unable to connect to the database");
        exit();
    }
    
    header("Location: ../user/placed_order.php");
    exit();

    function failedUpdate($msg) {
        $_SESSION["Failed"] = $msg;
        header("Location: ../main_pages/order.php");
    }
?>