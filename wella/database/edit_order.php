<?php
    session_start();
    require_once("wb_db_connect.php");

    if ($conn && isset($_POST['submit-order'])) {
        $qty = $_POST["quantity"];
        $request = $_POST["request"];

        if ($request == "") {
            $request = NULL;
        }

        if (isset($_SESSION["user_id"]) && isset($_SESSION["product_ID"])) {
            $user_ID = $_SESSION["user_id"];
            $product_ID = $_SESSION["product_ID"];
            
            $checkOrders = "SELECT * FROM orders WHERE User_ID = $user_ID AND Item_ID = $product_ID AND Status = 'Pending'";
            $orderResult = $conn->query($checkOrders);

            $checkProducts = "SELECT * FROM products WHERE ID = $product_ID";
            $productResult = $conn->query($checkProducts);

            if ($productResult->num_rows > 0) {

                $product_price = $productResult->fetch_assoc()["Price"];
                $newPrice = $qty * $product_price;

                if ($orderResult->num_rows > 0) {

                    $order_id = $orderResult->fetch_assoc()["ID"];

                    $insertQty = "UPDATE orders
                                SET
                                Quantity = $qty, Price = $newPrice, Request = '$request'
                                WHERE ID = $order_id";
                    
                    if ($conn->query($insertQty) == FALSE) {
                        failedUpdate("Unable to update order");
                        exit();
                    }
    
                } else {
                    failedUpdate("Order not found");
                    exit();
                }
            } else {
                failedUpdate("Product not found");
                exit();
            }
        } else {
            failedUpdate("User ID and product ID are unset");
            exit();
        }
    } else {
        failedUpdate("Unable to connect to the database");
        exit();
    }

    unset($_SESSION["product_ID"]);
    $_SESSION["Success"] = "Item updated";
    header("Location: ../main_pages/order.php");
    exit();

    function failedUpdate($msg) {
        $_SESSION["Failed"] = $msg;
        header("Location: ../user/edit_order.php");
    }
?>