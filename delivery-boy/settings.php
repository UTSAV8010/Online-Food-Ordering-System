<?php include('../frontend/config/constants.php');
      include('login-check.php');
      include('check-ban.php');

$delivery_username = $_SESSION['delivery-boy'];

$ei_order_notif = "SELECT order_status from tbl_eipay
                    WHERE order_status='Pending' OR order_status='Processing' OR order_status='OnTheWay'";
$res_ei_order_notif = mysqli_query($conn, $ei_order_notif);
$row_ei_order_notif = mysqli_num_rows($res_ei_order_notif);

$online_order_notif = "SELECT order_status from order_manager
                       WHERE order_status='Pending' OR order_status='Processing' OR order_status='OnTheWay'";
$res_online_order_notif = mysqli_query($conn, $online_order_notif);
$row_online_order_notif = mysqli_num_rows($res_online_order_notif);

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_name = trim($_POST['name'] ?? '');
    $current_image = trim($_POST['current_image'] ?? '');
    $new_image_path = $current_image;

    if ($new_name === '') {
        $error_message = 'Name is required.';
    } else {
        if (isset($_FILES['profile_image']) && !empty($_FILES['profile_image']['name'])) {
            $allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (!in_array($_FILES['profile_image']['type'], $allowed, true)) {
                $error_message = 'Only JPG, JPEG, PNG, and WEBP images are allowed.';
            } else {
                $upload_dir = 'uploads/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $ext = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
                $file_name = 'delivery_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                $target_path = $upload_dir . $file_name;

                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_path)) {
                    $new_image_path = $target_path;
                } else {
                    $error_message = 'Image upload failed. Please try again.';
                }
            }
        }

        if ($error_message === '') {
            $stmt = $conn->prepare('UPDATE tbl_delivery_boy SET name=?, adhar_image=? WHERE username=?');
            $stmt->bind_param('sss', $new_name, $new_image_path, $delivery_username);

            if ($stmt->execute()) {
                $success_message = 'Settings updated successfully.';
            } else {
                $error_message = 'Unable to update settings. Please try again.';
            }
        }
    }
}

$profile_stmt = $conn->prepare('SELECT name, adhar_image FROM tbl_delivery_boy WHERE username=? LIMIT 1');
$profile_stmt->bind_param('s', $delivery_username);
$profile_stmt->execute();
$profile_res = $profile_stmt->get_result();
$profile = $profile_res ? $profile_res->fetch_assoc() : null;
$current_name = $profile['name'] ?? $delivery_username;
$current_image = $profile['adhar_image'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style-admin.css">
    <link rel="icon" type="image/png" href="../images/logo2.png">
    <title>Delivery Settings</title>
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
        <li><a href="manage-delivery-payment.php"><i class="bx bx-rupee"></i><span class="text">Payment History</span></a></li>
        <li><a href="manage-review.php"><i class="bx bx-star"></i><span class="text">Your Review</span></a></li>
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

                    <div>
                        <label>Name</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($current_name); ?>" required>
                    </div>

                    <div>
                        <label>Delivery Boy Aadhaar Image</label><br>
                        <?php if ($current_image !== '' && file_exists($current_image)) { ?>
                            <img src="<?php echo htmlspecialchars($current_image); ?>" alt="Profile Image" class="profile-preview">
                        <?php } else { ?>
                            <p class="error">No image available.</p>
                        <?php } ?>
                    </div>

                    <div>
                        <label>Change Aadhaar Image</label>
                        <input type="file" name="profile_image" accept="image/*">
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
