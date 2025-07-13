<?php
session_start();
require_once("../database/wb_db_connect.php");
unset($_SESSION["paymentID"]);

if (!isset($_SESSION["user_id"]) || isset($_SESSION["user_type"]) && $_SESSION["user_type"] != 'Cashier') {
    header("location: ../index.php");
    exit();
}

$cashier_id = $_SESSION["user_id"];
$cashierFirstName = $_SESSION['fname'];
$cashierLastName = $_SESSION['lname'] ?? null;
$cashierEmail = $_SESSION['email'];

$branchStreet = "STREET";
$branchBarangay = "BARANGAY";
$branchCity = "CITY";
$contactInformation = "000000000000";

if ($conn) {
    $searchCashierInfo = "SELECT * FROM cashiers WHERE User_ID = $cashier_id";
    $cashierInfoResult = $conn->query($searchCashierInfo);
    if ($cashierInfoResult && $cashierInfoResult->num_rows > 0) {
        $cashier_info = $cashierInfoResult->fetch_assoc();
        $contactInformation = $cashier_info["Contact_Number"];
        $address_id = $cashier_info["Address_ID"];

        $searchAddress = "SELECT * FROM address WHERE ID = $address_id";
        $addressResult = $conn->query($searchAddress);
        if ($addressResult && $addressResult->num_rows > 0) {
            $address = $addressResult->fetch_assoc();
            $branchStreet = $address["Street"];
            $branchBarangay = $address["Barangay"];
            $branchCity = $address["City"];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wella's BBQ Cashier Page</title>
    <link rel="icon" type="ico" href="../wb.ico">
    <link href="../bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/Global_Design.css" rel="stylesheet">
    <link href="cashier_home.css" rel="stylesheet">
</head>
<body>
    <div class="container" style="max-width: 1500px; margin-inline: auto;">
        <div class="info-container pc-info">
            <div class="info-div" style="width: 48%">
                <h2>CASHIER INFO</h2>
                <h3><strong>Name:</strong> <?= $cashierFirstName ?> <?= $cashierLastName ?></h3>
                <h3><strong>Email:</strong> <?= $cashierEmail ?></h3>
                <h3><strong>Contact Number:</strong> <?= $contactInformation ?></h3>
            </div>
            <div class="info-div">
                <h2>ADDRESS INFO</h2>
                <h3><strong>Street:</strong> <?= $branchStreet ?></h3>
                <h3><strong>Barangay:</strong> <?= $branchBarangay ?></h3>
                <h3><strong>City:</strong> <?= $branchCity ?></h3>
            </div>
            <button id="log-out-button" onclick="logOutCashier()">LOG OUT</button>
            
        </div>

        <h2 id="order-title"><strong>ORDERS</strong></h2>
        <div id="item-container"></div>

        <div class="info-container mobile-info">
            <hr style="margin-top: 30px">
            <div class="info-div">
                <h2>CASHIER INFO</h2>
                <h3><strong>Name:</strong> <?= $cashierFirstName ?> <?= $cashierLastName ?></h3>
                <h3><strong>Email:</strong> <?= $cashierEmail ?></h3>
                <h3><strong>Contact Number:</strong> <?= $contactInformation ?></h3>
            </div>
            <hr>
            <div class="info-div">
                <h2>ADDRESS INFO</h2>
                <h3><strong>Street:</strong> <?= $branchStreet ?></h3>
                <h3><strong>Barangay:</strong> <?= $branchBarangay ?></h3>
                <h3><strong>City:</strong> <?= $branchCity ?></h3>
            </div>
            <center><button id="log-out-button" onclick="logOutCashier()">LOG OUT</button></center>
        </div>
    </div>

    <script src="../javascript/JQUERY.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            loadOrderContainers();
            setInterval(loadOrderContainers, 1000);
        });

        function loadOrderContainers() {
            fetch("cashier_order_database.php")
                .then(response => response.text())
                .then(html => document.getElementById("item-container").innerHTML = html);
        }

        function logOutCashier() {
            document.getElementById("confirmBox").style.display = "block";
            document.getElementById("confirmation-text").innerHTML = 'Are you sure you want to log out?';

    
            const oldBtn = document.getElementById("confirmation-confirm-button");
            const newBtn = oldBtn.cloneNode(true);
            oldBtn.parentNode.replaceChild(newBtn, oldBtn);

            newBtn.addEventListener("click", function () {
            window.location.href = "../database/log_out.php";
            });
        }

        function openOrderReview(id) {
            fetch("cashier_review_order.php", {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `paymentID=${id}`
            }).then(() => window.location = "cashier_review_order.php");
        }

        function removeOrderConfirmation(id) {
            $("#confirmBox").show();
            document.getElementById("confirmation-text").innerText = `Are you sure you want to remove this order?`;
            document.getElementById("confirmation-confirm-button").onclick = () => removeOrder(id);
        }

        function removeOrder(id) {
            fetch("cashier_remove_order.php", {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `paymentID=${id}`
            }).then(() => location.reload());
        }

        function completeOrderConfirm(id) {
            $("#confirmBox").show();
            document.getElementById("confirmation-text").innerText = `Notify Customer: Order Ready for Pickup`;
            document.getElementById("confirmation-confirm-button").onclick = () => completeOrder(id);
        }

        function completeOrder(id) {
            fetch("../database/complete_payment.php", {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `paymentID=${id}`
            }).then(() => location.reload());
        }

        function markAsReceived(id) {
            fetch("../database/mark_received.php", {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `paymentID=${id}`
            }).then(() => location.reload());
        }
    </script>

    <?php include "../reusable_php/confirmationpopup.php"; ?>

    <?php
    if (!$conn) {
        callError('CONNECTION UNSTABLE', 'Your internet connection is currently unstable, try again later');
    } elseif (isset($_SESSION["Success"])) {
        callSuccess($_SESSION['Success']);
        unset($_SESSION["Success"]);
    } elseif (isset($_SESSION["Failed"])) {
        callError('ERROR', $_SESSION['Failed']);
        unset($_SESSION["Failed"]);
    }

    function callError($title, $text) {
        include("../reusable_php/errorpopup.php");
        echo "<script>
            document.getElementById('error-title').innerHTML = '$title';
            document.getElementById('error-text').innerHTML = '$text';
        </script>";
    }

    function callSuccess($text) {
        include("../reusable_php/successpopup.php");
        echo "<script>
            document.getElementById('successful-text').innerHTML = '$text';
        </script>";
    }
    ?>
</body>
</html>
