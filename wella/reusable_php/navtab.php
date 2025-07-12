<link href="../css/navtab_design.css" rel="stylesheet">

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("../database/wb_db_connect.php");

$QTY_NAV_DISPLAY = 0;
$placedOrder = false;

if ($conn && isset($_SESSION["user_id"])) {
    $USER_ID_NAV = intval($_SESSION["user_id"]);

    $CHECK_ORDERS_NAV = "SELECT Quantity FROM orders WHERE User_ID = $USER_ID_NAV AND Status = 'Pending'";
    $ORDER_RESULT_NAV = $conn->query($CHECK_ORDERS_NAV);
    while ($CUR_ORDER_NAV = $ORDER_RESULT_NAV->fetch_assoc()) {
        $QTY_NAV_DISPLAY += $CUR_ORDER_NAV["Quantity"];
    }
    $ORDER_RESULT_NAV->free();

    $ongoingOrders = "SELECT 1 FROM orders WHERE User_ID = $USER_ID_NAV AND Status = 'Ongoing' LIMIT 1";
    $ongoingOrdersResult = $conn->query($ongoingOrders);
    $placedOrder = $ongoingOrdersResult->num_rows > 0;
}
?>

<nav class="navbar">
    <!-- PC NAVIGATION -->
    <div id="pcNav" style="max-width: 1200px; margin-inline: auto;">
        <a href="../index.php"><img src="../images/Icons/Logo.png" alt="Wella's BBQ" class="Logo"></a>
        <div class="nav-options">
            <a href="../main_pages/home.php">HOME</a>
            <a href="../main_pages/about.php">ABOUT</a>
            <a href="../main_pages/menu.php">MENU</a>
            <a href="../main_pages/career.php">CAREER</a>
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'Admin'): ?>
                <a href="../admin/admin_sales_report.php">SALES REPORT</a>
            <?php endif; ?>
        </div>
        <div class="user_nav_part">
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] !== "Guest"): ?>
                <button class="log-out-button" onclick="logoutConfirmation()">LOG OUT</button>
            <?php else: ?>
                <a class="log-in-button" href="../main_pages/log_in.php">LOG IN</a>
            <?php endif; ?>

            <?php if (!isset($_SESSION['user_type']) || ($_SESSION['user_type'] !== "Admin" && $_SESSION['user_type'] !== "Cashier")): ?>
                <div id="review-order-button-click">
                    <?php if ($placedOrder): ?>
                        <div class="order-qty" style="padding-inline: 15px; right: 13px; font-weight: 800;">ongoing</div>
                    <?php else: ?>
                        <div class="order-qty"><?php echo $QTY_NAV_DISPLAY ?></div>
                    <?php endif; ?>
                    <img src="../images/Icons/ViewOrderIcon.png" class="review-order">
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- MOBILE NAVIGATION -->
    <div id="mbNav">
        <a href="../index.php">
            <img src="../images/Icons/Logo.png" alt="Wella's BBQ">
        </a>
        <button class="more-icon" id="more-icon" onclick="expandNavigation(this)"></button>
    </div>
</nav>

<div id="expandTab">
    <div class="navigationTab">
        <a href="../main_pages/home.php"><button>HOME</button></a><br>
        <a href="../main_pages/about.php"><button>ABOUT</button></a><br>
        <a href="../main_pages/menu.php"><button>MENU</button></a><br>
        <a href="../main_pages/career.php"><button>CAREER</button></a><br>
    </div>

    <?php if (!isset($_SESSION['user_type']) || ($_SESSION['user_type'] !== "Admin" && $_SESSION['user_type'] !== "Cashier")): ?>
        <div id="expanded-review-order-button-click">
            <?php if ($placedOrder): ?>
                <div class="expanded-order-qty" style="padding-inline: 15px; right: 50px; font-weight: 800;">ongoing</div>
            <?php else: ?>
                <div class="expanded-order-qty"><?php echo $QTY_NAV_DISPLAY ?></div>
            <?php endif; ?>
            <img src="../images/Icons/ViewOrderIcon.png" class="expanded-review-order">
        </div>
        <br><br><br><br>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] !== "Guest"): ?>
        <button class="log-out-button" onclick="logoutConfirmation()">LOG OUT</button>
    <?php else: ?>
        <a class="expanded-log-in-button" href="../main_pages/log_in.php">LOG IN</a>
    <?php endif; ?>
</div>

<!-- Include the confirmation popup markup -->
<?php include '../reusable_php/confirmationpopup.php'; ?>

<!-- Confirmation Popup Script -->
<script>
function logoutConfirmation() {
    document.getElementById("confirmBox").style.display = "block";
    document.getElementById("confirmation-text").innerHTML = 'Are you sure you want to log out?';

    // Prevent multiple stacked click handlers
    const oldBtn = document.getElementById("confirmation-confirm-button");
    const newBtn = oldBtn.cloneNode(true);
    oldBtn.parentNode.replaceChild(newBtn, oldBtn);

    newBtn.addEventListener("click", function () {
        window.location.href = "../database/log_out.php";
    });
}
</script>
