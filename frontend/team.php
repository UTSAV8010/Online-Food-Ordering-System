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
/* chef section */
.text-green {
        color: #4CAF50;
    }

    .chefs-heading {
        margin-bottom: 18px;
    }

    .chefs-kicker {
        position: relative;
        display: inline-block;
        font-family: "Pacifico", cursive;
        color: #0d6efd;
        font-size: 18px;
        line-height: 1.1;
        padding: 0 66px;
        margin-bottom: 12px;
    }

    .chefs-kicker::before,
    .chefs-kicker::after {
        content: "";
        position: absolute;
        top: 52%;
        width: 48px;
        height: 2px;
        background: #fea116;
    }

    .chefs-kicker::before {
        left: 8px;
    }

    .chefs-kicker::after {
        right: 8px;
    }

    .chefs-title {
        margin: 0;
        font-size: 37px;
        color: #0f224a;
        font-weight: 800;
    }

    .chefs-title .accent {
        color: #2ea44f;
    }

    section#chefs {
        background-color: #f4f4f4;
        padding: 50px 0;
    }

    section#chefs .section-title h2 {
        font-size: 2.5rem;
        color: #4CAF50;
        font-weight: 600;
    }

    section#chefs .section-title .description-title {
        font-size: 1.8rem;
        color: #333;
        font-weight: 500;
    }

    #chefs .team-member {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(12, 26, 56, 0.1);
        transition: transform 0.35s ease, box-shadow 0.35s ease;
        overflow: hidden;
        padding: 22px;
    }

    #chefs .team-member:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 44px rgba(12, 26, 56, 0.18);
    }

    #chefs .member-img {
        position: relative;
        border-radius: 50%;
        overflow: hidden;
        margin-bottom: 16px;
    }

    #chefs .member-img img {
        width: 100%;
        height: auto;
        border-radius: 50%;
        transition: transform 0.45s ease;
    }

    #chefs .team-member:hover .member-img img {
        transform: scale(1.08);
    }

    #chefs .member-img::after {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(10, 20, 45, 0.52);
        backdrop-filter: blur(1px);
        opacity: 0;
        transition: opacity 0.35s ease;
    }

    #chefs .team-member:hover .member-img::after {
        opacity: 1;
    }

    #chefs .social {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -42%);
        display: flex;
        gap: 10px;
        z-index: 2;
    }

    #chefs .social a {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e69500;
        color: #fff;
        font-size: 15px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        opacity: 0;
        transform: translateY(14px) scale(0.86);
        transition: transform 0.28s ease, opacity 0.28s ease, background 0.2s ease;
    }

    #chefs .team-member:hover .social a {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

    #chefs .team-member:hover .social a:nth-child(1) { transition-delay: 0.04s; }
    #chefs .team-member:hover .social a:nth-child(2) { transition-delay: 0.08s; }
    #chefs .team-member:hover .social a:nth-child(3) { transition-delay: 0.12s; }
    #chefs .team-member:hover .social a:nth-child(4) { transition-delay: 0.16s; }

    #chefs .social a:hover {
        background: #ffb226;
        transform: translateY(-2px) scale(1.04) !important;
    }

    .team-member .member-info h4 {
        font-size: 1.5rem;
        color: #333;
        margin-bottom: 10px;
    }

    .team-member .member-info span {
        display: block;
        font-size: 1.1rem;
        color: #4CAF50;
        margin-bottom: 10px;
    }

    .team-member .member-info p {
        font-size: 1rem;
        color: #666;
        line-height: 1.6;
    }

    .team-extra-wrap {
        background: linear-gradient(180deg, #f6f8fc 0%, #eef3fb 100%);
        padding: 64px 0;
    }

    .team-extra-section {
        margin-bottom: 56px;
    }

    .team-extra-head {
        text-align: center;
        margin-bottom: 26px;
    }

    .team-extra-kicker {
        position: relative;
        display: inline-block;
        font-family: "Pacifico", cursive;
        color: #0d6efd;
        font-size: 1.15rem;
        padding: 0 58px;
        margin-bottom: 10px;
    }

    .team-extra-kicker::before,
    .team-extra-kicker::after {
        content: "";
        position: absolute;
        top: 55%;
        width: 40px;
        height: 2px;
        background: #fea116;
    }

    .team-extra-kicker::before { left: 8px; }
    .team-extra-kicker::after { right: 8px; }

    .team-extra-title {
        margin: 0;
        color: #0f224a;
        font-size: clamp(1.6rem, 3.5vw, 2.3rem);
        font-weight: 800;
    }

    .feature-card {
        background: #fff;
        border-radius: 18px;
        padding: 26px 22px;
        height: 100%;
        box-shadow: 0 10px 28px rgba(14, 35, 78, 0.09);
        transition: transform .25s ease, box-shadow .25s ease;
    }

    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 18px 38px rgba(14, 35, 78, 0.16);
    }

    .feature-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, #ffbf24, #e69500);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        margin-bottom: 14px;
    }

    .feature-card h5 {
        margin-bottom: 8px;
        color: #0f224a;
        font-weight: 800;
    }

    .feature-card p {
        margin: 0;
        color: #5d6d84;
        line-height: 1.65;
    }

    .process-card {
        background: #fff;
        border: 1px solid #e6edf8;
        border-radius: 18px;
        padding: 24px 20px;
        height: 100%;
        position: relative;
    }

    .process-step {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #0d6efd;
        color: #fff;
        font-weight: 800;
        font-size: .9rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }

    .process-card h6 {
        color: #0f224a;
        font-size: 1.12rem;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .process-card p {
        color: #5d6d84;
        margin: 0;
    }

    .team-cta-box {
        background: linear-gradient(135deg, #0f224a, #132f63);
        border-radius: 20px;
        padding: clamp(20px, 4vw, 34px);
        color: #fff;
        box-shadow: 0 18px 42px rgba(9, 24, 52, 0.35);
    }

    .team-cta-box h3 {
        margin-bottom: 10px;
        font-weight: 800;
        font-size: clamp(1.45rem, 3vw, 2rem);
        color: #fff;
    }

    .team-cta-box p {
        margin-bottom: 20px;
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.7;
    }

    .team-cta-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .team-cta-actions a {
        border-radius: 999px;
        padding: 10px 18px;
        font-size: .85rem;
        font-weight: 800;
        letter-spacing: .25px;
        text-transform: uppercase;
        text-decoration: none;
    }

    .team-cta-primary {
        background: linear-gradient(135deg, #ffbf24, #e69500);
        color: #fff;
    }

    .team-cta-secondary {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.24);
        color: #fff;
    }

    .team-cta-stat {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.16);
        border-radius: 14px;
        padding: 14px;
        text-align: center;
        height: 100%;
    }

    .team-cta-stat strong {
        display: block;
        font-size: 1.35rem;
        font-weight: 800;
    }

    .team-cta-stat span {
        font-size: .85rem;
        color: rgba(255, 255, 255, 0.8);
        letter-spacing: .2px;
    }

    .team-long-section {
        padding: 68px 0;
        background: #ffffff;
    }

    .team-long-section.alt {
        background: linear-gradient(180deg, #f7f9fd 0%, #eef3fb 100%);
    }

    .long-wrap {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .long-media {
        border-radius: 22px;
        overflow: hidden;
        box-shadow: 0 16px 38px rgba(12, 26, 56, 0.16);
        height: 100%;
        min-height: 300px;
    }

    .long-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .long-copy h3 {
        color: #0f224a;
        font-size: clamp(1.55rem, 3vw, 2.2rem);
        font-weight: 800;
        margin-bottom: 12px;
    }

    .long-copy p {
        color: #566884;
        line-height: 1.75;
        margin-bottom: 14px;
        font-size: 1rem;
    }

    .long-points {
        display: grid;
        gap: 12px;
        margin-top: 12px;
    }

    .long-point {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        color: #27416b;
        font-weight: 600;
    }

    .long-point i {
        margin-top: 2px;
        color: #1f9d5d;
    }

    @media (max-width: 767.98px) {
        .team-long-section {
            padding: 48px 0;
        }

        .long-wrap {
            padding: 0 12px;
        }
    }

    .team-faq-wrap {
        background: #f4f5f7;
        padding: 62px 0 74px;
    }

    .team-faq-shell {
        max-width: 980px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .team-faq-title {
        text-align: center;
        color: #18233b;
        font-weight: 800;
        font-size: clamp(1.9rem, 3.8vw, 3rem);
        margin-bottom: 26px;
        letter-spacing: 0.2px;
    }

    .team-faq-accordion .accordion-item {
        border: 0;
        border-radius: 22px !important;
        overflow: hidden;
        margin-bottom: 16px;
        box-shadow: 0 1px 0 rgba(0, 0, 0, 0.08);
        background: #ffffff;
    }

    .team-faq-accordion .accordion-button {
        background: #ffffff;
        color: #17253f;
        font-weight: 700;
        font-size: clamp(1rem, 2vw, 1.08rem);
        padding: 22px 22px;
        border-radius: 22px !important;
        box-shadow: none !important;
    }

    .team-faq-accordion .accordion-button:not(.collapsed) {
        color: #0f224a;
    }

    .team-faq-accordion .accordion-button::after {
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

    .team-faq-accordion .accordion-button:not(.collapsed)::after {
        content: "-";
    }

    .team-faq-accordion .accordion-body {
        padding: 0 22px 20px;
        color: #5b6980;
        line-height: 1.75;
        font-size: 0.98rem;
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
        <!-- Team Start -->
        <section id="chefs" class="chefs section">

        <!-- Section Title -->
        

        <div class="container">
 <div class="team-extra-section">
                    <div class="team-extra-head">
                        <span class="team-extra-kicker">How We Work</span>
                        <h2 class="team-extra-title">From Ingredients To Delivery</h2>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6 col-lg-4">
                            <div class="process-card">
                                <span class="process-step">01</span>
                                <h6>Fresh Sourcing</h6>
                                <p>We select top-quality ingredients daily and verify freshness before kitchen prep begins.</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="process-card">
                                <span class="process-step">02</span>
                                <h6>Expert Preparation</h6>
                                <p>Recipes are executed by skilled chefs using measured portions and controlled cook times.</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="process-card">
                                <span class="process-step">03</span>
                                <h6>Quick Dispatch</h6>
                                <p>Orders are packed securely and handed off immediately for faster and hotter delivery.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container text-center chefs-heading" data-aos="fade-up">
            <span class="chefs-kicker">Our Chefs</span>
            <h5 class="chefs-title"><span class="accent">Our</span> Professional Chefs</h5>
        </div>
            <div class="row gy-4">

                <div class="col-lg-4 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="100">
                    <div class="team-member">
                        <div class="member-img">
                            <img src="./img/chefs-1.jpg" class="img-fluid" alt="">
                            <div class="social" style="color: white;">
                                <a href=""><i class="bi bi-twitter"></i></a>
                                <a href=""><i class="bi bi-facebook"></i></a>
                                <a href=""><i class="bi bi-instagram"></i></a>
                                <a href=""><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                        <div class="member-info">
                            <h4>Ravi Kumar</h4>
                            <span>Master Chef</span>
                            <p>Ravi is known for his culinary mastery in traditional Indian cuisines. He combines
                                classic techniques with modern flair, offering an unforgettable dining experience.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="200">
                    <div class="team-member">
                        <div class="member-img">
                            <img src="./img/chefs-2.jpg" class="img-fluid" alt="">
                            <div class="social" style="color: white;">
                                <a href=""><i class="bi bi-twitter"></i></a>
                                <a href=""><i class="bi bi-facebook"></i></a>
                                <a href=""><i class="bi bi-instagram"></i></a>
                                <a href=""><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                        <div class="member-info">
                            <h4>Anjali Patel</h4>
                            <span>Patissier</span>
                            <p>Anjali specializes in creating delicate and flavorful pastries that embody the richness
                                of Indian flavors. Her desserts are not only beautiful but a true culinary delight.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="300">
                    <div class="team-member">
                        <div class="member-img">
                            <img src="./img/chefs-3.jpg" class="img-fluid" alt="">
                            <div class="social" style="color: white;">
                                <a href=""><i class="bi bi-twitter"></i></a>
                                <a href=""><i class="bi bi-facebook"></i></a>
                                <a href=""><i class="bi bi-instagram"></i></a>
                                <a href=""><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                        <div class="member-info">
                            <h4>Arjun Singh</h4>
                            <span>Cook</span>
                            <p>Arjun is a passionate cook who blends authentic Indian spices with fresh ingredients. His
                                diverse approach to cooking brings out the true essence of every dish.</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </section>
        <!-- Team End -->

        <section class="team-extra-wrap">
            <div class="container">
                <div class="team-extra-section">
                    <div class="team-extra-head">
                        <span class="team-extra-kicker">Why Our Team</span>
                        <h2 class="team-extra-title">Kitchen Values We Follow Daily</h2>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6 col-lg-4">
                            <div class="feature-card">
                                <span class="feature-icon"><i class="fa fa-heart"></i></span>
                                <h5>Passion In Every Plate</h5>
                                <p>Each chef focuses on flavor balance, consistency, and presentation so every dish feels premium.</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="feature-card">
                                <span class="feature-icon"><i class="fa fa-shield-alt"></i></span>
                                <h5>Strict Hygiene Standard</h5>
                                <p>From prep to packaging, we follow clean-station routines and quality checks for safe food handling.</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="feature-card">
                                <span class="feature-icon"><i class="fa fa-bolt"></i></span>
                                <h5>Fast Kitchen Workflow</h5>
                                <p>Our stations are optimized for speed, helping us serve fresh orders quickly during peak hours.</p>
                            </div>
                        </div>
                    </div>
                </div>

               

                <!-- <div class="team-cta-box">
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-7">
                            <h3>Want To Join Our Culinary Team?</h3>
                            <p>We are always looking for talented chefs and kitchen professionals who care about food quality and guest experience.</p>
                            <div class="team-cta-actions">
                                <a href="contact.php" class="team-cta-primary">Apply Now</a>
                                <a href="about.php" class="team-cta-secondary">Learn More</a>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="team-cta-stat">
                                        <strong>20+</strong>
                                        <span>Kitchen Experts</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="team-cta-stat">
                                        <strong>1200+</strong>
                                        <span>Meals Served Daily</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="team-cta-stat">
                                        <strong>4.8/5</strong>
                                        <span>Chef Ratings</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="team-cta-stat">
                                        <strong>7 Days</strong>
                                        <span>Active Kitchen</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </section>

        <section class="team-long-section">
            <div class="long-wrap">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-6">
                        <div class="long-media">
                            <img src="img/chefs-2.jpg" alt="Chef in professional kitchen">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="long-copy">
                            <h3>Inside Our Kitchen Culture</h3>
                            <p>Our kitchen operates on discipline, creativity, and consistency. Every chef follows a structured prep flow, but still has space to innovate and improve flavors using seasonal ingredients and customer feedback.</p>
                            <p>From morning mise en place to final dish pass, the team coordinates with clear communication and timing standards. This keeps service fast while preserving presentation quality and taste balance.</p>
                            <div class="long-points">
                                <div class="long-point"><i class="fa fa-check-circle"></i><span>Daily tasting rounds for quality consistency.</span></div>
                                <div class="long-point"><i class="fa fa-check-circle"></i><span>Smart station planning to reduce order delays.</span></div>
                                <div class="long-point"><i class="fa fa-check-circle"></i><span>Strong hygiene + food safety checks every shift.</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="team-long-section alt">
            <div class="long-wrap">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-6 order-lg-2">
                        <div class="long-media">
                            <img src="img/chefs-1.jpg" alt="Chef preparing dish">
                        </div>
                    </div>
                    <div class="col-lg-6 order-lg-1">
                        <div class="long-copy">
                            <h3>Training, Growth, And Standards</h3>
                            <p>We invest in ongoing chef development through monthly workshops, menu simulation sessions, and peer review tasting. Junior chefs train directly under seniors to build speed, accuracy, and confidence under pressure.</p>
                            <p>Every new menu cycle includes recipe standardization, plating benchmarks, and service-time targets. This ensures each dish is reproducible across teams and shifts without compromising freshness or guest experience.</p>
                            <div class="long-points">
                                <div class="long-point"><i class="fa fa-check-circle"></i><span>Mentor-based growth path for every kitchen role.</span></div>
                                <div class="long-point"><i class="fa fa-check-circle"></i><span>Practical performance reviews tied to real service quality.</span></div>
                                <div class="long-point"><i class="fa fa-check-circle"></i><span>Continuous upgrades in plating, prep, and consistency.</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="team-long-section">
            <div class="long-wrap">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-6">
                        <div class="long-media">
                            <img src="img/chefs-3.jpg" alt="Chef leadership and operations">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="long-copy">
                            <h3>Leadership, Innovation, And Guest Experience</h3>
                            <p>Our senior chefs lead by example during live service, guiding plating quality, speed, and kitchen coordination. They also review guest feedback weekly to identify patterns and improve signature items with measurable changes.</p>
                            <p>Innovation here is practical. We test new ideas in controlled batches, monitor prep complexity, and only launch dishes that meet flavor goals and delivery performance. This approach keeps the menu exciting without compromising consistency.</p>
                            <div class="long-points">
                                <div class="long-point"><i class="fa fa-check-circle"></i><span>Weekly review cycle for flavor, service time, and guest ratings.</span></div>
                                <div class="long-point"><i class="fa fa-check-circle"></i><span>Menu innovation backed by real kitchen feasibility checks.</span></div>
                                <div class="long-point"><i class="fa fa-check-circle"></i><span>Chef-led quality control from prep station to final handoff.</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="team-long-section">
            <div class="long-wrap">
                <div class="team-cta-box">
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-7">
                            <h3>Want To Join Our Culinary Team?</h3>
                            <p>We are always looking for talented chefs and kitchen professionals who care about food quality and guest experience.</p>
                            <div class="team-cta-actions">
                                <a href="contact.php" class="team-cta-primary">Apply Now</a>
                                <a href="about.php" class="team-cta-secondary">Learn More</a>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="team-cta-stat">
                                        <strong>20+</strong>
                                        <span>Kitchen Experts</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="team-cta-stat">
                                        <strong>1200+</strong>
                                        <span>Meals Served Daily</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="team-cta-stat">
                                        <strong>4.8/5</strong>
                                        <span>Chef Ratings</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="team-cta-stat">
                                        <strong>7 Days</strong>
                                        <span>Active Kitchen</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="team-faq-wrap">
            
            <div class="team-faq-shell">
                <h2 class="team-faq-title">Frequently Asked Questions</h2>
                <div class="accordion team-faq-accordion" id="teamFaq">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqOneHead">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqOne" aria-expanded="true" aria-controls="faqOne">
                                What is a Chef page?
                            </button>
                        </h2>
                        <div id="faqOne" class="accordion-collapse collapse show" aria-labelledby="faqOneHead" data-bs-parent="#teamFaq">
                            <div class="accordion-body">
                                The Chef page introduces your culinary team, highlights each member's skills, and helps visitors trust your food quality and kitchen standards.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqTwoHead">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqTwo" aria-expanded="false" aria-controls="faqTwo">
                                How does this section help my business?
                            </button>
                        </h2>
                        <div id="faqTwo" class="accordion-collapse collapse" aria-labelledby="faqTwoHead" data-bs-parent="#teamFaq">
                            <div class="accordion-body">
                                Showing real chefs and process details increases credibility, improves user confidence, and can boost order conversions from new visitors.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqThreeHead">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqThree" aria-expanded="false" aria-controls="faqThree">
                                Can I update chef details later?
                            </button>
                        </h2>
                        <div id="faqThree" class="accordion-collapse collapse" aria-labelledby="faqThreeHead" data-bs-parent="#teamFaq">
                            <div class="accordion-body">
                                Yes. Chef names, roles, photos, and descriptions can be changed anytime to keep your page current with new team members or updated highlights.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqFourHead">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqFour" aria-expanded="false" aria-controls="faqFour">
                                Is this FAQ design mobile responsive?
                            </button>
                        </h2>
                        <div id="faqFour" class="accordion-collapse collapse" aria-labelledby="faqFourHead" data-bs-parent="#teamFaq">
                            <div class="accordion-body">
                                Yes. The layout adapts for mobile, tablet, and desktop with touch-friendly spacing and clean readability across all screen sizes.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqFiveHead">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqFive" aria-expanded="false" aria-controls="faqFive">
                                Can I add more questions later?
                            </button>
                        </h2>
                        <div id="faqFive" class="accordion-collapse collapse" aria-labelledby="faqFiveHead" data-bs-parent="#teamFaq">
                            <div class="accordion-body">
                                Absolutely. You can add unlimited FAQ entries by duplicating one accordion item block and updating the IDs, question text, and answer content.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

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


