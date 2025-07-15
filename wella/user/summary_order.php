    <?php
    session_start();
    require_once("../database/wb_db_connect.php");
    unset($_SESSION["product_ID"]);

    $orderResult = null;
    $totalQty = 0;
    $totalPrice = 0.00;

    if ($conn && isset($_SESSION["user_id"])) {
        $user_id = $_SESSION["user_id"];

        $paymentQuery = "SELECT ID, Total_Quantity, Total_Payment FROM payment 
                        WHERE User_ID = $user_id 
                        AND (Remarks = 'Ongoing' OR (Remarks = 'Completed' AND Rating_Status = 'NotReceived'))
                        ORDER BY ID DESC LIMIT 1";

        $paymentResult = $conn->query($paymentQuery);

        if ($paymentResult && $paymentResult->num_rows > 0) {
            $paymentInfo = $paymentResult->fetch_assoc();
            $paymentID = $paymentInfo['ID'];
            $totalQty = $paymentInfo['Total_Quantity'];
            $totalPrice = $paymentInfo['Total_Payment'];

            $checkOrders = "SELECT * FROM orders 
                            WHERE User_ID = $user_id 
                            AND Payment_ID = $paymentID
                            AND Status IN ('Ongoing', 'Complete')
                            ORDER BY ID ASC";
            $orderResult = $conn->query($checkOrders);
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
        <link href="../css/order_design.css" rel="stylesheet" />

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
                    color: black;
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
                    filter: invert(1);
                    position: absolute;
                    transform: translate(0px, -43px);
                    width: 50px;
                    cursor: pointer;
                    z-index: 100;
                }
                #item-container {
                    height: fit-content;
                }
            </style>
    </head>
    <body>
        <video autoplay muted loop id="bgVideo">
            <source src="../smoky.mp4" type="video/mp4" />
        </video>
    <?php include "../reusable_php/navtab.php" ?>

    <div id="MainContent">
        <div class="container">
            <div id="order-review">

                <img id="back-button" src="../images/Icons/BackIcon.png" alt="Back" onclick="history.back()" />

                <div id="item-container">
                    <?php if ($orderResult && $orderResult->num_rows > 0): ?>
                        <?php while ($orderRow = $orderResult->fetch_assoc()): ?>
                            <?php
                                $productID = $orderRow["Item_ID"];
                                $productQuery = "SELECT * FROM products WHERE ID = $productID";
                                $productResult = $conn->query($productQuery);
                                $product = $productResult->fetch_assoc();

                                $Name = $product["Name"];
                                $productImg = !empty($product["Img_Dir"]) ? "../database/web_img/" . $product["Img_Dir"] : "../images/Icons/EmpEmptyOrderIcon.png";
                            ?>
                            <!-- Unified order box -->
                            <div class="order-item">
                                <img class="order-product-img" src="<?php echo $productImg; ?>" alt="<?php echo htmlspecialchars($Name); ?>" />
                                <div class="details">
                                    <h2><?php echo htmlspecialchars($Name); ?></h2>
                                    <h3>Qty: <?php echo $orderRow["Quantity"]; ?></h3>
                                    <p>₱ <?php echo number_format($orderRow["Price"], 2); ?></p>
                                </div>
                            </div>

                            <?php if (!empty($orderRow["Request"])): ?>
                                <div style="width: 100%;
                                            background-color: white;
                                            padding: 10px 25px;
                                            margin-bottom: 10px;
                                            border: 1px black solid;
                                            border-radius: 10px;
                                            word-wrap: break-word;">
                                    <p><strong>Request:</strong> <?php echo htmlspecialchars($orderRow["Request"]); ?></p>
                                </div>
                            <?php endif; ?>
                        <?php endwhile; ?>
                        <?php $orderResult->free(); ?>
                    <?php else: ?>
                        <p>No current orders found.</p>
                    <?php endif; ?>
                </div>

                <div class="total">
                    <span style="margin-top: 10px; color: white;">QUANTITY: <?php echo $totalQty; ?></span><br />
                    <span style="margin-top: 10px; color: white;">TOTAL: ₱ <?php echo number_format($totalPrice, 2); ?></span>
                </div>
            </div>
        </div>
    </div>

    <script src="../javascript/JQUERY.js"></script>
    <script src="../javascript/globaljavascript.js"></script>
    <script src="../javascript/expandnavtab.js"></script>

    <?php include "../reusable_php/confirmationpopup.php"; ?>
    <?php include "../javascript/summary_order.php"; ?>

    <?php
    if (!$conn) {
        callError('CONNECTION UNSTABLE', 'Your internet connection is currently unstable, try again later');
        echo "<script>document.getElementById('confirmation-title').style.fontSize = '35px';</script>";
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
            document.getElementById('confirmation-title').innerHTML = '$title';
            document.getElementById('confirmation-text').innerHTML = '$text';
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
