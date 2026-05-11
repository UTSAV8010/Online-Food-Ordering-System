<?php include('../frontend/config/constants.php');
	  //include('login-check.php');
	  error_reporting(0);
      @ini_set('display_errors', 0);

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

<?php 
    //CHeck whether id is set or not 
    if(isset($_GET['id']))
    {
        //Get all the details
        $id = (int)$_GET['id'];

        //SQL Query to Get the Selected Food
        $sql2 = "SELECT * FROM tbl_food WHERE id=$id";
        //execute the Query
        $res2 = mysqli_query($conn, $sql2);

        //Get the value based on query executed
        $row2 = mysqli_fetch_assoc($res2);
        if (!$row2) {
            //Redirect to Manage Food
            header('location:'.SITEURL.'manage-food.php');
            exit;
        }

        //Get the Individual Values of Selected Food
        $title = $row2['title'];
        $description = $row2['description'];
        $price = $row2['price'];
        $current_image = $row2['image_name'];
        $current_category = $row2['category_id'];
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
            <h1>Update Menu Item</h1>
            <ul class="breadcrumb">
                <li>
                    <a href="index.php" class="clickable">
                        Dashboard
                    </a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class='clickable' href="manage-food.php">Food Menu</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="manage-admin.php">Update</a></li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <table>
                        <tr>
                            <td>Title</td>
                            <td><input type="text" name="title" value="<?php echo $title; ?>" id="ip2"><span id="titleError" class="error"></span></td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td><textarea name="description" cols="30" rows="5"><?php echo $description; ?></textarea><span id="descriptionError" class="error"></span></td>
                        </tr>
                        <tr>
                            <td>Price</td>
                            <td><input type="number" name="price" value="<?php echo $price; ?>" id="ip2"><span id="priceError" class="error"></span></td>
                        </tr>
                        <tr>
                            <td>Current Image</td>
                            <td>
                                <?php
                                if ($current_image == "") {
                                    echo "<div class='error'>Image not Available.</div>";
                                } else {
                                    echo "<img src='" . SITEURL . "../images/food/$current_image' width='150px'>";
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Select New Image</td>
                            <td><input type="file" name="image"></td>
                        </tr>
                        <tr>
                            <td>Category</td>
                            <td>
                                <select name="category">
                                    <?php
                                    $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                                    $res = mysqli_query($conn, $sql);
                                    $count = mysqli_num_rows($res);

                                    if ($count > 0) {
                                        while ($row = mysqli_fetch_assoc($res)) {
                                            $category_title = $row['title'];
                                            $category_id = $row['id'];
                                            ?>
                                            <option <?php if ($current_category == $category_id) echo "selected"; ?>
                                                    value="<?php echo $category_id; ?>"><?php echo $category_title; ?></option>
                                            <?php
                                        }
                                    } else {
                                        echo "<option value='0'>Category Not Available.</option>";
                                    }
                                    ?>
                                </select><span id="categoryError" class="error"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Featured</td>
                            <td>
                                <input <?php if ($featured == "Yes") echo "checked"; ?> type="radio" name="featured" value="Yes"> Yes
                                <input <?php if ($featured == "No") echo "checked"; ?> type="radio" name="featured" value="No"> No
                            </td>
                        </tr>
                        <tr>
                            <td>Stock</td>
                            <td><input type="number" name="stock" value="<?php echo $stock; ?>" id="ip2"><span id="stockError" class="error"></span></td>
                        </tr>
                        <tr>
                            <td>Active</td>
                            <td>
                                <input <?php if ($active == "Yes") echo "checked"; ?> type="radio" name="active" value="Yes"> Yes
                                <input <?php if ($active == "No") echo "checked"; ?> type="radio" name="active" value="No"> No
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                                <input type="submit" name="submit" value="Update Food" class="button-8" role="button">
                            </td>
                        </tr>
                    </table>
                </form>

                <script>
                    function validateForm() {
                        let valid = true;

                        // Title validation
                        let title = document.getElementsByName("title")[0].value;
                        if (title == "") {
                            document.getElementById("titleError").innerText = "Title is required!";
                            valid = false;
                        } else {
                            document.getElementById("titleError").innerText = "";
                        }

                        // Description validation
                        let description = document.getElementsByName("description")[0].value;
                        if (description == "") {
                            document.getElementById("descriptionError").innerText = "Description is required!";
                            valid = false;
                        } else {
                            document.getElementById("descriptionError").innerText = "";
                        }

                        // Price validation
                        let price = document.getElementsByName("price")[0].value;
                        if (price == "" || price <= 0) {
                            document.getElementById("priceError").innerText = "Price must be a positive number!";
                            valid = false;
                        } else {
                            document.getElementById("priceError").innerText = "";
                        }

                        // Category validation
                        let category = document.getElementsByName("category")[0].value;
                        if (category == "" || category == "0") {
                            document.getElementById("categoryError").innerText = "Please select a category!";
                            valid = false;
                        } else {
                            document.getElementById("categoryError").innerText = "";
                        }

                        // Stock validation
                        let stock = document.getElementsByName("stock")[0].value;
                        if (stock == "" || stock <= 0) {
                            document.getElementById("stockError").innerText = "Stock must be a positive number!";
                            valid = false;
                        } else {
                            document.getElementById("stockError").innerText = "";
                        }

                        return valid;
                    }
                </script>

                <?php
                if (isset($_POST['submit'])) {
                    // Get all form details
                    $id = (int)($_POST['id'] ?? 0);
                    $title = $_POST['title'] ?? '';
                    $description = $_POST['description'] ?? '';
                    $price = $_POST['price'] ?? '';
                    $current_image = $_POST['current_image'] ?? '';
                    $category = $_POST['category'] ?? '';
                    $featured = $_POST['featured'] ?? 'No';
                    $stock = $_POST['stock'] ?? '';
                    $active = $_POST['active'] ?? 'No';

                    if ($id <= 0) {
                        $_SESSION['update'] = "<div class='error text-center'>Invalid food id.</div>";
                        echo "<script>window.location = 'manage-food.php';</script>";
                        exit;
                    }

                    $image_name = $current_image;
                    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
                        $original_name = $_FILES['image']['name'];
                        $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
                        if ($ext == '') {
                            $_SESSION['update'] = "<div class='error text-center'>Invalid image file.</div>";
                            echo "<script>window.location = 'update-food.php?id=$id';</script>";
                            exit;
                        }
                        $image_name = "Food-Name-" . rand(1000, 9999) . "." . $ext;
                        $src = $_FILES['image']['tmp_name'];
                        $dst = "../images/food/" . $image_name;
                        $upload = move_uploaded_file($src, $dst);
                        if ($upload == false) {
                            $_SESSION['update'] = "<div class='error text-center'>Failed to upload image.</div>";
                            echo "<script>window.location = 'update-food.php?id=$id';</script>";
                            exit;
                        }

                        if ($current_image != "") {
                            $remove_path = "../images/food/" . $current_image;
                            if (file_exists($remove_path)) {
                                @unlink($remove_path);
                            }
                        }
                    }

                    $safe_title = mysqli_real_escape_string($conn, $title);
                    $safe_description = mysqli_real_escape_string($conn, $description);
                    $safe_featured = mysqli_real_escape_string($conn, $featured);
                    $safe_active = mysqli_real_escape_string($conn, $active);
                    $safe_image = mysqli_real_escape_string($conn, $image_name);
                    $safe_category = (int)$category;
                    $safe_stock = (int)$stock;
                    $safe_price = (float)$price;

                    // Update the food item in the database
                    $sql3 = "UPDATE tbl_food SET 
                        title = '$safe_title',
                        description = '$safe_description',
                        price = $safe_price,
                        image_name = '$safe_image',
                        category_id = $safe_category,
                        featured = '$safe_featured',
                        stock = $safe_stock,
                        active = '$safe_active'
                        WHERE id = $id";

                    $res3 = mysqli_query($conn, $sql3);

                    if ($res3 == true) {
                        $_SESSION['update'] = "<div class='success text-center'>Food Updated Successfully.</div>";
                    } else {
                        $_SESSION['update'] = "<div class='error text-center'>Failed to Update Food. " . mysqli_error($conn) . "</div>";
                    }

                    // Redirect after the form has been processed
                    echo "<script>window.location = 'manage-food.php';</script>";
                }
                ?>
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




