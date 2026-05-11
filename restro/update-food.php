<?php include('../frontend/config/constants.php');
	  //include('login-check.php');
	  error_reporting(0);
      @ini_set('display_errors', 0);

?>
<?php
$restroname=$_SESSION['restro-name'];
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


?>

<?php 
    //CHeck whether id is set or not 
    if(isset($_GET['id']))
    {
        //Get all the details
        $id = $_GET['id'];

        //SQL Query to Get the Selected Food
        $sql2 = "SELECT * FROM tbl_restro_food_item WHERE id=$id";
        //execute the Query
        $res2 = mysqli_query($conn, $sql2);

        //Get the value based on query executed
        $row2 = mysqli_fetch_assoc($res2);

        //Get the Individual Values of Selected Food
        $title = $row2['title'];
        $description = $row2['description'];
        $price = $row2['price'];
        $current_image = $row2['image_name'];
        $current_category = $row2['cid'];
        $featured = $row2['featured'];
        $stock = $row2['stock'];
        $active = $row2['active'];

    }
    else
    {
        //Redirect to Manage Food
        header('location:'.SITEURL.'manage-food.php');
    }
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

	<title> Restaurant-management</title>
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
			
			<li>
				<a href="manage-category.php">
					<i class='bx bxs-category'></i>
					<span class="text">Category</span>
				</a>
			</li>
			<li class="active">
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

<!-- MAIN --><?php

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM tbl_restro_food_item WHERE id = $id";
    $res = mysqli_query($conn, $sql);
    if ($res == true) {
        $row = mysqli_fetch_assoc($res);
        $title = $row['title'];
        $description = $row['description'];
        $price = $row['price'];
        $current_image = $row['image_name'];
        $current_category = $row['cid'];
        $featured = $row['featured'];
        $stock = $row['stock'];
        $active = $row['active'];
    }
}
?>

<main>
    <div class="head-title">
    <div class="left">
            <h1>Update Food Item</h1>
            <ul class="breadcrumb">
						<li>
							<a href="index.php" class="clickable">
								Dashboard
							</a>
						</li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class='clickable' href="manage-food.php">Manage Food Item</a></li>
				<li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="update-food.php">Update Food Item</a></li>
            </ul>
        </div>
    </div>
    </div>
    <div class="table-data">
        <div class="order">
            <form id="updateForm" action="" method="POST">
                <table>
                    <tr>
                        <td>Title</td>
                        <td><input type="text" name="title" id="ip2" value="<?php echo $title; ?>">
                            <span class="error" id="titleError"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td><textarea name="description"  cols="30" rows="5"><?php echo $description; ?></textarea>
                            <span class="error" id="descError"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Price</td>
                        <td><input type="number" name="price" id="ip2" value="<?php echo $price; ?>">
                            <span class="error" id="priceError"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Category</td>
                        <td>
                            <select name="category" id="category">
                                <option value="none">None</option>
                                <?php
                                $sql = "SELECT * FROM tbl_rcategory_notapproved WHERE active='Yes' AND status='approved' and restro_name='$restroname'";
                                $res = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $category_title = $row['title'];
                                    $category_id = $row['cid'];
                                    echo "<option value='$category_id' " . ($current_category == $category_id ? "selected" : "") . ">$category_title</option>";
                                }
                                ?>
                            </select>
                            <span class="error" id="categoryError"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Stock</td>
                        <td><input type="number" name="stock" id="ip2" value="<?php echo $stock; ?>">
                            <span class="error" id="stockError"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <input type="submit" name="submit" value="Update Food" class="button-8">
                        </td>
                    </tr>
                </table>
            </form>
            <script>
                document.getElementById('updateForm').onsubmit = function () {
                    let valid = true;
                    document.querySelectorAll('.error').forEach(e => e.innerHTML = '');

                    if (document.getElementsByName('title')[0].value.trim() === '') {
                        document.getElementById('titleError').innerText = 'Title is required';
                        valid = false;
                    }
                    if (document.getElementsByName('description')[0].value.trim() === '') {
                        document.getElementById('descError').innerText = 'Description is required';
                        valid = false;
                    }
                    if (document.getElementsByName('price')[0].value.trim() === '') {
                        document.getElementById('priceError').innerText = 'Price is required';
                        valid = false;
                    }
                    if (document.getElementById('category').value === 'none') {
                        document.getElementById('categoryError').innerText = 'Please select a category';
                        valid = false;
                    }
                    if (document.getElementsByName('stock')[0].value.trim() === '') {
                        document.getElementById('stockError').innerText = 'Stock is required';
                        valid = false;
                    }
                    return valid;
                };
            </script>
            
            <?php
            if (isset($_POST['submit'])) {
                $id = $_POST['id'];
                $title = $_POST['title'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $cid = $_POST['category'];
                $stock = $_POST['stock'];

                $sql3 = "UPDATE tbl_restro_food_item SET 
                    title = '$title',
                    description = '$description',
                    price = $price,
                    cid = '$cid',
                    stock = '$stock',
                    status = 'not_approved'
                    WHERE id = $id";
                
                $res3 = mysqli_query($conn, $sql3);

                if ($res3 == true) {
                    echo "<script>alert('Food Updated Successfully'); window.location = 'manage-food.php';</script>";
                } else {
                    echo "<script>alert('Failed to Update Food: " . mysqli_error($conn) . "');</script>";
                }
            }
            ?>
        </div>
    </div>
</main>

		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="script-admin.js"></script>
</body>
</html>




