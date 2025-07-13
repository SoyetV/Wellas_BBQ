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
        <link href="../css/create_product.css" rel="stylesheet">
        
    </head>
    <body>
        <!-- Navbar -->
        <?php include "../reusable_php/navtab.php" ?>

        <!-- Main Content -->
        <div id="MainContent">
            <div class="product-create-container">
                <div class="product-create-box" style="margin-top: 25px">
                    <h1>CREATE MEAL</h1>
                    <img id="back-button" src="../images/Icons/BackIcon.png">
                    <div class="form-container">
                        <form name="product-create-form" action="../database/create_product.php" method="post" enctype="multipart/form-data">
                            
                            <div class="product-image-container">
                                <img src="../images/Icons/AddImage.png" alt="" id="product-image">
                                <input name="image" id="product-input-image" type="file" accept=".png, .jpg, .jpeg" value="">
                            </div><br>

                            <button id="resetImageButton" type="button" onclick="resetImage()">Reset Image</button><br><br>
                            
                            <label for="product-input-name">Name</label><br>
                            <input name="name" id="product-input-name" type="text" autocomplete="on">

                            <div class="product-div">

                                <div class="name-div-part">
                                    <label for="product-input-price">Price (₱)</label><br>
                                    <input name="price" id="product-input-price" type="number" step="0.01" min="0" autocomplete="on">
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

                            <input type="submit" name="product-submit" value="Create New Meal">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="../javascript/JQUERY.js"></script>
        <script src="../javascript/globaljavascript.js"></script>
        <script src="../javascript/edit_product.js"></script>
        <script src="../javascript/expandnavtab.js"></script>

        <script>
            function resetImage() {
                document.getElementById("product-image").src = "../images/Icons/AddImage.png";
                document.getElementById("product-image").alt = "";
                document.getElementById("product-input-image").value = "";
            }
        </script>
    </body>
</html>