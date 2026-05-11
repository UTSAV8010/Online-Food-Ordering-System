<?php include('config/constants.php'); 
include('config/blocked-check.php');  ?>

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

.testimonial-kicker {
    position: relative;
    display: inline-block;
    font-family: "Pacifico", cursive;
    color: #0d6efd;
    font-size: 1.15rem;
    font-weight:500;
    line-height: 1.1;
    padding: 0 82px;
    margin-bottom: 10px;
}

.testimonial-kicker::before,
.testimonial-kicker::after {
    content: "";
    position: absolute;
    top: 54%;
    width: 56px;
    height: 2px;
    background: #fea116;
}

.testimonial-kicker::before { left: 12px; }
.testimonial-kicker::after { right: 12px; }

.testimonial-subhead {
    position: relative;
    display: inline-block;
    font-family: "Pacifico", cursive;
    color: #0d6efd;
    font-size: 1.1rem;
    font-weight: 500;
    line-height: 1.1;
    padding: 0 92px;
    margin-bottom: 10px;
}

.testimonial-subhead::before,
.testimonial-subhead::after {
    content: "";
    position: absolute;
    top: 55%;
    width: 72px;
    height: 2px;
    background: #fea116;
}

.testimonial-subhead::before { left: 6px; }
.testimonial-subhead::after { right: 6px; }

.testimonial-info {
    text-align: center;
}

.testimonial-info p {
    margin-bottom: 0;
    color: #6b7280;
}

.testimonial-features {
    margin-bottom: 2.25rem;
}

.testimonial-feature {
    background: #ffffff;
    border: 1px solid #eef2f7;
    border-radius: 18px;
    padding: 24px 22px;
    height: 100%;
    box-shadow: 0 10px 24px rgba(15, 23, 43, 0.08);
    text-align: center;
}

.testimonial-feature .feature-icon {
    width: 54px;
    height: 54px;
    border-radius: 16px;
    background: rgba(254, 161, 22, 0.15);
    color: #fea116;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    margin-bottom: 12px;
}

.testimonial-feature p {
    margin: 0;
    color: #6b7280;
}

.customer-say-section {
    margin: 1.75rem auto 2.5rem;
}

.customer-say-card {
    position: relative;
    border-radius: 28px;
    border: 1px solid #edf1f7;
    background: #ffffff;
    box-shadow: 0 18px 38px rgba(15, 23, 43, 0.12);
    padding: 36px 28px 30px;
    overflow: hidden;
}

.customer-say-card::before {
    content: "";
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at top, rgba(254, 161, 22, 0.08), transparent 60%);
    pointer-events: none;
}

.customer-say-content {
    position: relative;
    text-align: center;
    max-width: 520px;
    margin: 0 auto 22px;
}

.customer-say-content h2 {
    font-size: clamp(1.6rem, 2.8vw, 2.1rem);
    font-weight: 800;
    color: #0f172f;
    margin-bottom: 8px;
}

.customer-say-content p {
    margin-bottom: 14px;
    color: #6b7280;
}

.customer-say-avatars {
    position: relative;
    width: min(560px, 100%);
    height: clamp(180px, 32vw, 230px);
    margin: 0 auto 16px;
}

.customer-say-avatars .avatar {
    position: absolute;
    border-radius: 999px;
    overflow: hidden;
    border: 4px solid #ffffff;
    box-shadow: 0 10px 24px rgba(15, 23, 43, 0.18);
    background: #fff;
    animation: float 6s ease-in-out infinite;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.customer-say-avatars .avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.customer-say-avatars .avatar:hover {
    transform: translateY(-6px) scale(1.08);
    box-shadow: 0 16px 30px rgba(15, 23, 43, 0.25);
}

.customer-say-avatars .avatar.active {
    box-shadow: 0 18px 36px rgba(254, 161, 22, 0.35);
    transform: translateY(-8px) scale(1.08);
    border-color: rgba(254, 161, 22, 0.6);
}

.avatar-1 { width: 78px; height: 78px; top: 8%; left: 8%; animation-delay: 0s; }
.avatar-2 { width: 62px; height: 62px; top: 38%; left: 2%; animation-delay: 0.8s; }
.avatar-3 { width: 86px; height: 86px; top: 18%; left: 38%; animation-delay: 1.3s; }
.avatar-4 { width: 96px; height: 96px; top: 5%; left: 62%; animation-delay: 0.3s; }
.avatar-5 { width: 68px; height: 68px; top: 45%; left: 78%; animation-delay: 1.6s; }
.avatar-6 { width: 58px; height: 58px; top: 62%; left: 22%; animation-delay: 2.1s; }
.avatar-7 { width: 70px; height: 70px; top: 60%; left: 52%; animation-delay: 1.9s; }

.customer-say-avatars .dot {
    position: absolute;
    width: 6px;
    height: 6px;
    border-radius: 999px;
    background: rgba(15, 23, 43, 0.2);
}

.dot-1 { top: 8%; left: 30%; }
.dot-2 { top: 22%; left: 88%; }
.dot-3 { top: 72%; left: 10%; }
.dot-4 { top: 88%; left: 44%; }
.dot-5 { top: 48%; left: 92%; }

.customer-say-footer {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    color: #6b7280;
    text-align: center;
}

.customer-say-footer p {
    margin: 0;
    max-width: 420px;
}

.customer-say-nav {
    width: 38px;
    height: 38px;
    border-radius: 999px;
    border: 1px solid #e5e7eb;
    background: #ffffff;
    color: #0f172f;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.customer-say-nav:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(15, 23, 43, 0.15);
}

@keyframes float {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

.section-heading {
    text-align: center;
    margin-bottom: 1.6rem;
}

.section-heading h2 {
    font-size: clamp(1.6rem, 3vw, 2.2rem);
    font-weight: 800;
    color: #0f172f;
    margin-bottom: 6px;
}

.section-heading p {
    color: #6b7280;
    margin: 0;
}

.stats-section .stat-card {
    background: #ffffff;
    border: 1px solid #eef2f7;
    border-radius: 18px;
    padding: 22px 18px;
    text-align: center;
    height: 100%;
    box-shadow: 0 10px 24px rgba(15, 23, 43, 0.08);
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}

.stats-section .stat-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 30px rgba(15, 23, 43, 0.15);
    border-color: rgba(254, 161, 22, 0.5);
}

.stats-section .stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    margin: 0 auto 10px;
    background: rgba(254, 161, 22, 0.15);
    color: #fea116;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
}

.stats-section h2 {
    font-size: 1.8rem;
    font-weight: 800;
    color: #0f172f;
    margin-bottom: 4px;
}

.stats-section p {
    margin: 0;
    color: #6b7280;
}

.cta-card {
    border-radius: 24px;
    border: 1px solid #f1e4d0;
    background: linear-gradient(120deg, rgba(254, 161, 22, 0.18), rgba(255, 255, 255, 0.9));
    box-shadow: 0 18px 34px rgba(15, 23, 43, 0.12);
    padding: 26px 28px;
    gap: 20px;
}

.cta-text h3 {
    margin-bottom: 6px;
    font-weight: 800;
    color: #0f172f;
}

.cta-text p {
    margin-bottom: 0;
    color: #6b7280;
}

.cta-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.gallery-item {
    position: relative;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 12px 26px rgba(15, 23, 43, 0.12);
}

.gallery-item img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    transition: transform 0.4s ease;
    display: block;
}

.gallery-item::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0, 0, 0, 0), rgba(15, 23, 43, 0.35));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-item span {
    position: absolute;
    left: 12px;
    bottom: 12px;
    background: rgba(15, 23, 43, 0.75);
    color: #ffffff;
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 0.85rem;
    z-index: 1;
}

.gallery-item:hover img {
    transform: scale(1.05);
}

.gallery-item:hover::after {
    opacity: 1;
}

.faq-section .accordion-item {
    border: 0;
    border-radius: 22px;
    margin-bottom: 16px;
    box-shadow: 0 1px 0 rgba(0, 0, 0, 0.08);
    overflow: hidden;
    background: #ffffff;
}

.testimonial-faq-shell {
    max-width: 980px;
    margin: 0 auto;
    padding: 0 16px;
}

.faq-section .accordion-button {
    background: #ffffff;
    color: #17253f;
    font-weight: 700;
    font-size: clamp(1rem, 2vw, 1.08rem);
    padding: 22px 22px;
    border-radius: 22px !important;
    box-shadow: none !important;
}

.faq-section .accordion-button:not(.collapsed) {
    color: #0f224a;
}

.faq-section .accordion-button::after {
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
/* .accordion{
    width: 980px;
    margin:0 auto;
} */
.faq-section .accordion-button:not(.collapsed)::after {
    content: "-";
}

.faq-section .accordion-body {
    padding: 0 22px 20px;
    color: #5b6980;
    line-height: 1.75;
    font-size: 0.98rem;
}

.faq-section .accordion-button:focus {
    box-shadow: none;
    border-color: transparent;
}

.contact-strip {
    background: linear-gradient(120deg, #0f172f, #1a2a4a);
    border-radius: 24px;
    padding: 24px 22px;
    color: #ffffff;
    box-shadow: 0 18px 34px rgba(15, 23, 43, 0.2);
}

.contact-strip-item {
    display: flex;
    align-items: center;
    gap: 14px;
}

.contact-strip-icon {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.12);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fea116;
    font-size: 1.1rem;
}

.contact-strip h6 {
    margin: 0 0 4px;
    font-weight: 700;
    color: #ffffff;
}

.contact-strip p,
.contact-strip a {
    margin: 0;
    color: rgba(255, 255, 255, 0.85);
    text-decoration: none;
}

.contact-strip a:hover {
    color: #fea116;
}

@media (max-width: 767.98px) {
    .testimonial-subhead {
        font-size: 1rem;
        padding: 0 60px;
    }

    .testimonial-subhead::before,
    .testimonial-subhead::after {
        width: 40px;
    }

    .customer-say-card {
        padding: 28px 20px 24px;
    }

    .customer-say-avatars {
        height: 190px;
    }

    .avatar-4,
    .avatar-5 {
        display: none;
    }

    .cta-card {
        text-align: center;
    }

    .cta-actions {
        justify-content: center;
    }

    .gallery-item img {
        height: 190px;
    }

    .contact-strip-item {
        justify-content: center;
        text-align: center;
    }
}

@media (max-width: 575.98px) {
    .testimonial-subhead {
        padding: 0 44px;
    }

    .testimonial-subhead::before,
    .testimonial-subhead::after {
        width: 28px;
    }

    .customer-say-avatars {
        height: 170px;
    }

    .avatar-1 { width: 64px; height: 64px; }
    .avatar-2 { width: 54px; height: 54px; }
    .avatar-3 { width: 74px; height: 74px; }
    .avatar-6,
    .avatar-7 {
        display: none;
    }

    .gallery-item img {
        height: 160px;
    }

    .testimonial-faq-shell {
        padding: 0 12px;
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
        </div>
        <!-- Testimonial Start -->
        <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
            <div class="container">
                <div class="text-center">
                    <h5 class="testimonial-kicker">Testimonials</h5>
                    <h1 class="mb-5">Our Clients Say!!!</h1>
                </div>
                <div class="row g-4 justify-content-center mt-2 testimonial-features">
                    <div class="col-lg-4 col-md-6">
                        <div class="testimonial-feature">
                            <span class="feature-icon"><i class="fa fa-user"></i></span>
                            <h5 class="testimonial-subhead">Students</h5>
                            <p>Students love the quick ordering, affordable combos, and the taste they can count on between classes.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="testimonial-feature">
                            <span class="feature-icon"><i class="fa fa-briefcase"></i></span>
                            <h5 class="testimonial-subhead">Business</h5>
                            <p>Teams appreciate the reliable catering, clean packaging, and on-time delivery for busy workdays.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="testimonial-feature">
                            <span class="feature-icon"><i class="fa fa-book"></i></span>
                            <h5 class="testimonial-subhead">Teachers</h5>
                            <p>Educators highlight the consistent quality, hygienic preparation, and friendly support from staff.</p>
                        </div>
                    </div>
                </div>
                <div class="customer-say-section wow fadeInUp" data-wow-delay="0.15s">
                    <div class="customer-say-card">
                        <div class="customer-say-content">
                            <h2>What Our Customers Say</h2>
                            <p>Real stories from happy people! See how our services and support create memorable experiences.</p>
                            <a class="btn btn-primary py-2 px-4" href="contact.php">Book Now</a>
                        </div>
                        <div class="customer-say-avatars">
                            <span class="avatar avatar-1 active" data-avatar="0"><img src="images/avatar1.jpeg" alt="Customer"></span>
                            <span class="avatar avatar-2" data-avatar="1"><img src="images/avatar1.jpeg" alt="Customer"></span>
                            <span class="avatar avatar-3" data-avatar="2"><img src="images/avatar1.jpeg" alt="Customer"></span>
                            <span class="avatar avatar-4" data-avatar="3"><img src="images/avatar1.jpeg" alt="Customer"></span>
                            <span class="avatar avatar-5" data-avatar="4"><img src="images/avatar1.jpeg" alt="Customer"></span>
                            <span class="avatar avatar-6" data-avatar="5"><img src="images/avatar1.jpeg" alt="Customer"></span>
                            <span class="avatar avatar-7" data-avatar="6"><img src="images/avatar1.jpeg" alt="Customer"></span>
                            <span class="dot dot-1"></span>
                            <span class="dot dot-2"></span>
                            <span class="dot dot-3"></span>
                            <span class="dot dot-4"></span>
                            <span class="dot dot-5"></span>
                        </div>
                        <div class="customer-say-footer">
                            <button class="customer-say-nav" type="button" data-direction="prev" aria-label="Previous testimonial">
                                <i class="fa fa-chevron-left"></i>
                            </button>
                            <p class="customer-say-quote" aria-live="polite">This platform made my experience seamless and enjoyable. The quality of service exceeded my expectations!</p>
                            <button class="customer-say-nav" type="button" data-direction="next" aria-label="Next testimonial">
                                <i class="fa fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="stats-section mt-5">
                    <div class="section-heading">
                        <h5 class="testimonial-kicker">Highlights</h5>
                        <h2>Numbers That Matter</h2>
                        <p>Reliable service and great food, proven by our community.</p>
                    </div>
                    <div class="row g-4">
                        <div class="col-6 col-lg-3">
                            <div class="stat-card wow fadeInUp" data-wow-delay="0.1s">
                                <span class="stat-icon"><i class="fa fa-smile"></i></span>
                                <h2><span data-toggle="counter-up">1500</span>+</h2>
                                <p>Happy Customers</p>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="stat-card wow fadeInUp" data-wow-delay="0.2s">
                                <span class="stat-icon"><i class="fa fa-utensils"></i></span>
                                <h2><span data-toggle="counter-up">320</span>+</h2>
                                <p>Daily Orders</p>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="stat-card wow fadeInUp" data-wow-delay="0.3s">
                                <span class="stat-icon"><i class="fa fa-store"></i></span>
                                <h2><span data-toggle="counter-up">45</span>+</h2>
                                <p>Partner Restaurants</p>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="stat-card wow fadeInUp" data-wow-delay="0.4s">
                                <span class="stat-icon"><i class="fa fa-star"></i></span>
                                <h2><span data-toggle="counter-up">98</span>%</h2>
                                <p>Satisfaction Rate</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="gallery-section mt-5">
                    <div class="section-heading">
                        <h5 class="testimonial-kicker">Gallery</h5>
                        <h2>Fresh From The Kitchen</h2>
                        <p>Handpicked favorites from our most loved dishes.</p>
                    </div>
                    <div class="row g-3">
                        <div class="col-6 col-lg-4">
                            <div class="gallery-item wow fadeInUp" data-wow-delay="0.1s">
                                <img src="images/food/Food-Name-1242.jpg" alt="Signature Meals">
                                <span>Signature Meals</span>
                            </div>
                        </div>
                        <div class="col-6 col-lg-4">
                            <div class="gallery-item wow fadeInUp" data-wow-delay="0.2s">
                                <img src="images/food/Food-Name-1499.jpg" alt="Chef Specials">
                                <span>Chef Specials</span>
                            </div>
                        </div>
                        <div class="col-6 col-lg-4">
                            <div class="gallery-item wow fadeInUp" data-wow-delay="0.3s">
                                <img src="images/food/Food-Name-2913.jpeg" alt="Healthy Plates">
                                <span>Healthy Plates</span>
                            </div>
                        </div>
                        <div class="col-6 col-lg-4">
                            <div class="gallery-item wow fadeInUp" data-wow-delay="0.1s">
                                <img src="images/food/Food-Name-3562.jpg" alt="Fresh Bowls">
                                <span>Fresh Bowls</span>
                            </div>
                        </div>
                        <div class="col-6 col-lg-4">
                            <div class="gallery-item wow fadeInUp" data-wow-delay="0.2s">
                                <img src="images/food/Food-Name-5049.jpg" alt="Comfort Food">
                                <span>Comfort Food</span>
                            </div>
                        </div>
                        <div class="col-6 col-lg-4">
                            <div class="gallery-item wow fadeInUp" data-wow-delay="0.3s">
                                <img src="images/food/Food-Name-5500.jpg" alt="Seasonal Picks">
                                <span>Seasonal Picks</span>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="section-heading mt-5">
                    <h5 class="testimonial-kicker">Customer</h5>
                    <h2>Customer Reviews</h2>
                </div>
                <div class="owl-carousel testimonial-carousel mt-5">
                    <div class="testimonial-item bg-transparent border rounded p-4">
                        <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                        <p>First of all, I love their interior design.Their services was so nice & amazing .And also i like their food so much</p>
                        <div class="d-flex align-items-center">
                            <img class="img-fluid flex-shrink-0 rounded-circle" src="images/avatar1.jpeg" style="width: 50px; height: 50px;">
                            <div class="ps-3">
                                <h5 class="mb-1">Rasel Hossain</h5>
                                <small>Student</small>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item bg-transparent border rounded p-4">
                        <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                        <p>I was quite amazed by their unique concept. Hats off to Pasar-kita.com and their whole team.</p>
                        <div class="d-flex align-items-center">
                            <img class="img-fluid flex-shrink-0 rounded-circle" src="images/avatar1.jpeg" style="width: 50px; height: 50px;">
                            <div class="ps-3">
                                <h5 class="mb-1">Ashraf Alam</h5>
                                <small>Businessman</small>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item bg-transparent border rounded p-4">
                        <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                        <p>Nice environment also provide healthy and tasty food.Like it very much</p>
                        <div class="d-flex align-items-center">
                            <img class="img-fluid flex-shrink-0 rounded-circle" src="images/avatar1.jpeg" style="width: 50px; height: 50px;">
                            <div class="ps-3">
                                <h5 class="mb-1">Sumon Mollah</h5>
                                <small>Teacher</small>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item bg-transparent border rounded p-4">
                        <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                        <p>WOW!! Exceptional concept of Pasar-kita.com.Food quality is good, keep it up.</p>
                        <div class="d-flex align-items-center">
                            <img class="img-fluid flex-shrink-0 rounded-circle" src="images/avatar1.jpeg" style="width: 50px; height: 50px;">
                            <div class="ps-3">
                                <h5 class="mb-1">Labony Haque</h5>
                                <small>Student</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cta-section mt-5 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="cta-card d-flex flex-column flex-lg-row align-items-center justify-content-between">
                        <div class="cta-text mb-3 mb-lg-0">
                            <h3>Ready for your next meal?</h3>
                            <p>Order now or plan a group catering with our trusted partners.</p>
                        </div>
                        <div class="cta-actions">
                            <a class="btn btn-primary py-2 px-4" href="menu.php">Order Now</a>
                            <a class="btn btn-dark py-2 px-4" href="contact.php">Contact Us</a>
                        </div>
                    </div>
                </div>
                <div class="faq-section mt-5">
                    <div class="section-heading">
                        <h5 class="testimonial-kicker">FAQ</h5>
                        <h2>Frequently Asked Questions</h2>
                        <p>Everything you need to know before your next order.</p>
                    </div>
                    <div class="testimonial-faq-shell">
                    <div class="accordion" id="testimonialFaq">
                        <div class="accordion-item wow fadeInUp" data-wow-delay="0.1s">
                            <h2 class="accordion-header" id="faqOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseOne" aria-expanded="true" aria-controls="faqCollapseOne">
                                    How do I place an order?
                                </button>
                            </h2>
                            <div id="faqCollapseOne" class="accordion-collapse collapse show" aria-labelledby="faqOne" data-bs-parent="#testimonialFaq">
                                <div class="accordion-body">
                                    Choose a restaurant, pick your dishes, and confirm delivery details. You will receive updates by SMS or email.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item wow fadeInUp" data-wow-delay="0.2s">
                            <h2 class="accordion-header" id="faqTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseTwo" aria-expanded="false" aria-controls="faqCollapseTwo">
                                    Can I schedule orders in advance?
                                </button>
                            </h2>
                            <div id="faqCollapseTwo" class="accordion-collapse collapse" aria-labelledby="faqTwo" data-bs-parent="#testimonialFaq">
                                <div class="accordion-body">
                                    Yes, schedule deliveries for lunch meetings, events, or daily meal plans from your favorite partners.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item wow fadeInUp" data-wow-delay="0.3s">
                            <h2 class="accordion-header" id="faqThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseThree" aria-expanded="false" aria-controls="faqCollapseThree">
                                    What payment methods are accepted?
                                </button>
                            </h2>
                            <div id="faqCollapseThree" class="accordion-collapse collapse" aria-labelledby="faqThree" data-bs-parent="#testimonialFaq">
                                <div class="accordion-body">
                                    We accept online payments, UPI, and cash on delivery depending on the restaurant and location.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item wow fadeInUp" data-wow-delay="0.4s">
                            <h2 class="accordion-header" id="faqFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseFour" aria-expanded="false" aria-controls="faqCollapseFour">
                                    How do I contact support?
                                </button>
                            </h2>
                            <div id="faqCollapseFour" class="accordion-collapse collapse" aria-labelledby="faqFour" data-bs-parent="#testimonialFaq">
                                <div class="accordion-body">
                                    Reach out anytime via the contact form, email, or phone. Our support team responds quickly.
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="contact-strip mt-5 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="row g-4 align-items-center">
                        <div class="col-md-4">
                            <div class="contact-strip-item">
                                <span class="contact-strip-icon"><i class="fa fa-phone-alt"></i></span>
                                <div>
                                    <h6>Call Us</h6>
                                    <a href="tel:9978043407">+91 9978043407</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="contact-strip-item">
                                <span class="contact-strip-icon"><i class="fa fa-envelope-open"></i></span>
                                <div>
                                    <h6>Email</h6>
                                    <a href="mailto:Pasar-kita@gmail.com">Pasar-kita@gmail.com</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="contact-strip-item">
                                <span class="contact-strip-icon"><i class="fa fa-map-marker-alt"></i></span>
                                <div>
                                    <h6>Location</h6>
                                    <p>Surat, Ahmedabad, Baroda</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Testimonial End -->
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const section = document.querySelector(".customer-say-section");
            if (!section) {
                return;
            }

            const quoteEl = section.querySelector(".customer-say-quote");
            const avatars = Array.from(section.querySelectorAll(".customer-say-avatars .avatar"));
            const navButtons = Array.from(section.querySelectorAll(".customer-say-nav"));

            if (!quoteEl || avatars.length === 0) {
                return;
            }

            const testimonials = [
                {
                    quote: "This platform made my experience seamless and enjoyable. The quality of service exceeded my expectations!",
                    avatar: 0
                },
                {
                    quote: "Ordering was quick and the support team was super helpful. Everything arrived fresh and on time.",
                    avatar: 2
                },
                {
                    quote: "Great variety, clean packaging, and friendly service. I recommend it to my classmates.",
                    avatar: 1
                },
                {
                    quote: "Reliable catering for meetings and events. The team was professional from start to finish.",
                    avatar: 3
                },
                {
                    quote: "Consistent quality and excellent hygiene. It is my go-to choice for daily meals.",
                    avatar: 5
                }
            ];

            let currentIndex = 0;
            let autoTimer = null;

            function setActive(index) {
                const safeIndex = (index + testimonials.length) % testimonials.length;
                const data = testimonials[safeIndex];
                quoteEl.textContent = data.quote;

                avatars.forEach((avatar, i) => {
                    avatar.classList.toggle("active", i === data.avatar);
                });

                currentIndex = safeIndex;
            }

            function next() {
                setActive(currentIndex + 1);
            }

            function prev() {
                setActive(currentIndex - 1);
            }

            function startAuto() {
                stopAuto();
                autoTimer = setInterval(next, 5000);
            }

            function stopAuto() {
                if (autoTimer) {
                    clearInterval(autoTimer);
                }
                autoTimer = null;
            }

            navButtons.forEach(btn => {
                btn.addEventListener("click", function () {
                    const dir = this.dataset.direction;
                    if (dir === "prev") {
                        prev();
                    } else {
                        next();
                    }
                    startAuto();
                });
            });

            section.addEventListener("mouseenter", stopAuto);
            section.addEventListener("mouseleave", startAuto);

            setActive(0);
            startAuto();
        });
    </script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
<?php include('site-footer.php'); ?>
</body>

</html>

