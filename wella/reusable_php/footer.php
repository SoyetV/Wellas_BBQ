<link href="../css/footer_design.css" rel="stylesheet">

<footer>
    <div class="footerContainer">
        <div class="logoFooter">
            <img src="../images/Icons/Logo.png">
        </div>
        <div class="blockContainer socialMedias">
            <h3>FOLLOW US</h3>
            <br>
            <div class="socialMedias-icons">
                <a href="https://www.facebook.com/WellasBarbecueSaLarsianPoblacionTalisay"><img src="../images/Icons/Facebook.png"></a>
            </div>
        </div>
        <div class="blockContainer quickSites">
            <h3>QUICKSITES</h3>
            <br>
            <nav>
                <a href="../main_pages/about.php">ABOUT</a><br><br>
                <a href="../main_pages/menu.php">MENU</a><br><br>
                <a href="../main_pages/career.php">CAREER</a>
            </nav>
        </div>
        <?php if(isset($_SESSION['email'])): ?>
            <a href="../main_pages/menu.php" class="order-button-a"><button class="order-button">ORDER NOW</button></a>
        <?php else: ?>
            <a href="../main_pages/log_in.php" class="order-button-a"><button class="order-button">SIGN IN</button></a>
        <?php endif ?>
    </div>
    
</footer>