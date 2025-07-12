<?php
    session_start();
    require_once("../database/wb_db_connect.php");

    unset($_SESSION["product_ID"]);

    $noPendingOrders = true;

    $branchStreet = "STREET";
    $branchBarangay = "BARANGAY";
    $branchCity = "CITY";

    if ($conn) {

        if (isset($_SESSION["user_id"])) {
            $user_id = $_SESSION["user_id"];
            $orderResult = NULL;

            $checkOrders = "SELECT * FROM orders WHERE User_ID = $user_id AND Status = 'Ongoing'";

            $result = $conn->query($checkOrders);

            if($result->num_rows == 0) {

                $noPendingOrders = false;

                $checkOrders = "SELECT * FROM orders WHERE User_ID = $user_id AND Status = 'Pending'";
                $orderResult = $conn->query($checkOrders);

            } else {
                header("location: ../user/placed_order.php");
            }
        }
        
        $searchAddress = "SELECT * FROM address WHERE ID = 1";

        if($conn->query($searchAddress)->num_rows > 0) {

            $address = $conn->query($searchAddress)->fetch_assoc();

            $branchStreet = $address["Street"];
            $branchBarangay = $address["Barangay"];
            $branchCity = $address["City"];

        }
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
        </style>
    </head>
    <body>
        <video autoplay muted loop id="bgVideo">
            <source src="../smoky.mp4" type="video/mp4" />
        </video>
        <!-- Navbar -->
        <?php include "../reusable_php/navtab.php" ?>

        <!-- Main Content -->
        <div id="MainContent">
            <div class="container">
                <form name="order-review" action="../database/create_payment.php" method="post" id="order-review">

                    <div class="pickup-order">
                        <h1>PICK UP ORDER</h1>
                        <p style="font-weight: 800">STORE ADDRESS:</p>
                        <p style="font-weight: 500; font-size: 25px; margin-top: 25px"><?php echo strtoupper($branchStreet) ?></p>
                        <p style="font-weight: 500"><?php echo strtoupper($branchBarangay) ?>, <?php echo strtoupper($branchCity) ?></p>
                    </div>

                    <div id="item-container">

                        <?php if(!$noPendingOrders): ?>
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
                                    <img class="order-product-img" src="<?php echo $productImgDir ?>" alt="<?php $productImgName ?>">
                                
                                    <div class="details">
                                        <h2><?php echo $Name; ?></h2>
                                        <h3>Qty: <?php echo $orderRow["Quantity"]?></h3>
                                        <p>₱ <?php echo number_format((float)$orderRow["Price"], 2, '.', '') ?></p>
                                    </div>
                                    <?php $orderID = $orderRow["ID"] ?>
                                    <button type="button" id="editButton" onclick="openEditor(<?php echo $orderID ?>)">EDIT</button>
                                </div>
                            <?php
                                endwhile;
                                $orderResult->free();
                            ?>
                        <?php endif; ?>

                    </div>

                    <!-- Total and Place Order Button -->
                    <div class="total">
                        <span>TOTAL:
                        <?php
                            $totalPrice = 0.00;
                            if (!$noPendingOrders) {
                                $user_id = $_SESSION["user_id"];
                                $checkOrders = "SELECT * FROM orders WHERE User_ID = $user_id AND Status = 'Pending'";
                                $orderResult = $conn->query($checkOrders);
                                while($priceRow = $orderResult->fetch_assoc()) {
                                    $totalPrice += $priceRow["Price"];
                                }
                                $orderResult->free();
                            }
                        ?>
                        ₱ <?php echo number_format((float)$totalPrice, 2, '.', '') ?></span>
                    </div>
                    <div class="order-button-container">
                        <img src="../images/Icons/TrashCanIcon.png" alt="removeOrder" title="Cancel Order" id="cancel-order">
                        <input type="submit" name="place-order" id="place-order" value="PLACE ORDER"></input>
                    </div>
                </form>
            </div>
            
        </div>
        
        <script src="../javascript/JQUERY.js"></script>
        <script src="../javascript/globaljavascript.js"></script>
        <script src="../javascript/expandnavtab.js"></script>

        <?php include "../reusable_php/confirmationpopup.php" ?>

        <?php include "../javascript/review_order.php" ?>

        <?php
            if (!$conn) {
                callError('CONNECTION UNSTABLE', 'Your internet connection is currently unstable, try again later');
                echo "<script>
                    document.getElementById('confirmation-title').style.fontSize = '35px';
                </script>";
            }
            else if (isset($_SESSION["Success"])) {
                callSuccess($_SESSION['Success']);
                unset($_SESSION["Success"]);
            }
            else if (isset($_SESSION["Failed"])) {
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