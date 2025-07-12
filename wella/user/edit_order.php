<?php
    session_start();

    require_once("../database/wb_db_connect.php");

    if (isset($_SESSION["Failed"])) {
        $failAlert = $_SESSION['Failed'];
        echo "<script>alert('$failAlert');</script>";
        unset($_SESSION["Failed"]);
    }

    if (isset($_POST["orderID"])) {
        $_SESSION["order_ID"] = $_POST["orderID"];
    }
    
    if (isset($_SESSION["order_ID"])) {
        $orderID = $_SESSION["order_ID"];
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
        <link href="../css/order_product_design.css" rel="stylesheet">
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
            <div id="order-container">
                <div id="img-container">
                    <img id="product-img">
                </div>
                <div id="form-container">
                    <img id="back-button" src="../images/Icons/BackIcon.png">

                    <h1 id="product-name">Item</h1>
                    <h4 id="product-price">₱ 0.00</h4>

                    <hr>

                    <form name="make-order" action="../database/edit_order.php" method="post">

                        <label for="order-qty">Quantity</label><br>
                        <div class="quantity-container">
                            <button type="button" onclick="updateQuantity('-')">-</button>
                            <input name="quantity" id="order-qty" type="number" value="1" min="1" autocomplete="on">
                            <button type="button" onclick="updateQuantity('+')">+</button>
                        </div>

                        <div id="req-more-info-p">
                            For a customizable order, input your request here. Note: Make sure your description is limited to the meal or drink ordered.
                            <div class="triangle-down"></div>
                        </div>

                        <label for="order-request" style="margin-top: 20px;">Order Request (Optional)</label>
                        <img id="req-dropdown" src="../images/Icons/moreArrorIcon.png">
                        <img id="req-more-info" src="../images/Icons/moreInfoButton.png">
                        <br>
                        <textarea name="request" id="order-request" maxlength="255" autocomplete="on"></textarea><br>

                        <hr>

                        <h5 id="total-display">Total: ₱ 0.00</h5>

                        <div id="buttons-container">
                            <button type="button" id="delete-button" onclick="deleteOrder(<?php echo $orderID ?>)">DELETE</button>
                            <input type="submit" name="submit-order" id="submit-order" value="UPDATE">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="../javascript/JQUERY.js"></script>
        <script src="../javascript/globaljavascript.js"></script>
        <script src="../javascript/expandnavtab.js"></script>

        <?php include "../reusable_php/confirmationpopup.php" ?>

        <?php include("../javascript/edit_order.php"); ?>    

    </body>
</html>