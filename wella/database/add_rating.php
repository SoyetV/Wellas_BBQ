<?php
session_start();
require_once("wb_db_connect.php");

if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != 'Member') {
    header("Location: ../index.php");
    exit();
}

if ($conn && isset($_POST["submit-rate"])) {
    $user_id = intval($_SESSION["user_id"]);
    $rate_num = intval($_POST["rating"]);
    $rate_comment = mysqli_real_escape_string($conn, $_POST["comment"]);
    $payment_id = intval($_POST["payment_id"]);


    $insertRating = "INSERT INTO ratings (User_ID, Payment_ID, Rating, Comment)
                     VALUES ($user_id, $payment_id, $rate_num, '$rate_comment')";

    $updateStatus = "UPDATE payment
                     SET Rating_Status = 'Rated'
                     WHERE ID = $payment_id";

    if ($conn->query($insertRating) === TRUE && $conn->query($updateStatus) === TRUE) {
        unset($_SESSION["rating_num"]);
        header("Location: ../main_pages/home.php");
        exit();
    } else {
  
        echo "Error: " . $conn->error;
    }
}


echo "<script>alert('Unable to submit rating.'); window.location='../main_pages/home.php';</script>";
exit();
?>
