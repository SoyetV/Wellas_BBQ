<?php
    session_start();
    require_once("wb_db_connect.php");

    if (!isset($_SESSION["product_ID"])) {
        failedUpdate("Product ID is not set");
        exit();
    }

    if ($conn && isset($_SESSION["product_ID"]) && isset($_POST['product-submit'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $category = $_POST['type'];
        $dir = $_FILES['image']['name'];

        $id = $_SESSION["product_ID"];

        $checkProducts = "SELECT * FROM products WHERE ID = $id";

        $result = $conn->query($checkProducts);

        if($result->num_rows > 0) {
            $insertQuery = "UPDATE products
                            SET
                            Name = '$name', Price = $price, Type = '$category'
                            WHERE ID = $id";
            
            if ($conn->query($insertQuery) == TRUE) {

                if ($dir == "" && isset($_SESSION['resetImage'])) {
                    $imgInsertQuery = "UPDATE products
                                        SET
                                        Img_Dir = NULL
                                        WHERE ID = $id";
                    unset($_SESSION['resetImage']);
                } else if ($dir != "") {
                    $imgInsertQuery = "UPDATE products
                                        SET
                                        Img_Dir = '$dir'
                                        WHERE ID = $id";
                }

                if ($conn->query($imgInsertQuery) == TRUE) {
                    if ($dir != "") {
                        $target_file = "web_img/".$dir;
                        $uploading_file = $_FILES['image']['tmp_name'];
    
                        if (!move_uploaded_file($uploading_file, $target_file)) {
                            failedUpdate("Unable to upload image");
                            exit();
                        }
                    }
                } else {
                    failedUpdate("Unable to upload image");
                    exit();
                }
            } else {
                failedUpdate("Failed to update product details");
                exit();
            }
        } else {
            failedUpdate("Unable to find product ID in the database");
            exit();
        }
    }

    unset($_SESSION["product_ID"]);
    $_SESSION["Success"] = "Successfully updated product";
    header("Location: ../main_pages/menu.php");
    exit();

    function failedUpdate($msg) {
        unset($_SESSION["product_ID"]);
        $_SESSION["Failed"] = $msg;
        header("Location: ../admin/edit_product.php");
    }
?>