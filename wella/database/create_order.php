<?php
    session_start();
    require_once("wb_db_connect.php");

    if ($conn && isset($_POST['submit-order'])) {
        $qty = $_POST["quantity"];
        $request = $_POST["request"];

        if (isset($_SESSION["user_id"]) && isset($_SESSION["product_ID"])) {
            $user_ID = $_SESSION["user_id"];
            $product_ID = $_SESSION["product_ID"];

            $ongoingOrders = "SELECT * FROM orders WHERE User_ID = $user_ID AND Status = 'Ongoing'";
            $ongoingOrdersResult = $conn->query($ongoingOrders);

            if ($ongoingOrdersResult->num_rows == 0) {

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
                                    Quantity = Quantity + $qty, Price = Price + $newPrice
                                    WHERE ID = $order_id";
                        
                        if ($conn->query($insertQty) == FALSE) {
                            failedUpdate("Unable to update order");
                            exit();
                        }
        
                        // Updates the request if the new request is not empty
                        if ($request != "") {
                            
                            $insertRequest = "UPDATE orders
                                            SET
                                            Request = '$request'
                                            WHERE ID = $order_id";
                            
                            if ($conn->query($insertRequest) == FALSE) {
                                failedUpdate("Unable to update request");
                                exit();
                            }
                        }
        
                    } else {
        
                        // Inserts a new order
                        $insertQuery = "INSERT INTO orders (User_ID, Item_ID, Quantity, Price, Request, OrderMadeDate)
                                        VALUES ($user_ID, $product_ID, $qty, $newPrice, '$request', CURDATE())";
                
                        if ($conn->query($insertQuery) == FALSE) {
                            failedUpdate("Unable to make order");
                            exit();
                        }
                    }
                } else {
                    failedUpdate("Product not found");
                    exit();
                }
            
            } else {
                unset($_SESSION["product_ID"]);
                $_SESSION["Failed"] = "You currently have an order placed";
                header("Location: ../main_pages/menu.php");
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
    $_SESSION["Success"] = "Item is added to your order";
    header("Location: ../main_pages/menu.php");
    exit();

    function failedUpdate($msg) {
        $_SESSION["Failed"] = $msg;
        header("Location: ../user/order_product.php");
    }
?>