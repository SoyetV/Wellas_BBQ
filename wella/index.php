<?php
    session_start();

    if (isset($_SESSION["user_type"]) && $_SESSION["user_type"] == 'Cashier') {
        header("Location: cashier/cashier_home.php");
    } else {
        header("Location: main_pages/home.php");
    }

    exit();
?>