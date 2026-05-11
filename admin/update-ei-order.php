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
	<link rel="icon" 
      type="image/png" 
      href="../images/logo2.png">

	<title>Admin</title>
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
			<li class="active">
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
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Update Eat In Order</h1>
					<ul class="breadcrumb">
						<li>
							<a href="index.php">Dashboard</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="manage-ei-order.php">Eat In Orders</a>
						</li>
					</ul>
				</div>
</div>

<br>

        			<?php 
 
        $id=$_GET['id'];
        $sql="SELECT * FROM tbl_eipay WHERE id=$id";
        $res=mysqli_query($conn, $sql);

        if($res == true)
        {
            $count = mysqli_num_rows($res);
            if($count==1)
            {
                $row=mysqli_fetch_assoc($res);

                $table_id = $row['table_id'];
                $amount = $row['amount'];
                $tran_id = $row['tran_id'];
                $order_date = $row['order_date'];
                $payment_status = $row['payment_status'];
                $order_status = $row['order_status'];
            }
            else{
                //Redirect to manage admin page
                header('location:'.SITEURL.'manage-ei-order.php');
            }
        }

        
        ?>
		<div class="table-data">
			<div class="order">
			<div class="head">

        <form action="" method="POST">


        <table class="rtable">
            <tr>
                <td>Table ID</td>
                <td>
                    <input type="text" name="table_id" value="<?php echo $table_id; ?>" id="ip2">
                </td>
            </tr>
            <tr>
                <td>Amount</td>
                <td>
                    <input type="text" name="amount" value="<?php echo $amount; ?>" id="ip2">
                </td>
            </tr>
            <tr>
                <td>Transaction ID</td>
                <td>
                    <input type="text" name="tran_id" value="<?php echo $tran_id; ?>" id="ip2">
                </td>
            </tr>
            <tr>
                <td>Order Date</td>
                <td>
                    <input type="text" name="order_date" value="<?php echo $order_date; ?>" id="ip2">
                </td>
            </tr>

            <tr>
                <td>Payment Status</td>
                <td>
                    <input type="text" name="payment_status" value="<?php echo $payment_status; ?>" id="ip2">
                </td>
            </tr>
            <tr>
                <td>Order Status</td>
                <td>
                     <select name="order_status">
                        <option <?php if($order_status=="Pending"){ echo "selected";} ?> value="Pending">Pending</option>
                        <option <?php if($order_status=="Processing"){ echo "selected";} ?> value="Processing">Processing</option>
                        <option <?php if($order_status=="Delivered"){ echo "selected";} ?> value="Delivered">Delivered</option>
                        <option <?php if($order_status=="Cancelled"){ echo "selected";} ?> value="Cancelled">Cancelled</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="submit" name="submit" value="Update" class="button-8" role="button">
                </td>
            </tr>

        </table>



        </form>
	</div>
    </div>
</div>
	</div>

<?php 
//Check whether the Submit button is clicked or not

if(isset($_POST['submit'])){
    //echo "Button Clicked";
    //Get all the values from form to update

     $id = $_POST['id'];
     $table_id = $_POST['table_id'];
     $amount = $_POST['amount'];
     $tran_id = $_POST['tran_id'];
     $order_date = $_POST['order_date'];
     $payment_status = $_POST['payment_status'];
     $order_status = $_POST['order_status'];


     $sql = "UPDATE tbl_eipay SET
     table_id = '$table_id',
     amount = '$amount',
     tran_id = '$tran_id',
     order_date = '$order_date',
     payment_status = '$payment_status',
     order_status = '$order_status' 
     WHERE id='$id'
     ";

     $res = mysqli_query($conn, $sql);

     if($res == true){

         $_SESSION['update'] = "<div class='success'>Order Updated Successfully</div>";
         header('location:'.SITEURL.'manage-ei-order.php');
     }

     else{
        $_SESSION['update'] = "<div class='error'>Failed to Update Order</div>";
         header('location:'.SITEURL.'manage-ei-order.php');
         
     }

}
?>

	
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="script-admin.js"></script>
</body>
</html>




