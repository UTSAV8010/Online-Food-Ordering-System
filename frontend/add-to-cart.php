<?php include('config/constants.php'); include('config/blocked-check.php');?>

<?php
$addSuccess = false;
$statusMessage = 'Item Added to Cart!';
$statusClass = 'text-success';

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

$foodId = isset($_GET['food_id']) ? (int)$_GET['food_id'] : 0;
$item = null;

if ($foodId > 0) {
    $stmt = $conn->prepare('SELECT id, title, price, restro_name FROM tbl_food WHERE id = ? LIMIT 1');
    if ($stmt) {
        $stmt->bind_param('i', $foodId);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $item = array(
                'Item_Name' => $row['title'],
                'Price' => $row['price'],
                'Id' => $row['id'],
                'Restro_Name' => $row['restro_name'],
                'Quantity' => 1
            );
        }
        $stmt->close();
    }

    if ($item === null) {
        $stmt = $conn->prepare('SELECT id, title, price, restro_name FROM tbl_restro_food_item WHERE id = ? LIMIT 1');
        if ($stmt) {
            $stmt->bind_param('i', $foodId);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && $res->num_rows > 0) {
                $row = $res->fetch_assoc();
                $item = array(
                    'Item_Name' => $row['title'],
                    'Price' => $row['price'],
                    'Id' => $row['id'],
                    'Restro_Name' => $row['restro_name'],
                    'Quantity' => 1
                );
            }
            $stmt->close();
        }
    }

    if ($item !== null) {
        $myitems = array_column($_SESSION['cart'], 'Item_Name');
        if (in_array($item['Item_Name'], $myitems, true)) {
            $statusMessage = 'Item Already In Cart';
            $statusClass = 'text-warning';
        } else {
            $_SESSION['cart'][] = $item;
            $addSuccess = true;
        }
    } else {
        $statusMessage = 'Unable to add item. Please try again.';
        $statusClass = 'text-danger';
    }
} else {
    $statusMessage = 'Invalid item selection.';
    $statusClass = 'text-danger';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Pasar-kita.com</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link rel="icon" 
      type="image/png" 
      href="images/logo2.png">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&family=Pacifico&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <style>
        .container-xxl{
        max-width:100%;
    }

.scroll-top-button:hover {
    background: #e69500;
}
.back-to-top{
    right:0px!important;
    bottom:27px !important;
}
    </style>
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
</div>
        <!-- Spinner End -->

        <div class="container-xxl position-relative p-0">
            <?php include('site-hader.php'); ?>
        </div>
 <div class="card" style="max-width: 500px;">
  <div class="row no-gutters justify-content-center">
    <div class="col-md-4">
      <img src="images/Cart-add-icon.png" class="card-img" alt="...">
    </div>
    <div class="col-md-12">
      <div class="card-body">
        <h5 class="card-title text-center <?php echo $statusClass; ?>"><?php echo $statusMessage; ?></h5>
        <a href="<?php echo SITEURL; ?>menu.php" class="btn btn-primary btn-lg btn-block">Continue</a>
        <a href="<?php echo SITEURL; ?>mycart.php" class="btn btn-primary btn-lg btn-block"><i class="fas fa-shopping-cart"></i><span>  View Cart</span></a>
        <a href="<?php echo SITEURL; ?>mycart.php" class="btn btn-primary btn-lg btn-block">Checkout</a>
      </div>
    </div>
  </div>
        <?php include('chatbot.php'); ?>

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries --> 


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

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
<?php include('site-footer.php'); ?>
</body>

</html>


