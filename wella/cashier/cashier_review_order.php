<?php
    session_start();
    
    require_once("../database/wb_db_connect.php");

    $payment_id = NULL;
    $orderResult = NULL;

    if (isset($_POST["paymentID"])) {
        $_SESSION["paymentID"] = $_POST["paymentID"];
    }

    if ($conn && isset($_SESSION["paymentID"])) {
        $payment_id = $_SESSION["paymentID"];
        $checkOrders = "SELECT * FROM orders WHERE Payment_ID = $payment_id";
        $orderResult = $conn->query($checkOrders);
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Wella's BBQ</title>
        <link rel="icon" type="ico" href="../wb.ico">
        <link href="../bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/Global_Design.css" rel="stylesheet">
        <link href="../css/order_design.css" rel="stylesheet">

        <style>
            * {
                color: rgb(67, 44, 26);
            }
            .container {
                border-top: red solid 15px;
            }
            #order-review {
                padding-top: 40px;
            }
            #item-container {
                position: relative;
                height: 100vh;
                max-height: 450px;
            }
            #back-button {
                position: absolute;
                transform: translate(0px, -43px);
                width: 50px;
                cursor: pointer;
                z-index: 100;
            }
            #item-container {
                height: fit-content;
            }
            h3 {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                font-weight: 800;
                font-size: 30px;
                text-align: center;
            }
            h4 {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                text-align: center;
            }
            #payment-id-header {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                font-weight: 700;
                font-size: 100px;
                text-align: center;
            }
            .qualitative_summary {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                font-weight: 500;
            }
            #cancel_button {
                margin-top: 50px;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                font-size: 20px;
                font-weight: 800;
                width: 100%;
                border-radius: 50px;
                padding-top: 15px;
                padding-bottom: 15px;
                background-color: red;
                border: 5px solid red;
                color: white;
            }
            @media all and (min-width: 1200px) {
                #cancel_button:hover {
                    background-color: white;
                    color: red;
                }
            }

            @media all and (max-width: 1200px) {
                #cancel_button:active {
                    background-color: white;
                    color: red;
                }
            }
        </style>

    </head>
    <body>
        <!-- Main Content -->
        <div id="MainContent">
            <div class="container">
                <div id="order-review">

                    <img id="back-button" src="../images/Icons/BackIcon.png">

                    <?php if($conn && $orderResult != NULL): ?>
                        <?php
                            $fname = NULL;
                            $lname = NULL;
                            $order_customer_id = $orderResult->fetch_assoc()["User_ID"];
                            $searchUserID = "SELECT * FROM users WHERE ID = $order_customer_id";
                            if ($conn->query($searchUserID)->num_rows > 0) {
                                $fname = $conn->query($searchUserID)->fetch_assoc()["First_Name"];
                                $lname = $conn->query($searchUserID)->fetch_assoc()["Last_Name"];
                            }
                        ?>
                        <div>
                            <h3>ORDER NUMBER</h3>
                            <h1 id="payment-id-header"><?php echo $payment_id ?></h1>
                            <?php if($fname != NULL): ?>
                                <hr>
                                <h3>CUSTOMER</h3>
                                <?php if($fname != NULL): ?>
                                    <?php if($lname != NULL): ?>
                                        <h4><?php echo $fname; ?> <?php echo $lname ?></h4>
                                    <?php else: ?>
                                        <h4><?php echo $fname; ?></h4>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                            <hr>
                            <h3 style="text-align: left;">ORDERS</h3>
                        </div>
                    <?php endif; ?>

                    <div id="item-container">

                        <?php
                            if ($conn && isset($_SESSION["paymentID"])) {
                                $payment_id = $_SESSION["paymentID"];
                                $checkOrders = "SELECT * FROM orders WHERE Payment_ID = $payment_id";
                                $orderResult = $conn->query($checkOrders);
                            }
                        ?>

                        <?php if($orderResult != NULL): ?>
                            <?php
                                while($orderRow = $orderResult->fetch_assoc()): ?>
                                <!-- Order Items -->
                                <div class="order-item">
                                    <?php
                                        $productID = $orderRow["Item_ID"];
                                        $checkProducts = "SELECT * FROM products WHERE ID = $productID";
                                        $productsResult = $conn->query($checkProducts);
                                        $productDetails = $productsResult->fetch_assoc();
                                        $Name = $productDetails["Name"];
                                        if (isset($productDetails["Img_Dir"])) {
                                            $productImgName = $productDetails["Img_Dir"];
                                            $productImgDir = "../database/web_img/".$productImgName;
                                        } else {
                                            $productImgName = $Name;
                                            $productImgDir = "../images/Icons/TemporaryImage.png";
                                        }
                                    ?>
                                    <div style="display: block; width: 100%;">
                                        <div style="display: flex;">
                                            <img class="order-product-img" src="<?php echo $productImgDir ?>" alt="<?php $productImgName ?>">
                                            <div class="details">
                                                <h2 style="text-align: left;"><?php echo $Name; ?></h2>
                                                <h3 style="text-align: left;">Qty: <?php echo $orderRow["Quantity"]?></h3s>
                                                <p style="text-align: left;">₱ <?php echo number_format((float)$orderRow["Price"], 2, '.', '') ?></p>
                                            </div>
                                        </div>
                                        <?php if ($orderRow["Request"] != NULL): ?>
                                            <div style="width: 100%;
                                                        background-color: white;
                                                        padding-top: 15px;
                                                        padding-inline: 25px;
                                                        border: 1px black solid;
                                                        border-radius: 10px;
                                                        align-item: justify;
                                                        word-wrap: break-word;">
                                                <p><strong>Request:</strong> <?php echo $orderRow["Request"] ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php $orderID = $orderRow["ID"] ?>
                                </div>
                            <?php
                                endwhile;
                                $orderResult->free();
                            ?>
                        <?php endif; ?>

                    </div>

                    <!-- Total -->
                    <div class="total">
                        <?php
                            $totalQty = 0;
                            $totalPrice = 0.00;
                            if ($conn && $payment_id != NULL) {
                        
                                $checkPayments = "SELECT * FROM payment WHERE ID = $payment_id";
                        
                                if($conn->query($checkPayments)->num_rows > 0) {
                        
                                    $paymentInfo = $conn->query($checkPayments)->fetch_assoc();
                                    $totalQty = $paymentInfo["Total_Quantity"];
                                    $totalPrice = $paymentInfo["Total_Payment"];
                                }
                            }
                        ?>
                        <span class="qualitative-summary" style="margin-top: 10px;">QUANTITY: <?php echo $totalQty ?>
                        </span>
                        <br>
                        <span class="qualitative-summary" style="margin-top: 10px;">TOTAL: ₱ <?php echo number_format((float)$totalPrice, 2, '.', '') ?>
                        </span>
                    </div>
                </div>
            </div>
            
        </div>
        
        <script src="../javascript/JQUERY.js"></script>
        <script src="../javascript/globaljavascript.js"></script>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                <?php if($totalQty == 0): ?>
                    const itemContainer = document.getElementById("item-container");
                    itemContainer.style.display = "flex";
                    itemContainer.style.justifyContent = "center";
                    itemContainer.style.alignItems = "center";
                    itemContainer.style.overflow = "hidden";

                    const emptyDrawing = document.createElement("img");
                    emptyDrawing.src = "../images/Icons/EmptyOrderIcon.png";
                    emptyDrawing.alt = "Your order is currently empty";
                    emptyDrawing.style.opacity = "0.22";
                    emptyDrawing.style.height = "50%";
                    emptyDrawing.style.scale = "0.2";
                    itemContainer.appendChild(emptyDrawing);
                <?php endif; ?>
            })

            document.getElementById("back-button").addEventListener("click", () => {
                window.location = "cashier_home.php";
            })
        </script>

    </body>
</html>