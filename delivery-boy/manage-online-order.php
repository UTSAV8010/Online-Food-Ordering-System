<?php include('../frontend/config/constants.php'); include('check-ban.php'); ?>
<?php //include('login-check.php'); ?>

<?php
$ei_order_notif = "SELECT order_status from tbl_eipay
                    WHERE order_status='Pending' OR order_status='Processing' OR order_status='OnTheWay'";
$res_ei_order_notif = mysqli_query($conn, $ei_order_notif);
$row_ei_order_notif = mysqli_num_rows($res_ei_order_notif);

$online_order_notif = "SELECT order_status from order_manager
                       WHERE order_status='Pending' OR order_status='Processing' OR order_status='OnTheWay'";
$res_online_order_notif = mysqli_query($conn, $online_order_notif);
$row_online_order_notif = mysqli_num_rows($res_online_order_notif);

$stock_notif = "SELECT stock FROM tbl_food WHERE stock<50";
$res_stock_notif = mysqli_query($conn, $stock_notif);
$row_stock_notif = mysqli_num_rows($res_stock_notif);

$message_notif = "SELECT message_status FROM message WHERE message_status = 'unread'";
$res_message_notif = mysqli_query($conn, $message_notif);
$row_message_notif = mysqli_num_rows($res_message_notif);

$delivery_boy_name = $_SESSION['delivery-boy'] ?? '';
$allowed_statuses = ['Pending', 'Processing', 'OnTheWay', 'Delivered', 'Cancelled'];
$status_filter = '';
if (isset($_GET['status'])) {
    $requested_status = trim((string)$_GET['status']);
    if (in_array($requested_status, $allowed_statuses, true)) {
        $status_filter = $requested_status;
    }
}
$remaining_only = isset($_GET['remaining']) && $_GET['remaining'] === '1';
$mine_only = isset($_GET['mine']) && $_GET['mine'] === '1';
$page_title = 'Online Orders';
if ($remaining_only) {
    $page_title = 'Delivery Remaining Orders';
} elseif ($status_filter === 'Delivered') {
    $page_title = 'Completed Orders';
}
$orders_link = 'manage-online-order.php';
if ($status_filter !== '' || $mine_only || $remaining_only) {
    $params = [];
    if ($status_filter !== '') {
        $params[] = 'status=' . urlencode($status_filter);
    }
    if ($remaining_only) {
        $params[] = 'remaining=1';
    }
    if ($mine_only) {
        $params[] = 'mine=1';
    }
    $orders_link .= '?' . implode('&', $params);
}

function renderDeliveryOnlineOrderRows($conn, $limit, $offset, $statusFilter, $mineOnly, $deliveryBoy, $remainingOnly)
{
    ob_start();

    $limit = (int)$limit;
    $offset = (int)$offset;
    $where = "WHERE 1=1";
    if ($remainingOnly && $statusFilter === '') {
        $where .= " AND order_status IN ('Pending', 'Processing', 'OnTheWay')";
    } elseif ($statusFilter !== '') {
        $where .= " AND order_status='$statusFilter'";
    }
    if ($mineOnly && $deliveryBoy !== '') {
        $where .= " AND delivery_boy_name='$deliveryBoy'";
    }
    $query = "SELECT * FROM order_manager $where ORDER BY order_id DESC LIMIT $limit OFFSET $offset";
    $user_result = mysqli_query($conn, $query);

    if ($user_result && mysqli_num_rows($user_result) > 0) {
        while ($user_fetch = mysqli_fetch_assoc($user_result)) {
            $order_id = $user_fetch['order_id'];
            $cus_name = $user_fetch['cus_name'];
            $cus_add1 = $user_fetch['cus_add1'];
            $cus_phone = $user_fetch['cus_phone'];
            $payment_status = $user_fetch['payment_status'];
            $order_status = $user_fetch['order_status'];
            $total_amount = $user_fetch['total_amount'];
            $location = $user_fetch['location'];
            $delivery_boy_name = $user_fetch['delivery_boy_name'];
            ?>
<tr>
    <td><?php echo htmlspecialchars((string)$order_id); ?></td>
    <td><?php echo htmlspecialchars((string)$cus_name); ?></td>
    <td><?php echo htmlspecialchars((string)$cus_add1); ?></td>
    <td><?php echo htmlspecialchars((string)$cus_phone); ?></td>
    <td>
        <?php
        if (!empty($location) && strpos($location, ',') !== false) {
            [$latitude, $longitude] = array_map('trim', explode(',', $location, 2));
            $lat = (float)$latitude;
            $lng = (float)$longitude;
            echo "<div id='map-" . htmlspecialchars((string)$order_id) . "' class='order-map' data-lat='" . htmlspecialchars((string)$lat) . "' data-lng='" . htmlspecialchars((string)$lng) . "'></div>";
        } else {
            echo "Location not available.";
        }
        ?>
    </td>
    <td>
        <?php
        if ($payment_status == 'successful' || $payment_status == 'upi') {
            echo "<span class='status completed'>$payment_status</span>";
        } else if ($payment_status == 'Refunded') {
            echo "<span class='status pending'>$payment_status</span>";
        } else if ($payment_status == 'cod') {
            echo "<span class='status process'>$payment_status</span>";
        }
        ?>
    </td>
    <td>
        <?php
        if ($order_status == 'Pending') {
            echo "<span class='status process'>$order_status</span>";
        } else if ($order_status == 'Processing') {
            echo "<span class='status pending'>$order_status</span>";
        } else if ($order_status == 'OnTheWay') {
            echo "<span class='status completed'>$order_status</span>";
        } else if ($order_status == 'Delivered') {
            echo "<span class='status completed'>$order_status</span>";
        } else if ($order_status == 'Cancelled') {
            echo "<span class='status cancelled'>$order_status</span>";
        }
        ?>
    </td>
    <td><?php echo htmlspecialchars((string)$total_amount); ?></td>
    <td>
        <?php
        if ($order_status == 'Cancelled' || $order_status == 'Delivered' || $order_status == 'Pending') {
            echo 'No action';
        } else if (!empty($delivery_boy_name) && $delivery_boy_name !== $_SESSION['delivery-boy']) {
            echo 'Delivery is already taken by ' . htmlspecialchars((string)$delivery_boy_name) . '.';
        } else if (empty($delivery_boy_name)) {
            echo "<a href='take-order.php?id=" . urlencode((string)$order_id) . "' class='button-8'>Take Order</a>";
        } else if ($order_status == 'OnTheWay' && $delivery_boy_name === $_SESSION['delivery-boy']) {
            echo "<a href='finish-order.php?id=" . urlencode((string)$order_id) . "' class='button-8'>Finish Delivery</a>";
        }
        ?>
    </td>
</tr>
<?php
        }
    } else {
        echo "<tr><td colspan='9'>No orders found.</td></tr>";
    }

    return ob_get_clean();
}

$orders_per_page = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $orders_per_page;

$count_where = "WHERE 1=1";
if ($status_filter !== '') {
    $count_where .= " AND order_status='$status_filter'";
}
if ($remaining_only && $status_filter === '') {
    $count_where .= " AND order_status IN ('Pending', 'Processing', 'OnTheWay')";
}
if ($mine_only && $delivery_boy_name !== '') {
    $count_where .= " AND delivery_boy_name='$delivery_boy_name'";
}
$count_query = "SELECT COUNT(*) AS total_orders FROM order_manager $count_where";
$count_result = mysqli_query($conn, $count_query);
$count_row = $count_result ? mysqli_fetch_assoc($count_result) : ['total_orders' => 0];
$total_orders = (int)($count_row['total_orders'] ?? 0);
$total_pages = max(1, (int)ceil($total_orders / $orders_per_page));

if ($page > $total_pages) {
    $page = $total_pages;
    $offset = ($page - 1) * $orders_per_page;
}

if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    header('Content-Type: application/json');
    echo json_encode([
        'rows_html' => renderDeliveryOnlineOrderRows($conn, $orders_per_page, $offset, $status_filter, $mine_only, $delivery_boy_name, $remaining_only),
        'current_page' => $page,
        'total_pages' => $total_pages
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style-admin.css">
    <link rel="icon" type="image/png" href="../images/logo2.png">
    <title>Delivery-boy Management</title>
    <style>
        a.clickable {
            color: gray !important;
            pointer-events: auto !important;
            text-decoration: none !important;
        }

        a.clickable:hover {
            color: #007bff !important;
        }

        .orders-table-wrap {
            overflow-x: auto;
        }

        .orders-table {
            min-width: 1150px;
        }
    </style>
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
            <li class="active">
                <a href="manage-online-order.php">
                    <i class='bx bxs-cart'></i>
                    <span class="text">Online Orders&nbsp;</span>
                    <?php if($row_online_order_notif>0){ ?><span class="num-ei"><?php echo $row_online_order_notif; ?></span><?php } else { ?><span class=""></span><?php } ?>
                </a>
            </li>
            <li><a href="manage-delivery-payment.php"><i class="bx bx-rupee"></i><span class="text">Payment History</span></a></li>
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
                    } else {
                        echo "<span class=''></span>";
                    }
                    ?>
                </div>
            </div>
        </nav>

        <main>
            <div class="head-title">
                <div class="left">
                    <h1><?php echo htmlspecialchars($page_title); ?></h1>
                    <ul class="breadcrumb">
                        <li><a href="index.php" class="clickable">Dashboard</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="<?php echo $orders_link; ?>"><?php echo htmlspecialchars($page_title); ?></a></li>
                    </ul>
                </div>
            </div>
            <br />

            <div class="table-data">
                <div class="order">
                    <div class="orders-table-wrap" id="orders-table-wrapper">
                        <table class="orders-table" id="orders-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Phone</th>
                                    <th>Location</th>
                                    <th>Payment Status</th>
                                    <th>Order Status</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="orders-table-body"><?php echo renderDeliveryOnlineOrderRows($conn, $orders_per_page, $offset, $status_filter, $mine_only, $delivery_boy_name, $remaining_only); ?></tbody>
                        </table>
                    </div>
                    <div class="orders-pagination-row">
                        <div class="table-pagination ajax-pagination" id="orders-pagination"
                            data-current-page="<?php echo $page; ?>"
                            data-total-pages="<?php echo $total_pages; ?>">
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </section>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        window.orderPageData = {
            perPage: <?php echo $orders_per_page; ?>,
            currentPage: <?php echo $page; ?>,
            totalPages: <?php echo $total_pages; ?>
        };
    </script>
    <script src="script-admin.js"></script>
</body>
</html>



