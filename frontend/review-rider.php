<?php
include('config/constants.php');
include('config/blocked-check.php');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $delivery_boy_name = $_POST['delivery_boy_name'];
    $order_id = $_POST['order_id'];
    $username = $_POST['username'];
    $message = $_POST['message'];
    $review_star = $_POST['review_star'];
    $tip = isset($_POST['tip']) ? $_POST['tip'] : 0;

    if (empty($delivery_boy_name) || empty($order_id) || empty($username) || empty($message) || empty($review_star)) {
        echo "<div class='container mt-3'><div class='alert alert-danger mb-0'>All fields are required.</div></div>";
    } else {
        $sql = "INSERT INTO tbl_review (name, order_id, message, review_star, username, tip, created_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW())";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, 'sisssd', $delivery_boy_name, $order_id, $message, $review_star, $username, $tip);

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['add'] = "<div class='success'>Review Added Successfully.</div>";
                header('location:' . SITEURL . 'view-orders.php');
                exit();
            } else {
                echo "<div class='container mt-3'><div class='alert alert-danger mb-0'>Failed to add review: " . mysqli_error($conn) . "</div></div>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<div class='container mt-3'><div class='alert alert-danger mb-0'>Failed to prepare query: " . mysqli_error($conn) . "</div></div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Pasar-kita.com</title>
    <meta content="" name="keywords">
    <meta content="" name="description">

    <link rel="icon" type="image/png" href="images/logo2.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&family=Pacifico&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <style>
        .container-xxl {
            max-width: 100%;
        }

        .back-to-top {
            right: 0 !important;
            bottom: 27px !important;
        }

        .scroll-top-button:hover {
            background: #e69500;
        }

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

        .tip-error {
            color: #dc3545;
            display: none;
            margin-top: 6px;
            font-size: 0.86rem;
            font-weight: 600;
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
            color:#e69500;
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
    </style>
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
</div>

        <div class="container-xxl position-relative p-0">
            <?php include('site-hader.php'); ?>
        </div>

        <?php
        if (isset($_POST['review_rider']) || isset($_POST['submit'])) {
            $order_id = isset($_POST['order_id']) ? (int) $_POST['order_id'] : 0;
            if ($order_id <= 0) {
                header('location:' . SITEURL . 'view-orders.php');
                exit();
            }

            $sql = "SELECT delivery_boy_name FROM order_manager WHERE order_id = ?";
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'i', $order_id);
                mysqli_stmt_execute($stmt);
                $res = mysqli_stmt_get_result($stmt);
                mysqli_stmt_close($stmt);
            } else {
                $res = false;
            }

            if ($res && mysqli_num_rows($res) > 0) {
                $row = mysqli_fetch_assoc($res);
                $delivery_boy_name = $row['delivery_boy_name'];
                $username = isset($_SESSION['name']) ? $_SESSION['name'] : (isset($_POST['username']) ? $_POST['username'] : '');
            } else {
                $_SESSION['fetch_error'] = "<div class='error'>Failed to fetch delivery boy details.</div>";
                header('location:' . SITEURL . 'view-orders.php');
                exit();
            }
        } else {
            header('location:' . SITEURL . 'view-orders.php');
            exit();
        }

        $form_message = isset($_POST['message']) ? $_POST['message'] : '';
        $form_star = isset($_POST['review_star']) ? $_POST['review_star'] : '';
        $form_tip = isset($_POST['tip']) ? $_POST['tip'] : '';
        ?>

        <section class="container-xxl py-5 review-page">
            <div class="container review-shell">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h5 class="section-title ff-secondary text-center text-primary fw-normal">Review Rider</h5>
                    <h1 class="review-title mb-2">Add Delivery Boy Review</h1>
                    <p class="review-subtitle mb-4">Share your delivery experience to help us improve service quality for every order.</p>
                </div>

                <?php if (isset($_SESSION['fetch_error'])) { ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['fetch_error']; ?></div>
                    <?php unset($_SESSION['fetch_error']); ?>
                <?php } ?>

                <div class="card review-card border-0 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="card-body p-4 p-lg-5">
                        <form action="" method="POST" id="riderReviewForm" novalidate>
                            <input type="hidden" name="delivery_boy_name" value="<?php echo htmlspecialchars($delivery_boy_name); ?>">
                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
                            <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">

                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <div class="review-meta">
                                        <span class="label">Delivery Boy</span>
                                        <span class="value"><?php echo htmlspecialchars($delivery_boy_name); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="review-meta">
                                        <span class="label">Order ID</span>
                                        <span class="value"><?php echo htmlspecialchars($order_id); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="review-meta">
                                        <span class="label">Ordered By</span>
                                        <span class="value"><?php echo htmlspecialchars($username); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea name="message" id="message" class="form-control" placeholder="Write your delivery experience..." required><?php echo htmlspecialchars($form_message); ?></textarea>
                                    <div class="invalid-feedback">Message is required.</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="review_star" class="form-label">Review Stars</label>
                                    <input type="hidden" name="review_star" id="review_star" value="<?php echo htmlspecialchars($form_star); ?>">
                                    <div class="star-rating" id="starRating" aria-label="Choose review stars">
                                        <button type="button" class="star-btn" data-value="1" aria-label="1 star">&#9733;</button>
                                        <button type="button" class="star-btn" data-value="2" aria-label="2 stars">&#9733;</button>
                                        <button type="button" class="star-btn" data-value="3" aria-label="3 stars">&#9733;</button>
                                        <button type="button" class="star-btn" data-value="4" aria-label="4 stars">&#9733;</button>
                                        <button type="button" class="star-btn" data-value="5" aria-label="5 stars">&#9733;</button>
                                    </div>
                                    <div class="star-error" id="reviewStarError">Please select a star rating.</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="tip" class="form-label">Tip Amount (Optional)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">&#8377;</span>
                                        <input type="number" name="tip" id="tip" class="form-control" step="0.01" min="0" placeholder="Enter tip amount" value="<?php echo htmlspecialchars($form_tip); ?>">
                                    </div>
                                    <div class="tip-error" id="tipError">Tip amount must be a valid non-negative number.</div>
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

        <?php include('chatbot.php'); ?>

        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

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
    <script src="js/main.js"></script>

    <script>
        (function () {
            const form = document.getElementById("riderReviewForm");
            if (!form) {
                return;
            }

            const starInput = document.getElementById("review_star");
            const starRating = document.getElementById("starRating");
            const starButtons = starRating ? Array.from(starRating.querySelectorAll(".star-btn")) : [];
            const reviewStarError = document.getElementById("reviewStarError");
            const tipInput = document.getElementById("tip");
            const tipError = document.getElementById("tipError");
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
                tipError.style.display = "none";
                reviewStarError.style.display = "none";

                if (!form.checkValidity()) {
                    valid = false;
                }

                const starValue = parseInt(starInput.value, 10);
                if (!Number.isInteger(starValue) || starValue < 1 || starValue > 5) {
                    valid = false;
                    reviewStarError.style.display = "block";
                }

                if (tipInput.value !== "" && (isNaN(tipInput.value) || parseFloat(tipInput.value) < 0)) {
                    valid = false;
                    tipError.style.display = "block";
                }

                if (!valid) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                form.classList.add("was-validated");
            });
        })();
    </script>
<?php include('site-footer.php'); ?>
</body>

</html>

