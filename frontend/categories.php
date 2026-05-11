<?php include('config/constants.php'); include('config/blocked-check.php'); ?>

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
    .cat-page {
        background: radial-gradient(circle at top, #f9fbff 0%, #f3f5f9 45%, #eef2f7 100%);
        padding: 42px 0 64px;
    }

    .cat-shell {
        max-width: 1220px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .cat-section {
        margin-bottom: 50px;
    }

    .cat-head {
        text-align: center;
        margin-bottom: 24px;
    }

    .cat-eyebrow {
        position: relative;
        display: inline-block;
        font-family: "Pacifico", cursive;
        color: #0d6efd;
        font-size: 1.2rem;
        margin-bottom: 10px;
        padding: 0 62px;
        line-height: 1.1;
    }

    .cat-eyebrow::before,
    .cat-eyebrow::after {
        content: "";
        position: absolute;
        top: 55%;
        width: 42px;
        height: 2px;
        background: #fea116;
    }

    .cat-eyebrow::before {
        left: 8px;
    }

    .cat-eyebrow::after {
        right: 8px;
    }

    .cat-title {
        margin: 0;
        font-weight: 800;
        color: #0f224a;
        font-size: clamp(1.55rem, 3.2vw, 2.25rem);
        letter-spacing: 0.3px;
    }

    .cat-grid {
        row-gap: 22px;
    }

    .cat-card {
        height: 100%;
        border: 0;
        border-radius: 18px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 14px 36px rgba(10, 29, 74, 0.12);
        transition: transform 0.28s ease, box-shadow 0.28s ease;
    }

    .cat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 18px 44px rgba(10, 29, 74, 0.18);
    }

    .cat-card .card-img-top {
        height: 220px;
        object-fit: cover;
        transition: transform 0.35s ease;
    }

    .cat-card:hover .card-img-top {
        transform: scale(1.06);
    }

    .cat-card .card-body {
        padding: 18px 16px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }

    .cat-card .card-title {
        margin: 0;
        color: #0b1f49;
        font-size: 1.65rem;
        font-weight: 800;
        text-align: center;
    }

    .cat-btn {
        border: 0;
        border-radius: 10px;
        background: #e69500;
        color: #fff;
        /* font-weight: 800; */
        font-size: 0.82rem;
        letter-spacing: 0.4px;
        padding: 10px 16px;
        text-transform: uppercase;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .cat-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(230, 149, 0, 0.35);
    }

    .cat-empty {
        text-align: center;
        color: #b03a2e;
        font-weight: 700;
        padding: 10px 0;
    }

    @media (max-width: 991.98px) {
        .cat-page {
            padding: 30px 0 54px;
        }

        .cat-card .card-img-top {
            height: 205px;
        }
    }

    @media (max-width: 575.98px) {
        .cat-shell {
            padding: 0 12px;
        }

        .cat-section {
            margin-bottom: 36px;
        }

        .cat-head {
            margin-bottom: 18px;
        }

        .cat-card .card-img-top {
            height: 190px;
        }

        .cat-card .card-title {
            font-size: 1.45rem;
        }

        .cat-btn {
            width: 100%;
            max-width: 220px;
        }
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
        <!-- Categories Start -->
        <main class="cat-page">
        <div class="cat-shell">
            <section class="cat-section">
            <div class="cat-head">
                <span class="cat-eyebrow">Browse</span>
                <h2 class="cat-title">Food Categories</h2>
            </div>
            <div class="row cat-grid">
               
                    
                        <?php
                        $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                        $res = mysqli_query($conn, $sql);
                        $count = mysqli_num_rows($res);

                        if($count>0)
                         {
                            while($row=mysqli_fetch_assoc($res))
                             {
                                //Get the Values
                                $id = $row['id'];
                                $title = $row['title'];
                                $image_name = $row['image_name'];

                        ?>
                        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                            <div class="card cat-card">
                                <img src="<?php echo SITEURL; ?>images/category/<?php echo $image_name; ?>" class="card-img-top" alt="...">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo $title; ?></h5>
                                    <a href="<?php echo SITEURL; ?>category-foods.php?category_id=<?php echo $id; ?>">
                                    <button class="cat-btn">Explore Category</button>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <?php
                    
                             }

                           }
                            
                        else
                        {
                        //Categories are not available
                        echo "<p class='cat-empty'>Categories not found</p>";
                        }

                        
                        
                        ?>

                
              
            </div>
            </section>
<!-- restro category -->
<section class="cat-section">
<div class="cat-head">
    <span class="cat-eyebrow">Popular</span>
    <h3 class="cat-title">Restro Food Category</h3>
</div>
    <div class="row cat-grid">
        <?php
        $sql = "SELECT * FROM tbl_rcategory_notapproved WHERE active='Yes' and status='approved'";
        $res = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($res);

        if ($count > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                // Get the Values
                $id = $row['cid'];
                $title = $row['title'];
                $image_name = $row['image_name'];
                ?>
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="card cat-card">
                        <img src="<?php echo SITEURL; ?>restro/uploads/category/<?php echo $image_name; ?>" class="card-img-top" alt="Category Image">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo $title; ?></h5>
                            <a href="<?php echo SITEURL; ?>restro-category.php?category_id=<?php echo $id; ?>">
    <button class="cat-btn">Explore Category</button>
</a>

                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            // Categories are not available
            echo "<p class='cat-empty'>Categories not found.</p>";
        }
        ?>
    </div>
</section>
</div>
</main>

 <!-- restro category end -->
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


