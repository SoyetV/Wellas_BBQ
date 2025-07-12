<?php
require_once("../database/wb_db_connect.php");

$paymentArr = NULL;
if ($conn) {
    $checkPayments = "SELECT * FROM payment WHERE Remarks != 'Cancelled'";
    $paymentArr = $conn->query($checkPayments);
}
?>

<?php if ($paymentArr && $paymentArr->num_rows > 0): ?>
    <?php while ($paymentOrder = $paymentArr->fetch_assoc()): ?>
        <?php 
            $payment_id = $paymentOrder["ID"];
            $remarks = $paymentOrder["Remarks"];
            $rating_status = $paymentOrder["Rating_Status"];


            $orderCheckQuery = "SELECT COUNT(*) AS total FROM orders WHERE Payment_ID = $payment_id AND Status = 'Complete'";
            $orderStatus = $conn->query($orderCheckQuery)->fetch_assoc()["total"];
        ?>

        <?php if ($remarks === 'Ongoing'): ?>
            <!-- Ongoing Orders -->
            <div class="payment_container">
                <h1 class="payment-id-number"><?= $payment_id ?></h1>
                <span class="button-span">
                    <button onclick="openOrderReview(<?= $payment_id ?>)" class="review-button payment-buttons">PREVIEW</button>
                    <button onclick="completeOrderConfirm(<?= $payment_id ?>)" class="complete-button payment-buttons">READY</button>
                </span>
            </div>

        <?php elseif ($remarks === 'Completed' && $rating_status === 'NotReceived' && $orderStatus > 0): ?>
            <div class="payment_container" style="background-color: #ffcc99">
                <h1 class="payment-id-number"><?= $payment_id ?></h1>
                <span class="button-span">
                    <button onclick="markAsReceived(<?= $payment_id ?>)" class="complete-button payment-buttons" style="background-color: green;">ORDER RECEIVED</button>
                </span>
            </div>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>
