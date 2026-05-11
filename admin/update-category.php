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


	<!-- SIDEBAR -->
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
			<li class="active">
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
            <h1>Update Category</h1>
            <ul class="breadcrumb">
                <li>
                    <a class='clickable' href="index.php">Dashboard</a>
                </li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li>
                    <a class="clickable" href="manage-category.php">Manage Category</a>
                </li>
                <li><i class='bx bx-chevron-right' ></i></li>
                <li>
                    <a class="active" href="update-category.php.php">Update Category</a>
                </li>
            </ul>
        </div>
    </div>
    <br/> 

    <!-- Update Category Form Start-->

    <?php 
        // Initialize error messages
        $title_error = "";
        $featured_error = "";
        $active_error = "";

        //Check whether the id is set or not
        if(isset($_GET['id'])) {
            //Get the ID and all other details
            $id = $_GET['id'];
            //Create SQL Query to get all other details
            $sql = "SELECT * FROM tbl_category WHERE id=$id";

            //Execute the Query
            $res = mysqli_query($conn, $sql);

            //Count the Rows to check whether the id is valid or not
            $count = mysqli_num_rows($res);

            if($count==1) {
                //Get all the data
                $row = mysqli_fetch_assoc($res);
                $title = $row['title'];
                $current_image = $row['image_name'];
                $featured = $row['featured'];
                $active = $row['active'];
            } else {
                //redirect to manage category with session message
                $_SESSION['no-category-found'] = "<div class='error'>Category not Found.</div>";
                header('location:'.SITEURL.'manage-category.php');
            }
        } else {
            //redirect to Manage CAtegory
            header('location:'.SITEURL.'manage-category.php');
        }

        if(isset($_POST['submit'])) {
            // Initialize errors for individual fields
            $errors = array();

            //1. Get all the values from our form
            $title = $_POST['title'];
            $current_image = $_POST['current_image'];
            $featured = $_POST['featured'];
            $active = $_POST['active'];

            // Validation for Title
            if(empty($title)) {
                $title_error = "Title is required!";
                $errors[] = $title_error;
            }

            // Validation for Featured
            if(!isset($featured)) {
                $featured_error = "Please select whether it's featured!";
                $errors[] = $featured_error;
            }

            // Validation for Active
            if(!isset($active)) {
                $active_error = "Please select whether it's active!";
                $errors[] = $active_error;
            }

            // If there are no errors, proceed to update the category
            if(count($errors) == 0) {
                // Proceed with updating category

                //2. Updating New Image if selected (we don't check for image here)
                if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
                    $uploaded_image = $_FILES['image']['name'];
                    $ext = strtolower(pathinfo($uploaded_image, PATHINFO_EXTENSION));
                    $allowed_ext = array('jpg', 'jpeg', 'png', 'webp');

                    if(!in_array($ext, $allowed_ext)) {
                        $_SESSION['upload'] = "<div class='error'>Only jpg, jpeg, png, webp files are allowed.</div>";
                        header('location:'.SITEURL.'manage-category.php');
                        die();
                    }

                    // Auto rename image with larger random range to reduce collisions
                    $image_name = "Food_Category_".rand(1000, 999999).'.'.$ext; 

                    $source_path = $_FILES['image']['tmp_name'];
                    $destination_path = __DIR__ . "/../images/category/" . $image_name;

                    $upload = move_uploaded_file($source_path, $destination_path);

                    if($upload==false) {
                        $_SESSION['upload'] = "<div class='error'>Failed to Upload Image. </div>";
                        header('location:'.SITEURL.'manage-category.php');
                        die();
                    }

                    // Remove old image only if it exists on disk
                    if($current_image!="") {
                        $remove_path = __DIR__ . "/../images/category/" . $current_image;
                        if(file_exists($remove_path) && !unlink($remove_path)) {
                            $_SESSION['failed-remove'] = "<div class='error'>Failed to remove current Image.</div>";
                            header('location:'.SITEURL.'manage-category.php');
                            die();
                        }
                    }
                } else {
                    $image_name = $current_image;
                }

                //3. Update the Database
                $sql2 = "UPDATE tbl_category SET 
                    title = '$title',
                    image_name = '$image_name',
                    featured = '$featured',
                    active = '$active' 
                    WHERE id=$id
                ";

                //Execute the Query
                $res2 = mysqli_query($conn, $sql2);

                //4. Redirect to Manage Category with Message
                if($res2==true) {
                    $_SESSION['update'] = "<div class='success'>Category Updated Successfully.</div>";
                    header('location:'.SITEURL.'manage-category.php');
                } else {
                    $_SESSION['update'] = "<div class='error'>Failed to Update Category.</div>";
                    header('location:'.SITEURL.'manage-category.php');
                }
            }
        }
    ?>

    <div class="table-data">
        <div class="order">
            <div class="head">    
                <form action="" method="POST" enctype="multipart/form-data">
                    <table class="">
                        <tr>
                            <td>Title: </td>
                            <td>
                                <input type="text" name="title" value="<?php echo $title; ?>" id="ip2" >
                                <div class="error"><?php echo $title_error; ?></div>
                            </td>
                        </tr>

                        <tr>
                            <td>Current Image: </td>
                            <td>
                                <?php 
                                    if($current_image != "") {
                                        $current_image_path = __DIR__ . "/../images/category/" . $current_image;
                                        if(file_exists($current_image_path)) {
                                            echo "<img src='".SITEURL."../images/category/".$current_image."' width='150px'>";
                                        } else {
                                            echo "<div class='error'>Current image file is missing.</div>";
                                        }
                                    } else {
                                        //Display Message
                                        echo "<div class='error'>Image Not Added.</div>";
                                    }
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td>New Image: </td>
                            <td>
                                <input type="file" name="image">
                            </td>
                        </tr>

                        <tr>
                            <td>Featured: </td>
                            <td>
                                <input <?php if($featured=="Yes"){echo "checked";} ?> type="radio" name="featured" value="Yes" > Yes 
                                <input <?php if($featured=="No"){echo "checked";} ?> type="radio" name="featured" value="No" > No 
                                <div class="error"><?php echo $featured_error; ?></div>
                            </td>
                        </tr>

                        <tr>
                            <td>Active: </td>
                            <td>
                                <input <?php if($active=="Yes"){echo "checked";} ?> type="radio" name="active" value="Yes" > Yes 
                                <input <?php if($active=="No"){echo "checked";} ?> type="radio" name="active" value="No" > No 
                                <div class="error"><?php echo $active_error; ?></div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <input type="submit" name="submit" value="Update Category" class="button-8" role="button">
                            </td>
                        </tr>

                    </table>

                </form>
            </div>
        </div>
    </div>

    <!-- Update Category Form End -->

</main>

		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="script-admin.js"></script>
</body>
</html>





