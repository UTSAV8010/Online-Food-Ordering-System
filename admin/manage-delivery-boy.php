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


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
            <li>
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
            <li  class="" >
                <a href="manage-repeat-rate.php">
                <i class="bx bx-bar-chart-alt-2"></i>
                    <span class="text">Your Repeat Rate</span>
                </a>
            </li>
            <li >
                <a href="manage-ei-order.php">
                    <i class='bx bxs-user'></i>
                    <span class="text">Users&nbsp;&nbsp;&nbsp;

                    </span>
                    <?php 
					if($row_ei_order_notif>0)
					{
						?>
                    <span class="num-ei"><?php echo $row_ei_order_notif; ?></span>
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
            <li class="active">
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
            <h1>Delivery Boy's Information</h1>
            <ul class="breadcrumb">
                <li><a href="index.php" class="clickable">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="manage-delivery-boy.php">Delivery Boy's Information</a></li>
            </ul>
        </div>
    </div>
    <br>
    <div class="table-data">
        <div class="order">
            <div class="head"></div>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile Number</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>User Role</th>
                    <th>Aadhaar Image</th>
                    <th>Actions</th>
                </tr>
                <?php
                $sql = "SELECT * FROM tbl_delivery_boy";
                $res = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($res);
                $sn = 1;
                if ($count > 0) {
                    while ($row = mysqli_fetch_assoc($res)) {
                        $id = $row['id'];
                        $name = $row['name'];
                        $email = $row['email'];
                        $mobile_number = $row['mobile_number'];
                        $address = $row['address'];
                        $status = $row['status'];
                        $user_role = $row['user_role'];
                        $adhar_image = $row['adhar_image'];
                        $file_path = "../delivery-boy/$adhar_image";
                        ?>
                        <tr>
                            <td><?php echo $sn++; ?>.</td>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $email; ?></td>
                            <td><?php echo $mobile_number; ?></td>
                            <td><?php echo $address; ?></td>
                            <td><?php echo ucfirst($status); ?></td>
                            <td><?php echo $user_role == 1 ? 'Active' : 'Blocked'; ?></td>
                            <td>
                                <?php if (file_exists($file_path)) { ?>
                                    <img src="<?php echo $file_path; ?>" alt="Aadhaar Image" style="width: 100px; cursor: pointer;" onclick="openModal('<?php echo $file_path; ?>')">
                                <?php } else { ?>
                                    <p>Image not found</p>
                                <?php } ?>
                            </td>
                            <td class="table-actions">
                                <?php if ($status == 'not_verified') { ?>
                                    <a href="<?php echo SITEURL; ?>verify-delivery-boy.php?id=<?php echo $id; ?>" class="button-5" role="button">Verify</a>
                                    <a href="<?php echo SITEURL; ?>delete-delivery-boy.php?id=<?php echo $id; ?>" class="button-7" role="button">Delete</a>
                                <?php } elseif ($user_role == 1) { ?>
                                    <a href="<?php echo SITEURL; ?>update-delivery-boy-status.php?id=<?php echo $id; ?>&role=0" class="button-5" role="button">Block</a>
                                <?php } else { ?>
                                    <a href="<?php echo SITEURL; ?>update-delivery-boy-status.php?id=<?php echo $id; ?>&role=1" class="button-5" role="button">Unblock</a>
                                    <a href="<?php echo SITEURL; ?>delete-delivery-boy.php?id=<?php echo $id; ?>" class="button-7" role="button">Delete</a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='9' class='error'>No Delivery Boys Found</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</main>

<!-- Image Modal -->
<div id="imageModal" class="modal">
    <span class="close-btn" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImg">
</div>

<!-- JavaScript for Modal -->
<script>
    function openModal(imageSrc) {
        document.getElementById('modalImg').src = imageSrc;
        document.getElementById('imageModal').style.display = 'flex';
    }
    function closeModal() {
        document.getElementById('imageModal').style.display = 'none';
    }
</script>

<!-- CSS for Modal Styling -->
<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        justify-content: center;
        align-items: center;
    }
    .modal-content {
        max-width: 80%;
        max-height: 80%;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    .close-btn {
        position: absolute;
        top: 20px;
        right: 30px;
        font-size: 30px;
        font-weight: bold;
        color: white;
        cursor: pointer;
    }
    .close-btn:hover {
        color: red;
    }
</style>


        <!-- MAIN -->
    </section>

    <!-- CONTENT -->


    <script src="script-admin.js"></script>
</body>

</html>




