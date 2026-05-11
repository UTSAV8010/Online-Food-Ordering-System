<?php
include('../frontend/config/constants.php');
include('login-check.php');

header('Content-Type: application/json');

$restroname = $_SESSION['restro-name'] ?? '';
if ($restroname === '') {
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

$categories = (int)fetchSingleValue($conn, "SELECT COUNT(*) AS total FROM tbl_rcategory_notapproved WHERE restro_name='$restroname'", 'total', 0);
$menuItems = (int)fetchSingleValue($conn, "SELECT COUNT(*) AS total FROM tbl_restro_food_item WHERE restro_name='$restroname'", 'total', 0);
$ordersCompleted = (int)fetchSingleValue(
    $conn,
    "SELECT COUNT(DISTINCT om.order_id) AS total
     FROM order_manager om
     INNER JOIN online_orders_new oo ON oo.order_id = om.order_id
     WHERE oo.restro_name = '$restroname' AND om.order_status != 'Cancelled'",
    'total',
    0
);

$revenue = (float)fetchSingleValue(
    $conn,
    "SELECT COALESCE(SUM(oo.total_amount), 0) AS total
     FROM online_orders_new oo
     INNER JOIN order_manager om ON oo.order_id = om.order_id
     WHERE oo.restro_name = '$restroname' AND om.order_status = 'Delivered'",
    'total',
    0
);

$mostSoldItems = [];
$mostSoldQuery = mysqli_query(
    $conn,
    "SELECT Item_Name AS item_name, SUM(Quantity) AS total_qty
     FROM online_orders_new
     WHERE restro_name = '$restroname'
     GROUP BY Item_Name
     ORDER BY total_qty DESC
     LIMIT 6"
);

if ($mostSoldQuery) {
    while ($row = mysqli_fetch_assoc($mostSoldQuery)) {
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
    "SELECT DATE(om.order_date) AS order_day, SUM(oo.total_amount) AS total_sales
     FROM online_orders_new oo
     INNER JOIN order_manager om ON oo.order_id = om.order_id
     WHERE oo.restro_name = '$restroname'
       AND om.order_status != 'Cancelled'
       AND om.order_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
       AND om.order_date < DATE_ADD(CURDATE(), INTERVAL 1 DAY)
     GROUP BY DATE(om.order_date)
     ORDER BY DATE(om.order_date)"
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
    "SELECT DATE_FORMAT(om.order_date, '%Y-%m') AS month_key,
            COALESCE(SUM(oo.total_amount), 0) AS total_sales
     FROM online_orders_new oo
     INNER JOIN order_manager om ON oo.order_id = om.order_id
     WHERE oo.restro_name = '$restroname'
       AND om.order_status = 'Delivered'
       AND om.order_date >= '$monthStartDate'
     GROUP BY DATE_FORMAT(om.order_date, '%Y-%m')
     ORDER BY DATE_FORMAT(om.order_date, '%Y-%m')"
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

$allTimeMonthlyRevenue = [];
$allTimeQuery = mysqli_query(
    $conn,
    "SELECT DATE_FORMAT(om.order_date, '%Y-%m') AS month_key,
            DATE_FORMAT(om.order_date, '%b %Y') AS month_label,
            COALESCE(SUM(oo.total_amount), 0) AS total_sales
     FROM online_orders_new oo
     INNER JOIN order_manager om ON oo.order_id = om.order_id
     WHERE oo.restro_name = '$restroname'
       AND om.order_status = 'Delivered'
       AND om.order_date IS NOT NULL
     GROUP BY DATE_FORMAT(om.order_date, '%Y-%m')
     ORDER BY DATE_FORMAT(om.order_date, '%Y-%m')"
);

if ($allTimeQuery) {
    while ($row = mysqli_fetch_assoc($allTimeQuery)) {
        $label = (string)($row['month_label'] ?? $row['month_key'] ?? '');
        if ($label === '') {
            $label = '-';
        }
        $allTimeMonthlyRevenue[] = [
            'month' => $label,
            'total_revenue' => (float)($row['total_sales'] ?? 0)
        ];
    }
}

echo json_encode([
    'success' => true,
    'timestamp' => date('c'),
    'kpis' => [
        'categories' => $categories,
        'revenue' => $revenue,
        'orders_completed' => $ordersCompleted,
        'menu_items' => $menuItems
    ],
    'most_sold_items' => $mostSoldItems,
    'sales_by_hour' => $salesByHour,
    'monthly_revenue' => $monthlyRevenue,
    'all_time_monthly_revenue' => $allTimeMonthlyRevenue
]);
