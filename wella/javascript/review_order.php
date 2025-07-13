<?php
    $orderRows = 0;

    if ($conn && isset($_SESSION["user_id"])) {
        $user_id = $_SESSION["user_id"];
        $checkOrders = "SELECT * FROM orders WHERE User_ID = $user_id AND Status = 'Pending'";
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

        const emptyDrawing = document.createElement("img");
        emptyDrawing.src = "../images/Icons/EmptyOrderIcon.png";
        emptyDrawing.alt = "Your order is currently empty";
        emptyDrawing.style.opacity = "0.22";
        emptyDrawing.style.height = "50%";
        itemContainer.appendChild(emptyDrawing);
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

function openEditor(order_id) {
        const dir = "../user/edit_order.php";
        fetch(dir, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `orderID=${order_id}`
            })
            .then(() => {
                window.location = dir;
            })
            .catch(error => {
                console.error(error);
                alert("Error: Unable to order product");
            });
}

document.getElementById("cancel-order").addEventListener("click", () => {
    <?php if ($totalPrice > 0.00 && $orderRows > 0.00): ?>
        $("#confirmBox").show(0);
        document.getElementById("confirmation-text").innerHTML = `Are you sure you want to cancel your order?`;
        document.getElementById("confirmation-confirm-button").onclick = function() {window.location = "../database/cancel_user_orders.php";};
    <?php endif; ?>
})

const placeOrder = document.forms["order-review"];
placeOrder.onsubmit = function(event) {
    <?php if ($totalPrice <= 0.00 || $orderRows <= 0.00): ?>
        event.preventDefault();
    <?php endif; ?>
}

</script>