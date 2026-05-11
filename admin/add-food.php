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
			<li >
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



        <main>
    <div class="head-title">
        <div class="left">
            <h1>Add Food</h1>
            <ul class="breadcrumb">
                <li>
                    <a href="index.php" class="clickable">
                        Dashboard
                    </a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a class="clickable" href="manage-food.php">Food Menu</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a class="active" href="add-food.php">Add Food</a>
                </li>
            </ul>
        </div>
    </div>
    <br>

    <?php 
    if (isset($_SESSION['add'])) {
        echo $_SESSION['add'];
        unset($_SESSION['add']);
    }
    if (isset($_SESSION['upload'])) {
        echo $_SESSION['upload'];
        unset($_SESSION['upload']);
    }
    ?>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <table class="rtable">
                        <tr>
                            <td>Title</td>
                            <td>
                                <input type="text" name="title" id="ip2">
                                <span id="titleError" class="error"></span>
                            </td>
                        </tr>

                        <tr>
                            <td>Description</td>
                            <td>
                                <textarea name="description" cols="24" rows="5"></textarea>
                                <span id="descError" class="error"></span>
                            </td>
                        </tr>

                        <tr>
                            <td>Price</td>
                            <td>
                                <input type="number" name="price" id="ip2">
                                <span id="priceError" class="error"></span>
                            </td>
                        </tr>

                        <tr>
                            <td>Select Image</td>
                            <td>
                                <input type="file" name="image">
                                <span id="imageError" class="error"></span>
                            </td>
                        </tr>

                        <tr>
                            <td>Category</td>
                            <td>
                            <select name="category">
    <option value="" selected disabled>Select a Category</option>
    <?php 
    $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
    $res = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($res);
    if ($count > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $id = $row['id'];
            $title = $row['title'];
            echo "<option value=\"$id\">$title</option>";
        }
    } else {
        echo "<option value=\"0\">No Category Found</option>";
    }
    ?>
</select>
 
                                <span id="categoryError" class="error"></span>
                            </td>
                        </tr>

                        <tr>
                            <td>Featured</td>
                            <td>
                                <input type="radio" name="featured" value="Yes"> Yes 
                                <input type="radio" name="featured" value="No"> No
                                <span id="featuredError" class="error"></span>
                            </td>
                        </tr>

                        <tr>
                            <td>Stock</td>
                            <td>
                                <input type="number" name="stock" id="ip2">
                                <span id="stockError" class="error"></span>
                            </td>
                        </tr>

                        <tr>
                            <td>Active</td>
                            <td>
                                <input type="radio" name="active" value="Yes"> Yes 
                                <input type="radio" name="active" value="No"> No
                                <span id="activeError" class="error"></span>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <input type="submit" name="submit" value="Add Food" class="button-8" role="button">
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>

    <script>
    function validateForm() {
        let valid = true;

        // Title validation
        const title = document.getElementsByName('title')[0].value;
        if (title == "") {
            document.getElementById('titleError').textContent = "Title is required!";
            valid = false;
        } else {
            document.getElementById('titleError').textContent = "";
        }

        // Description validation
        const description = document.getElementsByName('description')[0].value;
        if (description == "") {
            document.getElementById('descError').textContent = "Description is required!";
            valid = false;
        } else {
            document.getElementById('descError').textContent = "";
        }

        // Price validation
        const price = document.getElementsByName('price')[0].value;
        if (price == "" || price <= 0) {
            document.getElementById('priceError').textContent = "Price must be a positive number!";
            valid = false;
        } else {
            document.getElementById('priceError').textContent = "";
        }

        // Image validation
        const image = document.getElementsByName('image')[0].value;
        if (image == "") {
            document.getElementById('imageError').textContent = "Image is required!";
            valid = false;
        } else {
            document.getElementById('imageError').textContent = "";
        }

        // Category validation
        const category = document.getElementsByName('category')[0].value;
        if (category == "" || category == "0") {
            document.getElementById('categoryError').textContent = "Please select a category!";
            valid = false;
        } else {
            document.getElementById('categoryError').textContent = "";
        }

        // Featured validation
        const featured = document.querySelector('input[name="featured"]:checked');
        if (!featured) {
            document.getElementById('featuredError').textContent = "Please select featured option!";
            valid = false;
        } else {
            document.getElementById('featuredError').textContent = "";
        }

        // Stock validation
        const stock = document.getElementsByName('stock')[0].value;
        if (stock == "" || stock <= 0) {
            document.getElementById('stockError').textContent = "Stock must be a positive number!";
            valid = false;
        } else {
            document.getElementById('stockError').textContent = "";
        }

        // Active validation
        const active = document.querySelector('input[name="active"]:checked');
        if (!active) {
            document.getElementById('activeError').textContent = "Please select active status!";
            valid = false;
        } else {
            document.getElementById('activeError').textContent = "";
        }

        return valid;
    }
    </script>

    <?php 
    if (isset($_POST['submit'])) {
        // Add the food in database
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = $_POST['price'] ?? '';
        $category = $_POST['category'] ?? '';
        $stock = $_POST['stock'] ?? '';

        // Check whether radio button for "featured" is checked or not
        $featured = isset($_POST['featured']) ? $_POST['featured'] : "No";

        // Check whether radio button for "active" is checked or not
        $active = isset($_POST['active']) ? $_POST['active'] : "No";

        $errors = array();
        if ($title === '') {
            $errors[] = "Title is required.";
        }
        if ($description === '') {
            $errors[] = "Description is required.";
        }
        if ($price === '' || !is_numeric($price) || $price <= 0) {
            $errors[] = "Price must be a positive number.";
        }
        if ($category === '' || !ctype_digit((string)$category) || (int)$category <= 0) {
            $errors[] = "Please select a valid category.";
        }
        if ($stock === '' || !is_numeric($stock) || $stock <= 0) {
            $errors[] = "Stock must be a positive number.";
        }
        if ($featured !== "Yes" && $featured !== "No") {
            $errors[] = "Please select featured option.";
        }
        if ($active !== "Yes" && $active !== "No") {
            $errors[] = "Please select active status.";
        }

        // Upload the image if selected
        $image_name = "";
        if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
            $errors[] = "Image is required.";
        } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Failed to upload image (error code: " . (int)$_FILES['image']['error'] . ").";
        } else {
            $original_name = $_FILES['image']['name'];
            $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
            if ($ext === '') {
                $errors[] = "Invalid image file.";
            } else {
                $image_name = "Food-Name-" . rand(1000, 9999) . "." . $ext;
                $src = $_FILES['image']['tmp_name'];
                $dst = "../images/food/" . $image_name;
                $upload = move_uploaded_file($src, $dst);
                if ($upload == false) {
                    $errors[] = "Failed to upload image.";
                }
            }
        }

        if (!empty($errors)) {
            $_SESSION['add'] = "<div class='error text-center'>" . implode(" ", $errors) . "</div>";
            header('location:' . SITEURL . 'add-food.php');
            exit;
        }

        $safe_title = mysqli_real_escape_string($conn, $title);
        $safe_description = mysqli_real_escape_string($conn, $description);
        $safe_featured = mysqli_real_escape_string($conn, $featured);
        $safe_active = mysqli_real_escape_string($conn, $active);
        $safe_image = mysqli_real_escape_string($conn, $image_name);
        $safe_category = (int)$category;
        $safe_stock = (int)$stock;
        $safe_price = (float)$price;
        $restro_name = "Pasar Kita";
        $safe_restro_name = mysqli_real_escape_string($conn, $restro_name);

        // Insert into database (fallback to manual id if AUTO_INCREMENT is missing)
        $use_manual_id = false;
        $next_id = null;
        $col_res = mysqli_query($conn, "SHOW COLUMNS FROM tbl_food LIKE 'id'");
        if ($col_res && mysqli_num_rows($col_res) === 1) {
            $col = mysqli_fetch_assoc($col_res);
            $extra = strtolower($col['Extra'] ?? '');
            if (strpos($extra, 'auto_increment') === false) {
                $use_manual_id = true;
                $max_res = mysqli_query($conn, "SELECT MAX(id) AS max_id FROM tbl_food");
                $max_row = $max_res ? mysqli_fetch_assoc($max_res) : null;
                $next_id = (int)($max_row['max_id'] ?? 0) + 1;
            }
        }

        $id_sql = $use_manual_id ? "id = $next_id,\n            " : "";
        $sql2 = "INSERT INTO tbl_food SET
            $id_sql" . "title = '$safe_title',
            description = '$safe_description',
            price = $safe_price,
            restro_name = '$safe_restro_name',
            image_name = '$safe_image',
            category_id = $safe_category,
            featured = '$safe_featured',
            active = '$safe_active',
            stock = $safe_stock";

        $res2 = mysqli_query($conn, $sql2);

        // Retry once if manual id collided
        if ($res2 == false && $use_manual_id && mysqli_errno($conn) == 1062) {
            $max_res = mysqli_query($conn, "SELECT MAX(id) AS max_id FROM tbl_food");
            $max_row = $max_res ? mysqli_fetch_assoc($max_res) : null;
            $next_id = (int)($max_row['max_id'] ?? 0) + 1;
            $id_sql = "id = $next_id,\n            ";
            $sql2 = "INSERT INTO tbl_food SET
            $id_sql" . "title = '$safe_title',
                description = '$safe_description',
                price = $safe_price,
                restro_name = '$safe_restro_name',
                image_name = '$safe_image',
                category_id = $safe_category,
                featured = '$safe_featured',
                active = '$safe_active',
                stock = $safe_stock";
            $res2 = mysqli_query($conn, $sql2);
        }

        // Redirect with message to manage food page
        if ($res2 == true) {
            $_SESSION['add'] = "<div class='success text-center'>Food Added Successfully</div>";
            header('location:' . SITEURL . 'manage-food.php');
        } else {
            $_SESSION['add'] = "<div class='error text-center'>Failed to Add Food. " . mysqli_error($conn) . "</div>";
            header('location:' . SITEURL . 'add-food.php');
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




