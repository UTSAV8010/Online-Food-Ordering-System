<?php include('../frontend/config/constants.php');
	  //include('login-check.php');

?>
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
    <link rel="icon" 
      type="image/png" 
      href="../images/logo2.png">

	<title> Admin</title>
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


	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="index.php" class="brand">
			<img src="../images/logo2.png" width="80px" alt="">
		</a>
		<ul class="side-menu top">
			<li >
				<a href="index.php">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li >
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
			<li >
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
			<li class="active">
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
	<!-- SIDEBAR -->



	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
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
		<!-- NAVBAR -->

		<!-- MAIN --><main>
    <div class="head-title">
        <div class="left">
            <h1>Update Coupons</h1>
            <ul class="breadcrumb">
                <li>
                    <a class="clickable" href="index.php">Dashboard</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a class="clickable" href="manage-coupons.php">Manage Coupons</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a class="active" href="update-coupon.php">Update Coupons</a>
                </li>
            </ul>
        </div>
    </div>

    <br/>

    <?php 
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM tbl_coupon WHERE id=$id";
            $res = mysqli_query($conn, $sql);
            $count = mysqli_num_rows($res);
            if ($count == 1) {
                $row = mysqli_fetch_assoc($res);
                $name = $row['name'];
                $coupon_code = $row['coupon_code'];
                $discount = $row['discount'];
                $active = $row['status'];
            } else {
                $_SESSION['no-coupon-found'] = "<div class='error'>Coupon not found.</div>";
                header('location:' . SITEURL . 'manage-coupons.php');
            }
        } else {
            header('location:' . SITEURL . 'manage-coupons.php');
        }
    ?>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <form action="" method="POST" onsubmit="return validateForm()">
                    <table>
                        <tr>
                            <td>Name: </td>
                            <td>
                                <input id="ip2" type="text" name="name" value="<?php echo $name; ?>">
                                <span class="error" id="name_error"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Coupon Code: </td>
                            <td>
                                <input type="text" id="ip2" name="coupon_code" value="<?php echo $coupon_code; ?>">
                                <span class="error" id="coupon_code_error"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Discount (%): </td>
                            <td>
                                <input type="number" id="ip2" name="discount" value="<?php echo $discount; ?>" min="0" max="100">
                                <span class="error" id="discount_error"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Active: </td>
                            <td>
                                <input <?php if($active == "active") echo "checked"; ?> type="radio" name="active" value="active"> Active
                                <input <?php if($active == "inactive") echo "checked"; ?> type="radio" name="active" value="inactive"> Inactive
                                <span class="error" id="status_error"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <input type="submit" name="submit" value="Update Coupon" class="button-8">
                            </td>
                        </tr>
                    </table>
                </form>

                <?php 
                    if (isset($_POST['submit'])) {
                        $id = $_POST['id'];
                        $name = $_POST['name'];
                        $coupon_code = $_POST['coupon_code'];
                        $discount = $_POST['discount'];
                        $active = isset($_POST['active']) ? $_POST['active'] : 'inactive';
                    
                        $sql2 = "UPDATE tbl_coupon SET 
                            name = '$name',
                            coupon_code = '$coupon_code',
                            discount = $discount,
                            status = '$active' 
                            WHERE id = $id";
                    
                        $res2 = mysqli_query($conn, $sql2);
                    
                        if ($res2 == true) {
                            $_SESSION['update'] = "<div class='success'>Coupon Updated Successfully.</div>";
                            header('location:' . SITEURL . 'manage-coupons.php');
                        } else {
                            $_SESSION['update'] = "<div class='error'>Failed to Update Coupon.</div>";
                            header('location:' . SITEURL . 'manage-coupons.php');
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</main>

<script>
    function validateForm() {
        let isValid = true;

        let name = document.querySelector("input[name='name']").value;
        let couponCode = document.querySelector("input[name='coupon_code']").value;
        let discount = document.querySelector("input[name='discount']").value;
        let active = document.querySelector("input[name='active']:checked");

        document.getElementById("name_error").innerHTML = "";
        document.getElementById("coupon_code_error").innerHTML = "";
        document.getElementById("discount_error").innerHTML = "";
        document.getElementById("status_error").innerHTML = "";

        if (name.trim() === "") {
            document.getElementById("name_error").innerHTML = "Please enter a name.";
            isValid = false;
        }
        if (couponCode.trim() === "") {
            document.getElementById("coupon_code_error").innerHTML = "Please enter a coupon code.";
            isValid = false;
        }
        if (discount === "" || discount < 0 || discount > 100) {
            document.getElementById("discount_error").innerHTML = "Enter a valid discount between 0-100.";
            isValid = false;
        }
        if (!active) {
            document.getElementById("status_error").innerHTML = "Please select a status.";
            isValid = false;
        }

        return isValid;
    }
</script>

<style>
    .error {
        color: red;
        font-size: 14px;
        display: block;
    }
</style>


		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="script-admin.js"></script>
</body>
</html>




