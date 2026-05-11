<?php include('../frontend/config/constants.php'); ?>
<?php //include('login-check.php'); ?>

<?php
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
                       AND oon.restro_name='Pasar Kita'";

$res_online_order_notif = mysqli_query($conn, $online_order_notif);

$row_online_order_notif = mysqli_num_rows($res_online_order_notif);

$stock_notif = "SELECT stock FROM tbl_food
				WHERE stock<=$low_stock_threshold";

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
$page_title = 'Online Orders';
if ($remaining_only) {
    $status_sql = " AND om.order_status IN ('Pending','Processing','OnTheWay')";
    $page_title = 'New Online Orders';
} elseif ($status_filter !== '') {
    $status_sql = " AND om.order_status='$status_filter'";
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

function renderOnlineOrderRows($conn, $limit, $offset, $statusSql)
{
    ob_start();

    $limit = (int)$limit;
    $offset = (int)$offset;
    $statusSql = $statusSql !== '' ? $statusSql : '';
    $order_query = "SELECT DISTINCT oo.order_id
                    FROM `online_orders_new` oo
                    INNER JOIN order_manager om ON oo.order_id = om.order_id
                    WHERE oo.restro_name='Pasar Kita' $statusSql
                    ORDER BY oo.order_id DESC
                    LIMIT $limit OFFSET $offset";
    $result = mysqli_query($conn, $order_query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $orderid = (int)$row['order_id'];
            $query = "SELECT * FROM `order_manager` WHERE order_id='$orderid' LIMIT 1";
            $user_result = mysqli_query($conn, $query);

            if ($user_fetch = mysqli_fetch_assoc($user_result)) {
                $order_id = $user_fetch['order_id'];
                $cus_name = $user_fetch['cus_name'];
                $cus_add1 = $user_fetch['cus_add1'];
                $cus_phone = $user_fetch['cus_phone'];
                $payment_status = $user_fetch['payment_status'];
                $order_status = $user_fetch['order_status'];
                $location = $user_fetch['location'];
                $delivery_boy_name = $user_fetch['delivery_boy_name'];
                ?>
<tr>
    <td><?php echo htmlspecialchars($order_id); ?></td>
    <td><?php echo htmlspecialchars($cus_name); ?></td>
    <td><?php echo htmlspecialchars($cus_add1); ?></td>
    <td><?php echo htmlspecialchars($cus_phone); ?></td>
    <td>
        <?php
                    if (!empty($location) && strpos($location, ',') !== false) {
                        [$latitude, $longitude] = array_map('trim', explode(',', $location, 2));
                        $lat = (float)$latitude;
                        $lng = (float)$longitude;
                        echo "<div id='map-" . htmlspecialchars($order_id) . "' class='order-map' data-lat='" . htmlspecialchars((string)$lat) . "' data-lng='" . htmlspecialchars((string)$lng) . "'></div>";
                    } else {
                        echo "Location not available.";
                    }
                    ?>
    </td>
    <td><?php echo !empty($delivery_boy_name) ? htmlspecialchars($delivery_boy_name) : "Order is not taken by any delivery boy."; ?>
      </td>
      <td>
          <?php
                      $transaction_id = trim((string) ($user_fetch['transaction_id'] ?? ''));
                      $upi_id = '';
                      $upi_ref = '';
                      if (preg_match('/UPI\\s*:\\s*([^|]+)\\|\\s*UTR\\s*:\\s*(.+)$/i', $transaction_id, $upiMatch)) {
                          $upi_id = trim($upiMatch[1] ?? '');
                          $upi_ref = trim($upiMatch[2] ?? '');
                      }
                      if ($upi_id !== '' || $upi_ref !== '') {
                          echo "UPI ID: " . htmlspecialchars($upi_id !== '' ? $upi_id : 'N/A') . "<br>";
                          echo "UPI Ref: " . htmlspecialchars($upi_ref !== '' ? $upi_ref : 'N/A');
                      } else {
                          echo $transaction_id !== '' ? htmlspecialchars($transaction_id) : 'N/A';
                      }
                      ?>
      </td>
      <td>
        <?php
                    if ($payment_status == "successful" || $payment_status == "upi") {
                        echo "<span class='status completed'>$payment_status</span>";
                    } else if ($payment_status == "Refunded") {
                        echo "<span class='status pending'>$payment_status</span>";
                    } else if ($payment_status == "cod") {
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
                    } else if ($order_status == "Delivered" || $order_status == "OnTheWay") {
                        echo "<span class='status completed'>$order_status</span>";
                    } else if ($order_status == "Cancelled") {
                        echo "<span class='status cancelled'>$order_status</span>";
                    }
                    ?>
        <br><br>
        <?php if ($order_status != "Delivered" && $order_status != "Cancelled") { ?>
        <span>
            <a href="<?php echo SITEURL; ?>update-online-order.php?id=<?php echo urlencode((string)$order_id); ?>" class="button-8"
                role="button">Update</a>
        </span>
        <?php } ?>
    </td>
    <td>
        <?php
                    $total_query = "SELECT SUM(total_amount) as total_amount FROM `online_orders_new` WHERE `order_id`='$order_id' AND restro_name='Pasar Kita'";
                    $total_result = mysqli_query($conn, $total_query);
                    $total_fetch = mysqli_fetch_assoc($total_result);
                    echo !empty($total_fetch['total_amount']) ? htmlspecialchars($total_fetch['total_amount']) : 'N/A';
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
                            $order_query_items = "SELECT * FROM `online_orders_new` WHERE `order_id`='$order_id' AND restro_name='Pasar Kita' ORDER BY order_id DESC";
                $order_result_items = mysqli_query($conn, $order_query_items);

                while ($order_fetch = mysqli_fetch_assoc($order_result_items)) {
                    echo "<tr>
                            <td>" . htmlspecialchars($order_fetch['Item_Name']) . "</td>
                            <td>" . htmlspecialchars($order_fetch['Price']) . "</td>
                            <td>" . htmlspecialchars($order_fetch['Quantity']) . "</td>
                        </tr>";
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
        echo "<tr><td colspan='11'>No orders found.</td></tr>";
    }

    return ob_get_clean();
}

$orders_per_page = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $orders_per_page;
$count_query = "SELECT COUNT(DISTINCT oo.order_id) AS total_orders
                FROM `online_orders_new` oo
                INNER JOIN order_manager om ON oo.order_id = om.order_id
                WHERE oo.restro_name='Pasar Kita' $status_sql";
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
        'rows_html' => renderOnlineOrderRows($conn, $orders_per_page, $offset, $status_sql),
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
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="style-admin.css">
    <link rel="icon" type="image/png" href="../images/logo2.png">

    <title> Admin</title>
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

        .orders-table-wrap {
            overflow-x: auto;
        }

        .orders-table {
            min-width: 1300px;
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
            <li>
                <a href="manage-admin.php">
                    <i class='bx bxs-group'></i>
                    <span class="text">Admin Panel</span>
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
                    <span class="num-ei">
                        <?php echo $row_online_order_notif; ?>
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
            <li  class="" >
                <a href="manage-repeat-rate.php">
                <i class="bx bx-bar-chart-alt-2"></i>
                    <span class="text">Your Repeat Rate</span>
                </a>
            </li>
            <li>
                <a href="manage-ei-order.php">
                    <i class='bx bxs-user'></i>
                    <span class="text">Users&nbsp;&nbsp;&nbsp;

                    </span>
                    <?php 
					if($row_ei_order_notif>0)
					{
						?>
                    <span class="num-ei">
                        <?php echo $row_ei_order_notif; ?>
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
                <i class='bx bx-dish'></i> <!-- Dish -->


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
            <li >
                <a href="manage-delivery-payment.php">
                <i class="bx bx-rupee"></i>
                    <span class="text">Payment History</span>
                </a>
            </li>
            <li   >
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
            <div class="fetch_message">
                <div class="action_message notfi_message">
                    <a href="messages.php"><i class='bx bxs-envelope'></i></a>
                    <?php 

					if($row_message_notif>0)
					{
						?>
                    <span class="num">
                        <?php echo $row_message_notif; ?>
                    </span>
                    <?php
					}
					else
					{
						?>
                    <span class=""></span>
                    <?php

					}
					?>

                </div>

            </div>
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
            <div class="orders-table-wrap" id="orders-table-wrapper">
                <table class="orders-table" id="orders-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Location</th>
                            <th>Delivery Boy</th>
                            <th>Payment Status</th>
                            <th>Payment Details</th>
                            <th>Order Status</th>
                            <th>Total</th>
                            <th>Order Items</th>
                        </tr>
                    </thead>
                    <tbody id="orders-table-body"><?php echo renderOnlineOrderRows($conn, $orders_per_page, $offset, $status_sql); ?></tbody>
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







        <!-- MAIN -->
    </section>
    <!-- CONTENT -->


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







