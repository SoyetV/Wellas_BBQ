<?php
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: ../main_pages/log_in.php");
    exit();
}

require_once("../database/wb_db_connect.php");

$selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('Y-m');

$query = "
    SELECT
        p.ID AS Payment_ID,
        u.First_Name,
        u.Last_Name,
        p.Total_Payment,
        p.Total_Quantity,
        COALESCE(MIN(o.OrderMadeDate), p.PaymentCompletedDate) AS FirstOrderDate,
        p.Remarks,
        r.Rating,
        r.Comment
    FROM payment p
    JOIN users u ON u.ID = p.User_ID
    LEFT JOIN orders o ON o.Payment_ID = p.ID
    LEFT JOIN ratings r ON r.Payment_ID = p.ID
    WHERE COALESCE(o.OrderMadeDate, p.PaymentCompletedDate) IS NOT NULL
      AND DATE_FORMAT(COALESCE(o.OrderMadeDate, p.PaymentCompletedDate), '%Y-%m') = ?
    GROUP BY p.ID
    ORDER BY FirstOrderDate DESC, Payment_ID DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $selectedMonth);
$stmt->execute();
$result = $stmt->get_result();

$payments = [];
$totalSales = 0;
$totalOrders = 0;

while ($row = $result->fetch_assoc()) {
    $remarks = isset($row['Remarks']) ? strtolower(trim($row['Remarks'])) : '';
    $isCancelled = ($remarks === 'cancelled');

    $row['isCancelled'] = $isCancelled;
    $payments[] = $row;

    if (!$isCancelled) {
        $totalSales += $row['Total_Payment'];
        $totalOrders++;
    }
}

// 🔄 Fetch product data
$productMap = [];
if (!empty($payments)) {
    $paymentIds = array_column($payments, 'Payment_ID');
    $placeholders = implode(',', array_fill(0, count($paymentIds), '?'));
    $types = str_repeat('i', count($paymentIds));

    $orderQuery = "
        SELECT o.Payment_ID, pr.Name AS ProductName, o.Quantity, o.Price
        FROM orders o
        JOIN products pr ON pr.ID = o.Item_ID
        WHERE o.Payment_ID IN ($placeholders)
    ";

    $stmt = $conn->prepare($orderQuery);
    $stmt->bind_param($types, ...$paymentIds);
    $stmt->execute();
    $orderResult = $stmt->get_result();

    while ($item = $orderResult->fetch_assoc()) {
        $productMap[$item['Payment_ID']][] = $item;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wella's BBQ - Sales Report</title>
    <link rel="icon" href="../wb.ico" type="image/x-icon">
    <link href="../bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/Global_Design.css" rel="stylesheet">
    <link href="../css/admin_sales_report.css" rel="stylesheet">
</head>
<body>

<?php include "../reusable_php/navtab.php"; ?>

<div class="container mt-4">
    <h2>Monthly Sales Report</h2>

    <form method="get" action="" class="mb-4">
        <label for="month" class="form-label" style="color:white;">Select Month:</label>
        <input type="month" id="month" name="month" value="<?= htmlspecialchars($selectedMonth) ?>" class="form-control" style="max-width: 300px;">
        <button type="submit" class="btn btn-primary mt-2">View</button>
    </form>

    <div class="sales-table-container">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Payment ID</th>
                    <th>Customer</th>
                    <th>Items Ordered</th>
                    <th>Total Quantity</th>
                    <th>Total Payment</th>
                    <th>Date</th>
                    <th>Remarks</th>
                    <th>Rating</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($payments)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-white">No records found for this month.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($payments as $payment): ?>
                        <tr style="text-align: center; <?= $payment['isCancelled'] ? 'background-color: #f8d7da;' : '' ?>"> 
                            <td><?= $payment['Payment_ID'] ?></td>
                            <td><?= htmlspecialchars($payment['First_Name'] . ' ' . $payment['Last_Name']) ?></td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    <?php foreach ($productMap[$payment['Payment_ID']] ?? [] as $item): ?>
                                        <li><?= htmlspecialchars($item['ProductName']) ?> x<?= $item['Quantity'] ?> (₱<?= number_format($item['Price'], 2) ?>)</li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                            <td><?= $payment['Total_Quantity'] ?></td>
                            <td>₱<?= number_format($payment['Total_Payment'], 2) ?></td>
                            <td><?= $payment['FirstOrderDate'] ?></td>
                            <td>
                                <?php if ($payment['isCancelled']): ?>
                                            <p style = "font-size: 16px; color: red; font-weight: bold;">Cancelled</p> 
                                <?php else: ?>
                                    <?= htmlspecialchars($payment['Remarks']) ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!is_null($payment['Rating'])): ?>
                                    <?= str_repeat('⭐', (int)$payment['Rating']) ?>
                                    <?php if (!empty($payment['Comment'])): ?>
                                        <br><small><?= htmlspecialchars($payment['Comment']) ?></small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    Not Rated
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot class="table-secondary">
                <tr>
                    <td colspan="4" class="text-end fw-bold">Total Sales:</td>
                    <td><strong>₱<?= number_format($totalSales, 2) ?></strong></td>
                    <td colspan="2"></td>
                    <td><strong>Orders: <?= $totalOrders ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script src="../javascript/JQUERY.js"></script>
<script src="../javascript/globaljavascript.js"></script>
<script src="../javascript/expandnavtab.js"></script>
</body>
</html>
