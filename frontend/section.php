
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasar-kita.com</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Open+Sans:wght@400;600&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">



    <style>
   /* ========== GLOBAL FIX ========== */
.container-xxl {
    max-width: 100%;
}

/* ========== CHEF SECTION ========== */
section#chefs {
    background-color: #fff;
    padding: 60px 0;
}

/* Section Title */
.text-green {
    color: #4CAF50;
    font-size:34px;
}

section#chefs .section-title h2 {
    font-size: 2.4rem;
    font-weight: 700;
    margin-bottom: 10px;
}

section#chefs .description-title {
    color: #333;
    font-weight: 500;
}

/* ========== CARD STYLE ========== */
#chefs .team-member {
    background: #fff;
    border-radius: 18px;
    padding: 22px;
    text-align: left;
    box-shadow: 0 10px 30px rgba(12, 26, 56, 0.1);
    transition: transform 0.35s ease, box-shadow 0.35s ease;
    height: 100%;
    overflow: hidden;
}

#chefs .team-member:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 44px rgba(12, 26, 56, 0.18);
}

/* ========== IMAGE AREA ========== */
#chefs .member-img {
    position: relative;
    /* width: 220px;
    height: 220px; */
    margin: 0 auto 20px auto;
    border-radius: 50%;
    overflow: hidden;
}

#chefs .member-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
    transition: transform 0.45s ease;
}

/* Image Zoom on Hover */
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

/* ========== SOCIAL OVERLAY (HIDDEN FIRST) ========== */
#chefs .social {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -42%);
    display: flex;
    gap: 12px;
    z-index: 2;
}

/* Social Icons */
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

/* ========== TEXT CONTENT ========== */
#chefs .member-info h4 {
    font-size: 1.35rem;
    margin-bottom: 6px;
    color: #222;
    font-weight: 600;
}

#chefs .member-info span {
    display: block;
    color: #4CAF50;
    font-size: 1rem;
    margin-bottom: 12px;
    font-weight: 500;
}

#chefs .member-info p {
    font-size: 0.95rem;
    color: #666;
    line-height: 1.6;
    margin: 0;
}

/* ========== RESPONSIVE FIXES ========== */

/* Tablet */
@media (max-width: 991px) {
    .member-img {
        /* width: 190px;
        height: 190px; */
    }

    .team-member {
        padding: 22px;
    }
}

/* Mobile */
@media (max-width: 767px) {
    section#chefs {
        padding: 40px 0;
    }

    .member-img {
        /* width: 170px;
        height: 170px; */
    }

    .team-member {
        text-align: center;
    }

    .member-info p {
        font-size: 0.92rem;
    }
}

/* Small Mobile */
@media (max-width: 480px) {
    .member-img {
        /* width: 150px;
        height: 150px; */
    }
}


   
    </style>
</head>

<body>
    <?php if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) { include('site-hader.php'); } ?>

 


    <!-- Chefs Section -->
    <section id="chefs" class="chefs section">

        <!-- Section Title -->
        <div class="container  text-center" data-aos="fade-up">
            <h5 class="section-title ff-secondary text-center text-primary fw-normal">Our Chefs</h5>
            <h2 class="text-green">Our <span class="description-title">Professional Chefs</span></h2>
        </div>
        <!-- <h2 class="mb-5 text-green">Our <span class=" mb-5 description-title">Professional Chefs</span></h2> -->

        <div class="container">

            <div class="row gy-4">

                <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="100">
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

                <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="200">
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

                <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="300">
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
    <!-- resturent section -->

 



    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script>
    window.onscroll = function() {
        const scrollBtn = document.getElementById("scrollToTopBtn");
        if (!scrollBtn) {
            return;
        }

        if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
            scrollBtn.style.display = "block";
        } else {
            scrollBtn.style.display = "none";
        }
    };

    // Scroll to top function
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
<?php if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) { include('site-footer.php'); } ?>
</body>

</html>


