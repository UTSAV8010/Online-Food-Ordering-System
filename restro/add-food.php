<?php include('../frontend/config/constants.php');
	  include('login-check.php');
      
$restroname = $_SESSION['restro-name'] ?? '';
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

	<title>Restaurant-management </title>
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



        <main>
    <div class="head-title">
        <div class="left">
            <h1>Add Food</h1>
            <ul class="breadcrumb">
                <li><a href="index.php" class="clickable">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="clickable" href="manage-food.php">Food Menu</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="add-food.php">Add Food</a></li>
            </ul>
        </div>
    </div>
    <br>

    <?php 
    if(isset($_SESSION['upload'])) {
        echo "<div class='error text-center'>{$_SESSION['upload']}</div>";
        unset($_SESSION['upload']);
    }
    if(isset($_SESSION['add'])) {
        echo "<div class='error text-center'>{$_SESSION['add']}</div>";
        unset($_SESSION['add']);
    }
    ?>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <form id="foodForm" action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <table class="rtable">
                        <tr>
                            <td>Title</td>
                            <td>
                                <input type="text" id="ip2" name="title">
                                <span class="error-message"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td>
                                <textarea name="description" cols="24" rows="5"></textarea>
                                <span class="error-message"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Price</td>
                            <td>
                                <input type="number" name="price" id="ip2" step="0.01">
                                <span class="error-message"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Select Image</td>
                            <td>
                                <input type="file" name="image" accept="image/*">
                                <span class="error-message"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Category</td>
                            <td>
                                <select name="category">
                                    <option value="">None</option>
                                    <?php 
                                    $restro_name = trim((string)$restroname);
                                    $restro_name_sql = mysqli_real_escape_string($conn, $restro_name);

                                    $sql = "SELECT cid, title
                                            FROM tbl_rcategory_notapproved
                                            WHERE active='Yes'
                                              AND status IN ('approved', 'not_approved')
                                              AND LOWER(TRIM(restro_name)) = LOWER(TRIM('$restro_name_sql'))
                                            ORDER BY title ASC";
                                    $res = mysqli_query($conn, $sql);

                                    if (mysqli_num_rows($res) > 0) {
                                        while ($row = mysqli_fetch_assoc($res)) {
                                            echo "<option value='{$row['cid']}'>{$row['title']}</option>";
                                        }
                                    } else {
                                        echo "<option value='' disabled>No category found</option>";
                                    }
                                    ?>
                                </select>
                                <span class="error-message"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Featured</td>
                            <td>
                                <input type="radio" name="featured" value="Yes"> Yes 
                                <input type="radio" name="featured" value="No"> No
                                <span class="error-message"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Stock</td>
                            <td>
                                <input type="number" id="ip2" name="stock" min="1">
                                <span class="error-message"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Active</td>
                            <td>
                                <input type="radio" name="active" value="Yes"> Yes 
                                <input type="radio" name="active" value="No"> No
                                <span class="error-message"></span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="submit" name="submit" value="Add Food" class="button-8">
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>

    <script>
        function validateForm() {
            let isValid = true;
            let errorMessages = document.querySelectorAll('.error-message');
            errorMessages.forEach(error => error.innerText = "");
    
            let title = document.querySelector("input[name='title']");
            let description = document.querySelector("textarea[name='description']");
            let price = document.querySelector("input[name='price']");
            let category = document.querySelector("select[name='category']");
            let stock = document.querySelector("input[name='stock']");
            let featured = document.querySelector("input[name='featured']:checked");
            let active = document.querySelector("input[name='active']:checked");
            let image = document.querySelector("input[name='image']");
    
            function showError(element, message) {
                element.nextElementSibling.innerText = message;
                element.nextElementSibling.style.color = "red";
                isValid = false;
            }
    
            if (title.value.trim() === "") {
                showError(title, "Title is required");
            }
            if (description.value.trim() === "") {
                showError(description, "Description is required");
            }
            if (price.value.trim() === "" || price.value <= 0) {
                showError(price, "Valid price is required");
            }
            if (category.value.trim() === "") {
                showError(category, "Please select a category");
            }
            if (!featured) {
                document.querySelector("input[name='featured']").parentElement.querySelector(".error-message").innerText = "Please select an option";
                document.querySelector("input[name='featured']").parentElement.querySelector(".error-message").style.color = "red";
                isValid = false;
            }
            if (!active) {
                document.querySelector("input[name='active']").parentElement.querySelector(".error-message").innerText = "Please select an option";
                document.querySelector("input[name='active']").parentElement.querySelector(".error-message").style.color = "red";
                isValid = false;
            }
            if (stock.value.trim() === "" || stock.value <= 0) {
                showError(stock, "Valid stock quantity is required");
            }
            if (image.files.length === 0) {
                showError(image, "Please select an image");
            }
    
            return isValid;
        }
    </script>

    <?php 
    if(isset($_POST['submit'])) {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $price = floatval($_POST['price']);  
        $category = intval($_POST['category']);  
        $stock = intval($_POST['stock']);  
        $restro_name = mysqli_real_escape_string($conn, $_SESSION['restro-name']);
        $featured = $_POST['featured'];
        $active = $_POST['active'];

        $image_name = "";
        if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image_name = "Food-".rand(1000,9999).".".$ext;
            $destination = "uploads/food/".$image_name;

            if(!move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $_SESSION['upload'] = "<div class='error text-center'>Failed to Upload Image.</div>";
                header('location:add-food.php');
                exit();
            }
        }

        $sql2 = "INSERT INTO tbl_restro_food_item (title, description, price, image_name, cid, featured, active, stock, restro_name) 
                 VALUES ('$title', '$description', $price, '$image_name', $category, '$featured', '$active', $stock, '$restro_name')";

        if(mysqli_query($conn, $sql2)) {
            $_SESSION['add'] = "<div class='success text-center'>Food Added Successfully</div>";
            header('location:manage-food.php');
            exit();
        } else {
            $_SESSION['add'] = "<div class='error text-center'>Failed to Add Food. Error: " . mysqli_error($conn) . "</div>";
            header('location:add-food.php');
            exit();
        }
    }
    ?>
</main>

<style>
    .error-message{
        color: red;
    }
</style>


		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="script-admin.js"></script>
</body>
</html>




