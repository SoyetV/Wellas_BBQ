<script src="../javascript/JQUERY.js"></script>

<?php
    session_start();
    require_once("wb_db_connect.php");

    if ($conn) {
        if (isset($_POST["deleteProductID"])) {
            
            $id = $_POST["deleteProductID"];

            $deleteRow = "DELETE FROM products WHERE ID = $id";

            if ($conn->query($deleteRow) == FALSE) {
                $_SESSION["Failed"] = "Product not found";
                header("Location: ../main_pages/menu.php");
                exit();
            }
        }
    } else {
        $_SESSION["Failed"] = "Unable to connect to the database";
        header("Location: ../main_pages/menu.php");
        exit();
    }

    $_SESSION["Success"] = "Item successfully removed";
    header("Location: ../main_pages/menu.php");
    exit();
?>