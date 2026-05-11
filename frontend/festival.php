<?php
// Include database connection
include('config/constants.php');

include('config/blocked-check.php');

// Fetch festival coupon details
$sql = "SELECT festival_name, coupon_code FROM tbl_fest_coupon ORDER BY id DESC LIMIT 1"; // Get the latest coupon
$result = $conn->query($sql);

$festival_name = "Special Festival Offer!";
$coupon_code = "No Coupon Available";

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $festival_name = $row['festival_name'];
    $coupon_code = $row['coupon_code'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Festival Offer</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .container-xxl{
        max-width:100%;
    }
        .festival-offer {
            background: #fffbf2;
            padding: 30px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
        }
        .festival-text {
            flex: 1;
            padding: 20px;
        }
        .festival-heading {
            font-size: 24px;
            font-weight: bold;
            color: #ff5733;
        }
        .coupon-code {
            font-size: 22px;
            font-weight: bold;
            color: #fff;
            background: #ff5733;
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .festival-images {
            flex: 1;
            text-align: center;
        }
        .festival-images img {
            width: 80%;
            border-radius: 10px;
            max-height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
<?php include('site-hader.php'); ?>

<div class="container">
    <div class="festival-offer">
        <!-- Left Side: Coupon Details -->
        <div class="festival-text">
            <div class="festival-heading"><?php echo htmlspecialchars($festival_name); ?></div>
            <div class="coupon-code"><?php echo htmlspecialchars($coupon_code); ?></div>
        </div>

        <!-- Right Side: Food Images -->
        <div class="festival-images">
            <img src="food1.jpg" alt="Delicious Food">
        </div>
    </div>
</div>

<?php include('site-footer.php'); ?>
<?php include('chatbot.php'); ?>
</body>
</html>


