<?php
session_start();
require_once("wb_db_connect.php");

if ($conn) {
    if (isset($_POST["paymentID"])) {
        $_SESSION["is_cashier"] = true;
        $_SESSION["payment_id"] = $_POST["paymentID"];
    }

    if (isset($_SESSION["payment_id"])) {
        $payment_id = $_SESSION["payment_id"];
        unset($_SESSION["payment_id"]);

    
        $updatePayment = "UPDATE payment
                          SET
                              Remarks = 'Completed',
                              PaymentCompletedDate = CURDATE(),
                              Rating_Status = 'NotReceived' 
                          WHERE ID = $payment_id";

        if ($conn->query($updatePayment) === TRUE) {

            $updateOrderStatus = "UPDATE orders
                                  SET Status = 'Complete'
                                  WHERE Payment_ID = $payment_id";

            if ($conn->query($updateOrderStatus) === FALSE) {
                if (isset($_SESSION["is_cashier"])) {
                    header("Location: ../cashier/cashier_home.php");
                    exit();
                }
                failedUpdate("Unable to complete order, try again");
                exit();
            }

        } else {
            if (isset($_SESSION["is_cashier"])) {
                header("Location: ../cashier/cashier_home.php");
                exit();
            }
            failedUpdate("Unable to complete order, try again");
            exit();
        }
    }
} else {
    if (isset($_SESSION["is_cashier"])) {
        header("Location: ../cashier/cashier_home.php");
        exit();
    }
    failedUpdate("Unable to connect to the database");
    exit();
}


if (isset($_SESSION["is_cashier"])) {
    unset($_SESSION["is_cashier"]);
    header("Location: ../cashier/cashier_home.php");
    exit();
}

header("Location: ../index.php");
exit();

function failedUpdate($msg) {
    $_SESSION["Failed"] = $msg;
    header("Location: ../user/placed_order.php");
}
?>
