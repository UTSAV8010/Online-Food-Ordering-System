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

$stock_notif = "SELECT stock FROM tbl_restro_food_item
				WHERE stock<=$low_stock_threshold";

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

    <title>Admin </title>
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
            <li>
                <a href="manage-admin.php">
                    <i class='bx bxs-group'></i>
                    <span class="text">Admin Panel</span>
                </a>
            </li>
            <li class="">
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
            <li class="active">
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
                    <a href=""><i class='bx bxs-envelope'></i></a>
                    <?php 

					if($row_message_notif>0)
					{
						?>
                    <span class="num"><?php echo $row_message_notif; ?></span>
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
        $total_notif =  $row_online_order_notif + $row_ei_order_notif;
        if ($total_notif > 0) {
            echo "<span class='num'>$total_notif</span>";
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
            <h1>Restro Review Information</h1>
            <ul class="breadcrumb">
                <li>
                    <a href="index.php" class="clickable">Dashboard</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a class="active" href="manage-restro-review.php">Restro Review Information</a>
                </li>
            </ul>
        </div>
    </div>

    <br>
    
    <div class="table-data">
        <div class="order">
            <table class="">
                <tr>
                    <th>Id</th>
                
                    <th>Restaurant Name</th>
                    <th>Review Message</th>
                    <th>Rating</th>
                    <th>Customer Name</th>
                    <th>Created At</th>
                </tr>

                <?php 
                $ratings = [0, 0, 0, 0, 0]; // To store count of each star rating
                $total_rating = 0; // Sum of ratings
                $total_reviews = 0; // Number of reviews

                // Query for tbl_review_restro
                $sql = "SELECT * FROM tbl_review_restro ";
                $res = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($res);
                $sn = 1;

                if ($count > 0) {
                    while ($row = mysqli_fetch_assoc($res)) {
                        $id = $row['id'];
                      
                        $restro_name = $row['restro_name'];
                        $review_message = $row['description'];
                        $review_star = $row['rating_star'];
                        $customer_name = $row['customer_name'];
                        $created_at = $row['created_at'];

                        // Track rating count
                        if ($review_star >= 1 && $review_star <= 5) {
                            $ratings[$review_star - 1]++;
                            $total_rating += $review_star;
                            $total_reviews++;
                        }
                        ?>

                        <tr>
                            <td><?php echo $sn++; ?>. </td>
                          
                            <td><?php echo $restro_name; ?></td>
                            <td><?php echo $review_message; ?></td>
                            <td>
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    echo ($i <= $review_star) ? "&#9733;" : "&#9734;";
                                }
                                ?>
                            </td>
                            <td><?php echo $customer_name; ?></td>
                            <td><?php echo $created_at; ?></td>
                        </tr>

                        <?php
                    }
                    // Calculate overall average rating
                    $average_rating = ($total_reviews > 0) ? round($total_rating / $total_reviews, 1) : 0;
                } else {
                    echo "<tr><td colspan='7' class='error'>No Reviews Available</td></tr>";
                    $average_rating = 0;
                }
                ?>
            </table>
        </div>
    </div>

    <!-- Overall Rating Display -->
    <div style="text-align: center; margin-top: 20px;">
        <h2>Overall Rating: <?php echo $average_rating; ?> / 5</h2>
        <p style="font-size: 24px;">
            <?php 
            for ($i = 1; $i <= 5; $i++) {
                echo ($i <= $average_rating) ? "&#9733;" : "&#9734;";
            }
            ?>
        </p>
    </div>

</main>


<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('ratingChart').getContext('2d');

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
            datasets: [{
                label: 'Ratings',
                data: [<?php echo implode(",", $ratings); ?>],
                backgroundColor: [
                    'red', 'orange', 'yellow', 'lightgreen', 'green'
                ],
                hoverOffset: 4
            }]
        }
    });
</script>


<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('ratingChart').getContext('2d');

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
            datasets: [{
                label: 'Ratings',
                data: [<?php echo implode(",", $ratings); ?>],
                backgroundColor: [
                    'red', 'orange', 'yellow', 'lightgreen', 'green'
                ],
                hoverOffset: 4
            }]
        }
    });
</script>


                        




        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

   
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="script-admin.js"></script>
</body>

</html>



