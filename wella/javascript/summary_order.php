<?php
    $orderRows = 0;

    if ($conn && isset($_SESSION["user_id"])) {
        $user_id = $_SESSION["user_id"];
        $checkOrders = "SELECT * FROM orders WHERE User_ID = $user_id AND Status IN ('Ongoing', 'Complete')";
        $orderResult = $conn->query($checkOrders);
        $orderRows = $orderResult->num_rows;
    }
?>

<script>

document.addEventListener("DOMContentLoaded", () => {
    <?php if ($totalPrice > 0.00 && $orderRows > 0.00): ?>
        const submitStyle = document.createElement("style");
        const updateCancelButtonStyle = `\n#place-order {background-color: red; border-color: red; cursor: pointer;}
                                        #cancel-order {filter: grayscale(1) brightness(1); cursor: pointer;}
                                        @media all and (min-width: 1200px) {
                                            #place-order:hover {background-color: white; color: red}
                                            #cancel-order:hover {filter: brightness(1) invert(0);}
                                        }
                                        @media all and (max-width: 1200px) {
                                            #place-order:active {background-color: white; color: red}
                                            #cancel-order:active {filter: brightness(1) invert(0);}
                                        }\n`;
        submitStyle.innerHTML = updateCancelButtonStyle;
        document.head.appendChild(submitStyle);
    <?php else: ?>
        const itemContainer = document.getElementById("item-container");
        itemContainer.style.display = "flex";
        itemContainer.style.justifyContent = "center";
        itemContainer.style.alignItems = "center";
        itemContainer.style.overflow = "hidden";

        const emptyDrawing = document.createElement("img");
        emptyDrawing.src = "../images/Icons/EmptyOrderIcon.png";
        emptyDrawing.alt = "Your order is currently empty";
        emptyDrawing.style.opacity = "0.22";
        emptyDrawing.style.height = "50%";
        emptyDrawing.style.scale = "0.2";
        itemContainer.appendChild(emptyDrawing);
    <?php endif; ?>
})

document.getElementById("back-button").addEventListener("click", () => {
    window.location = "placed_order.php";
})

</script>