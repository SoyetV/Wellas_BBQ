<?php 
    session_start();

    if (isset($_SESSION['Failed'])) {
        $failed_upload = htmlspecialchars($_SESSION['Failed']);
        echo "<script>alert('$failed_upload');</script>";
        unset($_SESSION["Failed"]);
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
        <link href="../css/edit_product.css" rel="stylesheet">
    </head>
    <body>
        <!-- Navbar -->
        <?php include "../reusable_php/navtab.php" ?>

        <!-- Main Content -->
        <div id="MainContent">
            <div class="product-edit-container">
                <div class="product-edit-box" style="margin-top: 25px">
                    <h1>EDIT MEAL</h1>
                    <img id="back-button" src="../images/Icons/BackIcon.png">
                    <div class="form-container">
                        <form name="product-edit-form" action="../database/update_product.php" method="post" enctype="multipart/form-data">

                            <div class="product-image-container">
                                <img src="../images/Icons/AddImage.png" alt="" id="product-image">
                                <input name="image" id="product-input-image" type="file" accept=".png, .jpg, .jpeg">
                            </div><br>

                            <button id="resetImageButton" type="button" onclick="resetImage()">Reset Image</button><br><br>
                            
                            <label for="product-input-name">Name</label><br>
                            <input name="name" id="product-input-name" type="text" autocomplete="on">

                            <div class="product-div">

                                <div class="name-div-part">
                                    <label for="product-input-price">Price (₱)</label><br>
                                    <input name="price" id="product-input-price" type="number" value = 0 step="0.01" min="0" autocomplete="on">
                                </div>
                                
                                <div class="name-div-part">
                                    <label for="product-input-category">Category</label><br>
                                    <select name="type" id="product-input-category" required>
                                         <option value="">-- Select Category --</option>
                                            <option value="Pork">Pork</option>
                                            <option value="Chicken">Chicken</option>
                                            <option value="Others">Others</option>
                                    </select>
                                </div>
                                
                            </div>

                            <input type="submit" name="product-submit" value="Update Meal">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="../javascript/JQUERY.js"></script>
        <script src="../javascript/globaljavascript.js"></script>
        <script src="../javascript/edit_product.js"></script>
        <script src="../javascript/expandnavtab.js"></script>

        <?php
            require_once("../database/wb_db_connect.php");

            if (isset($_POST["productID"])) {
                $_SESSION["product_ID"] = $_POST["productID"];
            }

            if (isset($_SESSION["product_ID"])) {
                $productID = $_SESSION["product_ID"];
        
                $checkProducts = "SELECT * FROM products WHERE ID = $productID";
            
                $result = $conn->query($checkProducts);

                $foundProducts = false;
            
                if ($result->num_rows > 0) {
                    $curProduct = $result->fetch_assoc();
            
                    $productName = $curProduct['Name'];
                    $productPrice = $curProduct['Price'];
                    $productType = $curProduct['Type'];
                    $productImg = $curProduct['Img_Dir'];
                    $productImgDir = "../database/web_img/".$productImg;

                    $foundProducts = true;
                }
            }
            
            unset($_SESSION['Failed']);
        ?>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                loadProductData();
            })

            function loadProductData() {
                <?php if(isset($_SESSION["product_ID"]) && $foundProducts == true): ?>
                    <?php if($productImg != ""): ?>
                        document.getElementById("product-image").src = "<?php echo htmlspecialchars($productImgDir); ?>";
                        document.getElementById("product-image").alt = "<?php echo htmlspecialchars($productImg); ?>";
                    <?php endif; ?> 
                    document.getElementById("product-input-image").value = "";
                    document.getElementById("product-input-name").value = "<?php echo htmlspecialchars($productName); ?>";
                    document.getElementById("product-input-price").value = <?php echo $productPrice; ?>;
                    document.getElementById("product-input-category").value = "<?php echo htmlspecialchars($productType); ?>";
                <?php endif; ?> 
            }

            function resetImage() {
                document.getElementById("product-image").src = "../images/Icons/AddImage.png";
                document.getElementById("product-image").alt = "";
                document.getElementById("product-input-image").value = "";

                <?php $_SESSION['resetImage'] = true ?>
            }
        </script>

    </body>
</html>