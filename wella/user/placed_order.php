<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../main_pages/order.php");
    exit();
}

require_once("../database/wb_db_connect.php");

if (isset($_SESSION["Failed"])) {
    echo "<script>alert('" . $_SESSION['Failed'] . "');</script>";
    unset($_SESSION["Failed"]);
}

$paymentID = 0;
$totalQty = 0;
$totalPrice = 0.00;

$branchStreet = "STREET";
$branchBarangay = "BARANGAY";
$branchCity = "CITY";
$cashierContactNum = "000000000000";

if ($conn) {
    $user_id = $_SESSION["user_id"];


    $checkPayment = "SELECT * FROM payment 
                     WHERE User_ID = $user_id 
                     AND ((Remarks = 'Ongoing') OR (Remarks = 'Completed' AND Rating_Status = 'NotReceived')) 
                     ORDER BY ID DESC LIMIT 1";

    $result = $conn->query($checkPayment);

    if ($result && $result->num_rows > 0) {
        $paymentInfo = $result->fetch_assoc();
        $paymentID = $paymentInfo["ID"];
        $_SESSION["payment_id"] = $paymentID;
        $totalQty = $paymentInfo["Total_Quantity"];
        $totalPrice = $paymentInfo["Total_Payment"];
        $addressID = $paymentInfo["Address_ID"];
        $remarks = $paymentInfo["Remarks"]; 
        $ratingStatus = $paymentInfo["Rating_Status"]; 

        // Get Address
        $searchAddress = "SELECT * FROM address WHERE ID = $addressID";
        $addressResult = $conn->query($searchAddress);
        if ($addressResult && $addressResult->num_rows > 0) {
            $address = $addressResult->fetch_assoc();
            $branchStreet = $address["Street"];
            $branchBarangay = $address["Barangay"];
            $branchCity = $address["City"];
        }


        $searchCashier = "SELECT * FROM cashiers WHERE Address_ID = $addressID";
        $cashierResult = $conn->query($searchCashier);
        if ($cashierResult && $cashierResult->num_rows > 0) {
            $cashierContactNum = $cashierResult->fetch_assoc()["Contact_Number"];
        }
    } else {
     
        $checkRating = "SELECT ID FROM payment 
                        WHERE User_ID = $user_id 
                        AND Remarks = 'Completed' 
                        AND Rating_Status = 'Received' 
                        LIMIT 1";
        $ratingResult = $conn->query($checkRating);
        if ($ratingResult && $ratingResult->num_rows > 0) {
            header("Location: ../user/ratings.php");
            exit();
        }

        header("Location: ../main_pages/order.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Wella's BBQ</title>
    <link rel="icon" type="ico" href="../wb.ico" />
    <link href="../bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../css/Global_Design.css" rel="stylesheet" />
    <meta http-equiv="refresh" content="4" /> 

    <style>
       #bgVideo {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            z-index: -1;
            object-fit: cover;
            filter: brightness(0.4);
        }
        * {
            color: rgb(67, 44, 26);
        }
        #MainContent {
            padding-inline: 20px;
        }
        #order-container {
            border-top: red solid 15px;
            max-width: 800px;
            margin: 20px auto;
            padding: 25px 5px;
            padding-inline: 40px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            text-wrap: balance;
        }
        h2 {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: 700;
        }
        h1 {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: 700;
            font-size: 120px;
        }
        h3 {
            font-weight: 700;
            margin-top: 50px;
            margin-bottom: 30px;
        }
        h4 {
            margin-bottom: 5px;
        }
        .place-order-buttons {
            width: 100%;
            margin-top: 50px;
            display: flex;
            gap: 10px;
        }
        #cancel, #complete {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: 800;
            font-size: 20px;
            width: 100%;
            padding-top: 15px;
            padding-bottom: 15px;
            border-radius: 50px;
            transition: all 0.1s ease-in-out;
            cursor: pointer;
        }
        #cancel {
            background-color: red;
            border: red solid 3px;
            color: white;
        }
        #complete {
            background-color: rgb(10, 195, 0);
            border: rgb(10, 195, 0) solid 3px;
            color: white;
        }
        #summaryOrders {
            background-color: rgb(248, 17, 17);
            border: 2px solid rgb(248, 17, 17);
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: 800;
            font-size: 20px;
            padding: 15px 25px;
            border-radius: 50px;
        }
        #summaryOrders:hover {
            background-color: white;
            color: rgb(248, 17, 17);
        }
        @media all and (min-width: 1200px) {
            #cancel:hover {
                background-color: white;
                color: red;
            }
            #complete:hover {
                background-color: white;
                color: rgb(10, 195, 0);
            }
        }

        #streetH {
            font-size: 35px;
        }
        #addH {
            margin-top: 15px;
            font-size: 25px;
        }

        @media all and (max-width: 1200px) {
            #order-container {
                padding-inline: 10px;
            }
            .place-order-buttons {
                display: block;
            }
            #cancel:active {
                background-color: white;
                color: red;
            }
            #complete:active {
                background-color: white;
                color: rgb(10, 195, 0);
            }
            #complete {
                margin-top: 15px;
            }
            #streetH {
                font-size: 20px;
            }
            #addH {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
        <video autoplay muted loop id="bgVideo">
            <source src="../smoky.mp4" type="video/mp4" />
        </video>
    <?php include "../reusable_php/navtab.php"; ?>

    <div id="MainContent">
        <div id="order-container">

         
            <?php if (isset($remarks, $ratingStatus) && $remarks === 'Completed' && $ratingStatus === 'NotReceived'): ?>
                <center>
                    <div style="
                        background-color: rgb(10, 195, 0); 
                        color: black; 
                        padding: 10px; 
                        font-size: 20px;
                        font-weight: bold; 
                        margin-top: 10px; 
                        border-radius: 8px;
                    ">
                       YOUR ORDER IS READY. PLEASE PICK UP YOUR ORDER.
                    </div>
                </center>
            <?php endif; ?>

            <center><h2>YOUR ORDER NUMBER:</h2></center>
            <center><h1><?php echo $paymentID; ?></h1></center>

            <center><h3>ADDRESS:</h3></center>
            <center><h4 id="streetH"><?php echo strtoupper($branchStreet); ?></h4></center>
            <center><h4 id="addH"><?php echo strtoupper($branchBarangay); ?>, <?php echo strtoupper($branchCity); ?></h4></center>

            <center style="margin-top: 30px;"><h3><strong>PHONE NUMBER:</strong></h3></center>
            <center><h4><?php echo $cashierContactNum; ?></h4></center>

            <br><hr>

            <center><h3>ORDERS:</h3></center>
            <center><button onclick="reviewOrders()" id="summaryOrders">CHECK ORDERS</button></center>

            <br><br><hr>

            <center class="place-order-buttons">
                <button id="cancel">CANCEL ORDER</button>
            </center>
        </div>
    </div>

    <script src="../javascript/JQUERY.js"></script>
    <script src="../javascript/globaljavascript.js"></script>
    <script src="../javascript/expandnavtab.js"></script>

    <?php include "../reusable_php/confirmationpopup.php"; ?>

    <script>
        document.getElementById("cancel").addEventListener("click", () => {
            $("#confirmBox").show(0);
            document.getElementById("confirmation-text").innerHTML = `Are you sure you want to cancel your order?`;
            document.getElementById("confirmation-confirm-button").onclick = function () {
                window.location = "../database/cancel_payment.php";
            };
        });

        document.getElementById("complete").addEventListener("click", () => {
            window.location = "../database/complete_payment.php";
        });

        function reviewOrders() {
            window.location = "summary_order.php";
        }
    </script>
</body>
</html>
