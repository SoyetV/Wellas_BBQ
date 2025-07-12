<?php
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: ../main_pages/log_in.php");
    exit();
}

require_once("../database/wb_db_connect.php");

$selectedMonth = date('Y-m');
if (isset($_GET['month'])) {
    $selectedMonth = $_GET['month'];
}


$sql = "SELECT 
            p.ID as Payment_ID, 
            u.First_Name, 
            u.Last_Name, 
            p.Total_Payment, 
            p.Total_Quantity, 
            p.PaymentStartedDate, 
            p.Remarks
        FROM payment p
        JOIN users u ON u.ID = p.User_ID
        WHERE DATE_FORMAT(p.PaymentStartedDate, '%Y-%m') = ?
        ORDER BY p.PaymentStartedDate DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $selectedMonth);
$stmt->execute();
$result = $stmt->get_result();

$totalSales = 0;
$totalOrders = 0;
$payments = [];

while ($row = $result->fetch_assoc()) {
    $payments[] = $row;
    $totalSales += $row['Total_Payment'];
    $totalOrders++;
}


$productMap = [];

if (!empty($payments)) {
    $paymentIds = implode(',', array_column($payments, 'Payment_ID'));
    $orderQuery = "
        SELECT 
            o.Payment_ID, 
            pr.Name AS ProductName, 
            o.Quantity, 
            o.Price
        FROM orders o
        JOIN products pr ON pr.ID = o.Item_ID
        WHERE o.Payment_ID IN ($paymentIds)
    ";
    $orderResult = $conn->query($orderQuery);
    while ($orderRow = $orderResult->fetch_assoc()) {
        $pid = $orderRow['Payment_ID'];
        if (!isset($productMap[$pid])) {
            $productMap[$pid] = [];
        }
        $productMap[$pid][] = $orderRow;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wella's BBQ</title>
    <link rel="icon" type="ico" href="../wb.ico">
    <link href="../bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/Global_Design.css" rel="stylesheet">
    <link href="../css/admin_sales_report.css" rel="stylesheet">
</head>
<body>
<?php include "../reusable_php/navtab.php"; ?>

<div class="container">
    <h2>Monthly Sales Report</h2>

    <form method="get" action="">
        <label for="month">Select Month:</label>
        <input type="month" id="month" name="month" value="<?= htmlspecialchars($selectedMonth) ?>">
        <button type="submit">View</button>
    </form>

    <div class="sales-table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Customer</th>
                    <th>Items Ordered</th>
                    <th>Total Quantity</th>
                    <th>Total Payment</th>
                    <th>Date</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($payments) === 0): ?>
                <tr><td colspan="7">No records found for this month.</td></tr>
            <?php else: ?>
                <?php foreach ($payments as $payment): ?>
                    <tr>
                        <td><?= $payment['Payment_ID'] ?></td>
                        <td><?= htmlspecialchars($payment['First_Name'] . ' ' . $payment['Last_Name']) ?></td>
                        <td style="text-align:left;">
                            <ul style="padding-left:15px;">
                                <?php foreach ($productMap[$payment['Payment_ID']] ?? [] as $item): ?>
                                    <li>
                                        <?= htmlspecialchars($item['ProductName']) ?> 
                                        x<?= $item['Quantity'] ?> 
                                        (₱<?= number_format($item['Price'], 2) ?>)
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td><?= $payment['Total_Quantity'] ?></td>
                        <td>₱<?= number_format($payment['Total_Payment'], 2) ?></td>
                        <td><?= $payment['PaymentStartedDate'] ?></td>
                        <td><?= $payment['Remarks'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4">Total</th>
                    <th>₱<?= number_format($totalSales, 2) ?></th>
                    <th colspan="2">Orders: <?= $totalOrders ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
</body>
</html>
