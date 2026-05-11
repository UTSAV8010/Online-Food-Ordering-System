<?php include('config/constants.php'); include('config/blocked-check.php');  ?>


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
        .menu-page {
            background: radial-gradient(circle at top, #f9fbff 0%, #f3f6fb 50%, #edf1f7 100%);
            padding: 42px 0 70px;
        }

        .menu-shell {
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 16px;
        }

        .menu-section {
            margin-bottom: 56px;
        }

        .menu-head {
            text-align: center;
            margin-bottom: 24px;
        }

        .menu-eyebrow {
            position: relative;
            display: inline-block;
            font-family: "Pacifico", cursive;
            color: #0d6efd;
            font-size: 1.2rem;
            margin-bottom: 10px;
            padding: 0 62px;
            line-height: 1.1;
        }

        .menu-eyebrow::before,
        .menu-eyebrow::after {
            content: "";
            position: absolute;
            top: 55%;
            width: 42px;
            height: 2px;
            background: #fea116;
        }

        .menu-eyebrow::before {
            left: 8px;
        }

        .menu-eyebrow::after {
            right: 8px;
        }

        .menu-title {
            margin: 0;
            font-weight: 800;
            color: #0f224a;
            font-size: clamp(1.55rem, 3.1vw, 2.2rem);
            letter-spacing: 0.3px;
        }

        .menu-grid {
            row-gap: 22px;
        }

        .menu-card {
            height: 100%;
            border: 0;
            border-radius: 18px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 14px 36px rgba(8, 27, 70, 0.14);
            transition: transform .28s ease, box-shadow .28s ease;
        }

        .menu-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 44px rgba(8, 27, 70, 0.2);
        }

        .menu-card .card-img-top {
            height: 220px;
            object-fit: cover;
            transition: transform .35s ease;
        }

        .menu-card:hover .card-img-top {
            transform: scale(1.06);
        }

        .menu-card .card-body {
            padding: 18px 16px 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            justify-content: center;
            align-items: center;
        }

        .menu-card .card-title {
            margin: 0;
            text-align: center;
            color: #0b1f49;
            font-weight: 800;
            font-size: 1.62rem;
            line-height: 1.25;
        }

        .menu-card .card-text {
            margin: 0;
            color: #5f6f86;
            font-weight: 700;
            font-size: 1.02rem;
        }

        .menu-btn {
            border: 0;
            border-radius: 10px;
            background: #e69500;;
            color: #fff;
            /* font-weight: 800; */
            font-size: 0.82rem;
            letter-spacing: 0.35px;
            padding: 10px 18px;
            text-transform: uppercase;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .menu-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(230, 149, 0, 0.35);
        }

        .menu-empty {
            text-align: center;
            color: #b03a2e;
            font-weight: 700;
            padding: 10px 0;
        }

        @media (max-width: 991.98px) {
            .menu-page {
                padding: 30px 0 56px;
            }

            .menu-card .card-img-top {
                height: 205px;
            }
        }

        @media (max-width: 575.98px) {
            .menu-shell {
                padding: 0 12px;
            }

            .menu-section {
                margin-bottom: 40px;
            }

            .menu-head {
                margin-bottom: 18px;
            }

            .menu-card .card-img-top {
                height: 190px;
            }

            .menu-card .card-title {
                font-size: 1.45rem;
            }

            .menu-btn {
                width: 100%;
                max-width: 220px;
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
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
</div>
        <!-- Spinner End -->

        <div class="container-xxl position-relative p-0">
            <?php include('site-hader.php'); ?>
        </div>
        <!-- Menu Start -->
        <main class="menu-page">
        <div class="menu-shell">
            <section class="menu-section">
            <div class="menu-head">
                <span class="menu-eyebrow">Fresh Picks</span>
                <h2 class="menu-title">Popular Menu Items</h2>
            </div>
            <div class="row menu-grid">
               
                    
                        <?php
                        $sql = "SELECT * FROM tbl_food WHERE active='Yes' and stock !=0";
                        $res = mysqli_query($conn, $sql);
                        $count = mysqli_num_rows($res);

                        if($count>0)
                         {
                            while($row=mysqli_fetch_assoc($res))
                             {
                                //Get the Values
                                $id = $row['id'];
                                $title = $row['title'];
                                $price = $row['price'];
                                
                        $restro_name = $row['restro_name'];
                                $image_name = $row['image_name'];
                                

                                ?>
                        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                            <div class="card menu-card">
                                <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" class="card-img-top" alt="...">
                                <div class="card-body text-center">
                                    <form action="<?php echo SITEURL; ?>manage-cart" method="POST">
                                    <h5 class="card-title"><?php echo $title; ?></h5>
                                    <p class="card-text"><?php echo $price; ?></p>
                                    <button type="submit" name="Add_To_Cart" class="menu-btn">Add To Cart</button>
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

                           }
                            
                        else
                        {
                        //Categories are not available
                        echo "<p class='menu-empty'>Items not found</p>";
                        }

                        
                        
                        ?>

                
              
            </div>
            </section>
<!-- restro menu start -->
<section class="menu-section wow fadeInUp" data-wow-delay="0.1s">
        <div class="menu-head">
            <span class="menu-eyebrow">Chef Selection</span>
            <h3 class="menu-title">Restro Items</h3>
        </div>
            <div class="row menu-grid">
                <?php
                $sql = "SELECT * FROM tbl_restro_food_item WHERE featured='Yes' AND active='Yes' AND status='approved' and stock !=0";
                $res = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($res);

                if($count > 0) {
                    while($row = mysqli_fetch_assoc($res)) {
                        $id = $row['id'];
                        $restro_name = $row['restro_name'];
                        $title = $row['title'];
                        $image_name = $row['image_name'];
                        $price = $row['price'];
                ?>
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="card menu-card">
                        <img src="<?php echo SITEURL; ?>restro/uploads/food/<?php echo $image_name; ?>"
                            class="card-img-top" alt="...">
                        <div class="card-body text-center">
                            <form action="<?php echo SITEURL; ?>manage-cart" method="POST">
                                <h5 class="card-title"> <?php echo $restro_name; ?> </h5>
                                <h5 class="card-title"> <?php echo $title; ?> </h5>
                                <p class="card-text"> <?php echo $price; ?> </p>
                                <button type="submit" name="Add_To_Cart" class="menu-btn">Add To Cart</button>
                                <input type="hidden" name="Restro_Name" value="<?php echo $restro_name; ?>">
                                <input type="hidden" name="Item_Name" value="<?php echo $title; ?>">
                                <input type="hidden" name="Price" value="<?php echo $price; ?>">
                                <input type="hidden" name="Id" value="<?php echo $id; ?>">
                            </form>
                        </div>
                    </div>
                </div>
                <?php
                    }
                } else {
                    echo "<p class='menu-empty'>Restro items not found</p>";
                }
                ?>
            </div>
        </section>
</div>
</main>


 <!-- restro product end -->
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


