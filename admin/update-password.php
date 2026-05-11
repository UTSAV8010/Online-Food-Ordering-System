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
				<a href="manager-category.php">
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

		<!-- MAIN -->
		<?php

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$current_password_err = $new_password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate current password
    if (empty($current_password)) {
        $current_password_err = "Current password is required.";
    }

    // Validate new password (must contain uppercase, lowercase, special character, number, and length >= 8)
    if (empty($new_password)) {
        $new_password_err = "New password is required.";
    } elseif (!preg_match("/[A-Z]/", $new_password) || !preg_match("/[a-z]/", $new_password) || !preg_match("/\d/", $new_password) || !preg_match("/[\W_]/", $new_password) || strlen($new_password) < 8) {
        $new_password_err = "Password must be at least 8 characters long and contain one uppercase, one lowercase, one number, and one special character.";
    }

    // Validate confirm password
    if (empty($confirm_password)) {
        $confirm_password_err = "Please confirm your new password.";
    } elseif ($new_password !== $confirm_password) {
        $confirm_password_err = "Passwords do not match.";
    }

    if (empty($current_password_err) && empty($new_password_err) && empty($confirm_password_err)) {
        // No errors, proceed to check and update password
        $sql = "SELECT password FROM tbl_admin WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed_password);
            $stmt->fetch();

            if (password_verify($current_password, $hashed_password)) {
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the password
                $sql2 = "UPDATE tbl_admin SET password=? WHERE id=?";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->bind_param("si", $new_hashed_password, $id);
                $res2 = $stmt2->execute();

                if ($res2) {
                    $_SESSION['change-pwd'] = "<div class='success'>Password Changed Successfully.</div>";
                } else {
                    $_SESSION['pwd-not-match'] = "<div class='error'>Failed to Change Password. Try Again.</div>";
                }

                header('Location: manage-admin.php');  // Redirect to the same page or a different page after successful change
                exit();
            } else {
                $_SESSION['user-not-found'] = "<div class='error'>Incorrect Current Password.</div>";
                header('Location: manage-admin.php');
                exit();
            }
        } else {
            $_SESSION['user-not-found'] = "<div class='error'>User Not Found.</div>";
            header('Location: manage-admin.php');
            exit();
        }
    }
}
?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Change Password</h1>
            <ul class="breadcrumb">
                <li><a href="index.php" class="clickable">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="clickable" href="manage-admin.php">Manage Admin</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="manage-admin.php">Change Password</a></li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <form action="" method="POST">
                    <table class="tbl-30">
                        <tr>
                            <td>Current Password</td>
                            <td>
                                <input type="password" name="current_password" value="<?php echo isset($current_password) ? $current_password : ''; ?>" id="ip2">
                                <span class="error"><?php echo $current_password_err; ?></span>
                            </td>
                        </tr>

                        <tr>
                            <td>New Password</td>
                            <td>
                                <input type="password" name="new_password" value="<?php echo isset($new_password) ? $new_password : ''; ?>" id="ip2">
                                <span class="error"><?php echo $new_password_err; ?></span>
                            </td>
                        </tr>

                        <tr>
                            <td>Confirm Password</td>
                            <td>
                                <input type="password" name="confirm_password" value="<?php echo isset($confirm_password) ? $confirm_password : ''; ?>" id="ip2">
                                <span class="error"><?php echo $confirm_password_err; ?></span>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <input type="hidden" name="id" value="<?php echo $id ?>">
                                <input type="submit" name="submit" value="Change Password" class="button-8" role="button">
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</main>


		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="script-admin.js"></script>
</body>
</html>




