<?php
session_start();
require_once("wb_db_connect.php");

// 🔐 Validate session
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] !== 'Member') {
    header("Location: ../index.php");
    exit();
}

$user_id = intval($_SESSION["user_id"]);

if ($conn && isset($_POST["submit-rate"])) {
    // 🎯 Grab inputs safely
    $rate_num = intval($_POST["rating"]);
    $rate_comment = trim($_POST["comment"]);
    $payment_id = intval($_POST["payment_id"]);

    // ✅ Validate payment ownership & eligibility
    $checkPaymentQuery = "SELECT ID FROM payment WHERE ID = ? AND User_ID = ? AND Rating_Status = 'Received'";
    $checkPaymentStmt = $conn->prepare($checkPaymentQuery);
    $checkPaymentStmt->bind_param("ii", $payment_id, $user_id);
    $checkPaymentStmt->execute();
    $paymentResult = $checkPaymentStmt->get_result();

    if ($paymentResult->num_rows === 0) {
        echo "<script>alert('Invalid or already rated payment.'); window.location='../main_pages/home.php';</script>";
        exit();
    }

    // 🚫 Prevent duplicate ratings
    $checkRatingQuery = "SELECT Rating_ID FROM ratings WHERE Payment_ID = ?";
    $checkRatingStmt = $conn->prepare($checkRatingQuery);
    $checkRatingStmt->bind_param("i", $payment_id);
    $checkRatingStmt->execute();
    $ratingResult = $checkRatingStmt->get_result();

    if ($ratingResult->num_rows > 0) {
        echo "<script>alert('You have already rated this payment.'); window.location='../main_pages/home.php';</script>";
        exit();
    }

    // 📝 Insert rating
    $insertRatingQuery = "INSERT INTO ratings (User_ID, Payment_ID, Rating, Comment) VALUES (?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertRatingQuery);
    $insertStmt->bind_param("iiis", $user_id, $payment_id, $rate_num, $rate_comment);
    $insertSuccess = $insertStmt->execute();

    // 🔄 Update rating status
    $updateStatusQuery = "UPDATE payment SET Rating_Status = 'Rated' WHERE ID = ?";
    $updateStmt = $conn->prepare($updateStatusQuery);
    $updateStmt->bind_param("i", $payment_id);
    $updateSuccess = $updateStmt->execute();

    // ✅ Redirect if successful
    if ($insertSuccess && $updateSuccess) {
        header("Location: ../main_pages/home.php");
        exit();
    } else {
        echo "<script>alert('Error submitting your rating.'); window.location='../main_pages/home.php';</script>";
        exit();
    }
}

// ⛔ Catch any non-submission access
echo "<script>alert('Unable to submit rating.'); window.location='../main_pages/home.php';</script>";
exit();
?>