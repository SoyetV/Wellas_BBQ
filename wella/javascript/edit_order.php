<?php
    $foundProducts = false;

    if ($conn && isset($orderID)) {
        $checkOrders = "SELECT * FROM orders WHERE ID = $orderID AND Status = 'Pending'";
        $orderResult = $conn->query($checkOrders);
        
        if ($orderResult->num_rows > 0) {
            $curOrder = $orderResult->fetch_assoc();

            $quantity = $curOrder["Quantity"];
            $requestText = $curOrder["Request"];
            $totalPrice = $curOrder["Price"];

            $productID = $curOrder["Item_ID"];
            
            $_SESSION["product_ID"] = $productID;
            
            $checkProducts = "SELECT * FROM products WHERE ID = $productID";
            $productResults = $conn->query($checkProducts);
        
            if ($productResults->num_rows > 0) {
                $curProduct = $productResults->fetch_assoc();
                $productName = $curProduct['Name'];
                $productPrice = $curProduct['Price'];
                $productImg = $curProduct['Img_Dir'];
                $productImgDir = "../database/web_img/".$productImg;

                $foundProducts = true;
            }
        }
    }
?>

<script>

    var dropped = true;

    document.addEventListener("DOMContentLoaded", () => {
        <?php if($foundProducts): ?>
            const productName = document.getElementById("product-name");
            productName.textContent = "<?php echo $productName; ?>";

            const productPrice = document.getElementById("product-price");
            productPrice.textContent = "₱ <?php echo number_format((float)$productPrice, 2, '.', '') ?>";

            const quantityInput = document.getElementById("order-qty");
            quantityInput.value = "<?php echo $quantity ?>";

            const requestInput = document.getElementById("order-request");
            requestInput.value = "<?php echo $requestText; ?>";

            <?php if($requestText != ""): ?>
                const descDropdown = document.getElementById("req-dropdown");
                const descTextArea = document.getElementById("order-request");
                descDropdown.style.rotate = "0deg";
                descTextArea.style.height = "200px";
                dropped = !dropped;
            <?php endif; ?>

            const totalDisplay = document.getElementById("total-display");
            const total_price = <?php echo number_format((float)$totalPrice, 2, '.', ''); ?>;
            totalDisplay.innerHTML = `Total: ₱ ${parseFloat(total_price).toFixed(2)}`;

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
        
        <?php if($foundProducts): ?>
            const totalDisplay = document.getElementById("total-display");
            const productPrice = <?php echo number_format((float)$productPrice, 2, '.', ''); ?>;
            totalDisplay.innerHTML = `Total: ₱ ${parseFloat(productQty.value * productPrice).toFixed(2)}`;
        <?php endif; ?>
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

        <?php if($foundProducts): ?>
            const totalDisplay = document.getElementById("total-display");
            const productPrice = <?php echo number_format((float)$productPrice, 2, '.', ''); ?>;
            totalDisplay.innerHTML = `Total: ₱ ${parseFloat(productQty.value * productPrice).toFixed(2)}`;
        <?php endif; ?>
    }

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
        window.location = "../main_pages/order.php";
    })

    function deleteOrder(order_id) {
        $("#confirmBox").show(0);

        document.getElementById("confirmation-text").innerHTML = `Do you want to delete this order?`;
        document.getElementById("confirmation-confirm-button").onclick = function() {removeItem(order_id)};
    }

    function removeItem(order_id) {
        $("#confirmBox").hide(0);
        const fetch_dir = "../database/delete_order.php";
        fetch(fetch_dir, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `deleteOrder=${order_id}`
            })
            .then(() => {
                window.location = fetch_dir;
            })
            .catch(error => {
                console.error(error);
                alert("Error: Unale to remove order");
            });
    }

</script>