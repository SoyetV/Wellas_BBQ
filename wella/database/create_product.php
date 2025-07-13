<?php
    session_start();
    require_once("wb_db_connect.php");

    if ($conn && isset($_POST['product-submit'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $category = $_POST['type'];
        $dir = $_FILES['image']['name'];

        if ($dir == "") {
            $insertQuery = "INSERT INTO products (Name, Price, Type, Img_Dir)
                            VALUES ('$name', $price, '$category', NULL)";
        } else {
            $insertQuery = "INSERT INTO products (Name, Price, Type, Img_Dir)
                            VALUES ('$name', $price, '$category', '$dir')";
        }

        if ($conn->query($insertQuery) == FALSE) {
            $_SESSION["Failed"] = "Unable to add product to database";
            header("Location: ../admin/create_product.php");
            exit();
        }

        if ($dir != "") {
            $target_file = "web_img/".$dir;
            $uploading_file = $_FILES['image']['tmp_name'];

            if (!move_uploaded_file($uploading_file, $target_file)) {
                failedUpdate("Unable to upload image");
                exit();
            }
        }
    }

    $_SESSION["Success"] = "Successfully updated the meal!";
    header("Location: ../main_pages/menu.php");
    exit();
?>