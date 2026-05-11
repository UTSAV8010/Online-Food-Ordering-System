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
        .container-xxl{
        max-width:100%;
    }
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
        min-height: 46px;
    }

    .catalog-btn-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 14px;
    }

    .catalog-btn {
        border: 0;
        border-radius: 10px;
        padding: 10px 12px;
        font-size: .78rem;
        /* font-weight: 800; */
        text-transform: uppercase;
        letter-spacing: .35px;
        transition: transform .2s ease, box-shadow .2s ease;
        color: #fff !important;
        text-decoration: none;
        display: inline-flex;
        justify-content: center;
        align-items: center;
    }

    .catalog-btn.menu {
        background: #e69500;
    }

    .catalog-btn.review {
        background: #178d51;
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

    .restro-help-section {
        margin: 0 0 30px;
        padding: 6px 0 2px;
    }

    .restro-help-head {
        text-align: center;
        margin-bottom: 26px;
    }

    .restro-help-subtitle {
        display: inline-flex;
        align-items: center;
        gap: 16px;
        font-family: "Pacifico", cursive;
        color: #0d6efd;
        font-size: 1.14rem;
        line-height: 1.1;
        margin-bottom: 8px;
    }

    .restro-help-subtitle::before,
    .restro-help-subtitle::after {
        content: "";
        width: 60px;
        height: 2px;
        border-radius: 999px;
        background: #fea116;
    }

    .restro-help-head h3 {
        margin: 0;
        color: #0f224a;
        font-size: clamp(1.75rem, 4vw, 2.7rem);
        font-weight: 800;
    }

    .restro-help-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .restro-help-card {
        height: 100%;
        text-align: center;
        border-radius: 22px;
        border: 1px solid #e6ebf2;
        background: #f6f8fb;
        box-shadow: 0 12px 26px rgba(15, 23, 43, 0.08);
        padding: 22px 22px 24px;
    }

    .restro-help-card-top {
        display: grid;
        grid-template-columns: auto 1fr;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .restro-help-icon {
        width:54px;
        height: 54px;
        border-radius: 20px;
        background: rgba(254, 161, 22, 0.18);
        color: #f39a12;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.65rem;
    }

    .restro-help-card h5 {
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-family: "Pacifico", cursive;
        color: #0d6efd;
        font-size: 1.1rem;
        font-weight: 500;
        line-height: 1.1;
    }

    .restro-help-card h5::before,
    .restro-help-card h5::after {
        content: "";
        flex: 1 1 24px;
        max-width: 58px;
        height: 2px;
        border-radius: 999px;
        background: #fea116;
    }

    .restro-help-card p {
        margin: 0;
        color: #5f6f86;
        font-size: 1.03rem;
        line-height: 1.55;
    }

    .restro-faq-section {
        margin: 48px 0 10px;
    }

    .restro-faq-head {
        text-align: center;
        margin-bottom: 24px;
    }

    .restro-faq-kicker {
        display: inline-flex;
        align-items: center;
        gap: 16px;
        font-family: "Pacifico", cursive;
        color: #0d6efd;
        font-size: 1.12rem;
        line-height: 1.1;
        margin-bottom: 10px;
    }

    .restro-faq-kicker::before,
    .restro-faq-kicker::after {
        content: "";
        width: 60px;
        height: 2px;
        border-radius: 999px;
        background: #fea116;
    }

    .restro-faq-head h2 {
        margin: 0;
        color: #0f224a;
        /* font-size: clamp(1.75rem, 4vw, 2.75rem); */
        font-weight: 800;
    }

    .restro-faq-head p {
        margin: 10px 0 0;
        color: #6a778c;
        /* font-size: clamp(1rem, 2vw, 1.55rem); */
    }

    .restro-faq-shell {
        max-width: 980px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .restro-faq-accordion .accordion-item {
        border: 0;
        border-radius: 22px !important;
        overflow: hidden;
        margin-bottom: 16px;
        box-shadow: 0 1px 0 rgba(0, 0, 0, 0.08);
        background: #ffffff;
    }

    .restro-faq-accordion .accordion-button {
        background: #ffffff;
        color: #17253f;
        font-weight: 700;
        font-size: clamp(1rem, 2vw, 1.08rem);
        padding: 22px 22px;
        border-radius: 22px !important;
        box-shadow: none !important;
    }

    .restro-faq-accordion .accordion-button:not(.collapsed) {
        color: #0f224a;
    }

    .restro-faq-accordion .accordion-button::after {
        background-image: none;
        content: "+";
        width: 30px;
        height: 30px;
        border: 2px solid #8b8f97;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #666b75;
        font-size: 1.35rem;
        line-height: 1;
        transform: none;
        flex-shrink: 0;
    }

    .restro-faq-accordion .accordion-button:not(.collapsed)::after {
        content: "-";
    }

    .restro-faq-accordion .accordion-body {
        padding: 0 22px 20px;
        color: #5b6980;
        line-height: 1.75;
        font-size: 0.98rem;
    }

    .restro-faq-accordion .accordion-button:focus {
        box-shadow: none;
        border-color: transparent;
    }

    @media (max-width: 991.98px) {
        .restro-help-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .restro-help-card h5 {
            font-size: 1.1rem;
        }
    }

    @media (max-width: 575.98px) {
        .catalog-shell {
            padding: 0 12px;
        }

        .catalog-card .card-img-top {
            height: 190px;
        }

        .catalog-btn-group {
            grid-template-columns: 1fr;
        }

        .restro-help-subtitle::before,
        .restro-help-subtitle::after {
            width: 34px;
        }

        .restro-help-head h3 {
            font-size: clamp(1.55rem, 8vw, 2rem);
        }

        .restro-help-grid {
            grid-template-columns: 1fr;
        }

        .restro-help-card {
            padding: 20px 16px;
        }

        .restro-help-card-top {
            grid-template-columns: 1fr;
            gap: 10px;
            margin-bottom: 10px;
        }

        .restro-help-icon {
            width: 54px;
            height: 54px;
            margin: 0 auto;
        }

        .restro-help-card h5 {
            font-size: 1.35rem;
        }

        .restro-faq-kicker::before,
        .restro-faq-kicker::after {
            width: 34px;
        }

        .restro-faq-head h2 {
            /* font-size: clamp(1.5rem, 8vw, 2.1rem); */
        }

        .restro-faq-head p {
            /* font-size: 1rem; */
        }

        .restro-faq-shell {
            padding: 0 12px;
        }
    }

/* Fix uneven card height */
.row {
    row-gap: 25px;
}

.card {
    height: 100%;
    border: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transition: 0.3s;
    display: flex;
    flex-direction: column;
}
.card-body {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    text-align: center;
}
.restro-btns {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 15px;
}
.container-xxl{
    max-width:100%;
}
.restro-btns .btn {
    padding: 6px 14px;
    font-size: 14px;
}
.card:hover {
    transform: translateY(-5px);
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
        <main class="catalog-page">
            
	        <div class="catalog-shell">

    <section class="restro-help-section" aria-labelledby="restro-help-title">
        <div class="restro-help-head">
            <span class="restro-help-subtitle">Quick Guide</span>
            <h2 id="restro-help-title">How To Start In 3 Steps</h2>
        </div>
        <div class="restro-help-grid">
            <article class="restro-help-card"> 
                <div class="restro-help-card-top">
                    <span class="restro-help-icon"><i class="fa fa-store"></i></span>
                    <h5>Choose</h5>
                </div>
                <p>Select a restaurant card based on your preferred location and name.</p>
            </article>
            <article class="restro-help-card">
                <div class="restro-help-card-top">
                    <span class="restro-help-icon"><i class="fa fa-utensils"></i></span>
                    <h5>Menu</h5>
                </div>
                <p>Click <strong>View Menu</strong> to explore available food items and prices.</p>
            </article>
            <article class="restro-help-card">
                <div class="restro-help-card-top">
                    <span class="restro-help-icon"><i class="fa fa-star"></i></span>
                    <h5>Review</h5>
                </div>
                <p>After ordering, use <strong>Give Review</strong> to rate your experience.</p>
            </article>
        </div>
    </section>

	    <div class="catalog-head">
        <span class="catalog-eyebrow">Top Picks</span>
        <h2 class="catalog-title">Restaurants</h2>
    </div>
    <div class="row catalog-grid">
        <?php
        $sql = "SELECT * FROM tbl_restro WHERE status='approved'";
        $res = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($res);

        if ($count > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                // Get the Values
                $id = $row['id'];
                $restro_name = $row['restro_name'];
                $restro_image = $row['restro_image'];
                $address = $row['restro_address'];
        ?>
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <div class="card catalog-card">
                        <img src="<?php echo SITEURL; ?>restro/<?php echo $restro_image; ?>" class="card-img-top" alt="Restaurant Image">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo $restro_name; ?></h5>
                            <p class="card-text"><?php echo $address; ?></p>
                            
                           <div class="catalog-btn-group">
    <a href="<?php echo SITEURL; ?>restro-menu.php?restro_name=<?php echo urlencode($row['restro_name']); ?>" class="catalog-btn menu">View Menu</a>

    <a href="<?php echo SITEURL; ?>review-restro.php?restro_name=<?php echo urlencode($row['restro_name']); ?>" class="catalog-btn review">Give Review</a>
</div>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<p class='catalog-empty'>No approved restaurants found.</p>";
        }
        ?>
    </div>

    <section class="restro-faq-section" aria-labelledby="restro-faq-title">
        <div class="restro-faq-head">
            <span class="restro-faq-kicker">FAQ</span>
            <h2 id="restro-faq-title">Frequently Asked Questions</h2>
            <p>Restaurant-related help before you place your order.</p>
        </div>
        <div class="restro-faq-shell">
        <div class="accordion restro-faq-accordion" id="restaurantFaq">
            <div class="accordion-item">
                <h2 class="accordion-header" id="restroFaqOneHead">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#restroFaqOne" aria-expanded="true" aria-controls="restroFaqOne">
                        How do I choose the right restaurant?
                    </button>
                </h2>
                <div id="restroFaqOne" class="accordion-collapse collapse show" aria-labelledby="restroFaqOneHead" data-bs-parent="#restaurantFaq">
                    <div class="accordion-body">
                        Check the restaurant name, address, menu variety, and recent ratings to pick the best match for your taste and location.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="restroFaqTwoHead">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#restroFaqTwo" aria-expanded="false" aria-controls="restroFaqTwo">
                        Can I view menu and prices before ordering?
                    </button>
                </h2>
                <div id="restroFaqTwo" class="accordion-collapse collapse" aria-labelledby="restroFaqTwoHead" data-bs-parent="#restaurantFaq">
                    <div class="accordion-body">
                        Yes. Click <strong>View Menu</strong> on any restaurant card to see dishes, prices, and options before you confirm your order.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="restroFaqThreeHead">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#restroFaqThree" aria-expanded="false" aria-controls="restroFaqThree">
                        How can I check restaurant quality?
                    </button>
                </h2>
                <div id="restroFaqThree" class="accordion-collapse collapse" aria-labelledby="restroFaqThreeHead" data-bs-parent="#restaurantFaq">
                    <div class="accordion-body">
                        Use customer feedback by opening <strong>Give Review</strong> to read and compare ratings before placing an order.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="restroFaqFourHead">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#restroFaqFour" aria-expanded="false" aria-controls="restroFaqFour">
                        What if a restaurant menu item is unavailable?
                    </button>
                </h2>
                <div id="restroFaqFour" class="accordion-collapse collapse" aria-labelledby="restroFaqFourHead" data-bs-parent="#restaurantFaq">
                    <div class="accordion-body">
                        Availability can change during busy hours. If an item is unavailable, choose an alternative dish or try another listed restaurant.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="restroFaqFiveHead">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#restroFaqFive" aria-expanded="false" aria-controls="restroFaqFive">
                        Can I leave a review after my order?
                    </button>
                </h2>
                <div id="restroFaqFive" class="accordion-collapse collapse" aria-labelledby="restroFaqFiveHead" data-bs-parent="#restaurantFaq">
                    <div class="accordion-body">
                        Yes. After ordering, use the <strong>Give Review</strong> button to share your experience and help other users choose better.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="restroFaqSixHead">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#restroFaqSix" aria-expanded="false" aria-controls="restroFaqSix">
                        Who should I contact for restaurant order issues?
                    </button>
                </h2>
                <div id="restroFaqSix" class="accordion-collapse collapse" aria-labelledby="restroFaqSixHead" data-bs-parent="#restaurantFaq">
                    <div class="accordion-body">
                        If you face delays, wrong items, or payment problems, use the support/contact options on the site for quick assistance.
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

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


