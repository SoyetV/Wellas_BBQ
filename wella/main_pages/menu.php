<?php
    session_start();
    unset($_SESSION["product_ID"]);

    require_once("../database/wb_db_connect.php");
    require_once("../database/products_update_json.php");
    if ($conn) {
        CreateProductsJSONData("Pork", "../database/data/pork.json", $conn);
        CreateProductsJSONData("Chicken", "../database/data/chicken.json", $conn);
        CreateProductsJSONData("Others", "../database/data/others.json", $conn);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wella's BBQ</title>
    <link rel="icon" type="image/x-icon" href="../wb.ico">
    <link href="../bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/Global_Design.css" rel="stylesheet">
    <link href="../css/menu_design.css" rel="stylesheet">

    <style>
        /* Video Background */
        #backgroundVideo {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        .menuContainer {
            position: relative;
            z-index: 1;
            padding-top: 20px;
        }

        .container_menu {
            border-radius: 10px;
            padding: 20px;
        }

        .main-content {
            height: auto;
        }

        #confirm-container {
            z-index: 999;
            position: fixed;
            background-color: white;
            justify-content: center;
        }
    </style>
</head>
<body>
  
    <video autoplay muted loop id="backgroundVideo">
        <source src="../smoky.mp4" type="video/mp4">
    </video>

    
    <?php include "../reusable_php/navtab.php" ?>

    
    <div id="MainContent" class="menuContainer">
        <div class="container_menu">
            <!-- Sidebar -->
            <?php include "../reusable_php/menusidebar.php" ?>

            <!-- Add Button (Admin Only) -->
            <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'Admin'): ?>
                <img src="../images/Icons/AddNewIcon.png" id="addNewButton">
            <?php endif; ?>

            <!-- Menu Items -->
            <div class="main-content">
                <div id="itemContainer"></div>
            </div>
        </div>

        <!-- Footer -->
        <?php include "../reusable_php/footer.php" ?>
    </div>


    <script src="../javascript/JQUERY.js"></script>
    <script src="../javascript/globaljavascript.js"></script>
    <script src="../javascript/expandnavtab.js"></script>    
    <?php include "../reusable_php/confirmationpopup.php" ?>
    <?php include "../javascript/menu_functions.php" ?>


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
                document.getElementById('error-title').innerHTML = '$title';
                document.getElementById('error-text').innerHTML = '$text';
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
