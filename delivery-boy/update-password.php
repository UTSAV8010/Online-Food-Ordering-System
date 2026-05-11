<?php include('../frontend/config/constants.php');   include('check-ban.php');?>
<?php //include('login-check.php'); ?>

<?php
$ei_order_notif = "SELECT order_status from tbl_eipay
					WHERE order_status='Pending' OR order_status='Processing'OR order_status='OnTheWay'";

$res_ei_order_notif = mysqli_query($conn, $ei_order_notif);

$row_ei_order_notif = mysqli_num_rows($res_ei_order_notif);

$online_order_notif = "SELECT order_status from order_manager
					WHERE order_status='Pending'OR order_status='Processing'OR order_status='OnTheWay' ";

$res_online_order_notif = mysqli_query($conn, $online_order_notif);

$row_online_order_notif = mysqli_num_rows($res_online_order_notif);

$stock_notif = "SELECT stock FROM tbl_food
				WHERE stock<50";

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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="style-admin.css">
    <link rel="icon" type="image/png" href="../images/logo2.png">
    <title> Delivery-boy Management</title>
    <style>
    a.clickable {
        color: gray !important;
        pointer-events: auto !important;
        text-decoration: none !important;
    }

    a.clickable:hover {
        color: #007bff !important;
    }
    .iframe-map {
        width: 100%;
        height: 150px;
        border: 0;
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
            <li>
                <a href="index.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            
            <li >
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
                <a href="manage-delivery-payment.php">
                <i class="bx bx-rupee"></i>
                    <span class="text">Payment History</span>
                </a>
            </li>
            <li >
                <a href="manage-review.php">
                <i class="bx bx-star"></i>
                    <span class="text">Your Review</span>
                </a>
            </li>
            <li class="active">
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
                    <i class='bx bxs-log-out-circle'></i>
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
            <i class='bx bx-menu'></i>
            <a href="#" class="nav-link"></a>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Search...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
            
            <div class="notification" onclick= "menuToggle();">
			<div class="action notif" onclick="menuToggle();">
        <i class='bx bxs-bell' onclick="menuToggle();"></i>
        <div class="notif_menu">
            <ul>
                <?php 
                // Check Stock Notifications
               

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
        $total_notif = $row_online_order_notif + $row_ei_order_notif;
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
    <!-- MAIN -->
    <?php

$current_password_err = $new_password_err = $confirm_password_err = "";

// Ensure user is logged in
if (!isset($_SESSION['delivery-boy'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['delivery-boy'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate current password
    if (empty($current_password)) {
        $current_password_err = "Current password is required.";
    }

    // Validate new password
    if (empty($new_password)) {
        $new_password_err = "New password is required.";
    } elseif (
        !preg_match("/[A-Z]/", $new_password) ||
        !preg_match("/[a-z]/", $new_password) ||
        !preg_match("/\d/", $new_password) ||
        !preg_match("/[\W_]/", $new_password) ||
        strlen($new_password) < 8
    ) {
        $new_password_err = "Password must be at least 8 characters long and contain one uppercase, one lowercase, one number, and one special character.";
    }

    // Validate confirm password
    if (empty($confirm_password)) {
        $confirm_password_err = "Please confirm your new password.";
    } elseif ($new_password !== $confirm_password) {
        $confirm_password_err = "Passwords do not match.";
    }

    if (empty($current_password_err) && empty($new_password_err) && empty($confirm_password_err)) {
        // Fetch current password
        $sql = "SELECT password FROM tbl_delivery_boy WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed_password);
            $stmt->fetch();

            if (password_verify($current_password, $hashed_password)) {
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update password
                $sql2 = "UPDATE tbl_delivery_boy SET password=? WHERE username=?";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->bind_param("ss", $new_hashed_password, $username);
                $res2 = $stmt2->execute();

                if ($res2) {
                    echo "<script>
                        alert('Password changed successfully!');
                        window.location.href = 'index.php';
                    </script>";
                    exit();
                } else {
                    echo "<script>
                        alert('Failed to change password. Try again.');
                        window.location.href = 'update-password.php';
                    </script>";
                    exit();
                }
            } else {
                echo "<script>
                    alert('Incorrect current password.');
                    window.location.href = 'update-password.php';
                </script>";
                exit();
            }
        } else {
            echo "<script>
                alert('User not found.');
                window.location.href = 'update-password.php';
            </script>";
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
                <li><a class="active" href="#">Change Password</a></li>
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
                                <input type="password" name="current_password" value="<?php echo isset($current_password) ? htmlspecialchars($current_password) : ''; ?>" id="ip2">
                                <span class="error"><?php echo $current_password_err; ?></span>
                            </td>
                        </tr>

                        <tr>
                            <td>New Password</td>
                            <td>
                                <input type="password" name="new_password" value="<?php echo isset($new_password) ? htmlspecialchars($new_password) : ''; ?>" id="ip2">
                                <span class="error"><?php echo $new_password_err; ?></span>
                            </td>
                        </tr>

                        <tr>
                            <td>Confirm Password</td>
                            <td>
                                <input type="password" name="confirm_password" value="<?php echo isset($confirm_password) ? htmlspecialchars($confirm_password) : ''; ?>" id="ip2">
                                <span class="error"><?php echo $confirm_password_err; ?></span>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
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

   
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="script-admin.js"></script>
</body>

</html>


