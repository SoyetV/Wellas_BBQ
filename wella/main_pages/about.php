<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="ico" href="../wb.ico">
        <link href="../bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/Global_Design.css" rel="stylesheet">
        <link href="../css/about_design.css" rel="stylesheet">
        <title>Wella's BBQ</title>
        <style>
      
        #bgVideo {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            object-fit: cover;
            z-index: -1;
            filter: brightness(0.4);
        }
    </style>
    </head>
    <body>
  
    <video autoplay muted loop id="bgVideo">
        <source src="../smoky.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

        <!-- Navbar -->
        <?php include "../reusable_php/navtab.php" ?>

        <!-- Main Content -->
        <main id="MainContent">
            <h1>ABOUT US</h1>
            <h2>Wella's BBQ</h2>
            <div class="content">
                <div>
                    <img src="..\images\Photos\WellasTeam.png" height="600px" width= "600px">
                </div>
                <div class="about-about">
                    <p> 
                        Grilling since 1989, turning every meal into a taste to remember.
                    </p><br>
                    <p>
                        Come and dine with us and enjoy every bite you share with your friends and family, paired with our flavorful barbecue sauce and warm service.
                    </p><br>
                    <p>
                        The proud staff of Wella's BBQ welcomes you with warmth and gratitude when you do visit anytime.
                    </p>
                </div>
                <div>
                    <button id="Order-About" onclick="locatePage('menu.php')">Order Now!</button>
                </div>
                <div>
                    <button id="Career-About" onclick="locatePage('career.php')">Career</button>
                </div>
            </div>
            
            <!-- Footer -->
            <?php include "../reusable_php/footer.php" ?>
        </main>

        <script src="../javascript/JQUERY.js"></script>
        <script src="../javascript/globaljavascript.js"></script>
        <script src="../javascript/expandnavtab.js"></script>
    </body>
</html>