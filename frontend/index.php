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
    <link rel="icon" type="image/png" href="images/logo2.png">

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
        :root {
            --site-primary: #e69500;
            --site-text: #1f2937;
            --site-muted: #6b7280;
            --site-surface: #ffffff;
            --site-border: #ececec;
            --site-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        }

        body {
            background: #f7f8fb;
            color: var(--site-text);
        }

        .content-section {
            padding-top: 5rem;
            padding-bottom: 5rem;
        }

        .site-navbar {
            padding-top: 0.75rem !important;
            padding-bottom: 0.75rem !important;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .site-logo {
            width: 92px;
            height: auto;
            max-height: 94px;
            object-fit: contain;
        }

        .navbar-nav .nav-link {
            padding-top: 0.75rem !important;
            padding-bottom: 0.75rem !important;
            padding-left: 0.85rem !important;
            padding-right: 0.85rem !important;
        }

        .cart-btn {
            background: var(--site-primary);
            border: none;
            border-radius: 999px;
            min-width: 130px;
        }

        .hero-header {
            background: linear-gradient(rgba(15, 23, 43, .88), rgba(15, 23, 43, .88)), url(images/bg_homepage.jpg);
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
            margin-bottom: 0 !important;
        }

        .hero-content {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        .hero-header img {
            animation: imgRotate 50s linear infinite;
            max-width: 88%;
        }

        .section-heading {
            margin-bottom: 2.5rem;
        }

        .section-heading h1 {
            margin-bottom: 0;
        }

        .category-card,
        .room-item,
        .testimonial-item {
            background: var(--site-surface);
            border: 1px solid var(--site-border);
            border-radius: 16px !important;
            box-shadow: var(--site-shadow);
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .category-card:hover,
        .room-item:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.12);
        }

        .category-card {
            padding: 2rem 1.25rem !important;
        }

        .category-img {
            width: 230px;
            height: 230px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto;
            display: block;
            border: 6px solid #fff;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .room-item .p-4 {
            padding: 1.5rem !important;
        }

        .room-item h5 {
            font-size: 1.15rem;
            margin-right: 0.5rem;
        }

        .room-item p {
            color: var(--site-muted);
            line-height: 1.6;
        }

        .section-btn {
            background: var(--site-primary);
            border: none;
            border-radius: 999px;
        }

        .mobile-menu-title {
            display: none;
        }

        .mobile-menu-backdrop {
            display: none;
        }

        .app-cta-box {
            background: linear-gradient(135deg, #fef7e7, #fff2d1);
            border: 1px solid #f4d8a3;
            border-radius: 14px;
        }

        .app-cta-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
            align-items: stretch;
            justify-content: center;
        }

        .app-cta-actions .btn {
            min-width: 180px;
            text-align: center;
            margin: 0 !important;
        }

        
        .hero-header {
            background: linear-gradient(rgba(15, 23, 43, .88), rgba(15, 23, 43, .88)), url(images/bg_homepage.jpg);
        }
        
        .hero-header img {
            animation: imgRotate 50s linear infinite;
        }

        @keyframes imgRotate {
            100% {
                transform: rotate(360deg);
            }
        }
        
        .back-to-top {
            right: 20px !important;
            bottom: 20px !important;
            z-index: 999;
        }

        @media (max-width: 991.98px) {
            .content-section {
                padding-top: 4rem;
                padding-bottom: 4rem;
            }

            .site-logo {
                width: 72px;
                max-height: 72px;
            }

            .hero-content {
                padding-top: 1rem;
                padding-bottom: 1rem;
            }

            .category-img {
                width: 185px;
                height: 185px;
            }

            .app-cta-actions .btn {
                min-width: 160px;
            }

            body.mobile-nav-open {
                overflow: hidden;
            }

            .site-navbar .navbar-toggler {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                border: 1px solid rgba(255, 255, 255, 0.2);
                background: rgba(255, 255, 255, 0.06);
                position: relative;
                z-index: 1302;
            }

            .site-navbar .navbar-toggler .fa {
                font-size: 1.25rem;
                color: #fff;
            }

            .site-navbar .navbar-brand {
                position: relative;
                z-index: 1302;
            }

            .mobile-menu-backdrop {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.55);
                opacity: 0;
                visibility: hidden;
                transition: opacity .25s ease, visibility .25s ease;
                z-index: 1188;
            }

            .mobile-menu-backdrop.show {
                opacity: 1;
                visibility: visible;
            }

            .site-navbar .navbar-collapse {
                display: block !important;
                position: fixed;
                top: 0;
                left: 0;
                width: min(84vw, 320px);
                height: 100vh;
                padding: 0.9rem;
                background: linear-gradient(180deg, #070f2f 0%, #05081f 100%);
                border-right: 1px solid rgba(255, 255, 255, 0.08);
                box-shadow: 18px 0 34px rgba(0, 0, 0, 0.45);
                transform: translateX(-105%);
                opacity: 0;
                visibility: hidden;
                transition: transform .28s ease, opacity .28s ease, visibility .28s;
                overflow-y: auto;
                z-index: 1301;
            }

            .site-navbar .navbar-collapse.show {
                transform: translateX(0);
                opacity: 1;
                visibility: visible;
            }

            .mobile-menu-title {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 0.75rem;
                padding: 0.15rem 0.15rem 0.75rem;
                border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            }

            .mobile-menu-logo {
                font-weight: 800;
                letter-spacing: 2px;
                font-size: 1.7rem;
                color: #3aa9ff;
            }

            .mobile-close-btn {
                width: 42px;
                height: 42px;
                border: 1px solid rgba(255, 255, 255, 0.15);
                border-radius: 10px;
                background: rgba(255, 255, 255, 0.08);
                color: #fff;
            }

            .site-navbar .navbar-nav {
                gap: 0.65rem;
                padding-right: 0 !important;
                margin-bottom: 0.7rem;
            }

            .site-navbar .navbar-nav .nav-link {
                margin-left: 0;
                padding: 0.85rem 1rem !important;
                border-radius: 14px;
                background: rgba(46, 164, 255, 0.15);
                color: #5cc0ff !important;
                font-weight: 700;
            }

            .site-navbar .navbar-nav .nav-link.active {
                background: rgba(46, 164, 255, 0.24);
            }

            .site-navbar .nav-item.dropdown > .nav-link {
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: rgba(255, 255, 255, 0.05);
                color: #ffffff !important;
            }

            .site-navbar .navbar-nav .dropdown-menu {
                position: static !important;
                transform: none !important;
                margin-top: 0.45rem;
                border-radius: 12px;
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(255, 255, 255, 0.08);
                padding: 0.35rem;
            }

            .site-navbar .navbar-nav .dropdown-item {
                color: #cde7ff;
                border-radius: 10px;
                padding: 0.55rem 0.75rem;
            }

            .site-navbar .navbar-nav .dropdown-item:hover {
                background: rgba(46, 164, 255, 0.2);
                color: #fff;
            }

            .site-navbar .navbar-collapse > .nav-item.nav-link,
            .site-navbar .navbar-collapse > .btn {
                display: block;
                width: 100%;
                margin: 0.65rem 0 0 !important;
            }

            .site-navbar .navbar-collapse > .btn {
                text-align: center;
                border-radius: 14px;
                padding: 0.8rem 1rem !important;
            }
        }

        @media (min-width: 992px) {
            .app-cta-actions {
                align-items: flex-end;
            }
        }

        .restro-section {
            background: linear-gradient(180deg, #f9fbff 0%, #ffffff 100%);
        }

        .restro-title {
            color: #0f224a;
            font-weight: 800;
        }

        .restro-address {
            color: var(--site-muted);
            min-height: 44px;
        }

        .restro-card {
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid var(--site-border);
            box-shadow: var(--site-shadow);
            background: #fff;
            transition: transform .28s ease, box-shadow .28s ease;
        }

        .restro-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.12);
        }

        .restro-card img {
            height: 210px;
            width: 100%;
            object-fit: cover;
            transition: transform .35s ease;
        }

        .restro-card:hover img {
            transform: scale(1.05);
        }

        .restro-image-placeholder {
            height: 210px;
            background: #f3f4f6;
            color: #9ca3af;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .restro-btn-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .restro-btn {
            border: 0;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 0.78rem;
            /* font-weight: 800; */
            text-transform: uppercase;
            letter-spacing: 0.35px;
            color: #fff !important;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .restro-btn.menu {
            background: #e69500;
        }

        .restro-btn.review {
            background: #178d51;
        }

        .restro-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(16, 40, 86, 0.22);
        }

        @media (max-width: 575.98px) {
            .restro-btn-group {
                grid-template-columns: 1fr;
            }
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

            <div class="container-xxl py-5 bg-dark hero-header">
                <div class="container my-5 py-5 hero-content">
                    <div class="row align-items-center g-5">
                        <div class="col-lg-6 text-center text-lg-start">
                            <h1 class="display-3 text-white animated slideInLeft">Enjoy Our<br>Delicious Meal</h1>
                            <p class="text-white animated slideInLeft mb-4 pb-2">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et eos. Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo justo magna dolore erat amet</p>
                            <a href="menu.php" class="btn btn-primary py-sm-3 px-sm-5 me-3 animated slideInLeft" style="background:#e69500; border:none;">Order Now</a>
                        </div>
                        <div class="col-lg-6 text-center text-lg-end overflow-hidden">
                            <img class="img-fluid" src="images/hero.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navbar & Hero End -->


        <!-- Categories Start -->
        <div class="container-xxl content-section">
    <div class="container">

        <div class="text-center wow fadeInUp section-heading" data-wow-delay="0.1s">
            <h5 class="section-title ff-secondary text-center text-primary fw-normal">Food Categories</h5>
            <h1>Our Popular Items</h1>
        </div>

        <div class="row g-4 ">
            <?php 
                // Show active categories on home page; keep featured ones on top
                $sql = "SELECT * FROM tbl_category
                        WHERE active='Yes'
                        ORDER BY CASE WHEN featured='Yes' THEN 0 ELSE 1 END, id DESC
                        LIMIT 6";
                $res = mysqli_query($conn, $sql);

                if(mysqli_num_rows($res) > 0)
                {
                    while($row = mysqli_fetch_assoc($res))
                    {
                        $id = $row['id'];
                        $title = $row['title'];
                        $image_name = $row['image_name'];
            ?>

            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="category-card shadow rounded text-center p-4 h-100">

                    <?php
                        $category_image_file = __DIR__ . '/../images/category/' . $image_name;
                        $has_category_image = ($image_name !== '' && is_file($category_image_file));
                    ?>
                    <?php if(!$has_category_image) { ?>
                        <div class="text-danger">Image Not Available</div>
                    <?php } else { ?>
                        <img src="<?php echo SITEURL; ?>images/category/<?php echo $image_name; ?>"
                             class="category-img mb-3"
                             alt="<?php echo $title; ?>">
                    <?php } ?>

                    <h5 class="mb-3"><?php echo $title; ?></h5>

                    <a href="<?php echo SITEURL; ?>category-foods.php?category_id=<?php echo $id; ?>"
                       class="btn btn-sm btn-dark rounded py-2 px-4">View Menu</a>

                </div>
            </div>

            <?php 
                    }
                }
                else
                {
                    echo "<div class='text-center text-danger'>Category Not Added.</div>";
                }
            ?>
        </div>

        <div class="text-center mt-5">
            <a href="categories.php" class="btn btn-primary py-3 px-5 section-btn">View All Categories</a>
        </div>

    </div>
</div>
<!-- Categories End -->

        

        <!-- Menu Start -->
         <div class="container-xxl content-section">
            <div class="container">
                <div class="text-center wow fadeInUp section-heading" data-wow-delay="0.1s">
                    <h5 class="section-title ff-secondary text-center text-primary fw-normal">Food Menu</h5>
                    <h1>Most Popular Items</h1>
                </div>
                
                <div class="row g-4">
                    <?php 
                    //Featured Foods
                    $sql2 = "SELECT * FROM tbl_food WHERE active='Yes' AND featured='Yes' AND stock > 0 LIMIT 6";
                    $res2 = mysqli_query($conn, $sql2);
                    $count2 = mysqli_num_rows($res2);

                    if($count2>0)
                    {
                        while($row=mysqli_fetch_assoc($res2))
                        {
                            $id = $row['id'];
                            $title = $row['title'];
                            $price = $row['price'];
                            $description = $row['description'];
                            $image_name = $row['image_name'];
                            $restro_name = $row['restro_name'];
                            ?>

                             <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                                <div class="room-item shadow rounded overflow-hidden">
                                    <div class="position-relative">
                                        <?php
                                            $food_image_file = __DIR__ . '/../images/food/' . $image_name;
                                            $has_food_image = ($image_name !== '' && is_file($food_image_file));
                                            if(!$has_food_image)
                                            {
                                                echo "<div class='error'>Image not available.</div>";
                                            }
                                            else
                                            {
                                                ?>
                                                <img class="img-fluid w-100" src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" alt="<?php echo $title; ?>" style="height: 250px; object-fit: cover;">
                                                <?php
                                            }
                                        ?>
                                        <small class="position-absolute start-0 top-100 translate-middle-y bg-primary text-white rounded py-1 px-3 ms-4" style="background:#e69500 !important; border:none;">₹ <?php echo $price; ?></small>
                                    </div>
                                    <div class="p-4 mt-2">
                                        <div class="d-flex justify-content-between mb-3">
                                            <h5 class="mb-0"><?php echo $title; ?></h5>
                                            <div class="ps-2">
                                                <small class="fa fa-star text-primary" style="color:#fea116 !important; "></small>
                                                <small class="fa fa-star text-primary" style="color:#fea116 !important; "></small>
                                                <small class="fa fa-star text-primary" style="color:#fea116 !important; "></small>
                                                <small class="fa fa-star text-primary" style="color:#fea116 !important; "></small>
                                                <small class="fa fa-star text-primary" style="color:#fea116 !important; "></small>
                                            </div>
                                        </div>
                                        <p class="text-body mb-3"><?php echo substr($description, 0, 100); ?>...</p>
                                         <form action="<?php echo SITEURL; ?>manage-cart" method="POST">
                                            <input type="hidden" name="Item_Name" value="<?php echo $title; ?>">
                                            <input type="hidden" name="Restro_Name" value="<?php echo $restro_name; ?>">
                                            <input type="hidden" name="Price" value="<?php echo $price; ?>">
                                            <input type="hidden" name="Id" value="<?php echo $id; ?>">
                                            <div class="d-flex justify-content-between">
                                                 <button type="submit" name="Add_To_Cart" class="btn btn-sm btn-dark rounded py-2 px-4">Add To Cart</button>
                                            </div>
                                         </form>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    else
                    {
                        echo "<div class='error'>Food not available.</div>";
                    }
                    ?>
                </div>

                <div class="text-center mt-5 wow fadeInUp" data-wow-delay="0.4s">
                     <a href="menu.php" class="btn btn-primary py-3 px-5 section-btn">Explore Full Menu</a>
                </div>
            </div>
        </div>
        <!-- Menu End -->
        <!-- Restro Start -->
        <div class="container-xxl content-section restro-section">
            <div class="container">
                <div class="text-center wow fadeInUp section-heading" data-wow-delay="0.1s">
                    <h5 class="section-title ff-secondary text-center text-primary fw-normal">Top Picks</h5>
                    <h1>Restaurants Near You</h1>
                </div>

                <div class="row g-4">
                    <?php
                        $sql_restro = "SELECT * FROM tbl_restro WHERE status='approved' ORDER BY id desc LIMIT 4";
                        $res_restro = mysqli_query($conn, $sql_restro);

                        if ($res_restro && mysqli_num_rows($res_restro) > 0) {
                            while ($row = mysqli_fetch_assoc($res_restro)) {
                                $restro_name = $row['restro_name'];
                                $restro_image = $row['restro_image'];
                                $address = $row['restro_address'];
                                $restro_image_path = __DIR__ . '/../restro/' . $restro_image;
                                $has_restro_image = ($restro_image !== '' && is_file($restro_image_path));
                    ?>
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="restro-card h-100">
                            <?php if(!$has_restro_image) { ?>
                                <div class="restro-image-placeholder d-flex align-items-center justify-content-center">
                                    Image Not Available
                                </div>
                            <?php } else { ?>
                                <img src="<?php echo SITEURL; ?>restro/<?php echo $restro_image; ?>"
                                     alt="<?php echo $restro_name; ?>">
                            <?php } ?>
                            <div class="p-4 text-center">
                                <h5 class="restro-title mb-2"><?php echo $restro_name; ?></h5>
                                <p class="restro-address mb-3"><?php echo $address; ?></p>
                                <div class="restro-btn-group">
                                    <a href="<?php echo SITEURL; ?>restro-menu.php?restro_name=<?php echo urlencode($restro_name); ?>"
                                       class="restro-btn menu">View Menu</a>
                                    <a href="<?php echo SITEURL; ?>review-restro.php?restro_name=<?php echo urlencode($restro_name); ?>"
                                       class="restro-btn review">Give Review</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                            }
                        } else {
                            echo "<div class='text-center text-danger'>No approved restaurants found.</div>";
                        }
                    ?>
                </div>

                <div class="text-center mt-5">
                    <a href="restaurant.php" class="btn btn-primary py-3 px-5 section-btn">View All Restaurants</a>
                </div>
            </div>
        </div>
        <!-- Restro End -->

        <!-- How It Works Start -->
        <div class="container-xxl content-section">
            <div class="container">
                <div class="text-center section-heading wow fadeInUp" data-wow-delay="0.1s">
                    <h5 class="section-title ff-secondary text-center text-primary fw-normal">How It Works</h5>
                    <h1>Order In 3 Simple Steps</h1>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="service-item text-center rounded p-4 h-100">
                            <i class="fa fa-3x fa-search text-primary mb-4"></i>
                            <h5 class="mb-3">Choose Your Meal</h5>
                            <p class="m-0">Browse categories and pick your favorite dishes from top restaurants.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="service-item text-center rounded p-4 h-100">
                            <i class="fa fa-3x fa-shopping-basket text-primary mb-4"></i>
                            <h5 class="mb-3">Add To Cart</h5>
                            <p class="m-0">Review your cart, update quantity, and place the order in seconds.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="service-item text-center rounded p-4 h-100">
                            <i class="fa fa-3x fa-motorcycle text-primary mb-4"></i>
                            <h5 class="mb-3">Fast Delivery</h5>
                            <p class="m-0">Get live order updates and enjoy quick doorstep delivery.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- How It Works End -->

       
        

        <!-- Team/Chefs Section -->
        <?php include('section.php'); ?>
 <!-- Why Choose Us Start -->
        <div class="container-xxl content-section pt-0">
            <div class="container">
                <div class="text-center section-heading wow fadeInUp" data-wow-delay="0.1s">
                    <h5 class="section-title ff-secondary text-center text-primary fw-normal">Why Choose Us</h5>
                    <h1>Better Food Experience</h1>
                </div>
                <div class="row g-4">
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="testimonial-item border rounded p-4 h-100 text-center">
                            <i class="fa fa-check-circle fa-2x text-primary mb-3"></i>
                            <h5 class="mb-2">Quality Assured</h5>
                            <p class="mb-0">Fresh ingredients and trusted kitchen partners.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="testimonial-item border rounded p-4 h-100 text-center">
                            <i class="fa fa-tags fa-2x text-primary mb-3"></i>
                            <h5 class="mb-2">Best Offers</h5>
                            <p class="mb-0">Save more with regular coupons and combo deals.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="testimonial-item border rounded p-4 h-100 text-center">
                            <i class="fa fa-headset fa-2x text-primary mb-3"></i>
                            <h5 class="mb-2">24/7 Support</h5>
                            <p class="mb-0">Quick help for orders, refunds, and delivery issues.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.4s">
                        <div class="testimonial-item border rounded p-4 h-100 text-center">
                            <i class="fa fa-shield-alt fa-2x text-primary mb-3"></i>
                            <h5 class="mb-2">Secure Payments</h5>
                            <p class="mb-0">Safe checkout with trusted online payment methods.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Why Choose Us End -->

        <!-- Testimonial Start -->
        <div class="container-xxl content-section wow fadeInUp" data-wow-delay="0.1s">
            <div class="container">
                <div class="text-center section-heading">
                    <h5 class="section-title ff-secondary text-center text-primary fw-normal">Testimonial</h5>
                    <h1>Our Clients Say!!!</h1>
                </div>
                <div class="owl-carousel testimonial-carousel">
                    <?php 
                        $sql3 = "SELECT * FROM tbl_review WHERE review_star >= 4 LIMIT 5";
                        $res3 = mysqli_query($conn, $sql3);
                        $count3 = mysqli_num_rows($res3);

                        if($count3 > 0)
                        {
                            while($row3 = mysqli_fetch_assoc($res3))
                            {
                                $customer_name = $row3['name']; // using 'name' column as per schema check
                                $message = $row3['message'];
                                $star = $row3['review_star'];
                                ?>
                                <div class="testimonial-item bg-transparent border rounded p-4">
                                    <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                                    <p><?php echo $message; ?></p>
                                    <div class="d-flex align-items-center">
                                        <!-- Placeholder for user image as tbl_review doesn't have image -->
                                        <img class="img-fluid flex-shrink-0 rounded-circle" src="images/testimonial-1.jpg" style="width: 50px; height: 50px;">
                                        <div class="ps-3">
                                            <h5 class="mb-1"><?php echo $customer_name; ?></h5>
                                            <small><?php echo str_repeat('&#9733;', $star); ?></small>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        else
                        {
                            // Static fallback if no reviews
                            ?>
                             <div class="testimonial-item bg-transparent border rounded p-4">
                                <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                                <p>Dolor et eos labore, stet justo sed est sed. Diam sed sed dolor stet amet eirmod eos labore diam</p>
                                <div class="d-flex align-items-center">
                                    <img class="img-fluid flex-shrink-0 rounded-circle" src="images/testimonial-1.jpg" style="width: 50px; height: 50px;">
                                    <div class="ps-3">
                                        <h5 class="mb-1">Client Name</h5>
                                        <small>Profession</small>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </div>
        <!-- Testimonial End -->
        <!-- App CTA Start -->
        <div class="container-xxl content-section pt-0 wow fadeInUp" data-wow-delay="0.1s">
            <div class="container">
                <div class="app-cta-box p-4 p-lg-5">
                    <div class="row align-items-center g-4">
                        <div class="col-lg-8">
                            <h2 class="mb-2">Get Faster Ordering On Mobile</h2>
                            <p class="mb-0">Track deliveries, reorder your favorites, and get app-only discounts.</p>
                        </div>
                        <div class="col-lg-4">
                            <div class="app-cta-actions">
                                <a href="menu.php" class="btn btn-primary py-3 px-5">Order Now</a>
                                <a href="contact.php" class="btn btn-dark py-3 px-5">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- App CTA End -->


        <?php include('chatbot.php'); ?>

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
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
    
    <!-- Chatbot Script -->
    
<?php include('site-footer.php'); ?>
</body>

</html>


