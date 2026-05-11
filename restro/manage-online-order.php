<?php include('../frontend/config/constants.php'); ?>
<?php include('login-check.php'); ?>

<?php
$restroname = $_SESSION['restro-name'] ?? '';
$ei_order_notif = "SELECT order_status from tbl_eipay
					WHERE order_status='Pending' OR order_status='Processing'OR order_status='OnTheWay'";

$res_ei_order_notif = mysqli_query($conn, $ei_order_notif);

$row_ei_order_notif = mysqli_num_rows($res_ei_order_notif);

$online_order_notif = "SELECT DISTINCT om.order_id 
                       FROM order_manager om
                       JOIN online_orders_new oon 
                       ON om.order_id = oon.order_id
                       WHERE (om.order_status='Pending' 
                              OR om.order_status='Processing' 
                              OR om.order_status='OnTheWay')
                       AND oon.restro_name='$restroname'";

$res_online_order_notif = mysqli_query($conn, $online_order_notif);

$row_online_order_notif = mysqli_num_rows($res_online_order_notif);

$stock_notif = "SELECT stock FROM tbl_restro_food_item
				WHERE stock<=$low_stock_threshold and restro_name = '$restroname'";

$res_stock_notif = mysqli_query($conn, $stock_notif);
$row_stock_notif = mysqli_num_rows($res_stock_notif);

//Message Notification
$message_notif = "SELECT message_status FROM message
				 WHERE message_status = 'unread'";
$res_message_notif = mysqli_query($conn, $message_notif);
$row_message_notif = mysqli_num_rows($res_message_notif);

$allowed_statuses = ['Pending', 'Processing', 'OnTheWay', 'Delivered', 'Cancelled'];
$status_filter = '';
$remaining_only = isset($_GET['remaining']) && $_GET['remaining'] === '1';
if (isset($_GET['status'])) {
    $requested_status = trim((string)$_GET['status']);
    if (in_array($requested_status, $allowed_statuses, true)) {
        $status_filter = $requested_status;
    }
}
$status_sql = '';
$order_status_filter_sql = '';
$page_title = 'Online Orders';
if ($remaining_only) {
    $status_sql = " AND om.order_status IN ('Pending','Processing','OnTheWay')";
    $order_status_filter_sql = " AND order_status IN ('Pending','Processing','OnTheWay')";
    $page_title = 'New Online Orders';
} elseif ($status_filter !== '') {
    $status_sql = " AND om.order_status='$status_filter'";
    $order_status_filter_sql = " AND order_status='$status_filter'";
    $page_title = $status_filter === 'Delivered' ? 'Completed Orders' : 'Online Orders';
}
$orders_link = 'manage-online-order.php';
if ($remaining_only || $status_filter !== '') {
    $params = [];
    if ($remaining_only) {
        $params[] = 'remaining=1';
    }
    if ($status_filter !== '') {
        $params[] = 'status=' . urlencode($status_filter);
    }
    $orders_link .= '?' . implode('&', $params);
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="style-admin.css">
    <link rel="icon" type="image/png" href="../images/logo2.png">

    <title> Restaurant-management</title>
    <style>
        a.clickable {
            color: gray !important;
            pointer-events: auto !important;
            text-decoration: none !important;
        }

        a.clickable:hover {
            color: #007bff !important;
        }

        .iframe-map {
            width: 100%;
            height: 150px;
            border: 0;
        }
    </style>
</head>

<body>


    <!-- SIDEBAR -->
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
                    <?php 
					if($row_online_order_notif>0)
					{
						?>
                    <span class="num-ei"><?php echo $row_online_order_notif; ?></span>
                    <?php
					}
					else
					{
						?>
                    <span class=""> </span>
                    <?php
					}
					?>
                </a>
            </li>
            
           
            <li>
                <a href="manage-category.php">
                    <i class='bx bxs-category'></i>
                    <span class="text">Category</span>
                </a>
            </li>
            
            <li >
                <a href="manage-food.php">
                    <i class='bx bxs-food-menu'></i>
                    <span class="text">Food Menu</span>
                </a>
            </li>
            <li class="">
                <a href="inventory.php">
                    <i class='bx bxs-box'></i>
                    <span class="text">Inventory</span>
                    <?php 
					if($row_stock_notif>0)
					{
						?>
                    <span class="num-ei">
                        <?php echo $row_stock_notif; ?>
                    </span>
                    <?php
					}
					else
					{
						?>
                    <span class=""> </span>
                    <?php
					}
					?>
                </a>
            </li>
           
            
        
            <li   >
                <a href="manage-review.php">
                <i class="bx bx-star"></i>
                    <span class="text">Your Review</span>
                </a>
            </li>
            <li  class="" >
                <a href="manage-repeat-rate.php">
                <i class="bx bx-bar-chart-alt-2"></i>
                    <span class="text">Your Repeat Rate</span>
                </a>
            </li>
            <li class="">
                <a href="update-password.php">
                <i class="bx bx-lock"></i>
                    <span class="text">Change Password</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>            
                <a href="settings.php"><i class='bx bxs-cog'></i>
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
    <!-- SIDEBAR -->



    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
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
            
            <div class="notification">
            <div class="action notif" onclick="menuToggle();">
        <i class='bx bxs-bell' onclick="menuToggle();"></i>
        <div class="notif_menu">
            <ul>
                <?php 
                // Check Stock Notifications
                if ($row_stock_notif > 0) {
                    $stock_message = $row_stock_notif == 1 ? "Item is" : "Items are";
                    echo "<li><a href='inventory.php?low=1'>$row_stock_notif&nbsp;$stock_message running out of stock</a></li>";
                }

                // Check Online Orders
                if ($row_online_order_notif > 0) {
                    echo "<li><a href='manage-online-order.php?remaining=1'>$row_online_order_notif&nbsp;New Online Order</a></li>";
                }

                // Check EI Orders
                if ($row_ei_order_notif > 0) {
                    echo "<li><a href='manage-online-order.php'>$row_ei_order_notif&nbsp;New EI Order</a></li>";
                }
                ?>
            </ul>
        </div>
        <?php 
        // Calculate total notifications
        $total_notif = $row_stock_notif + $row_online_order_notif + $row_ei_order_notif;
        if ($total_notif > 0) {
            echo "<span class='num'>$total_notif</span>";
        } else {
            echo "<span class=''></span>";
        }
        ?>
    </div>
            </div>

        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
    <div class="head-title">

    <div class="left">
            <h1><?php echo htmlspecialchars($page_title); ?></h1>
            <ul class="breadcrumb">
                <li>
                    <a href="index.php" class="clickable">Dashboard</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a class="active" href="<?php echo $orders_link; ?>"><?php echo htmlspecialchars($page_title); ?></a>
                </li>
            </ul>
        </div>
    </div>
    <br />

    <div class="table-data">
        <div class="order">
            <div class="head">
                <table class="">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Location</th>
                            <th>Delivery Boy</th>
                            <th>Payment Status</th>
                            <th>Order Status</th>
                            <th>Total</th>
                            <th>Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch all orders for the restaurant
                        $order_query = "SELECT DISTINCT oo.order_id
                                        FROM `online_orders_new` oo
                                        INNER JOIN order_manager om ON oo.order_id = om.order_id
                                        WHERE oo.restro_name='$restroname' $status_sql
                                        ORDER BY oo.order_id DESC"; 
                        $result = mysqli_query($conn, $order_query);
                        
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $orderid = $row['order_id'];

                                // Fetch order details for each order_id
                                $query = "SELECT * FROM `order_manager` WHERE order_id='$orderid'$order_status_filter_sql";
                                $user_result = mysqli_query($conn, $query);

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
                            <td><?php echo $order_id; ?></td>
                            <td><?php echo $cus_name; ?></td>
                            <td><?php echo $cus_add1; ?></td>
                            <td><?php echo $cus_phone; ?></td>
                            <td>
                                <?php
                                if (!empty($location)) {
                                    list($latitude, $longitude) = explode(',', $location);
                                    echo "
                                        <div id='map-{$order_id}' style='height: 150px; width: 250px;'></div>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function () {
                                                var map = L.map('map-{$order_id}').setView([$latitude, $longitude], 13);
                                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                                    maxZoom: 19,
                                                    attribution: '© OpenStreetMap'
                                                }).addTo(map);
                                                L.marker([$latitude, $longitude]).addTo(map)
                                                    .bindPopup('Latitude: $latitude, Longitude: $longitude')
                                                    .openPopup();
                                            });
                                        </script>
                                    ";
                                } else {
                                    echo "Location not available.";
                                }
                                ?>
                            </td>
                            <td>
                                <?php echo (!empty($delivery_boy_name)) ? $delivery_boy_name : "Order is not taken by any delivery boy."; ?>
                            </td>
                            <td>
                                <?php
                                if ($payment_status == "successful" || $payment_status == "upi") {
                                    echo "<span class='status completed'>$payment_status</span>";
                                } else if ($payment_status == "Refunded") {
                                    echo "<span class='status pending'>$payment_status</span>";
                                }
                                else if ($payment_status == "cod") {
                                    echo "<span class='status process'>$payment_status</span>";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($order_status == "Pending") {
                                    echo "<span class='status process'>$order_status</span>";
                                } else if ($order_status == "Processing") {
                                    echo "<span class='status pending'>$order_status</span>";
                                } else if ($order_status == "Delivered") {
                                    echo "<span class='status completed'>$order_status</span>";
                                } else if ($order_status == "OnTheWay") {
                                    echo "<span class='status completed'>$order_status</span>";
                                } else if ($order_status == "Cancelled") {
                                    echo "<span class='status cancelled'>$order_status</span>";
                                }
                                ?>
                                <br><br>
                                <?php if ( $order_status != "Delivered" && $order_status != "Cancelled" ) { ?>
                                <span>
                                    <a href="<?php echo SITEURL; ?>update-online-order.php?id=<?php echo $order_id; ?>"
                                        class="button-8" role="button">Update</a>
                                </span>
                                <?php } ?>
                            </td>
                            <td>
    <?php
    // Fetch total amount from online_orders_new
    $total_query = "SELECT SUM(total_amount) as total_amount FROM `online_orders_new` WHERE `order_id`='$order_id' AND restro_name='$restroname'";
    $total_result = mysqli_query($conn, $total_query);
    $total_fetch = mysqli_fetch_assoc($total_result);
    
    echo $total_fetch['total_amount'] ? $total_fetch['total_amount'] : 'N/A';
    ?>
</td>

                            <td>
                                <table class='tbl-full'>
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $order_query_items = "SELECT * FROM `online_orders_new` WHERE `order_id`='$order_id' AND restro_name='$restroname' ORDER BY order_id DESC";
                                        $order_result_items = mysqli_query($conn, $order_query_items);

                                        while ($order_fetch = mysqli_fetch_assoc($order_result_items)) {
                                            echo "
                                                <tr>
                                                    <td>{$order_fetch['Item_Name']}</td>
                                                    <td>{$order_fetch['Price']}</td>
                                                    <td>{$order_fetch['Quantity']}</td>
                                                </tr>
                                            ";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <?php
                                }
                            }
                        } else {
                            echo "<tr><td colspan='10'>No orders found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>






        <!-- MAIN -->
    </section>
    <!-- CONTENT -->


    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="script-admin.js"></script>
</body>

</html>




