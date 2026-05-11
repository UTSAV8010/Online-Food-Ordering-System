<?php
include('../frontend/config/constants.php');
include('login-check.php');
include('check-ban.php');

header('Content-Type: application/json');

$deliveryBoy = $_SESSION['delivery-boy'] ?? '';
if ($deliveryBoy === '') {
    echo json_encode(['success' => false]);
    exit;
}

function fetchSingleValue($conn, $query, $key, $default = 0)
{
    $result = mysqli_query($conn, $query);
    if (!$result) {
        return $default;
    }

    $row = mysqli_fetch_assoc($result);
    return $row && isset($row[$key]) ? $row[$key] : $default;
}

$tips = (float)fetchSingleValue($conn, "SELECT COALESCE(SUM(tip), 0) AS total FROM tbl_review WHERE name='$deliveryBoy'", 'total', 0);
$revenue = (float)fetchSingleValue(
    $conn,
    "SELECT COALESCE(SUM(salary), 0) AS total
     FROM tbl_delivery_payment
     WHERE username='$deliveryBoy'
       AND created_at >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
       AND created_at < DATE_ADD(DATE_FORMAT(CURDATE(), '%Y-%m-01'), INTERVAL 1 MONTH)",
    'total',
    0
);
$remainingOrders = (int)fetchSingleValue($conn, "SELECT COUNT(*) AS total FROM order_manager WHERE order_status != 'Delivered' AND order_status != 'Cancelled'", 'total', 0);
$deliveredByMe = (int)fetchSingleValue($conn, "SELECT COUNT(*) AS total FROM order_manager WHERE delivery_boy_name='$deliveryBoy'", 'total', 0);

$mostSoldItems = [];
$statusQuery = mysqli_query(
    $conn,
    "SELECT order_status AS item_name, COUNT(*) AS total_qty
     FROM order_manager
     WHERE delivery_boy_name = '$deliveryBoy'
     GROUP BY order_status
     ORDER BY total_qty DESC
     LIMIT 6"
);

if ($statusQuery) {
    while ($row = mysqli_fetch_assoc($statusQuery)) {
        $mostSoldItems[] = [
            'item_name' => (string)$row['item_name'],
            'total_qty' => (int)$row['total_qty']
        ];
    }
}

$salesByHour = [];
$dayKeys = [];
$dayTotals = [];
$today = new DateTimeImmutable('today');
for ($i = 6; $i >= 0; $i--) {
    $day = $today->modify("-{$i} day");
    $key = $day->format('Y-m-d');
    $dayKeys[] = $key;
    $dayTotals[$key] = [
        'label' => $day->format('d M'),
        'total' => 0.0
    ];
}

$salesByDayQuery = mysqli_query(
    $conn,
    "SELECT DATE(created_at) AS order_day, COALESCE(SUM(salary), 0) AS total_sales
     FROM tbl_delivery_payment
     WHERE username = '$deliveryBoy'
       AND created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
       AND created_at < DATE_ADD(CURDATE(), INTERVAL 1 DAY)
     GROUP BY DATE(created_at)
     ORDER BY DATE(created_at)"
);

if ($salesByDayQuery) {
    while ($row = mysqli_fetch_assoc($salesByDayQuery)) {
        $dayKey = (string)($row['order_day'] ?? '');
        if ($dayKey !== '' && array_key_exists($dayKey, $dayTotals)) {
            $dayTotals[$dayKey]['total'] = (float)$row['total_sales'];
        }
    }
}

foreach ($dayKeys as $dayKey) {
    $salesByHour[] = [
        'day' => $dayTotals[$dayKey]['label'],
        'total_sales' => (float)$dayTotals[$dayKey]['total']
    ];
}

$monthlyRevenue = [];
$monthKeys = [];
$monthTotals = [];
$monthStart = (new DateTimeImmutable('first day of this month'))->modify('-11 months');
$monthStartDate = $monthStart->format('Y-m-01');
for ($i = 0; $i < 12; $i++) {
    $month = $monthStart->modify("+{$i} months");
    $key = $month->format('Y-m');
    $monthKeys[] = $key;
    $monthTotals[$key] = [
        'label' => $month->format('M Y'),
        'total' => 0.0
    ];
}

$monthlyRevenueQuery = mysqli_query(
    $conn,
    "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month_key, COALESCE(SUM(salary), 0) AS total_sales
     FROM tbl_delivery_payment
     WHERE username = '$deliveryBoy'
       AND created_at >= '$monthStartDate'
     GROUP BY DATE_FORMAT(created_at, '%Y-%m')
     ORDER BY DATE_FORMAT(created_at, '%Y-%m')"
);

if ($monthlyRevenueQuery) {
    while ($row = mysqli_fetch_assoc($monthlyRevenueQuery)) {
        $monthKey = (string)($row['month_key'] ?? '');
        if ($monthKey !== '' && array_key_exists($monthKey, $monthTotals)) {
            $monthTotals[$monthKey]['total'] = (float)$row['total_sales'];
        }
    }
}

foreach ($monthKeys as $monthKey) {
    $monthlyRevenue[] = [
        'month' => $monthTotals[$monthKey]['label'],
        'total_revenue' => (float)$monthTotals[$monthKey]['total']
    ];
}

echo json_encode([
    'success' => true,
    'timestamp' => date('c'),
    'kpis' => [
        'categories' => $tips,
        'revenue' => $revenue,
        'orders_completed' => $remainingOrders,
        'menu_items' => $deliveredByMe
    ],
    'most_sold_items' => $mostSoldItems,
    'sales_by_hour' => $salesByHour,
    'monthly_revenue' => $monthlyRevenue
]);
