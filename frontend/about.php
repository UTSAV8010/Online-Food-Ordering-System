<?php include('config/constants.php');
include('config/blocked-check.php'); ?>

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
    <link
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&family=Pacifico&display=swap"
        rel="stylesheet">

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

.back-to-top {
            right: 0px !important;
            bottom: 27px !important;
        }

.about-story {
    background: linear-gradient(180deg, #f7f8fb 0%, #ffffff 100%);
}

.about-story .story-card {
    background: #ffffff;
    border: 1px solid #eef2f7;
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 12px 26px rgba(15, 23, 43, 0.08);
}

.about-story .story-card + .story-card {
    margin-top: 18px;
}

.about-story .story-title {
    font-weight: 800;
    color: #0f172f;
    margin-bottom: 8px;
}

.about-story .story-text {
    color: #6b7280;
    margin-bottom: 0;
}

.story-points {
    list-style: none;
    padding-left: 0;
    margin: 16px 0 0;
    display: grid;
    gap: 10px;
}

.story-points li {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    color: #6b7280;
}

.story-timeline .timeline-item {
    display: flex;
    gap: 16px;
    padding: 12px 0;
    border-bottom: 1px solid #eef2f7;
}

.story-timeline .timeline-item:last-child {
    border-bottom: 0;
}

.timeline-year {
    min-width: 62px;
    height: 32px;
    border-radius: 999px;
    background: rgba(254, 161, 22, 0.15);
    color: #0f172f;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
    margin-top: 16px;
}

.value-item {
    background: #f7f8fb;
    border-radius: 14px;
    padding: 12px 14px;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #6b7280;
    font-weight: 600;
}

.value-item i {
    color: #fea116;
}

@media (max-width: 767.98px) {
    .about-story .story-card {
        padding: 20px;
    }

    .values-grid {
        grid-template-columns: 1fr;
    }
}
    </style>
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
</div>
        <!-- Spinner End -->

        <div class="container-xxl position-relative p-0">
            <?php include('site-hader.php'); ?>
        </div>
        <!-- About Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6">
                        <div class="row g-3">
                            <div class="col-6 text-start">
                                <img class="img-fluid rounded w-100 wow zoomIn" data-wow-delay="0.1s"
                                    src="images/about-1.jpg">
                            </div>
                            <div class="col-6 text-start">
                                <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.3s"
                                    src="images/about-2.jpg" style="margin-top: 25%;">
                            </div>
                            <div class="col-6 text-end">
                                <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.5s"
                                    src="images/about-3.jpg">
                            </div>
                            <div class="col-6 text-end">
                                <img class="img-fluid rounded w-100 wow zoomIn" data-wow-delay="0.7s"
                                    src="images/about-4.jpg">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h5 class="section-title ff-secondary text-start text-primary fw-normal">About Us</h5>
                        <h1 class="mb-4">Welcome to <i class=" text-primary me-2"></i>Pasar-kita.com</h1>
                        <p class="mb-4">Robo Cafe began its journey from 2017 and has been one of the prominent
                            restaurants in town ever since.Originally serving fast food in Dhaka,
                            We only use the top-quality ingredients to prepare the dishes for our valued
                            customers.Quality is never compromised.</p>
                        <p class="mb-4">We serve our valued customers with premium quality food and to give the manelite
                            experience when it comes to fine dining.To be come one of the top-rated restaurants
                            nationally catering to the premium tastes of consumers.</p>
                        <div class="row g-4 mb-4">
                            </p>
                            <div class="row g-4 mb-4">
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center border-start border-5 border-primary px-3">
                                        <h1 class="flex-shrink-0 display-5 text-primary mb-0" data-toggle="counter-up">5
                                        </h1>
                                        <div class="ps-4">
                                            <p class="mb-0">Years of</p>
                                            <h6 class="text-uppercase mb-0">Experience</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center border-start border-5 border-primary px-3">
                                        <h1 class="flex-shrink-0 display-5 text-primary mb-0" data-toggle="counter-up">
                                            10</h1>
                                        <div class="ps-4">
                                            <p class="mb-0">Popular</p>
                                            <h6 class="text-uppercase mb-0">Master Chefs</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a class="btn btn-primary py-3 px-5 mt-2" href="">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- About Extended Start -->
        <div class="container-xxl py-5 about-story">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h5 class="section-title ff-secondary text-primary fw-normal">Our Story</h5>
                    <h1 class="mb-3">Built With Care, Served With Pride</h1>
                    <p class="mb-0 text-muted">From a small kitchen in 2017 to a trusted food partner for families, students, and offices.</p>
                </div>
                <div class="row g-4 align-items-start mt-4">
                    <div class="col-lg-6">
                        <div class="story-card wow fadeInUp" data-wow-delay="0.1s">
                            <h3 class="story-title">Our Mission</h3>
                            <p class="story-text">We make everyday meals feel special by delivering fresh ingredients, consistent flavors, and warm hospitality to every customer.</p>
                            <ul class="story-points">
                                <li><i class="fa fa-check-circle text-primary"></i><span>Curate menus that balance comfort food with healthy options.</span></li>
                                <li><i class="fa fa-check-circle text-primary"></i><span>Support local chefs and suppliers to keep quality high.</span></li>
                                <li><i class="fa fa-check-circle text-primary"></i><span>Deliver on time with care and clean, safe packaging.</span></li>
                            </ul>
                        </div>
                        <div class="story-card wow fadeInUp" data-wow-delay="0.2s">
                            <h3 class="story-title">Our Vision</h3>
                            <p class="story-text">To become the most loved restaurant platform in the region by building trust, improving convenience, and celebrating local flavors.</p>
                            <div class="values-grid">
                                <div class="value-item"><i class="fa fa-heart"></i>Community First</div>
                                <div class="value-item"><i class="fa fa-leaf"></i>Fresh Ingredients</div>
                                <div class="value-item"><i class="fa fa-star"></i>Quality Promise</div>
                                <div class="value-item"><i class="fa fa-bolt"></i>Fast Service</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="story-card story-timeline wow fadeInUp" data-wow-delay="0.15s">
                            <h3 class="story-title">Milestones</h3>
                            <div class="timeline-item">
                                <span class="timeline-year">2017</span>
                                <div>
                                    <strong>Humble Start</strong>
                                    <p class="story-text">Launched with a small team focused on fast, flavorful street food.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <span class="timeline-year">2019</span>
                                <div>
                                    <strong>Growing Community</strong>
                                    <p class="story-text">Expanded the menu and partnered with new chefs across the city.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <span class="timeline-year">2022</span>
                                <div>
                                    <strong>Service Upgrade</strong>
                                    <p class="story-text">Introduced smarter delivery tracking and improved packaging quality.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <span class="timeline-year">2025</span>
                                <div>
                                    <strong>Trusted Brand</strong>
                                    <p class="story-text">Reached new locations with consistent ratings and loyal customers.</p>
                                </div>
                            </div>
                        </div>
                        <div class="story-card wow fadeInUp" data-wow-delay="0.25s">
                            <h3 class="story-title">Why People Choose Us</h3>
                            <p class="story-text">Reliable taste, friendly support, and a service that respects your time.</p>
                            <ul class="story-points">
                                <li><i class="fa fa-check-circle text-primary"></i><span>Daily kitchen checks and quality control.</span></li>
                                <li><i class="fa fa-check-circle text-primary"></i><span>Easy ordering with multiple payment options.</span></li>
                                <li><i class="fa fa-check-circle text-primary"></i><span>Dedicated support for events and group orders.</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- About Extended End -->

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


