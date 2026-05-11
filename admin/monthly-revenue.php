<?php include('../frontend/config/constants.php'); ?>
<?php //include('login-check.php'); ?>
<?php
$ei_order_notif = "SELECT order_status FROM tbl_eipay
                    WHERE order_status='Pending' OR order_status='Processing' OR order_status='OnTheWay'";
$res_ei_order_notif = mysqli_query($conn, $ei_order_notif);
$row_ei_order_notif = mysqli_num_rows($res_ei_order_notif);

$online_order_notif = "SELECT DISTINCT om.order_id 
                       FROM order_manager om
                       JOIN online_orders_new oon 
                       ON om.order_id = oon.order_id
                       WHERE (om.order_status='Pending' 
                              OR om.order_status='Processing' 
                              OR om.order_status='OnTheWay')
                       AND oon.restro_name='Pasar Kita'";
$res_online_order_notif = mysqli_query($conn, $online_order_notif);
$row_online_order_notif = mysqli_num_rows($res_online_order_notif);

$stock_notif = "SELECT stock FROM tbl_food WHERE stock<=$low_stock_threshold";
$res_stock_notif = mysqli_query($conn, $stock_notif);
$row_stock_notif = mysqli_num_rows($res_stock_notif);

$message_notif = "SELECT message_status FROM message WHERE message_status = 'unread'";
$res_message_notif = mysqli_query($conn, $message_notif);
$row_message_notif = mysqli_num_rows($res_message_notif);

$monthStart = (new DateTimeImmutable('first day of this month'))->modify('-11 months');
$monthStartDate = $monthStart->format('Y-m-01');

$monthTotals = [];
for ($i = 0; $i < 12; $i++) {
    $month = $monthStart->modify("+{$i} months");
    $key = $month->format('Y-m');
    $monthTotals[$key] = [
        'label' => $month->format('M Y'),
        'total' => 0.0,
        'orders' => 0
    ];
}

$monthlyRevenueQuery = mysqli_query(
    $conn,
    "SELECT DATE_FORMAT(om.order_date, '%Y-%m') AS month_key,
            COALESCE(SUM(oo.total_amount), 0) AS total_amount,
            COUNT(DISTINCT om.order_id) AS orders
     FROM online_orders_new oo
     INNER JOIN order_manager om ON oo.order_id = om.order_id
     WHERE oo.restro_name = 'Pasar Kita'
       AND om.order_status = 'Delivered'
       AND om.order_date >= '$monthStartDate'
     GROUP BY DATE_FORMAT(om.order_date, '%Y-%m')
     ORDER BY DATE_FORMAT(om.order_date, '%Y-%m')"
);

if ($monthlyRevenueQuery) {
    while ($row = mysqli_fetch_assoc($monthlyRevenueQuery)) {
        $monthKey = (string)($row['month_key'] ?? '');
        if ($monthKey !== '' && array_key_exists($monthKey, $monthTotals)) {
            $monthTotals[$monthKey]['total'] = (float)$row['total_amount'];
            $monthTotals[$monthKey]['orders'] = (int)$row['orders'];
        }
    }
}

$allTimeMonthlyRows = [];
$allTimeMonthlyQuery = mysqli_query(
    $conn,
    "SELECT DATE_FORMAT(om.order_date, '%Y-%m') AS month_key,
            DATE_FORMAT(om.order_date, '%b %Y') AS month_label,
            COALESCE(SUM(oo.total_amount), 0) AS total_amount,
            COUNT(DISTINCT om.order_id) AS orders
     FROM online_orders_new oo
     INNER JOIN order_manager om ON oo.order_id = om.order_id
     WHERE oo.restro_name = 'Pasar Kita'
       AND om.order_status = 'Delivered'
       AND om.order_date IS NOT NULL
     GROUP BY DATE_FORMAT(om.order_date, '%Y-%m')
     ORDER BY DATE_FORMAT(om.order_date, '%Y-%m')"
);

if ($allTimeMonthlyQuery) {
    while ($row = mysqli_fetch_assoc($allTimeMonthlyQuery)) {
        $allTimeMonthlyRows[] = [
            'label' => (string)($row['month_label'] ?? $row['month_key'] ?? '-'),
            'total' => (float)($row['total_amount'] ?? 0),
            'orders' => (int)($row['orders'] ?? 0)
        ];
    }
}

$currentMonthKey = (new DateTimeImmutable('first day of this month'))->format('Y-m');
$currentMonthLabel = (new DateTimeImmutable('first day of this month'))->format('F Y');
$currentMonthTotal = $monthTotals[$currentMonthKey]['total'] ?? 0.0;
$currentMonthOrders = $monthTotals[$currentMonthKey]['orders'] ?? 0;

$last12Total = 0.0;
$last12Orders = 0;
foreach ($monthTotals as $row) {
    $last12Total += (float)$row['total'];
    $last12Orders += (int)$row['orders'];
}
$averageMonthly = $last12Total / 12;

$monthlyChartRows = [['Month', 'Revenue']];
foreach ($monthTotals as $row) {
    $monthlyChartRows[] = [$row['label'], (float)$row['total']];
}
$monthlyChartJson = json_encode($monthlyChartRows);

$allTimeChartRows = [['Month', 'Revenue']];
foreach ($allTimeMonthlyRows as $row) {
    $allTimeChartRows[] = [$row['label'], (float)$row['total']];
}
$allTimeChartJson = json_encode($allTimeChartRows);

$allTimeTotal = 0.0;
$allTimeOrders = 0;
foreach ($allTimeMonthlyRows as $row) {
    $allTimeTotal += (float)($row['total'] ?? 0);
    $allTimeOrders += (int)($row['orders'] ?? 0);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style-admin.css">
    <link rel="icon" type="image/png" href="../images/logo2.png">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <title>Admin</title>
    <style>
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 18px;
        }
        .summary-card {
            background: var(--bg-card);
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-soft);
            padding: 16px;
        }
        .summary-card h3 {
            margin: 0;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-muted);
        }
        .summary-card p {
            margin: 10px 0 4px;
            font-size: 28px;
            font-weight: 800;
            color: var(--text-main);
        }
        .summary-card small {
            color: var(--text-muted);
        }
        a.clickable {
            color: gray !important;
            pointer-events: auto !important;
            text-decoration: none !important;
        }
        a.clickable:hover {
            color: #007bff !important;
        }
        .chart-panel {
            margin-bottom: 18px;
        }
        .revenue-chart {
            width: 100% !important;
            min-height: 360px;
        }
        @media (max-width: 768px) {
            .revenue-chart {
                min-height: 300px;
            }
        }
        @media (max-width: 576px) {
            .revenue-chart {
                min-height: 260px;
            }
        }
    </style>
    <script type="text/javascript">
        const monthlyRevenueData = <?php echo $monthlyChartJson ?: '[]'; ?>;
        const allTimeRevenueData = <?php echo $allTimeChartJson ?: '[]'; ?>;
        const MONTHLY_RANGE_LABEL = 'Last 12 months';
        const ALL_TIME_LABEL = 'All time';
        let chartsReady = false;

        google.charts.load('current', { packages: ['corechart', 'bar'] });
        google.charts.setOnLoadCallback(function () {
            chartsReady = true;
            setMonthlyChip();
            drawMonthlyRevenueChart();
            setAllTimeChip();
            drawAllTimeRevenueChart();
        });

        function setMonthlyChip() {
            const chipEl = document.getElementById('monthly-chip');
            if (!chipEl) return;
            const now = new Date();
            chipEl.textContent = `Updated ${now.toLocaleTimeString('en-IN', { hour: '2-digit', minute: '2-digit' })} - ${MONTHLY_RANGE_LABEL}`;
        }

        function setAllTimeChip() {
            const chipEl = document.getElementById('all-time-chip');
            if (!chipEl) return;
            const now = new Date();
            chipEl.textContent = `Updated ${now.toLocaleTimeString('en-IN', { hour: '2-digit', minute: '2-digit' })} - ${ALL_TIME_LABEL}`;
        }

        function getChartTheme() {
            const isDark = document.body.classList.contains('dark');
            return {
                panelText: isDark ? '#dbe7ff' : '#1e293b',
                mutedText: isDark ? '#aabdda' : '#475569',
                grid: isDark ? '#2a3f69' : '#dbe3f1'
            };
        }

        function getChartSize(elementId, minHeight) {
            const el = document.getElementById(elementId);
            if (!el) return {};
            const width = Math.max(0, Math.floor(el.clientWidth || 0));
            const height = Math.max(minHeight || 0, Math.floor(el.clientHeight || 0));
            return {
                width: width || undefined,
                height: height || undefined
            };
        }

        function drawMonthlyRevenueChart() {
            if (!chartsReady || typeof google === 'undefined' || !google.visualization) return;
            if (!document.getElementById('monthly_revenue_chart')) return;
            const theme = getChartTheme();
            const isNarrow = window.innerWidth <= 576;
            const size = getChartSize('monthly_revenue_chart', isNarrow ? 260 : 360);
            const safeData = Array.isArray(monthlyRevenueData) && monthlyRevenueData.length > 1
                ? monthlyRevenueData
                : [['Month', 'Revenue'], ['-', 0]];
            const displayData = safeData.map((row, index) => {
                if (index === 0 || !isNarrow) return row;
                const label = String(row[0] ?? '-');
                const shortLabel = label.split(' ')[0];
                return [shortLabel, row[1]];
            });
            const data = google.visualization.arrayToDataTable(displayData);

            const options = {
                backgroundColor: 'transparent',
                chartArea: isNarrow
                    ? { left: 44, top: 12, width: '90%', height: '72%', backgroundColor: 'transparent' }
                    : { left: 64, top: 16, width: '88%', height: '74%', backgroundColor: 'transparent' },
                legend: { position: 'none' },
                colors: ['#f59e0b'],
                bar: { groupWidth: isNarrow ? '42%' : '56%' },
                hAxis: {
                    title: isNarrow ? '' : 'Month',
                    textStyle: { color: theme.mutedText, fontSize: isNarrow ? 9 : 12 },
                    titleTextStyle: { color: theme.panelText, italic: false },
                    slantedText: true,
                    slantedTextAngle: isNarrow ? 55 : 35,
                    showTextEvery: 1,
                    maxAlternation: 1,
                    baselineColor: theme.grid,
                    gridlines: { color: theme.grid }
                },
                vAxis: {
                    title: 'Revenue',
                    minValue: 0,
                    textStyle: { color: theme.mutedText, fontSize: isNarrow ? 10 : 12 },
                    titleTextStyle: { color: theme.panelText, italic: false },
                    baselineColor: theme.grid,
                    gridlines: { color: theme.grid }
                }
            };

            if (size.width) options.width = size.width;
            if (size.height) options.height = size.height;

            const chart = new google.visualization.ColumnChart(document.getElementById('monthly_revenue_chart'));
            chart.draw(data, options);
        }

        function drawAllTimeRevenueChart() {
            if (!chartsReady || typeof google === 'undefined' || !google.visualization) return;
            if (!document.getElementById('all_time_revenue_chart')) return;
            const theme = getChartTheme();
            const isNarrow = window.innerWidth <= 576;
            const size = getChartSize('all_time_revenue_chart', isNarrow ? 260 : 360);
            const safeData = Array.isArray(allTimeRevenueData) && allTimeRevenueData.length > 1
                ? allTimeRevenueData
                : [['Month', 'Revenue'], ['-', 0]];
            const displayData = safeData.map((row, index) => {
                if (index === 0 || !isNarrow) return row;
                const label = String(row[0] ?? '-');
                const shortLabel = label.split(' ')[0];
                return [shortLabel, row[1]];
            });
            const data = google.visualization.arrayToDataTable(displayData);

            const options = {
                backgroundColor: 'transparent',
                chartArea: isNarrow
                    ? { left: 44, top: 12, width: '90%', height: '72%', backgroundColor: 'transparent' }
                    : { left: 64, top: 16, width: '88%', height: '74%', backgroundColor: 'transparent' },
                legend: { position: 'none' },
                colors: ['#f59e0b'],
                bar: { groupWidth: isNarrow ? '42%' : '56%' },
                hAxis: {
                    title: isNarrow ? '' : 'Month',
                    textStyle: { color: theme.mutedText, fontSize: isNarrow ? 9 : 12 },
                    titleTextStyle: { color: theme.panelText, italic: false },
                    slantedText: true,
                    slantedTextAngle: isNarrow ? 55 : 35,
                    showTextEvery: 1,
                    maxAlternation: 1,
                    baselineColor: theme.grid,
                    gridlines: { color: theme.grid }
                },
                vAxis: {
                    title: 'Revenue',
                    minValue: 0,
                    textStyle: { color: theme.mutedText, fontSize: isNarrow ? 10 : 12 },
                    titleTextStyle: { color: theme.panelText, italic: false },
                    baselineColor: theme.grid,
                    gridlines: { color: theme.grid }
                }
            };

            if (size.width) options.width = size.width;
            if (size.height) options.height = size.height;

            const chart = new google.visualization.ColumnChart(document.getElementById('all_time_revenue_chart'));
            chart.draw(data, options);
        }

        window.addEventListener('resize', function () {
            if (!chartsReady) return;
            drawMonthlyRevenueChart();
            drawAllTimeRevenueChart();
        });

        window.addEventListener('admin-theme-change', function () {
            if (!chartsReady) return;
            drawMonthlyRevenueChart();
            setMonthlyChip();
            drawAllTimeRevenueChart();
            setAllTimeChip();
        });
    </script>
</head>
<body>
    <section id="sidebar">
        <a href="index.php" class="brand">
            <img src="../images/logo2.png" width="120px" alt="">
        </a>
        <ul class="side-menu top">
            <li>
                <a href="index.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="manage-admin.php">
                    <i class='bx bxs-group'></i>
                    <span class="text">Admin Panel</span>
                </a>
            </li>
            <li>
                <a href="manage-online-order.php">
                    <i class='bx bxs-cart'></i>
                    <span class="text">Online Orders&nbsp;</span>
                    <?php if($row_online_order_notif>0){ ?><span class="num-ei"><?php echo $row_online_order_notif; ?></span><?php } ?>
                </a>
            </li>
            <li class="">
                <a href="manage-repeat-rate.php">
                    <i class="bx bx-bar-chart-alt-2"></i>
                    <span class="text">Your Repeat Rate</span>
                </a>
            </li>
            <li>
                <a href="manage-ei-order.php">
                    <i class='bx bxs-user'></i>
                    <span class="text">Users&nbsp;&nbsp;&nbsp;</span>
                    <?php if($row_ei_order_notif>0){ ?><span class="num-ei"><?php echo $row_ei_order_notif; ?></span><?php } ?>
                </a>
            </li>
            <li>
                <a href="manage-category.php">
                    <i class='bx bxs-category'></i>
                    <span class="text">Category</span>
                </a>
            </li>
            <li>
                <a href="manage-food.php">
                    <i class='bx bxs-food-menu'></i>
                    <span class="text">Food Menu</span>
                </a>
            </li>
            <li class="">
                <a href="inventory.php">
                    <i class='bx bxs-box'></i>
                    <span class="text">Inventory</span>
                    <?php if($row_stock_notif>0){ ?><span class="num-ei"><?php echo $row_stock_notif; ?></span><?php } ?>
                </a>
            </li>
            <li class="">
                <a href="manage-restro.php">
                    <i class='bx bx-restaurant'></i>
                    <span class="text">All Restro</span>
                </a>
            </li>
            <li class="">
                <a href="manage-restro-category.php">
                    <i class='bx bx-food-menu'></i>
                    <span class="text">All Restro Category</span>
                </a>
            </li>
            <li class="">
                <a href="manage-restro-food.php">
                    <i class='bx bx-dish'></i>
                    <span class="text">All Restro Food Item</span>
                </a>
            </li>
            <li class="">
                <a href="manage-restro-review.php">
                    <i class='bx bx-comment-detail'></i>
                    <span class="text">All Restro Review</span>
                </a>
            </li>
            <li>
                <a href="manage-delivery-boy.php">
                    <i class='bx bxs-truck'></i>
                    <span class="text">Delivery Boy</span>
                </a>
            </li>
            <li>
                <a href="manage-coupons.php">
                    <i class='bx bxs-discount'></i>
                    <span class="text">Discount Coupons</span>
                </a>
            </li>
            <li class="">
                <a href="manage-fest-coupon.php">
                    <i class='bx bxs-gift'></i>
                    <span class="text">Festival Coupons</span>
                </a>
            </li>
            <li class="active">
                <a href="monthly-revenue.php">
                    <i class="bx bx-line-chart"></i>
                    <span class="text">Monthly Revenue</span>
                </a>
            </li>
            <li>
                <a href="manage-delivery-payment.php">
                    <i class="bx bx-rupee"></i>
                    <span class="text">Payment History</span>
                </a>
            </li>
            <li>
                <a href="manage-review.php">
                    <i class="bx bx-star"></i>
                    <span class="text">Customer Review</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="#">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
                <a href="logout.php" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>

    <section id="content">
        <nav>
            <i class='bx bx-menu'></i>
            <a href="#" class="nav-link"></a>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Search...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
            <div class="fetch_message">
                <div class="action_message notfi_message">
                    <a href="messages.php"><i class='bx bxs-envelope'></i></a>
                    <?php if($row_message_notif>0){ ?><span class="num"><?php echo $row_message_notif; ?></span><?php } ?>
                </div>
            </div>
            <div class="notification">
                <div class="action notif" onclick="menuToggle();">
                    <i class='bx bxs-bell' onclick="menuToggle();"></i>
                    <div class="notif_menu">
                        <ul>
                            <?php 
                            if ($row_stock_notif > 0) {
                                $stock_message = $row_stock_notif == 1 ? "Item is" : "Items are";
                                echo "<li><a href='inventory.php?low=1'>$row_stock_notif&nbsp;$stock_message running out of stock</a></li>";
                            }
                            if ($row_online_order_notif > 0) {
                                echo "<li><a href='manage-online-order.php?remaining=1'>$row_online_order_notif&nbsp;New Online Order</a></li>";
                            }
                            if ($row_ei_order_notif > 0) {
                                echo "<li><a href='manage-online-order.php'>$row_ei_order_notif&nbsp;New EI Order</a></li>";
                            }
                            ?>
                        </ul>
                    </div>
                    <?php 
                    $total_notif = $row_stock_notif + $row_online_order_notif + $row_ei_order_notif;
                    if ($total_notif > 0) {
                        echo "<span class='num'>$total_notif</span>";
                    }
                    ?>
                </div>
            </div>
        </nav>

        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Monthly Revenue</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="index.php" class="clickable">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="monthly-revenue.php">Monthly Revenue</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="summary-grid">
                <div class="summary-card">
                <h3>Total Revenue (All time)</h3>
                <p>Rs <?php echo number_format($allTimeTotal, 2); ?></p>
                <small><?php echo number_format((int)$allTimeOrders); ?> total orders</small>
            </div>
            <div class="summary-card">
                    <h3>Last 12 Months</h3>
                    <p>Rs <?php echo number_format($last12Total, 2); ?></p>
                    <small><?php echo number_format((int)$last12Orders); ?> total orders</small>
                </div>
                <div class="summary-card">
                    <h3>This Month (<?php echo htmlspecialchars($currentMonthLabel); ?>)</h3>
                    <p>Rs <?php echo number_format($currentMonthTotal, 2); ?></p>
                    <small><?php echo number_format((int)$currentMonthOrders); ?> orders</small>
                </div>
                
            <div class="summary-card">
                <h3>Average / Month</h3>
                <p>Rs <?php echo number_format($averageMonthly, 2); ?></p>
                <small>Based on last 12 months</small>
            </div>
            
        </div>

            <div class="chart-panel">
                <div class="panel-head">
                    <h3>Monthly Revenue</h3>
                    <span class="panel-chip" id="monthly-chip">Last 12 months</span>
                </div>
                <div class="chart revenue-chart" id="monthly_revenue_chart"></div>
            </div>
            <div class="chart-panel">
                <div class="panel-head">
                    <h3>Total Revenue By Month</h3>
                    <span class="panel-chip" id="all-time-chip">All time</span>
                </div>
                <div class="chart revenue-chart" id="all_time_revenue_chart"></div>
            </div>

            <div class="table-data">
                <div class="order">
                    <table class="">
                        <tr>
                            <th>Month</th>
                            <th>Total Revenue</th>
                            <th>Orders</th>
                        </tr>
                        <?php foreach ($monthTotals as $row) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['label']); ?></td>
                                <td>Rs <?php echo number_format((float)$row['total'], 2); ?></td>
                                <td><?php echo number_format((int)$row['orders']); ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </main>
    </section>

    <script src="script-admin.js"></script>
</body>
</html>




