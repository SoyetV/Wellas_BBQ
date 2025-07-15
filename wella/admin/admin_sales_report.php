<?php
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: ../main_pages/log_in.php");
    exit();
}

require_once("../database/wb_db_connect.php");

$viewMode = 'monthly';
$filterFormat = '';
$filterValueStart = null;
$filterValueEnd = null;
$filterValue = null;

if (!empty($_GET['date'])) {
    $viewMode = 'daily';
    $filterFormat = '%Y-%m-%d';
    $filterValue = $_GET['date'];
} elseif (!empty($_GET['week'])) {
    $viewMode = 'weekly';
    $weekInput = $_GET['week'];
    $weekStart = new DateTimeImmutable($weekInput);
    $filterValueStart = $weekStart->format('Y-m-d');
    $filterValueEnd = $weekStart->modify('+6 days')->format('Y-m-d');
} else {
    $viewMode = 'monthly';
    $filterFormat = '%Y-%m';
    $filterValue = $_GET['month'] ?? date('Y-m');
}

$query = "SELECT
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
    WHERE COALESCE(o.OrderMadeDate, p.PaymentCompletedDate) IS NOT NULL ";

if ($viewMode === 'weekly') {
    $query .= "AND DATE(COALESCE(o.OrderMadeDate, p.PaymentCompletedDate)) BETWEEN ? AND ? ";
} else {
    $query .= "AND DATE_FORMAT(COALESCE(o.OrderMadeDate, p.PaymentCompletedDate), ?) = ? ";
}

$query .= "GROUP BY p.ID ORDER BY FirstOrderDate DESC, Payment_ID DESC";

$stmt = $conn->prepare($query);

if ($viewMode === 'weekly') {
    $stmt->bind_param("ss", $filterValueStart, $filterValueEnd);
} else {
    $stmt->bind_param("ss", $filterFormat, $filterValue);
}

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
    <h2>Sales Report</h2>

    <form method="get" action="" class="mb-4">
  <div class="mb-3">
    <select id="filterType" class="form-select" onclick="showFilterInput(this.value)">
      <option value="daily">Daily</option>
      <option value="weekly">Weekly</option>
      <option value="monthly">Monthly</option>
    </select>
  </div>

  <div id="dailyInput" class="filter-group d-none">
    <label class="form-label fw-bold text-black">📅 Select a specific day:</label>
    <input type="date" name="date" class="form-control">
  </div>

  <div id="weeklyInput" class="filter-group d-none">
    <label class="form-label fw-bold text-black">🗓️ Select a week:</label>
    <input type="week" name="week" class="form-control">
  </div>

  <div id="monthlyInput" class="filter-group d-none">
    <label class="form-label fw-bold text-black">📆 Select a month:</label>
    <input type="month" name="month" class="form-control">
  </div>

  <div class="view button mt-3">
    <button type="submit" class="btn btn-dark px-5 fw-bold">View</button>
  </div>
</form>

    <p class="text-black"><strong>
        <?php
        if ($viewMode === 'daily') {
            echo "Showing sales for " . date('F d, Y', strtotime($filterValue));
        } elseif ($viewMode === 'weekly') {
            echo "Showing sales for week of " . date('F d', strtotime($filterValueStart)) . " to " . date('F d, Y', strtotime($filterValueEnd));
        } else {
            echo "Showing sales for " . date('F Y', strtotime($filterValue));
        }
        ?>
    </strong></p>

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
                        <td colspan="8" class="text-center text-black">No records found for this date.</td>
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
                                    <p class="text-danger fw-bold">Cancelled</p>
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
                    <td class="text-center fw-bold">₱<?= number_format($totalSales, 2) ?></td>
                    <td colspan="2"></td>
                    <td class="fw-bold">Completed Orders: <?= $totalOrders ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>  
<script src="../javascript/JQUERY.js"></script>
<script src="../javascript/globaljavascript.js"></script>
<script src="../javascript/expandnavtab.js"></script>
<script>
  function showFilterInput(type) {
    const groups = ['dailyInput', 'weeklyInput', 'monthlyInput'];
    groups.forEach(id => {
      document.getElementById(id).classList.add('d-none');
    });

    if (type === 'daily') {
      document.getElementById('dailyInput').classList.remove('d-none');
    } else if (type === 'weekly') {
      document.getElementById('weeklyInput').classList.remove('d-none');
    } else if (type === 'monthly') {
      document.getElementById('monthlyInput').classList.remove('d-none');
    }
  }
</script>
</body>
</html>
