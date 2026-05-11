<?php 
include('config/constants.php');
include('config/blocked-check.php'); 

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style-admin.css">
    <link rel="icon" type="image/png" href="images/logo2.png">
    <title>Pasar-kita.com</title>
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
.scroll-top-button:hover {
    background: #e69500;
}
.back-to-top{
    right:0px!important;
    bottom:27px !important;
}

/* Review form aligned with site theme */
.review-page {
    background: linear-gradient(180deg, #f6f8fc 0%, #ffffff 100%);
}
.review-shell {
    max-width: 920px;
    margin: 0 auto;
}
.review-title {
    color: #0f172f;
    font-weight: 800;
}
.review-subtitle {
    color: #6b7280;
    margin: 0 auto;
    max-width: 640px;
}
.review-card {
    border-radius: 20px;
    box-shadow: 0 14px 34px rgba(15, 23, 43, 0.1);
    overflow: hidden;
}
.review-meta {
    border: 1px solid #e9eef7;
    border-radius: 14px;
    padding: 14px 16px;
    background: #ffffff;
}
.review-meta .label {
    display: block;
    color: #6b7280;
    font-size: 0.82rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.2px;
    margin-bottom: 5px;
}
.review-meta .value {
    color: #0f172f;
    font-size: 1.03rem;
    font-weight: 700;
    line-height: 1.3;
    word-break: break-word;
}
.review-card .form-label {
    color: #0f172f;
    font-weight: 700;
    margin-bottom: 8px;
}
.review-card .form-control,
.review-card .form-select,
.review-card .input-group-text {
    border-radius: 12px;
    border-color: #dbe4f0;
    min-height: 48px;
}
.review-card .form-control:focus,
.review-card .form-select:focus {
    border-color: #fea116;
    box-shadow: 0 0 0 0.18rem rgba(254, 161, 22, 0.2);
}
.review-card textarea.form-control {
    min-height: 150px;
    resize: vertical;
}
.star-rating {
    display: flex;
    align-items: center;
    gap: 2px;
    min-height: 48px;
    padding: 4px 0;
}
.star-btn {
    border: 0;
    background: transparent;
    color: #d4d9e6;
    font-size: 1.85rem;
    line-height: 1;
    padding: 0 2px;
    cursor: pointer;
    transition: color 0.16s ease, transform 0.16s ease;
}
.star-btn:hover {
    transform: translateY(-1px);
}
.star-btn.active {
    color: #e69500;
}
.star-btn:focus-visible {
    outline: 2px solid rgba(111, 66, 193, 0.35);
    outline-offset: 2px;
    border-radius: 6px;
}
.star-error {
    color: #dc3545;
    display: none;
    margin-top: 6px;
    font-size: 0.86rem;
    font-weight: 600;
}
@media (max-width: 575.98px) {
    .review-card .card-body {
        padding: 22px 16px !important;
    }
}
/* Force breadcrumb bar to span full viewport width on this page */
.site-breadcrumb-wrap {
    width: 100vw;
    margin-left: calc(50% - 50vw);
    margin-right: calc(50% - 50vw);
}
body {
    overflow-x: hidden;
}
</style>
</head>
<body>

<div class="container-xxl bg-white p-0">
        <div class="container-xxl position-relative p-0">
            <?php include('site-hader.php'); ?>
        </div>
</div>
<?php 

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if restaurant name is passed in URL
if(isset($_GET['restro_name'])) {
    $restro_name = urldecode($_GET['restro_name']);
} else {
    $_SESSION['error'] = "<div class='error'>Restaurant name is missing!</div>";
    header('location:'.SITEURL.'restaurant.php');
    exit();
}

// Fetch customer name from session
if(isset($_SESSION['name'])) {
    $customer_name = $_SESSION['name'];
} else {
    $_SESSION['error'] = "<div class='error'>You need to log in first!</div>";
    header('location:'.SITEURL.'login.php');
    exit();
}

// Handle form submission
if(isset($_POST['submit'])) {
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $rating_star = $_POST['rating_star'];

    // Insert review into database
    $insert_sql = "INSERT INTO tbl_review_restro (customer_name, restro_name, description, rating_star, created_at) 
                   VALUES ('$customer_name', '$restro_name', '$description', '$rating_star', NOW())";

    $result = mysqli_query($conn, $insert_sql);

    if($result) {
        $_SESSION['success'] = "<div class='success'>Review added successfully!</div>";
        header('location:'.SITEURL.'restaurant.php');
        exit();
    } else {
        $_SESSION['error'] = "<div class='error'>Failed to add review. Error: " . mysqli_error($conn) . "</div>";
    }
}

$form_description = isset($_POST['description']) ? $_POST['description'] : '';
$form_star = isset($_POST['rating_star']) ? $_POST['rating_star'] : '';
?>

<main>
    <section class="container-xxl py-5 review-page">
        <div class="container review-shell">
            <div class="text-center mb-4">
                <h5 class="section-title ff-secondary text-center text-primary fw-normal">Review Restro</h5>
                <h1 class="review-title mb-2">Add Restaurant Review</h1>
                <p class="review-subtitle mb-4">Share your dining experience to help others choose the best food.</p>
            </div>

            <?php 
            if(isset($_SESSION['error'])) {
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            }
            ?>

            <div class="card review-card border-0">
                <div class="card-body p-4 p-lg-5">
                    <form action="" method="POST" id="restroReviewForm" novalidate>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="review-meta">
                                    <span class="label">Customer</span>
                                    <span class="value"><?php echo htmlspecialchars($customer_name); ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="review-meta">
                                    <span class="label">Restaurant</span>
                                    <span class="value"><?php echo htmlspecialchars($restro_name); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label for="description" class="form-label">Message</label>
                                <textarea name="description" id="description" class="form-control" placeholder="Write your dining experience..." required><?php echo htmlspecialchars($form_description); ?></textarea>
                                <div class="invalid-feedback">Message is required.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="rating_star" class="form-label">Review Stars</label>
                                <input type="hidden" name="rating_star" id="rating_star" value="<?php echo htmlspecialchars($form_star); ?>">
                                <div class="star-rating" id="starRating" aria-label="Choose review stars">
                                    <button type="button" class="star-btn" data-value="1" aria-label="1 star">&#9733;</button>
                                    <button type="button" class="star-btn" data-value="2" aria-label="2 stars">&#9733;</button>
                                    <button type="button" class="star-btn" data-value="3" aria-label="3 stars">&#9733;</button>
                                    <button type="button" class="star-btn" data-value="4" aria-label="4 stars">&#9733;</button>
                                    <button type="button" class="star-btn" data-value="5" aria-label="5 stars">&#9733;</button>
                                </div>
                                <div class="star-error" id="reviewStarError">Please select a star rating.</div>
                            </div>

                            <div class="col-12 pt-2">
                                <button type="submit" name="submit" class="btn btn-primary py-3 px-5">Submit Review</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
<script>
    (function () {
        const form = document.getElementById("restroReviewForm");
        if (!form) {
            return;
        }

        const starInput = document.getElementById("rating_star");
        const starRating = document.getElementById("starRating");
        const starButtons = starRating ? Array.from(starRating.querySelectorAll(".star-btn")) : [];
        const reviewStarError = document.getElementById("reviewStarError");

        let selectedStars = parseInt(starInput.value, 10);
        selectedStars = Number.isInteger(selectedStars) && selectedStars >= 1 && selectedStars <= 5 ? selectedStars : 0;

        function paintStars(value) {
            starButtons.forEach(function (btn) {
                btn.classList.toggle("active", Number(btn.dataset.value) <= value);
            });
        }

        if (starButtons.length > 0) {
            paintStars(selectedStars);

            starButtons.forEach(function (btn) {
                btn.addEventListener("mouseenter", function () {
                    paintStars(Number(btn.dataset.value));
                });

                btn.addEventListener("focus", function () {
                    paintStars(Number(btn.dataset.value));
                });

                btn.addEventListener("click", function () {
                    selectedStars = Number(btn.dataset.value);
                    starInput.value = String(selectedStars);
                    paintStars(selectedStars);
                    reviewStarError.style.display = "none";
                });
            });

            starRating.addEventListener("mouseleave", function () {
                paintStars(selectedStars);
            });
        }

        form.addEventListener("submit", function (e) {
            let valid = true;
            reviewStarError.style.display = "none";

            if (!form.checkValidity()) {
                valid = false;
            }

            const starValue = parseInt(starInput.value, 10);
            if (!Number.isInteger(starValue) || starValue < 1 || starValue > 5) {
                valid = false;
                reviewStarError.style.display = "block";
            }

            if (!valid) {
                e.preventDefault();
                e.stopPropagation();
            }

            form.classList.add("was-validated");
        });
    })();
</script>
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
