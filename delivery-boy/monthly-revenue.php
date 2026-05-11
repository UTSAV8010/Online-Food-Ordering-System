<?php
include('../frontend/config/constants.php');
include('login-check.php');
include('check-ban.php');

$delivery_boy_name = $_SESSION['delivery-boy'];

$ei_order_notif = "SELECT order_status FROM tbl_eipay
                    WHERE order_status='Pending' OR order_status='Processing' OR order_status='OnTheWay'";
$res_ei_order_notif = mysqli_query($conn, $ei_order_notif);
$row_ei_order_notif = mysqli_num_rows($res_ei_order_notif);

$online_order_notif = "SELECT order_status FROM order_manager
                       WHERE order_status='Pending' OR order_status='Processing' OR order_status='OnTheWay'";
$res_online_order_notif = mysqli_query($conn, $online_order_notif);
$row_online_order_notif = mysqli_num_rows($res_online_order_notif);

$monthStart = (new DateTimeImmutable('first day of this month'))->modify('-11 months');
$monthStartDate = $monthStart->format('Y-m-01');

$monthTotals = [];
for ($i = 0; $i < 12; $i++) {
    $month = $monthStart->modify("+{$i} months");
    $key = $month->format('Y-m');
    $monthTotals[$key] = [
        'label' => $month->format('M Y'),
        'total' => 0.0,
        'payments' => 0
    ];
}

$monthlyRevenueQuery = mysqli_query(
    $conn,
    "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month_key,
            COALESCE(SUM(salary), 0) AS total_amount,
            COUNT(*) AS payments
     FROM tbl_delivery_payment
     WHERE username = '$delivery_boy_name'
       AND created_at >= '$monthStartDate'
     GROUP BY DATE_FORMAT(created_at, '%Y-%m')
     ORDER BY DATE_FORMAT(created_at, '%Y-%m')"
);

if ($monthlyRevenueQuery) {
    while ($row = mysqli_fetch_assoc($monthlyRevenueQuery)) {
        $monthKey = (string)($row['month_key'] ?? '');
        if ($monthKey !== '' && array_key_exists($monthKey, $monthTotals)) {
            $monthTotals[$monthKey]['total'] = (float)$row['total_amount'];
            $monthTotals[$monthKey]['payments'] = (int)$row['payments'];
        }
    }
}

$currentMonthKey = (new DateTimeImmutable('first day of this month'))->format('Y-m');
$currentMonthLabel = (new DateTimeImmutable('first day of this month'))->format('F Y');
$currentMonthTotal = $monthTotals[$currentMonthKey]['total'] ?? 0.0;
$currentMonthPayments = $monthTotals[$currentMonthKey]['payments'] ?? 0;

$last12Total = 0.0;
$last12Payments = 0;
foreach ($monthTotals as $row) {
    $last12Total += (float)$row['total'];
    $last12Payments += (int)$row['payments'];
}
$averageMonthly = $last12Total / 12;

$delivery_initial = strtoupper(substr(trim((string)$delivery_boy_name), 0, 1));
if ($delivery_initial === '') {
    $delivery_initial = 'D';
}

$monthlyChartRows = [['Month', 'Revenue']];
foreach ($monthTotals as $row) {
    $monthlyChartRows[] = [$row['label'], (float)$row['total']];
}
$monthlyChartJson = json_encode($monthlyChartRows);
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
    <title>Delivery-boy Management</title>
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
        const MONTHLY_RANGE_LABEL = 'Last 12 months';
        let chartsReady = false;

        google.charts.load('current', { packages: ['corechart', 'bar'] });
        google.charts.setOnLoadCallback(function () {
            chartsReady = true;
            setMonthlyChip();
            drawMonthlyRevenueChart();
        });

        function setMonthlyChip() {
            const chipEl = document.getElementById('monthly-chip');
            if (!chipEl) return;
            const now = new Date();
            chipEl.textContent = `Updated ${now.toLocaleTimeString('en-IN', { hour: '2-digit', minute: '2-digit' })} - ${MONTHLY_RANGE_LABEL}`;
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

        window.addEventListener('resize', function () {
            if (!chartsReady) return;
            drawMonthlyRevenueChart();
        });

        window.addEventListener('admin-theme-change', function () {
            if (!chartsReady) return;
            drawMonthlyRevenueChart();
            setMonthlyChip();
        });
    </script>
</head>
<body>
<section id="sidebar">
    <a href="index.php" class="brand"><img src="../images/logo2.png" width="120" alt=""></a>
    <ul class="side-menu top">
        <li><a href="index.php"><i class='bx bxs-dashboard'></i><span class="text">Dashboard</span></a></li>
        <li><a href="manage-online-order.php"><i class='bx bxs-cart'></i><span class="text">Online Orders&nbsp;</span><?php if($row_online_order_notif>0){ ?><span class="num-ei"><?php echo $row_online_order_notif; ?></span><?php } ?></a></li>
        <li><a href="manage-delivery-payment.php"><i class="bx bx-rupee"></i><span class="text">Payment History</span></a></li>
        <li class="active"><a href="monthly-revenue.php"><i class='bx bx-line-chart'></i><span class="text">Monthly Revenue</span></a></li>
        <li><a href="manage-review.php"><i class="bx bx-star"></i><span class="text">Your Review</span></a></li>
        <li><a href="update-password.php"><i class="bx bx-lock"></i><span class="text">Change Password</span></a></li>
    </ul>
    <ul class="side-menu">
        <li><a href="settings.php"><i class='bx bxs-cog'></i><span class="text">Settings</span></a></li>
        <li><a href="logout.php" class="logout"><i class='bx bxs-log-out-circle'></i><span class="text">Logout</span></a></li>
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
        <div class="nav-actions">
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
            <div class="notification" onclick="menuToggle();">
                <div class="action notif" onclick="menuToggle();">
                    <i class='bx bxs-bell' onclick="menuToggle();"></i>
                    <div class="notif_menu">
                        <ul>
                            <?php
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
                    $total_notif = $row_online_order_notif + $row_ei_order_notif;
                    if ($total_notif > 0) {
                        echo "<span class='num'>$total_notif</span>";
                    }
                    ?>
                </div>
            </div>
            <div class="admin-avatar" title="<?php echo htmlspecialchars($delivery_boy_name); ?>"><?php echo htmlspecialchars($delivery_initial); ?></div>
        </div>
    </nav>

    <main>
        <div class="head-title">
            <div class="left">
                <h1>Monthly Revenue</h1>
                <ul class="breadcrumb">
                    <li><a href="index.php" class="clickable">Dashboard</a></li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li><a class="active" href="monthly-revenue.php">Monthly Revenue</a></li>
                </ul>
            </div>
        </div>

        <div class="summary-grid">
            <div class="summary-card">
                <h3>This Month (<?php echo htmlspecialchars($currentMonthLabel); ?>)</h3>
                <p>Rs <?php echo number_format($currentMonthTotal, 2); ?></p>
                <small><?php echo number_format((int)$currentMonthPayments); ?> payments</small>
            </div>
            <div class="summary-card">
                <h3>Last 12 Months</h3>
                <p>Rs <?php echo number_format($last12Total, 2); ?></p>
                <small><?php echo number_format((int)$last12Payments); ?> total payments</small>
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

        <div class="table-data">
            <div class="order">
                <table class="">
                    <tr>
                        <th>Month</th>
                        <th>Total Revenue</th>
                        <th>Payments</th>
                    </tr>
                    <?php
                    foreach ($monthTotals as $row) {
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['label']); ?></td>
                            <td>Rs <?php echo number_format((float)$row['total'], 2); ?></td>
                            <td><?php echo number_format((int)$row['payments']); ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>
    </main>
</section>

<script src="script-admin.js"></script>
</body>
</html>


