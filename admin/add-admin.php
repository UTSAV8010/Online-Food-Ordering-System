<?php include('../frontend/config/constants.php');
	  //include('login-check.php');

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
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="style-admin.css">
	<link rel="icon" 
      type="image/png" 
      href="../images/logo2.png">

	<title>Admin</title>
	<style>
		a.clickable {
			color: gray  !important;
			pointer-events: auto !important; 
			text-decoration: none !important;
		}
		
		a.clickable:hover {
			color: #007bff !important; 
		}
		
		</style>
</head>
<body>


	
<section id="sidebar">
	<a href="index.php" class="brand">
	<img src="../images/logo2.png" width="120px" alt="">
	</a>
		<ul class="side-menu top">
			<li >
				<a href="index.php">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li class="active">
				<a href="manage-admin.php">
					<i class='bx bxs-group' ></i>
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
			<li>
				<a href="manage-ei-order.php">
					<i class='bx bxs-user'></i>
					<span class="text" >Users&nbsp;&nbsp;&nbsp;
						
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
					<i class='bx bxs-cog' ></i>
					<span class="text">Settings</span>
				</a>
			</li>
			<li>
				<a href="logout.php" class="logout">
					<i class='bx bxs-log-out-circle' ></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>
	<section id="content">
		<nav>
			<i class='bx bx-menu' ></i>
			<a href="#" class="nav-link"></a>
			<form action="#">
				<div class="form-input">
					<input type="search" placeholder="Search...">
					<button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
				</div>
			</form>
			<input type="checkbox" id="switch-mode" hidden>
			<label for="switch-mode" class="switch-mode"></label>
			<div class="fetch_message">
				<div class="action_message notfi_message">
					<a href="messages.php"><i class='bx bxs-envelope' ></i></a>
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
<div class="notification" >
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


		<main>
    <div class="head-title">
        <div class="left">
            <h1>Add Admin</h1>
            <ul class="breadcrumb">
                <li><a href="index.php" class="clickable">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="clickable" href="manage-admin.php">Admin Panel</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="add-admin.php">Add Admin</a></li>
            </ul>
            <div id="message"></div> <!-- General message display -->
        </div>  
    </div>

    <br>
    <div class="table-data">
        <div class="order">
            <div class="head">  
                <form id="adminForm">
                    <table class="rtable-center">
                        <tr>
                            <td>Full Name</td>
                            <td>
                                <input type="text" name="full_name" id="ip2">
                                <span class="error" id="error_full_name"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>
                                <input type="email" name="email" id="ip2">
                                <span class="error" id="error_email"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Username</td>
                            <td>
                                <input type="text" name="username" id="ip2">
                                <span class="error" id="error_username"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td>
                                <input type="password" name="password" id="ip2">
                                <span class="error" id="error_password"></span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="submit" value="Add Admin" class="button-8">
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>  
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $("#adminForm").submit(function(e) {
        e.preventDefault(); // Prevent page refresh

        // Clear previous errors
        $(".error").text("");

        // Get form values
        let full_name = $("input[name='full_name']").val().trim();
        let email = $("input[name='email']").val().trim();
        let username = $("input[name='username']").val().trim();
        let password = $("input[name='password']").val().trim();
        let hasError = false;

        // Validate fields
        if (full_name === "") {
            $("#error_full_name").text("Please enter full name.");
            hasError = true;
        }
        if (email === "") {
            $("#error_email").text("Please enter email.");
            hasError = true;
        } else if (!/^\S+@\S+\.\S+$/.test(email)) {
            $("#error_email").text("Enter a valid email.");
            hasError = true;
        }
        if (username === "") {
            $("#error_username").text("Please enter username.");
            hasError = true;
        }
        if (password === "") {
            $("#error_password").text("Please enter password.");
            hasError = true;
        } else if (password.length < 8 || !/[a-z]/.test(password) || !/[A-Z]/.test(password) || !/\d/.test(password) || !/[\W_]/.test(password)) {
			$("#error_password").html("Password must be at least 8 characters long.<br>It must contain one uppercase letter.<br>It must contain one number and one special character.");

            hasError = true;
        }

        if (hasError) return;

        // AJAX request to check username and insert data
        $.ajax({
            url: "add-admin-handler.php",
            type: "POST",
            data: { full_name, email, username, password },
            success: function(response) {
                if (response === "exists") {
                    $("#error_username").text("Username already exists! Try a different one.");
                } else if (response === "success") {
                    // Display success message and redirect
                    $("#message").html("<div class='success'>Admin Added Successfully</div>");
                    setTimeout(function() {
                        window.location.href = "manage-admin.php"; // Redirect to manage-admin.php after 2 seconds
                    }); // Redirect after 2 seconds (for user to see the success message)
                } else {
                    $("#message").html("<div class='error'>Failed to Add Admin</div>");
                }
            }
        });
    });
});

</script>





	</section>
<script src="script-admin.js"></script>
</body>
</html>






