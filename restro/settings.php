<?php include('../frontend/config/constants.php');
      include('login-check.php');
      error_reporting(0);
      @ini_set('display_errors', 0);

$restroname = $_SESSION['restro-name'];

$ei_order_notif = "SELECT order_status from tbl_eipay
                    WHERE order_status='Pending' OR order_status='Processing' OR order_status='OnTheWay'";
$res_ei_order_notif = mysqli_query($conn, $ei_order_notif);
$row_ei_order_notif = mysqli_num_rows($res_ei_order_notif);

$online_order_notif = "SELECT DISTINCT om.order_id
                       FROM order_manager om
                       JOIN online_orders_new oon ON om.order_id = oon.order_id
                       WHERE (om.order_status='Pending' OR om.order_status='Processing' OR om.order_status='OnTheWay')
                         AND oon.restro_name='$restroname'";
$res_online_order_notif = mysqli_query($conn, $online_order_notif);
$row_online_order_notif = mysqli_num_rows($res_online_order_notif);

$stock_notif = "SELECT stock FROM tbl_restro_food_item
                WHERE stock<=$low_stock_threshold and restro_name = '$restroname'";
$res_stock_notif = mysqli_query($conn, $stock_notif);
$row_stock_notif = mysqli_num_rows($res_stock_notif);

$message_notif = "SELECT message_status FROM message WHERE message_status = 'unread'";
$res_message_notif = mysqli_query($conn, $message_notif);
$row_message_notif = mysqli_num_rows($res_message_notif);

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_name = trim($_POST['restro_name'] ?? '');
    $current_image = trim($_POST['current_image'] ?? '');
    $current_licence_image = trim($_POST['current_licence_image'] ?? '');
    $new_image_path = $current_image;
    $new_licence_image_path = $current_licence_image;

    if ($new_name === '') {
        $error_message = 'Restaurant name is required.';
    } else {
        if (isset($_FILES['restro_image']) && !empty($_FILES['restro_image']['name'])) {
            $allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (!in_array($_FILES['restro_image']['type'], $allowed, true)) {
                $error_message = 'Only JPG, JPEG, PNG, and WEBP images are allowed.';
            } else {
                $upload_dir = 'uploads/restro-img/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $ext = strtolower(pathinfo($_FILES['restro_image']['name'], PATHINFO_EXTENSION));
                $file_name = 'restro_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                $target_path = $upload_dir . $file_name;

                if (move_uploaded_file($_FILES['restro_image']['tmp_name'], $target_path)) {
                    $new_image_path = $target_path;
                } else {
                    $error_message = 'Image upload failed. Please try again.';
                }
            }
        }

        if ($error_message === '' && isset($_FILES['food_licence_image']) && !empty($_FILES['food_licence_image']['name'])) {
            $allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (!in_array($_FILES['food_licence_image']['type'], $allowed, true)) {
                $error_message = 'Only JPG, JPEG, PNG, and WEBP images are allowed.';
            } else {
                $upload_dir = 'uploads/licence/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $ext = strtolower(pathinfo($_FILES['food_licence_image']['name'], PATHINFO_EXTENSION));
                $file_name = 'licence_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                $target_path = $upload_dir . $file_name;

                if (move_uploaded_file($_FILES['food_licence_image']['tmp_name'], $target_path)) {
                    $new_licence_image_path = $target_path;
                } else {
                    $error_message = 'Food licence image upload failed. Please try again.';
                }
            }
        }

        if ($error_message === '') {
            mysqli_begin_transaction($conn);
            try {
                $stmt = $conn->prepare('UPDATE tbl_restro SET restro_name=?, restro_image=?, food_licence_image=? WHERE restro_name=?');
                $stmt->bind_param('ssss', $new_name, $new_image_path, $new_licence_image_path, $restroname);
                $stmt->execute();

                $stmt_food = $conn->prepare('UPDATE tbl_restro_food_item SET restro_name=? WHERE restro_name=?');
                $stmt_food->bind_param('ss', $new_name, $restroname);
                $stmt_food->execute();

                $stmt_cat = $conn->prepare('UPDATE tbl_rcategory_notapproved SET restro_name=? WHERE restro_name=?');
                $stmt_cat->bind_param('ss', $new_name, $restroname);
                $stmt_cat->execute();

                $stmt_orders = $conn->prepare('UPDATE online_orders_new SET restro_name=? WHERE restro_name=?');
                $stmt_orders->bind_param('ss', $new_name, $restroname);
                $stmt_orders->execute();

                mysqli_commit($conn);

                $_SESSION['restro-name'] = $new_name;
                $restroname = $new_name;
                $success_message = 'Settings updated successfully.';
            } catch (Throwable $e) {
                mysqli_rollback($conn);
                $error_message = 'Unable to update settings. Please try again.';
            }
        }
    }
}

$profile_sql = "SELECT restro_name, restro_image, food_licence_image FROM tbl_restro WHERE restro_name='$restroname' LIMIT 1";
$profile_res = mysqli_query($conn, $profile_sql);
$profile = $profile_res ? mysqli_fetch_assoc($profile_res) : null;
$current_name = $profile['restro_name'] ?? $restroname;
$current_image = $profile['restro_image'] ?? '';
$current_licence_image = $profile['food_licence_image'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style-admin.css">
    <link rel="icon" type="image/png" href="../images/logo2.png">
    <title>Restaurant Settings</title>
    <style>
        .profile-preview { width: 120px; height: 120px; border-radius: 12px; object-fit: cover; border: 1px solid var(--line); }
        .settings-grid { display: grid; gap: 14px; }
    </style>
</head>
<body>
<section id="sidebar">
    <a href="index.php" class="brand"><img src="../images/logo2.png" width="120px" alt=""></a>
    <ul class="side-menu top">
        <li><a href="index.php"><i class='bx bxs-dashboard'></i><span class="text">Dashboard</span></a></li>
        <li><a href="manage-online-order.php"><i class='bx bxs-cart'></i><span class="text">Online Orders&nbsp;</span><?php if($row_online_order_notif>0){ ?><span class="num-ei"><?php echo $row_online_order_notif; ?></span><?php } ?></a></li>
        <li><a href="manage-category.php"><i class='bx bxs-category'></i><span class="text">Category</span></a></li>
        <li><a href="manage-food.php"><i class='bx bxs-food-menu'></i><span class="text">Food Menu</span></a></li>
        <li><a href="inventory.php"><i class='bx bxs-box'></i><span class="text">Inventory</span><?php if($row_stock_notif>0){ ?><span class="num-ei"><?php echo $row_stock_notif; ?></span><?php } ?></a></li>
        <li><a href="manage-review.php"><i class="bx bx-star"></i><span class="text">Your Review</span></a></li>
        <li><a href="manage-repeat-rate.php"><i class="bx bx-bar-chart-alt-2"></i><span class="text">Your Repeat Rate</span></a></li>
        <li><a href="update-password.php"><i class="bx bx-lock"></i><span class="text">Change Password</span></a></li>
    </ul>
    <ul class="side-menu">
        <li class="active"><a href="settings.php"><i class='bx bxs-cog'></i><span class="text">Settings</span></a></li>
        <li><a href="logout.php" class="logout"><i class='bx bxs-log-out-circle'></i><span class="text">Logout</span></a></li>
    </ul>
</section>

<section id="content">
    <nav>
        <i class='bx bx-menu'></i>
        <a href="#" class="nav-link"></a>
        <form action="#"><div class="form-input"><input type="search" placeholder="Search..."><button type="submit" class="search-btn"><i class='bx bx-search'></i></button></div></form>
        <input type="checkbox" id="switch-mode" hidden><label for="switch-mode" class="switch-mode"></label>
    </nav>

    <main>
        <div class="head-title">
            <div class="left">
                <h1>Settings</h1>
                <ul class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li><a class="active" href="settings.php">Settings</a></li>
                </ul>
            </div>
        </div>

        <div class="table-data">
            <div class="order">
                <div class="head"><h3>Profile Settings</h3></div>
                <?php if ($success_message !== '') { echo '<p class="success">' . htmlspecialchars($success_message) . '</p>'; } ?>
                <?php if ($error_message !== '') { echo '<p class="error">' . htmlspecialchars($error_message) . '</p>'; } ?>

                <form method="POST" enctype="multipart/form-data" class="settings-grid">
                    <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($current_image); ?>">
                    <input type="hidden" name="current_licence_image" value="<?php echo htmlspecialchars($current_licence_image); ?>">

                    <div>
                        <label>Restaurant Name</label>
                        <input type="text" name="restro_name" value="<?php echo htmlspecialchars($current_name); ?>" required>
                    </div>

                    <div>
                        <label>Current Image</label><br>
                        <?php if ($current_image !== '' && file_exists($current_image)) { ?>
                            <img src="<?php echo htmlspecialchars($current_image); ?>" alt="Restaurant Image" class="profile-preview">
                        <?php } else { ?>
                            <p class="error">No image available.</p>
                        <?php } ?>
                    </div>

                    <div>
                        <label>Change Image</label>
                        <input type="file" name="restro_image" accept="image/*">
                    </div>

                    <div>
                        <label>Current Food Licence Image</label><br>
                        <?php if ($current_licence_image !== '' && file_exists($current_licence_image)) { ?>
                            <img src="<?php echo htmlspecialchars($current_licence_image); ?>" alt="Food Licence Image" class="profile-preview">
                        <?php } else { ?>
                            <p class="error">No food licence image available.</p>
                        <?php } ?>
                    </div>

                    <div>
                        <label>Change Food Licence Image</label>
                        <input type="file" name="food_licence_image" accept="image/*">
                    </div>

                    <div>
                        <button type="submit" class="button-8">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</section>

<script src="script-admin.js"></script>
</body>
</html>

