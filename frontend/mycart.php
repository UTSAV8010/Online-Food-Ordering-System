<?php
include('config/constants.php');
include('config/blocked-check.php');

$cartItems = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];
$couponMessage = '';
$couponClass = '';
$discount = 0;
$baseTotal = 0;
$isCartEmpty = count($cartItems) === 0;

foreach ($cartItems as $item) {
    $baseTotal += ((float)$item['Price'] * (int)$item['Quantity']);
}

$totalAmount = $baseTotal;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_coupon'])) {
    $couponCode = trim($_POST['coupon_code'] ?? '');

    if ($couponCode === '') {
        $couponMessage = 'Please enter a coupon code.';
        $couponClass = 'text-danger';
    } else {
        $couponCodeEsc = mysqli_real_escape_string($conn, $couponCode);
        $discountValue = 0;

        $couponSql = "SELECT discount FROM tbl_coupon WHERE coupon_code = '$couponCodeEsc' AND status = 'active' LIMIT 1";
        $couponRes = mysqli_query($conn, $couponSql);

        if ($couponRes && mysqli_num_rows($couponRes) > 0) {
            $couponRow = mysqli_fetch_assoc($couponRes);
            $discountValue = (float)$couponRow['discount'];
        } else {
            $festSql = "SELECT discount FROM tbl_fest_coupon WHERE coupon_code = '$couponCodeEsc' AND status = 'active' AND expire = 'active' LIMIT 1";
            $festRes = mysqli_query($conn, $festSql);
            if ($festRes && mysqli_num_rows($festRes) > 0) {
                $couponRow = mysqli_fetch_assoc($festRes);
                $discountValue = (float)$couponRow['discount'];
            }
        }

        if ($discountValue > 0) {
            $discount = $discountValue;
            $totalAmount = max(0, $baseTotal - ($baseTotal * ($discount / 100)));
            $couponMessage = "Coupon applied: $discount% OFF";
            $couponClass = 'text-success';
        } else {
            $couponMessage = 'Invalid or expired coupon code.';
            $couponClass = 'text-danger';
        }
    }
}

$isLoggedIn = isset($_SESSION['user']);
$username = '';
$cus_name = '';
$cus_email = '';
$cus_add1 = '';
$cus_city = '';
$cus_phone = '';

if ($isLoggedIn) {
    $username = $_SESSION['user'];
    $stmtUser = $conn->prepare('SELECT username, name, email, add1, city, phone FROM tbl_users WHERE username = ? LIMIT 1');
    if ($stmtUser) {
        $stmtUser->bind_param('s', $username);
        $stmtUser->execute();
        $resUser = $stmtUser->get_result();
        if ($resUser && $resUser->num_rows > 0) {
            $row = $resUser->fetch_assoc();
            $username = $row['username'];
            $cus_name = $row['name'];
            $cus_email = $row['email'];
            $cus_add1 = $row['add1'];
            $cus_city = $row['city'];
            $cus_phone = $row['phone'];
        } else {
            $isLoggedIn = false;
        }
    }
}

date_default_timezone_set('Asia/Kolkata');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['purchase'])) {
    $pay_mode = $_POST['pay_mode'] ?? '';
    $payment_status = 'cod';
    $tran_id = $_POST['tran_id'] ?? '';
    $upi_ref = $_POST['upi_ref'] ?? '';
    $upi_id = $_POST['upi_id'] ?? '';
    $amount = (float)($_POST['amount'] ?? 0);
    $username = $_POST['username'] ?? '';
    $cus_name = $_POST['cus_name'] ?? '';
    $cus_email = $_POST['cus_email'] ?? '';
    $cus_add1 = $_POST['cus_add1'] ?? '';
    $cus_city = $_POST['cus_city'] ?? '';
    $cus_phone = $_POST['cus_phone'] ?? '';
    $location = $_POST['location'] ?? '';
    $order_date = date('Y-m-d H:i:s');

    if ($pay_mode === 'card') {
        $card_number = $_POST['card_number'] ?? '';
        $card_expiry = $_POST['card_expiry'] ?? '';
        $card_cvv = $_POST['card_cvv'] ?? '';

        if ($card_number === '1234567890123456' && $card_expiry === '12/25' && $card_cvv === '123') {
            $payment_status = 'successful';
        } else {
            echo "<script>alert('Payment failed. Please check card details.');</script>";
            exit();
        }
    } elseif ($pay_mode === 'upi') {
        $upi_ref = trim($upi_ref);
        $upi_id = trim($upi_id);
        if ($upi_id === '') {
            echo "<script>alert('Please enter your UPI ID.');</script>";
            exit();
        }
        if (!preg_match('/^[A-Za-z0-9._-]{2,}@[A-Za-z0-9.-]{2,}$/', $upi_id)) {
            echo "<script>alert('Please enter a valid UPI ID (e.g., name@bank).');</script>";
            exit();
        }
        if ($upi_ref === '') {
            echo "<script>alert('Please enter your UPI transaction ID.');</script>";
            exit();
        }
        $payment_status = 'upi';
        $tran_id = 'UPI:' . $upi_id . ' | UTR:' . $upi_ref;
    }

    $insert_order_manager = "INSERT INTO `order_manager` (username, cus_name, cus_email, cus_add1, cus_city, cus_phone, payment_status, order_date, total_amount, transaction_id, order_status, location)
    VALUES ('$username', '$cus_name', '$cus_email', '$cus_add1', '$cus_city', '$cus_phone', '$payment_status', '$order_date', '$amount', '$tran_id', 'Pending', '$location')";

    if (mysqli_query($conn, $insert_order_manager)) {
        $order_id = mysqli_insert_id($conn);

        $card_type = $pay_mode === 'upi' ? 'upi' : 'card';
        $insert_aamarpay = "INSERT INTO `aamarpay` (order_id, cus_name, amount, status, transaction_id, card_type)
        VALUES ('$order_id', '$cus_name', '$amount', '$payment_status', '$tran_id', '$card_type')";
        mysqli_query($conn, $insert_aamarpay);

        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            $insert_online_orders_new = $conn->prepare("INSERT INTO `online_orders_new` (order_id, Item_Name, Price, Quantity, total_amount, Restro_Name) VALUES (?, ?, ?, ?, ?, ?)");

            foreach ($_SESSION['cart'] as $value) {
                $item_name = $value['Item_Name'];
                $price = (float)$value['Price'];
                $quantity = (int)$value['Quantity'];
                $line_total = $price * $quantity;
                $restro_name = $value['Restro_Name'];

                $insert_online_orders_new->bind_param('isiids', $order_id, $item_name, $price, $quantity, $line_total, $restro_name);
                $insert_online_orders_new->execute();

                $update_quantity_query = "UPDATE `tbl_food` SET stock = stock - $quantity WHERE title = '$item_name'";
                mysqli_query($conn, $update_quantity_query);
                $update_quantity = "UPDATE `tbl_restro_food_item` SET stock = stock - $quantity WHERE title = '$item_name'";
                mysqli_query($conn, $update_quantity);
            }

            $insert_online_orders_new->close();
        }

        unset($_SESSION['cart']);
        echo "<script>alert('Order placed successfully!'); window.location.href = 'view-orders.php';</script>";
    } else {
        echo "<script>alert('Failed to place order.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>My Cart | Pasar-kita</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <link rel="icon" type="image/png" href="images/logo2.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
       .container-xxl {
	max-width: 100%;
}

:root {
	--cart-primary: #0e1f4d;
	--cart-accent: #f2a208;
	--cart-text: #1c2743;
	--cart-muted: #70809f;
	--cart-border: #e6eaf4;
	--cart-surface: #ffffff;
	--cart-soft: #f6f9ff;
}

body {
	background: #f3f6fb;
	color: var(--cart-text);
}

.cart-page {
	margin: 12px auto 40px;
}

.cart-hero {
	background: radial-gradient(1200px 280px at 20% 0%, rgba(242, 162, 8, 0.18), transparent 72%), linear-gradient(130deg, #0a1842, #112a63 56%, #0a1a43);
	border-radius: 22px;
	color: #fff;
	padding: 28px 26px;
	box-shadow: 0 22px 48px rgba(13, 29, 72, 0.24);
}

.cart-hero h1 {
	margin: 0;
	font-weight: 800;
}

.cart-hero p {
	margin: 8px 0 0;
	color: rgba(255, 255, 255, .86);
}

.cart-layout {
	margin-top: 24px;
}

.cart-card,
.summary-card {
	background: var(--cart-surface);
	border: 1px solid var(--cart-border);
	border-radius: 20px;
	box-shadow: 0 14px 36px rgba(15, 33, 74, 0.08);
}

.cart-card {
	padding: 16px;
}

.cart-table {
	margin: 0;
}

.cart-table thead th {
	border: 0;
	font-size: .86rem;
	text-transform: uppercase;
	letter-spacing: .04em;
	color: #5a6b8d;
	font-weight: 800;
	padding: 14px 10px;
	background: #f7f9fe;
}

.cart-table tbody td {
	vertical-align: middle;
	border-color: #edf1f9;
	padding: 14px 10px;
	font-size: .95rem;
}

.qty-input {
	width: 88px;
	border-radius: 10px;
	border: 1px solid #cfdaee;
	padding: 6px 8px;
}

.remove-btn {
	border: 0;
	border-radius: 10px;
	background: #ffe6e8;
	color: #be2c36;
	font-weight: 700;
	padding: 7px 12px;
	transition: .2s;
}

.remove-btn:hover {
	background: #ffd4d8;
}

.summary-card {
	position: sticky;
	top: 110px;
	padding: 18px;
}

.summary-header {
	background: linear-gradient(135deg, #fff2d6, #ffffff);
	border: 1px solid #f3e1bf;
	border-radius: 16px;
	padding: 14px 16px;
	margin-bottom: 16px;
}

.summary-title {
	font-size: 1.35rem;
	font-weight: 800;
	margin: 0;
}

.summary-sub {
	color: var(--cart-muted);
	margin: 4px 0 0;
}

.total-pill {
	background: linear-gradient(135deg, #ffb833, #f2a208);
	color: #fff;
	border-radius: 16px;
	padding: 14px;
	margin-bottom: 14px;
	text-align: center;
	box-shadow: 0 12px 24px rgba(242, 162, 8, .25);
}

.total-pill .label {
	font-size: .85rem;
	opacity: .9;
}

.total-pill .value {
	font-size: 2rem;
	font-weight: 800;
	line-height: 1;
	margin-top: 4px;
}

.info-box {
	background: var(--cart-soft);
	border: 1px solid #e6edf7;
	border-radius: 14px;
	padding: 14px 14px 14px 18px;
	margin-bottom: 12px;
	position: relative;
	box-shadow: 0 10px 20px rgba(15, 33, 74, 0.06);
}

.info-box::before {
	content: "";
	position: absolute;
	left: 8px;
	top: 12px;
	bottom: 12px;
	width: 3px;
	border-radius: 999px;
	background: #f2a208;
}

.info-box h6 {
	font-size: .95rem;
	font-weight: 800;
	margin-bottom: 8px;
}

.info-box .form-check {
	display: flex;
	align-items: center;
	gap: 8px;
	padding: 6px 0;
	padding-left: 0 !important;
}

.info-box .form-check-input {
	margin-top: 0;
	margin-left: 0 !important;
	position: static;
}

#card-details {
	margin-top: 10px;
	background: #ffffff;
	border: 1px solid #e1e7f2;
	border-radius: 12px;
	padding: 10px;
}

#card-details .form-control {
	border-radius: 10px;
}

#upi-details {
	margin-top: 10px;
	background: #ffffff;
	border: 1px solid #e1e7f2;
	border-radius: 12px;
	padding: 10px;
}

#upi-details .form-control {
	border-radius: 10px;
}

.summary-card .form-control {
	border-radius: 10px;
	border-color: #d6e0f1;
}

.summary-card .btn-warning {
	border-radius: 10px;
    border: 0;
    background: #e69500;
    color: #fff;
    padding: 10px 16px;
}
.info-box .btn-primary{
    border-radius: 10px;
    border: 0;
    background: #e69500;
    color: #fff;
    padding: 10px 16px;
}
.summary-card .btn-outline-primary {
	border-radius: 10px;
}
.summary-card .btn-outline-primary:hover{
    color: #fff;
    background-color: #fea116;
    border-color: #fea116;
}


.checkout-btn {
	width: 100%;
	border: 0;
	border-radius: 12px;
	font-size: 1rem;
	font-weight: 800;
	color: #fff;
	background: linear-gradient(135deg, #ffb833, #f2a208);
	padding: 12px 14px;
	box-shadow: 0 12px 24px rgba(242, 162, 8, .3);
	transition: transform .2s ease, box-shadow .2s ease;
}

.checkout-btn:hover {
	transform: translateY(-1px);
	box-shadow: 0 16px 28px rgba(242, 162, 8, .35);
}

#map {
	height: 240px;
	border: 1px solid #d4deef;
	border-radius: 12px;
	overflow: hidden;
	box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.6);
}

.empty-cart {
	padding: 34px 14px;
	text-align: center;
	border: 2px dashed #d6dfef;
	border-radius: 14px;
	background: #f9fbff;
}

.empty-cart i {
	font-size: 2rem;
	color: #8ca0c5;
}

.empty-cart h5 {
	margin: 12px 0 6px;
	font-weight: 800;
}

.empty-cart p {
	color: #6e7d98;
	margin-bottom: 14px;
}

.btn-soft {
	border-radius: 10px;
	font-weight: 700;
}

.summary-card {
	margin-bottom: 130px;
}

.checkout-btn {
	margin-bottom: 10px;
}

@media (max-width: 991.98px) {
	.summary-card {
		margin-bottom: 160px;
	}
}


@media (max-width:991px) {
	.summary-card {
		position: static;
		margin-top: 16px;
	}
}

@media (max-width:767px) {
	.cart-hero {
		padding: 22px 18px;
	}

	.cart-table thead {
		display: none;
	}

	.cart-table tbody tr {
		display: block;
		border: 1px solid #e7edf9;
		border-radius: 14px;
		margin-bottom: 12px;
		padding: 8px 6px;
		background: #fff;
	}

	.cart-table tbody td {
		display: flex;
		justify-content: space-between;
		align-items: center;
		border: 0;
		padding: 7px 8px;
		text-align: right;
	}

	.cart-table tbody td::before {
		content: attr(data-label);
		font-weight: 700;
		color: #5c6f92;
		text-align: left;
		margin-right: 10px;
	}
}

.scroll-top-button:hover {
	background: #e69500;
}

.back-to-top {
	right: 0 !important;
	bottom: 27px !important;
}
    </style>
</head>
<body>
   <div class="container-xxl bg-white p-0">
   <div class="container-xxl position-relative p-0">
      <?php include('site-hader.php'); ?>
   </div>
   <div class="container cart-page">
      <!-- <div class="cart-hero">
         <h1>My Cart</h1>
         <p>Review items, set delivery location, choose payment mode, and place your order.</p>
         </div> -->
      <div class="row cart-layout g-4">
         <div class="<?php echo $isCartEmpty ? 'col-12' : 'col-lg-8'; ?>">
            <div class="cart-card">
               <?php if (count($cartItems) === 0): ?>
               <div class="empty-cart">
                  <i class="bi bi-cart-x"></i>
                  <h5>Your cart is empty</h5>
                  <p>Add delicious items from menu to continue.</p>
                  <a href="menu.php" class="btn btn-primary btn-soft">Browse Menu</a>
               </div>
               <?php else: ?>
               <div class="table-responsive">
                  <table class="table cart-table" id="cart_table">
                     <thead>
                        <tr>
                           <th>S.N.</th>
                           <th>Item</th>
                           <th>Price</th>
                           <th>Restaurant</th>
                           <th>Qty</th>
                           <th>Subtotal</th>
                           <th></th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach ($cartItems as $key => $value) { $sn = $key + 1; ?>
                        <tr>
                           <td data-label="S.N."><?php echo $sn; ?></td>
                           <td data-label="Item"><?php echo htmlspecialchars($value['Item_Name']); ?></td>
                           <td data-label="Price"><?php echo htmlspecialchars($value['Price']); ?><input type="hidden" class="iprice" value="<?php echo htmlspecialchars($value['Price']); ?>"></td>
                           <td data-label="Restaurant"><?php echo htmlspecialchars($value['Restro_Name']); ?></td>
                           <td data-label="Qty">
                              <form action="<?php echo SITEURL; ?>manage-cart" method="POST">
                                 <input class="qty-input text-center iquantity" name="Mod_Quantity" onchange="this.form.submit();" type="number" value="<?php echo (int)$value['Quantity']; ?>" min="1" max="20">
                                 <input type="hidden" name="Item_Name" value="<?php echo htmlspecialchars($value['Item_Name']); ?>">
                              </form>
                           </td>
                           <td data-label="Subtotal" class="itotal"></td>
                           <td data-label="Remove">
                              <form action="<?php echo SITEURL; ?>manage-cart" method="POST">
                                 <button name="Remove_Item" class="remove-btn">Remove</button>
                                 <input type="hidden" name="Item_Name" value="<?php echo htmlspecialchars($value['Item_Name']); ?>">
                              </form>
                           </td>
                        </tr>
                        <?php } ?>
                     </tbody>
                  </table>
               </div>
               <?php endif; ?>
            </div>
            <p id="cartError" class="text-danger mt-3 fw-bold" style="display: none;">Please add food items to your cart before checkout.</p>
         </div>
         <?php if (count($cartItems) > 0): ?>
         <div class="col-lg-4">
            <div class="summary-card">
               <div class="summary-header">
                  <h3 class="summary-title">Order Summary</h3>
                  <p class="summary-sub">Fast and secure checkout</p>
               </div>
               <div class="total-pill">
                  <div class="label">Grand Total</div>
                  <div class="value" id="gtotal"><?php echo number_format($totalAmount, 2); ?></div>
               </div>
               <?php if (!$isLoggedIn): ?>
               <div class="alert alert-warning mb-0">Please login to place an order.</div>
               <a href="login.php" class="btn btn-primary w-100 mt-3 btn-soft">Login Now</a>
               <?php else: ?>
               <div class="info-box">
                  <h6>Apply Coupon</h6>
                  <form action="" method="POST" id="coupon-form">
                     <div class="form-group mb-2"><input type="text" name="coupon_code" id="coupon_code" class="form-control" placeholder="Coupon code"></div>
                     <button type="submit" name="apply_coupon" class="btn btn-warning btn-sm fw-bold">Apply Coupon</button>
                  </form>
                  <?php if ($couponMessage !== ''): ?>
                  <div class="<?php echo $couponClass; ?> mt-2 fw-bold"><?php echo htmlspecialchars($couponMessage); ?></div>
                  <?php endif; ?>
               </div>
               <form action="" method="POST" id="checkoutForm">
                  <input type="hidden" name="amount" value="<?php echo (float)$totalAmount; ?>">
                  <input type="hidden" name="tran_id" value="ONL-PAY-<?php echo uniqid(); ?>">
                  <input type="hidden" name="payment_status" value="pending">
                  <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
                  <div class="info-box">
                     <h6>Delivery Address</h6>
                     <div class="small">
                        <div><?php echo htmlspecialchars($cus_name); ?></div>
                        <div><?php echo htmlspecialchars($cus_email); ?></div>
                        <div><?php echo htmlspecialchars($cus_add1); ?>, <?php echo htmlspecialchars($cus_city); ?></div>
                        <div><?php echo htmlspecialchars($cus_phone); ?></div>
                     </div>
                     <input type="hidden" name="cus_name" value="<?php echo htmlspecialchars($cus_name); ?>" required>
                     <input type="hidden" name="cus_email" value="<?php echo htmlspecialchars($cus_email); ?>" required>
                     <input type="hidden" name="cus_add1" value="<?php echo htmlspecialchars($cus_add1); ?>" required>
                     <input type="hidden" name="cus_city" value="<?php echo htmlspecialchars($cus_city); ?>" required>
                     <input type="hidden" name="cus_phone" value="<?php echo htmlspecialchars($cus_phone); ?>" required>
                     <a href="update-account.php" class="btn btn-outline-primary btn-sm mt-2">Change Address</a>
                  </div>
                  <div class="info-box">
                     <h6>Select Delivery Location</h6>
                     <div class="form-group mb-2">
                        <input type="text" id="search-location" class="form-control" placeholder="Search area, pincode, or full address">
                     </div>
                     <div class="d-flex flex-wrap gap-2 mb-2">
                        <button type="button" id="go-button" class="btn btn-primary btn-sm">Search</button>
                        <button type="button" id="geo-button" class="btn btn-outline-primary btn-sm">Use My Location</button>
                     </div>
                     <div id="map"></div>
                     <div class="mt-2 small text-muted" id="location-preview">No location selected yet.</div>
                     <div id="location-wrapper" class="mt-2">
                        <input type="hidden" id="live-location" name="location">
                        <input type="hidden" id="live-address" name="location_address">
                     </div>
                  </div>
                  <div class="info-box">
                     <h6>Payment Method</h6>
                     <div class="form-check"><input class="form-check-input" type="radio" name="pay_mode" value="cod" id="cod" required><label class="form-check-label" for="cod">Cash on Delivery</label></div>
                     <div class="form-check"><input class="form-check-input" type="radio" name="pay_mode" value="card" id="card" required><label class="form-check-label" for="card">Pay with Card</label></div>
                     <div class="form-check mb-2"><input class="form-check-input" type="radio" name="pay_mode" value="upi" id="upi" required><label class="form-check-label" for="upi">UPI / Online Bank Transfer (GPay, Paytm, PhonePe, etc.)</label></div>
                     <div id="card-details" style="display:none;">
                        <input type="text" name="card_number" class="form-control mb-2" placeholder="Card Number">
                        <input type="text" name="card_expiry" class="form-control mb-2" placeholder="MM/YY Expiry">
                        <input type="text" name="card_cvv" class="form-control" placeholder="CVV">
                     </div>
                     <div id="upi-details" style="display:none;">
                        <input type="text" name="upi_id" class="form-control mb-2" placeholder="UPI ID (e.g., name@bank)">
                        <input type="text" name="upi_ref" class="form-control" placeholder="UPI Transaction ID / UTR">
                        <small class="text-muted d-block mt-1">Use your reference from GPay, Paytm, PhonePe, or any UPI app.</small>
                     </div>
                  </div>
                  <button class="checkout-btn" name="purchase" id="checkoutBtn" type="submit">Checkout</button>
               </form>
               <?php endif; ?>
            </div>
         </div>
         <?php endif; ?>
      </div>
   </div>

<?php include('chatbot.php'); ?>
<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/wow/wow.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/counterup/counterup.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>
<script src="lib/tempusdominus/js/moment.min.js"></script>
<script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
<script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="js/main.js"></script>
<?php if (defined('GOOGLE_MAPS_API_KEY') && GOOGLE_MAPS_API_KEY !== '') { ?>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_KEY; ?>&libraries=places&callback=initGoogleMap&language=en&region=IN"></script>
<?php } ?>
<script>
(function () {
    const iprice = document.getElementsByClassName('iprice');
    const iquantity = document.getElementsByClassName('iquantity');
    const itotal = document.getElementsByClassName('itotal');
    const gtotal = document.getElementById('gtotal');

    function subTotal() {
        let gt = 0;
        for (let i = 0; i < iprice.length; i++) {
            const line = parseFloat(iprice[i].value) * parseInt(iquantity[i].value, 10);
            itotal[i].innerText = line.toFixed(2);
            gt += line;
        }
        if (gtotal && iprice.length > 0) {
            gtotal.innerText = gt.toFixed(2);
        }
    }

    subTotal();
})();
</script>

<script>
let map;
let marker;
let geocoder;
let autocomplete;
let placesService;

function initGoogleMap() {
    const mapNode = document.getElementById('map');
    if (!mapNode || !window.google || !google.maps) {
        return;
    }

    map = new google.maps.Map(mapNode, {
        center: { lat: 20.5937, lng: 78.9629 },
        zoom: 5,
        mapTypeControl: false,
        fullscreenControl: false
    });
    geocoder = new google.maps.Geocoder();
    placesService = new google.maps.places.PlacesService(map);
    marker = new google.maps.Marker({ map });

    map.addListener('click', function (event) {
        if (!event || !event.latLng) return;
        setMarker(event.latLng);
        setResolvingState();
        reverseGeocode(event.latLng);
    });

    const searchInput = document.getElementById('search-location');
    if (searchInput) {
        autocomplete = new google.maps.places.Autocomplete(searchInput, { types: ['geocode'] });
        autocomplete.setFields(['geometry', 'formatted_address', 'name']);
        autocomplete.addListener('place_changed', function () {
            const place = autocomplete.getPlace();
            if (!place || !place.geometry || !place.geometry.location) return;
            setMarker(place.geometry.location);
            map.setCenter(place.geometry.location);
            map.setZoom(15);
            const label = place.formatted_address || place.name || 'Selected location';
            updateLocation(label, place.geometry.location);
        });
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const cardDetails = document.getElementById('card-details');
    const upiDetails = document.getElementById('upi-details');
    const paymentInputs = document.querySelectorAll('[name="pay_mode"]');
    paymentInputs.forEach((input) => {
        input.addEventListener('change', () => {
            if (cardDetails) {
                cardDetails.style.display = input.value === 'card' && input.checked ? 'block' : 'none';
            }
            if (upiDetails) {
                upiDetails.style.display = input.value === 'upi' && input.checked ? 'block' : 'none';
            }
        });
    });

    const locationPreview = document.getElementById('location-preview');
    const addressInput = document.getElementById('live-address');
    const coordsInput = document.getElementById('live-location');
    const searchInput = document.getElementById('search-location');
    const goButton = document.getElementById('go-button');
    const geoButton = document.getElementById('geo-button');

    if (goButton) {
        goButton.addEventListener('click', function () {
            const raw = (searchInput.value || '').trim();
            if (!raw) {
                alert('Please enter an area, pincode, or full address.');
                return;
            }
            if (!geocoder) {
                alert('Google Maps is not ready yet.');
                return;
            }
            geocoder.geocode({ address: raw }, function (results, status) {
                if (status === 'OK' && results && results[0]) {
                    const location = results[0].geometry.location;
                    if (map) {
                        map.setCenter(location);
                        map.setZoom(15);
                    }
                    setMarker(location);
                    updateLocation(results[0].formatted_address, location);
                } else {
                    alert('Location not found.');
                }
            });
        });
    }

    if (geoButton) {
        geoButton.addEventListener('click', function () {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser.');
                return;
            }
            geoButton.disabled = true;
            geoButton.textContent = 'Locating...';
            navigator.geolocation.getCurrentPosition(function (pos) {
                const latLng = new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude);
                if (map) {
                    map.setCenter(latLng);
                    map.setZoom(15);
                }
                setMarker(latLng);
                setResolvingState();
                reverseGeocode(latLng);
                geoButton.disabled = false;
                geoButton.textContent = 'Use My Location';
            }, function () {
                alert('Unable to get your live location.');
                geoButton.disabled = false;
                geoButton.textContent = 'Use My Location';
            }, { enableHighAccuracy: true, timeout: 10000 });
        });
    }

    function setMarker(latLng) {
        if (!marker) return;
        marker.setPosition(latLng);
        marker.setMap(map);
    }

    function updateLocation(label, latLng) {
        if (locationPreview) locationPreview.textContent = label;
        if (addressInput) addressInput.value = label;
        if (coordsInput) coordsInput.value = latLng.lat().toFixed(6) + ',' + latLng.lng().toFixed(6);
        if (searchInput) searchInput.value = label;
    }

    function reverseGeocode(latLng) {
        if (!geocoder) return;
        geocoder.geocode({ location: latLng }, function (results, status) {
            if (status === 'OK' && results && results[0]) {
                const label = results[0].formatted_address || 'Selected location';
                updateLocation(label, latLng);
            } else {
                reverseLookupWithPlaces(latLng, status);
            }
        });
    }

    function reverseLookupWithPlaces(latLng, status) {
        if (!placesService) {
            setFallbackLocation(latLng, status);
            return;
        }
        placesService.nearbySearch(
            { location: latLng, radius: 80, rankBy: google.maps.places.RankBy.PROMINENCE },
            function (results, placeStatus) {
                if (placeStatus === 'OK' && results && results[0] && results[0].place_id) {
                    placesService.getDetails(
                        { placeId: results[0].place_id, fields: ['formatted_address', 'name'] },
                        function (place, detailsStatus) {
                            if (detailsStatus === 'OK' && place) {
                                const label = place.formatted_address || place.name || 'Selected location';
                                updateLocation(label, latLng);
                            } else {
                                setFallbackLocation(latLng, detailsStatus);
                            }
                        }
                    );
                } else {
                    setFallbackLocation(latLng, placeStatus);
                }
            }
        );
    }

    function setResolvingState() {
        if (locationPreview) locationPreview.textContent = 'Finding address...';
    }

    function setFallbackLocation(latLng, status) {
        const suffix = status ? ' (' + status + ')' : '';
        if (locationPreview) locationPreview.textContent = 'Address not available. Please search manually.' + suffix;
        if (addressInput) addressInput.value = '';
        if (coordsInput) coordsInput.value = latLng.lat().toFixed(6) + ',' + latLng.lng().toFixed(6);
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkoutButton = document.getElementById('checkoutBtn');
    const checkoutForm = document.getElementById('checkoutForm');
    if (!checkoutButton || !checkoutForm) return;

    const locationInput = document.getElementById('live-location');
    const paymentInputs = document.querySelectorAll('input[name="pay_mode"]');
    const cartTable = document.getElementById('cart_table');
    const cartError = document.getElementById('cartError');
    const cardNumberInput = document.querySelector('input[name="card_number"]');
    const cardExpiryInput = document.querySelector('input[name="card_expiry"]');
    const cardCVVInput = document.querySelector('input[name="card_cvv"]');
    const upiRefInput = document.querySelector('input[name="upi_ref"]');
    const upiIdInput = document.querySelector('input[name="upi_id"]');

    checkoutForm.addEventListener('submit', function (event) {
        let isValid = true;
        const rowCount = cartTable ? cartTable.getElementsByTagName('tbody')[0].getElementsByTagName('tr').length : 0;

        document.querySelectorAll('.error-message').forEach((el) => el.remove());
        if (cartError) cartError.style.display = 'none';

        if (rowCount === 0) {
            event.preventDefault();
            if (cartError) cartError.style.display = 'block';
            return;
        }

        if (!locationInput || !locationInput.value.trim()) {
            showError(locationInput, 'Please select a delivery location.');
            isValid = false;
        }

        let selectedPayment = null;
        paymentInputs.forEach((input) => {
            if (input.checked) selectedPayment = input.value;
        });

        if (!selectedPayment) {
            showError(paymentInputs[paymentInputs.length - 1], 'Please select a payment method.');
            isValid = false;
        }

        if (selectedPayment === 'card') {
            const cardNumber = (cardNumberInput.value || '').trim();
            const cardExpiry = (cardExpiryInput.value || '').trim();
            const cardCVV = (cardCVVInput.value || '').trim();

            if (!/^\d{16}$/.test(cardNumber)) {
                showError(cardNumberInput, 'Card number must be exactly 16 digits.');
                isValid = false;
            }
            if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(cardExpiry)) {
                showError(cardExpiryInput, 'Expiry must be in MM/YY format.');
                isValid = false;
            }
            if (!/^\d{3}$/.test(cardCVV)) {
                showError(cardCVVInput, 'CVV must be exactly 3 digits.');
                isValid = false;
            }
        }

        if (selectedPayment === 'upi') {
            const upiId = (upiIdInput ? upiIdInput.value : '').trim();
            const upiRef = (upiRefInput ? upiRefInput.value : '').trim();
            if (!upiId) {
                showError(upiIdInput, 'Please enter your UPI ID.');
                isValid = false;
            } else if (!/^[A-Za-z0-9._-]{2,}@[A-Za-z0-9.-]{2,}$/.test(upiId)) {
                showError(upiIdInput, 'UPI ID should look like name@bank.');
                isValid = false;
            }
            if (!upiRef) {
                showError(upiRefInput, 'Please enter your UPI transaction ID.');
                isValid = false;
            }
        }

        if (!isValid) event.preventDefault();
    });

    function showError(input, message) {
        const error = document.createElement('div');
        error.className = 'error-message text-danger mt-1 small fw-bold';
        error.textContent = message;

        if (input && input.type === 'hidden' && input.id === 'live-location') {
            const wrapper = document.getElementById('location-wrapper');
            if (wrapper) wrapper.appendChild(error);
        } else if (input && input.parentNode) {
            input.parentNode.appendChild(error);
        }
    }
});
</script>

<?php include('site-footer.php'); ?>
</body>
</html>
