<?php include('../frontend/config/constants.php');
	  //include('login-check.php');

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

	<title>Restaurant-management</title>
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

		<!-- MAIN -->
        <?php

// Check whether the id is set or not
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM tbl_rcategory_notapproved WHERE cid=$id";
    $res = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($res);
    
    if($count == 1) {
        $row = mysqli_fetch_assoc($res);
        $title = $row['title'];
        $current_image = $row['image_name'];
        $featured = $row['featured'];
        $active = $row['active'];
    } else {
        $_SESSION['no-category-found'] = "<div class='error'>Category not Found.</div>";
        header('location:'.SITEURL.'manage-category.php');
    }
} else {
    header('location:'.SITEURL.'manage-category.php');
}
?>
<script>
function validateForm() {
    let isValid = true;
    
    // Title validation
    let title = document.getElementById("ip2").value.trim();
    let titleError = document.getElementById("titleError");
    if (title === "") {
        titleError.innerHTML = "Title is required.";
        isValid = false;
    } else {
        titleError.innerHTML = "";
    }
    
    // Featured validation
    let featuredYes = document.getElementById("featuredYes").checked;
    let featuredNo = document.getElementById("featuredNo").checked;
    let featuredError = document.getElementById("featuredError");
    if (!featuredYes && !featuredNo) {
        featuredError.innerHTML = "Please select an option.";
        isValid = false;
    } else {
        featuredError.innerHTML = "";
    }
    
    // Active validation
    let activeYes = document.getElementById("activeYes").checked;
    let activeNo = document.getElementById("activeNo").checked;
    let activeError = document.getElementById("activeError");
    if (!activeYes && !activeNo) {
        activeError.innerHTML = "Please select an option.";
        isValid = false;
    } else {
        activeError.innerHTML = "";
    }
    
    return isValid;
}
</script>

<main>
    <div class="head-title">
    <div class="left">
            <h1>Update Category</h1>
            <ul class="breadcrumb">
						<li>
							<a href="index.php" class="clickable">
								Dashboard
							</a>
						</li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class='clickable' href="manage-category.php">Manage Category</a></li>
				<li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="update-category.php">Update Category</a></li>
            </ul>
        </div>
    </div>
    <br/>
    <div class="table-data">
        <div class="order">
            <div class="head">
                <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <table>
                        <tr>
                            <td>Title: </td>
                            <td>
                                <input type="text" name="title" value="<?php echo $title; ?>" id="ip2">
                                <div id="titleError" style="color: red;"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>Current Image: </td>
                            <td>
                                <?php if($current_image != "") { ?>
                                    <img src="<?php echo SITEURL; ?>uploads/category/<?php echo $current_image; ?>" width="150px">
                                <?php } else { echo "<div class='error'>Image Not Added.</div>"; } ?>
                            </td>
                        </tr>
                        <tr>
                            <td>New Image: </td>
                            <td><input type="file" name="image"></td>
                        </tr>
                        <tr>
                            <td>Featured: </td>
                            <td>
                                <input id="featuredYes" type="radio" name="featured" value="Yes" <?php if($featured == "Yes"){echo "checked";} ?>> Yes 
                                <input id="featuredNo" type="radio" name="featured" value="No" <?php if($featured == "No"){echo "checked";} ?>> No 
                                <div id="featuredError" style="color: red;"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>Active: </td>
                            <td>
                                <input id="activeYes" type="radio" name="active" value="Yes" <?php if($active == "Yes"){echo "checked";} ?>> Yes 
                                <input id="activeNo" type="radio" name="active" value="No" <?php if($active == "No"){echo "checked";} ?>> No 
                                <div id="activeError" style="color: red;"></div>
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

                <?php 
                if(isset($_POST['submit'])) {
                    $id = $_POST['id'];
                    $title = $_POST['title'];
                    $current_image = $_POST['current_image'];
                    $featured = $_POST['featured'];
                    $active = $_POST['active'];
                    $status = 'not_approved';
                    
                    if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
                        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                        $image_name = "Food_Category_".rand(000, 999).'.'.$ext;
                        $source_path = $_FILES['image']['tmp_name'];
                        $destination_path = "uploads/category/".$image_name;
                        
                        $upload = move_uploaded_file($source_path, $destination_path);
                        if(!$upload) {
                            $_SESSION['upload'] = "<div class='error'>Failed to Upload Image.</div>";
                            header('location:'.SITEURL.'manage-category.php');
                            die();
                        }
                        if($current_image != "") {
                            $remove_path = "uploads/category/".$current_image;
                            unlink($remove_path);
                        }
                    } else {
                        $image_name = $current_image;
                    }
                    
                    $sql2 = "UPDATE tbl_rcategory_notapproved SET title='$title', image_name='$image_name', featured='$featured', active='$active', status='$status' WHERE cid=$id";
                    $res2 = mysqli_query($conn, $sql2);
                    
                    if($res2 == true) {
                        $_SESSION['update'] = "<div class='success'>Category Updated Successfully. Awaiting Approval.</div>";
                        header('location:'.SITEURL.'manage-category.php');
                    } else {
                        $_SESSION['update'] = "<div class='error'>Failed to Update Category. Error: " . mysqli_error($conn) . "</div>";
                        header('location:'.SITEURL.'manage-category.php');
                    }
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




