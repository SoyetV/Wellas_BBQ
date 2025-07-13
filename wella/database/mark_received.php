<?php
session_start();
require_once("wb_db_connect.php");


if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] !== 'Cashier') {
    header("Location: ../index.php");
    exit();
}


if (!$conn) {
    $_SESSION["Failed"] = "Database connection error.";
    header("Location: ../cashier/cashier_home.php");
    exit();
}

if (!isset($_POST["paymentID"])) {
    $_SESSION["Failed"] = "No payment ID provided.";
    header("Location: ../cashier/cashier_home.php");
    exit();
}

$payment_id = intval($_POST["paymentID"]);


$updateOrderStatus = "UPDATE orders
                      SET Status = 'Received'
                      WHERE Payment_ID = $payment_id AND Status = 'Complete'";

$updateRatingStatus = "UPDATE payment
                       SET Rating_Status = 'Received'
                       WHERE ID = $payment_id AND Remarks = 'Completed'";


$orderResult = $conn->query($updateOrderStatus);
$ratingResult = $conn->query($updateRatingStatus);

if ($orderResult && $ratingResult) {
    $_SESSION["Success"] = "Order marked as received.";
} else {
    $_SESSION["Failed"] = "Failed to update order/payment status.";
}

header("Location: ../cashier/cashier_home.php");
exit();
?>
