<?php include('../frontend/config/constants.php');   include('check-ban.php');?>
<?php //include('login-check.php'); ?>

<?php
$ei_order_notif = "SELECT order_status from tbl_eipay
					WHERE order_status='Pending' OR order_status='Processing'OR order_status='OnTheWay'";

$res_ei_order_notif = mysqli_query($conn, $ei_order_notif);

$row_ei_order_notif = mysqli_num_rows($res_ei_order_notif);

$online_order_notif = "SELECT order_status from order_manager
					WHERE order_status='Pending'OR order_status='Processing'OR order_status='OnTheWay' ";

$res_online_order_notif = mysqli_query($conn, $online_order_notif);

$row_online_order_notif = mysqli_num_rows($res_online_order_notif);

$stock_notif = "SELECT stock FROM tbl_food
				WHERE stock<50";

$res_stock_notif = mysqli_query($conn, $stock_notif);
$row_stock_notif = mysqli_num_rows($res_stock_notif);

//Message Notification
$message_notif = "SELECT message_status FROM message
				 WHERE message_status = 'unread'";
$res_message_notif = mysqli_query($conn, $message_notif);
$row_message_notif = mysqli_num_rows($res_message_notif);


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
    <title> Delivery-boy Management</title>
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
            
            <li >
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
            <li class="active">
                <a href="manage-delivery-payment.php">
                <i class="bx bx-rupee"></i>
                    <span class="text">Payment History</span>
                </a>
            </li>
            <li >
                <a href="manage-review.php">
                <i class="bx bx-star"></i>
                    <span class="text">Your Review</span>
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
            
            <div class="notification" onclick= "menuToggle();">
			<div class="action notif" onclick="menuToggle();">
        <i class='bx bxs-bell' onclick="menuToggle();"></i>
        <div class="notif_menu">
            <ul>
                <?php 
                // Check Stock Notifications
               

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
        <!-- NAVBAR -->

        <!-- MAIN -->
    <!-- MAIN -->
    <main>
    <div class="head-title">
        <div class="left">
            <h1>Payment Information</h1>
            <ul class="breadcrumb">
                <li>
                    <a href="index.php" class="clickable">
                        Dashboard 
                    </a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a class="active" href="manage-delivery-payment.php">Payment Information</a>
                </li>
            </ul>
        </div>
    </div>

    <br>

    <div class="table-data">
        <div class="order">
            <div class="head">
            </div>

            <table class="">
                <tr>
                    <th>Id</th>
                    <th>Order Id</th>
                    <th>Name</th>
                    <th>Salary</th>
                    <th>Payment Status</th>
                </tr>

                <?php 
                $delivery_boy_name = $_SESSION['delivery-boy'];
                // Query for tbl_delivery_payment
                $sql = "SELECT * FROM tbl_delivery_payment where username = '$delivery_boy_name'";

                // Execute Query
                $res = mysqli_query($conn, $sql);

                // Count the Rows
                $count = mysqli_num_rows($res);
                $sn = 1; // Serial Number starting at 1

                if($count > 0) {
                    // If payments are available
                    while($row = mysqli_fetch_assoc($res)) {
                        // Get all payment details
                        $id = $row['id'];
                        $order_id = $row['order_id'];
                        $name = $row['username'];
                        $salary = $row['salary'];
                        $payment_status = $row['payment_status'];
                        ?>

                        <tr>
                            <td><?php echo $sn++; ?>. </td>
                            <td><?php echo $order_id; ?></td>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $salary; ?></td>
                            <td>
                                <?php
                                if ($payment_status == 'unpaid') {
                                    echo "<span class='status process'>$payment_status</span>";
                                } else if ($payment_status == 'paid') {
                                    echo "<span class='status completed'>$payment_status</span>";
                                }
                                ?>
                            </td>
                           
                            
                        </tr>

                        <?php
                    }
                } else {
                    // No payment records found
                    echo "<tr><td colspan='5' class='error'>No Payments Available</td></tr>";
                }
                ?>
            </table>
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


