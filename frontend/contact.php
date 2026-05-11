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
        body {
            background: #ffffff !important;
        }

        .container-xxl {
            max-width: 100% !important;
        }

        .container-xxl.position-relative.p-0 {
            width: 100%;
        }

.scroll-top-button:hover {
    background: #e69500;
}
.back-to-top{
    right:0px!important;
    bottom:27px !important;
}

.contact-kicker {
    position: relative;
    display: inline-block;
    font-family: "Pacifico", cursive;
    color: #0d6efd;
    font-size: 1.15rem;
    font-weight:500;
    line-height: 1.1;
    padding: 0 92px;
    margin-bottom: 12px;
}

.contact-kicker::before,
.contact-kicker::after {
    content: "";
    position: absolute;
    top: 55%;
    width: 74px;
    height: 2px;
    background: #fea116;
}

.contact-kicker::before { left: 8px; }
.contact-kicker::after { right: 8px; }

.contact-main-title {
    color: #0f172f;
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 800;
    margin-bottom: 50px !important;
}

.contact-subhead {
    position: relative;
    display: inline-block;
    font-family: "Pacifico", cursive;
    color: #0d6efd;
    font-size: 1.15rem;
    font-weight:500;
    line-height: 1.1;
    padding: 0 72px;
    margin-bottom: 10px;
}

.contact-subhead::before,
.contact-subhead::after {
    content: "";
    position: absolute;
    top: 55%;
    width: 64px;
    height: 2px;
    background: #fea116;
}

.contact-subhead::before { left: 0; }
.contact-subhead::after { right: 0; }

.contact-info-item p {
    margin-bottom: 0;
}

.contact-email {
    word-break: break-word;
}

.contact-map {
    position: relative;
    width: 100%;
    min-height: 280px;
    aspect-ratio: 4 / 3;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 10px 26px rgba(0, 0, 0, 0.08);
}

.contact-map iframe {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    border: 0;
    display: block;
}

@media (max-width: 991.98px) {
    .contact-main-title {
        margin-bottom: 32px !important;
    }
}

@media (max-width: 767.98px) {
    .contact-kicker,
    .contact-subhead {
        font-size: 1rem;
        padding: 0 56px;
    }

    .contact-kicker::before,
    .contact-kicker::after {
        width: 44px;
    }

    .contact-subhead::before,
    .contact-subhead::after {
        width: 40px;
    }

    .contact-info-item {
        text-align: center;
    }
}

@media (max-width: 575.98px) {
    .contact-kicker,
    .contact-subhead {
        padding: 0 40px;
    }

    .contact-kicker::before,
    .contact-kicker::after {
        width: 28px;
    }

    .contact-subhead::before,
    .contact-subhead::after {
        width: 24px;
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
        <!-- Contact Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h5 class="contact-kicker">Contact Us</h5>
                    <h1 class="contact-main-title">Contact For Any Query</h1>
                </div>
                <div class="row g-4">
                    <div class="col-12">
                        <div class="row gy-4">
                            <div class="col-lg-4 col-md-6 contact-info-item">
                                <h5 class="contact-subhead">Food Order</h5>
                                <p class="contact-email"><i class="fa fa-envelope-open text-primary me-2"></i>Food-order-management.com</p>
                            </div>
                            <div class="col-lg-4 col-md-6 contact-info-item">
                                <h5 class="contact-subhead">General</h5>
                                <p class="contact-email"><i class="fa fa-envelope-open text-primary me-2"></i>Food-management.com</p>
                            </div>
                            <div class="col-lg-4 col-md-6 contact-info-item">
                                <h5 class="contact-subhead">Technical</h5>
                                <p class="contact-email"><i class="fa fa-envelope-open text-primary me-2"></i>Technical-management.com</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                        <div class="contact-map">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3718.9052836838996!2d72.84992747503746!3d21.23560418046601!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be04f27ee8159e3%3A0xf6defb4d03e81080!2sSutex%20Bank%20College%20of%20Computer%20Applications%20%26%20Science!5e0!3m2!1sen!2sin!4v1737467331051!5m2!1sen!2sin" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="wow fadeInUp" data-wow-delay="0.2s">
                            <form action="message.php" method="POST">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" > 
                                            <label for="name">Your Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="email" name="phone" placeholder="Phone Number" >
                                            <label for="email">Phone Number</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" >
                                            <label for="subject">Subject</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea class="form-control" placeholder="Leave a message here" id="message" name="message" style="height: 150px" ></textarea>
                                            <label for="message">Message</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100 py-3" type="submit" name="submit_message">Send Message</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>

        <?php include('chatbot.php'); ?>

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    


<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("form").addEventListener("submit", function (e) {
        let isValid = true;

        // Remove existing error messages
        document.querySelectorAll(".error-message").forEach(el => el.remove());

        // Get form fields
        let name = document.getElementById("name");
        let phone = document.getElementById("email"); // This should be id="phone", update in your HTML
        let subject = document.getElementById("subject");
        let message = document.getElementById("message");

        // Validation function
        function showError(input, message) {
            let errorSpan = document.createElement("span");
            errorSpan.className = "error-message";
            errorSpan.style.color = "red";
            errorSpan.style.fontSize = "14px";
            errorSpan.style.display = "block";
            errorSpan.style.marginTop = "5px";
            errorSpan.innerText = message;
            input.parentNode.appendChild(errorSpan);
        }

        // Validate fields
        if (name.value.trim() === "") {
            showError(name, "Name is required.");
            isValid = false;
        }
        if (phone.value.trim() === "") {
            showError(phone, "Phone number is required.");
            isValid = false;
        }
        if (subject.value.trim() === "") {
            showError(subject, "Subject is required.");
            isValid = false;
        }
        if (message.value.trim() === "") {
            showError(message, "Message is required.");
            isValid = false;
        }

        // Prevent form submission if validation fails
        if (!isValid) {
            e.preventDefault();
        }
    });

    // Remove error message when user types
    document.querySelectorAll("input, textarea").forEach(input => {
        input.addEventListener("input", function () {
            if (this.nextSibling && this.nextSibling.className === "error-message") {
                this.nextSibling.remove();
            }
        });
    });
});
</script>


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


