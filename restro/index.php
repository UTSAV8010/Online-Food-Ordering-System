<?php include('../frontend/config/constants.php');
      include('login-check.php');

$restroname = $_SESSION['restro-name'];

$ei_order_notif = "SELECT order_status FROM tbl_eipay
                    WHERE order_status='Pending' OR order_status='Processing' OR order_status='OnTheWay'";
$res_ei_order_notif = mysqli_query($conn, $ei_order_notif);
$row_ei_order_notif = mysqli_num_rows($res_ei_order_notif);

$online_order_notif = "SELECT DISTINCT om.order_id
                       FROM order_manager om
                       JOIN online_orders_new oon ON om.order_id = oon.order_id
                       WHERE (om.order_status='Pending' OR om.order_status='Processing' OR om.order_status='OnTheWay')
                         AND oon.restro_name='$restroname'";
$res_online_order_notif = mysqli_query($conn, $online_order_notif);
$row_online_order_notif = mysqli_num_rows($res_online_order_notif);

$stock_notif = "SELECT stock FROM tbl_restro_food_item
                WHERE stock<=$low_stock_threshold AND restro_name = '$restroname'";
$res_stock_notif = mysqli_query($conn, $stock_notif);
$row_stock_notif = mysqli_num_rows($res_stock_notif);

$revenue = "SELECT SUM(o.total_amount) AS total_amount
            FROM online_orders_new o
            JOIN order_manager om ON o.order_id = om.order_id
            WHERE o.restro_name = '$restroname'
              AND om.order_status = 'Delivered'";
$res_revenue = mysqli_query($conn, $revenue);
$total_revenue = mysqli_fetch_array($res_revenue);

$orders_delivered = "SELECT DISTINCT om.order_id
                     FROM online_orders_new o
                     JOIN order_manager om ON o.order_id = om.order_id
                     WHERE o.restro_name = '$restroname'
                       AND om.order_status != 'Cancelled'";
$res_orders_delivered = mysqli_query($conn, $orders_delivered);
$total_orders_delivered = mysqli_num_rows($res_orders_delivered);

$sql_cat = "SELECT COUNT(*) AS total FROM tbl_rcategory_notapproved WHERE restro_name='$restroname'";
$res_cat = mysqli_query($conn, $sql_cat);
$row_cat = ($res_cat && ($cat_row = mysqli_fetch_assoc($res_cat))) ? (int)$cat_row['total'] : 0;

$sql_item = "SELECT COUNT(*) AS total FROM tbl_restro_food_item WHERE restro_name='$restroname'";
$res_item = mysqli_query($conn, $sql_item);
$row_item = ($res_item && ($item_row = mysqli_fetch_assoc($res_item))) ? (int)$item_row['total'] : 0;

$restro_initial = strtoupper(substr(trim((string)$restroname), 0, 1));
if ($restro_initial === '') {
    $restro_initial = 'R';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style-admin.css">
    <link rel="icon" type="image/png" href="../images/logo2.png">

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      let mostSoldItemsData = [['Item Name', 'Sales'], ['No Data', 1]];
      let salesByHourData = [['Day', 'Sales'], ['-', 0]];
      let monthlyRevenueData = [['Month', 'Revenue'], ['-', 0]];
      let allTimeMonthlyRevenueData = [['Month', 'Revenue'], ['-', 0]];
      let liveTimestamp = '';
      let refreshHandle = null;
      let chartsReady = false;
      const SALES_RANGE_LABEL = 'Last 7 days';
      const MONTHLY_RANGE_LABEL = 'Last 12 months';
      const ALL_TIME_LABEL = 'All time';

      google.charts.load('current', { packages: ['corechart', 'bar'] });
      google.charts.setOnLoadCallback(function () {
        chartsReady = true;
        fetchDashboardLiveData();
        refreshHandle = window.setInterval(fetchDashboardLiveData, 30000);
      });

      function drawDashboardCharts() {
        if (!chartsReady || typeof google === 'undefined' || !google.visualization) return;
        drawDonutChart();
        drawSalesBarChart();
        drawMonthlyRevenueChart();
        drawAllTimeMonthlyRevenueChart();
      }

      function setChipText() {
        const chipEl = document.getElementById('sales-chip');
        if (!chipEl) return;
        if (!liveTimestamp) {
          chipEl.textContent = SALES_RANGE_LABEL;
          return;
        }
        const time = new Date(liveTimestamp);
        chipEl.textContent = `Updated ${time.toLocaleTimeString('en-IN', { hour: '2-digit', minute: '2-digit' })} · ${SALES_RANGE_LABEL}`;
      }

      function toNumber(value) {
        const parsed = Number(value);
        return Number.isFinite(parsed) ? parsed : 0;
      }

      function setMonthlyChip() {
        const chipEl = document.getElementById('monthly-chip');
        if (!chipEl) return;
        if (!liveTimestamp) {
          chipEl.textContent = MONTHLY_RANGE_LABEL;
          return;
        }
        const time = new Date(liveTimestamp);
        chipEl.textContent = `Updated ${time.toLocaleTimeString('en-IN', { hour: '2-digit', minute: '2-digit' })} - ${MONTHLY_RANGE_LABEL}`;
      }

      function setAllTimeChip() {
        const chipEl = document.getElementById('all-time-chip');
        if (!chipEl) return;
        if (!liveTimestamp) {
          chipEl.textContent = ALL_TIME_LABEL;
          return;
        }
        const time = new Date(liveTimestamp);
        chipEl.textContent = `Updated ${time.toLocaleTimeString('en-IN', { hour: '2-digit', minute: '2-digit' })} - ${ALL_TIME_LABEL}`;
      }

      function updateKpiCards(payload) {
        const categoriesEl = document.getElementById('kpi-categories');
        const revenueEl = document.getElementById('kpi-revenue');
        const ordersEl = document.getElementById('kpi-orders');
        const menuEl = document.getElementById('kpi-menu');

        if (categoriesEl) categoriesEl.textContent = toNumber(payload.kpis?.categories).toLocaleString('en-IN');
        if (revenueEl) {
          revenueEl.textContent = `Rs ${toNumber(payload.kpis?.revenue).toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
          })}`;
        }
        if (ordersEl) ordersEl.textContent = toNumber(payload.kpis?.orders_completed).toLocaleString('en-IN');
        if (menuEl) menuEl.textContent = toNumber(payload.kpis?.menu_items).toLocaleString('en-IN');
      }

      function updateChartData(payload) {
        const soldRows = Array.isArray(payload.most_sold_items) ? payload.most_sold_items : [];
        const hourRows = Array.isArray(payload.sales_by_hour) ? payload.sales_by_hour : [];

        mostSoldItemsData = [['Item Name', 'Sales']];
        soldRows.forEach((row) => {
          mostSoldItemsData.push([String(row.item_name || 'Unknown'), toNumber(row.total_qty)]);
        });
        if (mostSoldItemsData.length === 1) {
          mostSoldItemsData.push(['No Data', 1]);
        }

        salesByHourData = [['Day', 'Sales']];
        hourRows.forEach((row) => {
          salesByHourData.push([String(row.day || row.hour || '-'), toNumber(row.total_sales)]);
        });
        if (salesByHourData.length === 1) {
          salesByHourData.push(['-', 0]);
        }
      }

      function updateMonthlyChartData(payload) {
        const monthlyRows = Array.isArray(payload.monthly_revenue) ? payload.monthly_revenue : [];
        monthlyRevenueData = [['Month', 'Revenue']];
        monthlyRows.forEach((row) => {
          monthlyRevenueData.push([String(row.month || row.label || '-'), toNumber(row.total_revenue || row.total_sales || row.total)]);
        });
        if (monthlyRevenueData.length === 1) {
          monthlyRevenueData.push(['-', 0]);
        }
      }

      function updateAllTimeMonthlyChartData(payload) {
        const monthlyRows = Array.isArray(payload.all_time_monthly_revenue) ? payload.all_time_monthly_revenue : [];
        allTimeMonthlyRevenueData = [['Month', 'Revenue']];
        monthlyRows.forEach((row) => {
          allTimeMonthlyRevenueData.push([String(row.month || row.label || '-'), toNumber(row.total_revenue || row.total_sales || row.total)]);
        });
        if (allTimeMonthlyRevenueData.length === 1) {
          allTimeMonthlyRevenueData.push(['-', 0]);
        }
      }

      async function fetchDashboardLiveData() {
        try {
          const response = await fetch(`dashboard-live-data.php?ts=${Date.now()}`, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
          });
          if (!response.ok) return;

          const payload = await response.json();
          if (!payload || payload.success !== true) return;

          liveTimestamp = payload.timestamp || '';
          updateKpiCards(payload);
          updateChartData(payload);
          updateMonthlyChartData(payload);
          updateAllTimeMonthlyChartData(payload);
          setChipText();
          setMonthlyChip();
          setAllTimeChip();
          drawDashboardCharts();
        } catch (error) {
          console.error('Dashboard live update failed:', error);
        }
      }

      function getChartTheme() {
        const isDark = document.body.classList.contains('dark');
        return {
          isDark: isDark,
          panelText: isDark ? '#dbe7ff' : '#1e293b',
          mutedText: isDark ? '#aabdda' : '#475569',
          grid: isDark ? '#2a3f69' : '#dbe3f1',
          donutCenter: isDark ? '#101a33' : '#ffffff'
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

      function drawDonutChart() {
        if (!document.getElementById('donutchart_msi')) return;
        const theme = getChartTheme();
        const isNarrow = window.innerWidth <= 576;
        const size = getChartSize('donutchart_msi', isNarrow ? 240 : 300);
        const data = google.visualization.arrayToDataTable(
          mostSoldItemsData.length > 1 ? mostSoldItemsData : [['Item Name', 'Sales'], ['No Data', 1]]
        );

        const options = {
          pieHole: 0.68,
          pieSliceText: isNarrow ? 'none' : 'percentage',
          fontName: 'Outfit',
          fontSize: 12,
          pieSliceTextStyle: { color: '#ffffff', fontSize: isNarrow ? 10 : 12 },
          chartArea: isNarrow
            ? { left: 8, top: 8, width: '100%', height: '82%' }
            : { left: 18, top: 16, width: '94%', height: '78%' },
          legend: isNarrow
            ? { position: 'none' }
            : { position: 'bottom', alignment: 'center', textStyle: { color: theme.mutedText, fontSize: 12 } },
          backgroundColor: 'transparent',
          pieSliceBorderColor: theme.donutCenter,
          pieStartAngle: -90,
          colors: ['#2563eb', '#ef4444', '#e69500', '#22c55e', '#8b5cf6']
        };

        if (size.width) options.width = size.width;
        if (size.height) options.height = size.height;

        const chart = new google.visualization.PieChart(document.getElementById('donutchart_msi'));
        chart.draw(data, options);
      }

      function drawSalesBarChart() {
        if (!document.getElementById('columnchart_material')) return;
        const theme = getChartTheme();
        const isNarrow = window.innerWidth <= 576;
        const size = getChartSize('columnchart_material', isNarrow ? 240 : 300);
        const data = google.visualization.arrayToDataTable(
          salesByHourData.length > 1 ? salesByHourData : [['Day', 'Sales'], ['-', 0]]
        );

        const options = {
          backgroundColor: 'transparent',
          chartArea: isNarrow
            ? { left: 40, top: 10, width: '90%', height: '72%', backgroundColor: 'transparent' }
            : { left: 58, top: 14, width: '88%', height: '76%', backgroundColor: 'transparent' },
          legend: { position: 'none' },
          colors: ['#e69500'],
          bar: { groupWidth: isNarrow ? '48%' : '56%' },
          hAxis: {
            title: isNarrow ? '' : 'Day',
            textStyle: { color: theme.mutedText, fontSize: isNarrow ? 10 : 12 },
            titleTextStyle: { color: theme.panelText, italic: false },
            slantedText: true,
            slantedTextAngle: 35,
            baselineColor: theme.grid,
            gridlines: { color: theme.grid }
          },
          vAxis: {
            title: 'Sales',
            minValue: 0,
            textStyle: { color: theme.mutedText, fontSize: isNarrow ? 10 : 12 },
            titleTextStyle: { color: theme.panelText, italic: false },
            baselineColor: theme.grid,
            gridlines: { color: theme.grid }
          }
        };

        if (size.width) options.width = size.width;
        if (size.height) options.height = size.height;

        const chart = new google.visualization.ColumnChart(document.getElementById('columnchart_material'));
        chart.draw(data, options);
      }

      function drawMonthlyRevenueChart() {
        if (!document.getElementById('monthly_revenue_chart')) return;
        const theme = getChartTheme();
        const isNarrow = window.innerWidth <= 576;
        const size = getChartSize('monthly_revenue_chart', isNarrow ? 260 : 340);
        const rawData = monthlyRevenueData.length > 1 ? monthlyRevenueData : [['Month', 'Revenue'], ['-', 0]];
        const displayData = rawData.map((row, index) => {
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

      function drawAllTimeMonthlyRevenueChart() {
        if (!document.getElementById('all_time_revenue_chart')) return;
        const theme = getChartTheme();
        const isNarrow = window.innerWidth <= 576;
        const size = getChartSize('all_time_revenue_chart', isNarrow ? 260 : 340);
        const rawData = allTimeMonthlyRevenueData.length > 1 ? allTimeMonthlyRevenueData : [['Month', 'Revenue'], ['-', 0]];
        const displayData = rawData.map((row, index) => {
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
        drawDashboardCharts();
      });

      window.addEventListener('admin-theme-change', function () {
        if (!chartsReady) return;
        drawDashboardCharts();
      });

      window.addEventListener('beforeunload', function () {
        if (refreshHandle) clearInterval(refreshHandle);
      });
    </script>
    <title>Restaurant Management</title>
</head>
<body>
<section id="sidebar">
    <a href="index.php" class="brand">
        <img src="../images/logo2.png" width="120" alt="">
    </a>
    <ul class="side-menu top">
        <li class="active"><a href="index.php"><i class='bx bxs-dashboard'></i><span class="text">Dashboard</span></a></li>
        <li><a href="manage-online-order.php"><i class='bx bxs-cart'></i><span class="text">Online Orders&nbsp;</span><?php if($row_online_order_notif>0){ ?><span class="num-ei"><?php echo $row_online_order_notif; ?></span><?php } ?></a></li>
        <li><a href="manage-category.php"><i class='bx bxs-category'></i><span class="text">Category</span></a></li>
        <li><a href="manage-food.php"><i class='bx bxs-food-menu'></i><span class="text">Food Menu</span></a></li>
        <li><a href="inventory.php"><i class='bx bxs-box'></i><span class="text">Inventory</span><?php if($row_stock_notif>0){ ?><span class="num-ei"><?php echo $row_stock_notif; ?></span><?php } ?></a></li>
        <li><a href="monthly-revenue.php"><i class='bx bx-line-chart'></i><span class="text">Monthly Revenue</span></a></li>
        <li><a href="manage-review.php"><i class="bx bx-star"></i><span class="text">Your Review</span></a></li>
        <li><a href="manage-repeat-rate.php"><i class="bx bx-bar-chart-alt-2"></i><span class="text">Your Repeat Rate</span></a></li>
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
                            if ($row_stock_notif > 0) {
                                $stock_message = $row_stock_notif == 1 ? 'Item is' : 'Items are';
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
            <div class="admin-avatar" title="<?php echo htmlspecialchars($restroname); ?>"><?php echo htmlspecialchars($restro_initial); ?></div>
        </div>
    </nav>

    <main>
        <div class="dashboard-grid">
          <a href="manage-category.php" class="kpi-card kpi-card-blue">
            <div class="kpi-media"><img src="../images/inventory.png" alt="Categories"><span class="kpi-badge">Live</span></div>
            <div class="kpi-meta"><h3 class="kpi-value" id="kpi-categories"><?php echo number_format($row_cat); ?></h3><p class="kpi-label">Categories</p></div>
          </a>

          <a href="monthly-revenue.php" class="kpi-card kpi-card-gold">
            <div class="kpi-media"><img src="../images/revenue.png" alt="Revenue"><span class="kpi-badge">Live</span></div>
            <div class="kpi-meta"><h3 class="kpi-value" id="kpi-revenue">Rs <?php echo number_format((float)($total_revenue['total_amount'] ?? 0), 2); ?></h3><p class="kpi-label">Revenue Generated</p></div>
          </a>

          <a href="manage-online-order.php?status=Delivered" class="kpi-card kpi-card-violet">
            <div class="kpi-media"><img src="../images/orders_completed.png" alt="Orders Completed"><span class="kpi-badge">Live</span></div>
            <div class="kpi-meta"><h3 class="kpi-value" id="kpi-orders"><?php echo number_format((int)$total_orders_delivered); ?></h3><p class="kpi-label">Orders Completed</p></div>
          </a>

          <a href="manage-food.php" class="kpi-card kpi-card-red">
            <div class="kpi-media"><img src="../images/folder2.png" alt="Menu Items"><span class="kpi-badge">Live</span></div>
            <div class="kpi-meta"><h3 class="kpi-value" id="kpi-menu"><?php echo number_format($row_item); ?></h3><p class="kpi-label">Menu Items</p></div>
          </a>
        </div>

        <div class="dashboard-charts">
          <div class="chart-panel">
            <div class="panel-head"><h3>Most Sold Items</h3><span class="panel-chip">Live Data</span></div>
            <div class="chart" id="donutchart_msi"></div>
          </div>
          <div class="chart-panel">
            <div class="panel-head"><h3>Sales By Day</h3><span class="panel-chip" id="sales-chip">Last 7 days</span></div>
            <div class="chart" id="columnchart_material"></div>
          </div>
          <div class="chart-panel chart-panel-wide">
            <div class="panel-head"><h3>Monthly Revenue</h3><span class="panel-chip" id="monthly-chip">Last 12 months</span></div>
            <div class="chart" id="monthly_revenue_chart"></div>
          </div>
          <div class="chart-panel chart-panel-wide">
            <div class="panel-head"><h3>Total Revenue By Month</h3><span class="panel-chip" id="all-time-chip">All time</span></div>
            <div class="chart" id="all_time_revenue_chart"></div>
          </div>
        </div>
    </main>
</section>
<script src="script-admin.js"></script>
</body>
</html>




