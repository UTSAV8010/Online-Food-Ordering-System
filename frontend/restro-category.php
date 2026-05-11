<?php include('config/constants.php');  include('config/blocked-check.php'); ?>

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
        .catalog-page {
            background: radial-gradient(circle at top, #f9fbff 0%, #f3f6fb 50%, #edf1f7 100%);
            padding: 36px 0 64px;
        }

        .catalog-shell {
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 16px;
        }

        .catalog-head {
            text-align: center;
            margin-bottom: 24px;
        }

        .catalog-eyebrow {
            position: relative;
            display: inline-block;
            font-family: "Pacifico", cursive;
            color: #0d6efd;
            font-size: 1.2rem;
            margin-bottom: 10px;
            padding: 0 62px;
            line-height: 1.1;
        }

        .catalog-eyebrow::before,
        .catalog-eyebrow::after {
            content: "";
            position: absolute;
            top: 55%;
            width: 42px;
            height: 2px;
            background: #fea116;
        }

        .catalog-eyebrow::before {
            left: 8px;
        }

        .catalog-eyebrow::after {
            right: 8px;
        }

        .catalog-title {
            margin: 0;
            color: #0f224a;
            font-weight: 800;
            font-size: clamp(1.5rem, 3vw, 2.2rem);
        }

        .catalog-grid {
            row-gap: 22px;
        }

        .catalog-card {
            height: 100%;
            border: 0;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 14px 36px rgba(8, 27, 70, 0.14);
            transition: transform .28s ease, box-shadow .28s ease;
        }

        .catalog-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 44px rgba(8, 27, 70, 0.2);
        }

        .catalog-card .card-img-top {
            height: 220px;
            object-fit: cover;
            transition: transform .35s ease;
        }

        .catalog-card:hover .card-img-top {
            transform: scale(1.05);
        }

        .catalog-card .card-body {
            padding: 18px 16px 20px;
            text-align: center;
        }

        .catalog-card .card-title {
            margin-bottom: 8px;
            color: #0b1f49;
            font-weight: 800;
            font-size: 1.55rem;
        }

        .catalog-card .card-text {
            color: #5f6f86;
            font-size: 1rem;
            min-height: 24px;
        }

        .catalog-btn {
            border: 0;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: .8rem;
            /* font-weight: 800; */
            text-transform: uppercase;
            letter-spacing: .35px;
            transition: transform .2s ease, box-shadow .2s ease;
            color: #fff !important;
            background: #e69500;
        }

        .catalog-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(16, 40, 86, .22);
            color: #fff;
        }

        .catalog-empty {
            text-align: center;
            color: #b03a2e;
            font-weight: 700;
        }

        @media (max-width: 575.98px) {
            .catalog-shell {
                padding: 0 12px;
            }

            .catalog-card .card-img-top {
                height: 190px;
            }

            .catalog-btn {
                width: 100%;
            }
        }
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
        <!-- <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
</div> -->
        <!-- Spinner End -->

        <div class="container-xxl position-relative p-0">
            <?php include('site-hader.php'); ?>
        </div>
        <!-- Menu Start -->
        
        <?php 
// Check whether category_id is passed or not
if(isset($_GET['category_id'])) {
    // Get the category_id from URL
    $category_id = $_GET['category_id'];

    // Get the category title based on category ID from tbl_rcategory_notapproved
    $sql = "SELECT title FROM tbl_rcategory_notapproved WHERE cid=$category_id and status='approved'";
    $res = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $category_title = $row['title']; 
    } else {
        // Redirect to homepage if category not found
        header('location:'.SITEURL);
        exit();
    }
} else {
    // Redirect to homepage if category_id is not passed
    header('location:'.SITEURL);
    exit();
}
?>

<main class="catalog-page">
<div class="catalog-shell">
    <div class="catalog-head">
        <span class="catalog-eyebrow">Category</span>
        <h2 class="catalog-title"><?php echo htmlspecialchars($category_title); ?></h2>
    </div>
    <div class="row catalog-grid">
        <?php
        // Fetch food items from tbl_restro_food_item where category_id matches
        $sql2 = "SELECT * FROM tbl_restro_food_item WHERE cid=$category_id and stock !=0 and status='approved'";
        $res2 = mysqli_query($conn, $sql2);
        $count2 = mysqli_num_rows($res2);

        if($count2 > 0) {
            // Items Available
            while($row2 = mysqli_fetch_assoc($res2)) {
                $id = $row2['id'];
                $title = $row2['title'];
                $price = $row2['price'];
                $description = $row2['description'];
                $restro_name = $row2['restro_name'];
                $image_name = $row2['image_name'];
                ?>

                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="card catalog-card">
                        <img src="<?php echo SITEURL; ?>restro/uploads/food/<?php echo $image_name; ?>" class="card-img-top" alt="Food Image">
                        <div class="card-body text-center">
                            <form action="<?php echo SITEURL; ?>manage-cart" method="POST">
                                <h5 class="card-title"><?php echo $title; ?></h5>
                                <p class="card-text"><?php echo $price; ?></p>
                                <button type="submit" name="Add_To_Cart" class="catalog-btn">Add To Cart</button>
                                <input type="hidden" name="Item_Name" value="<?php echo $title; ?>">
                                <input type="hidden" name="Restro_Name" value="<?php echo $restro_name; ?>">
                                <input type="hidden" name="Price" value="<?php echo $price; ?>">
                                <input type="hidden" name="Id" value="<?php echo $id; ?>">
                            </form>
                        </div>
                    </div>
                </div>

                <?php
            }
        } else {
            // No items found in this category
            echo "<p class='catalog-empty'>No food items available in this category.</p>";
        }
        ?>
    </div>
    </div>
</main>

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


