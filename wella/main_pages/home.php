<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Wella's BBQ</title>
    <link rel="icon" type="image/x-icon" href="../wb.ico"/>
    <link href="../bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../css/Global_Design.css" rel="stylesheet" />
    <link href="../css/home_design.css" rel="stylesheet" />

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

        .homeContainer {
            position: relative;
            z-index: 1;
            padding: 48px 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .about-section {
            margin-left: -20px;
        }

        @media all and (max-width: 1200px) {
            .about-section {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

  
    <video autoplay muted loop id="bgVideo">
        <source src="../smoky.mp4" type="video/mp4" />
    </video>

    <!-- Navbar -->
    <?php include "../reusable_php/navtab.php"; ?>

    <!-- Main Content -->
    <div id="MainContent">
        <div class="homeContainer">

            <?php if (isset($_SESSION['fname'])): ?>
                <div class="welcomeHeader">
                    <h1>WELCOME</h1><br>
                    <h2><?php echo htmlspecialchars($_SESSION['fname']); ?></h2>
                    <button onclick="locatePage('../main_pages/menu.php')">ORDER NOW</button>
                </div>
            <?php endif; ?>

            <div class="content" style="margin-bottom: 50px;">
                <div class="empty-box"></div>
                <div class="text-box">
                    <h1>Wella's</h1>
                    <h2>BBQ</h2>
                    <p>GRILLING SINCE 1989, TURNING EVERY MEAL INTO A TASTE TO REMEMBER</p>
                    <button onclick="locatePage('../main_pages/menu.php')">EXPLORE MENU</button>
                </div>
            </div>

            <!-- About section can be added here -->

        </div>
    </div>

    <!-- Footer -->
    <?php include "../reusable_php/footer.php"; ?>

    <!-- Scripts -->
    <script src="../javascript/JQUERY.js"></script>
    <script src="../javascript/globaljavascript.js"></script>
    <script src="../javascript/expandnavtab.js"></script>

</body>
</html>
