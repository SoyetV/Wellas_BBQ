<?php
    require_once("../database/wb_db_connect.php");

    $foundProducts = false;

    if ($conn && isset($_POST["productID"])) {
        $_SESSION["product_ID"] = $_POST["productID"];
    }
    
    if (isset($_SESSION["product_ID"])) {

        $productID = $_SESSION["product_ID"];

        $checkProducts = "SELECT * FROM products WHERE ID = $productID";
    
        $result = $conn->query($checkProducts);
    
        if ($result->num_rows > 0) {
            $curProduct = $result->fetch_assoc();
    
            $productName = $curProduct['Name'];
            $productPrice = $curProduct['Price'];
            $productImg = $curProduct['Img_Dir'];
            $productImgDir = "../database/web_img/".$productImg;

            $foundProducts = true;
        }
    }
?>

<script>
    $("#req-more-info-p").hide(0);

    document.addEventListener("DOMContentLoaded", () => {
        <?php if($foundProducts): ?>
            const productName = document.getElementById("product-name");
            productName.textContent = "<?php echo $productName; ?>";

            const productPrice = document.getElementById("product-price");
            productPrice.textContent = "₱ <?php echo number_format((float)$productPrice, 2, '.', '') ?>";

            const productQty = document.getElementById("order-qty");
            const totalDisplay = document.getElementById("total-display");
            const product_price = <?php echo number_format((float)$productPrice, 2, '.', ''); ?>;
            totalDisplay.innerHTML = `Total: ₱ ${parseFloat(productQty.value * product_price).toFixed(2)}`;

            const productImg = document.getElementById("product-img");
            <?php if($productImg != null): ?>
                productImg.src = "<?php echo $productImgDir; ?>";
            <?php else: ?>
                productImg.src = "../images/Icons/TemporaryImage.png";
            <?php endif; ?>
        <?php endif; ?>
    })

    $(document).ready(function() {
        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
    });

    document.getElementById("order-qty").addEventListener("change", () => {
        const productQty = document.getElementById("order-qty");
        if (productQty.value < 1) {
            productQty.value = 1;
        }
        
        const totalDisplay = document.getElementById("total-display");
        const productPrice = <?php echo number_format((float)$productPrice, 2, '.', ''); ?>;
        totalDisplay.innerHTML = `Total: ₱ ${parseFloat(productQty.value * productPrice).toFixed(2)}`;
    })

    function updateQuantity(operator) {
        const productQty = document.getElementById("order-qty");
        switch (operator) {
            case '-':
                if (productQty.value > 1) {
                    productQty.value--;
                }
                break;
            case '+':
                productQty.value++;
                break;
        }

        const totalDisplay = document.getElementById("total-display");
        const productPrice = <?php echo number_format((float)$productPrice, 2, '.', ''); ?>;
        totalDisplay.innerHTML = `Total: ₱ ${parseFloat(productQty.value * productPrice).toFixed(2)}`;
    }

    var dropped = true;

    document.getElementById("req-dropdown").addEventListener("click", () => {
        const descDropdown = document.getElementById("req-dropdown");
        const descTextArea = document.getElementById("order-request");
        if (dropped) {
            dropped = false;
            descDropdown.style.rotate = "-90deg";
            descTextArea.style.height = "0px";
        } else {
            dropped = true;
            descDropdown.style.rotate = "0deg";
            descTextArea.style.height = "200px";
        }
    })

    let timer;
    let showInstruction = false;
    $('#req-more-info-p').hide(0);

    document.getElementById("req-more-info").addEventListener("click", () => {
        if (timer) {
            clearInterval(timer);
        }
        showDescInstruction();
    })

    function showDescInstruction() {
        if (!showInstruction) {
            showInstruction = true;
            $('#req-more-info-p').show(0);
            timer = setInterval(showDescInstruction, 5000);
        } else {
            showInstruction = false;
            $('#req-more-info-p').fadeOut("fast", "swing");
        }
    }

    document.getElementById("back-button").addEventListener("click", () => {
        window.location = "../main_pages/menu.php";
    })

</script>