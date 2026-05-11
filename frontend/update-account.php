<?php include('config/constants.php'); 
include('config/blocked-check.php');  ?>

<?php 
 date_default_timezone_set('Asia/Dhaka');
 if(!isset($_SESSION['user'])) //If user session is not set
{
    //User is not logged in
    //Redirect to login page with message

    $_SESSION['no-login-message'] = "<div class='error'>Please login to access Admin Panel</div>";
    header('location:'.SITEURL.'login.php');
}

    if(isset($_SESSION['user']))
    {
       $username = $_SESSION['user'];

       $fetch_user = "SELECT * FROM tbl_users WHERE username = '$username'";

       $res_fetch_user = mysqli_query($conn, $fetch_user);

       while($rows=mysqli_fetch_assoc($res_fetch_user))
       {
           $id = $rows['id'];
           $name = $rows['name'];
           $email = $rows['email'];
           $add1 = $rows['add1'];
           $city = $rows['city'];
           $phone = $rows['phone'];
           $username = $rows['username'];
           $password = $rows['password'];

       }
    }

    $displayName = trim((string) ($name ?? ''));
    $displayUsername = trim((string) ($username ?? ''));
    $avatarName = $displayName !== '' ? $displayName : $displayUsername;
    if ($avatarName === '') {
        $avatarName = 'U';
    }
    if (function_exists('mb_substr') && function_exists('mb_strtoupper')) {
        $profileInitial = mb_strtoupper(mb_substr($avatarName, 0, 1, 'UTF-8'), 'UTF-8');
    } else {
        $profileInitial = strtoupper(substr($avatarName, 0, 1));
    }
?>

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
        .container-xxl {
            max-width: 100%;
        }

        :root {
            --card-bg: #ffffff;
            --title-color: #0f214a;
            --muted-text: #5f6b86;
            --border-soft: #e6eaf4;
        }

        .scroll-top-button:hover {
            background: #e69500;
        }

        .back-to-top {
            right: 0 !important;
            bottom: 27px !important;
        }

        .account-shell {
            padding-top: 12px;
            padding-bottom: 28px;
        }

        .profile-panel,
        .account-main-card {
            border: 1px solid var(--border-soft);
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 14px 36px rgba(15, 33, 74, 0.08);
        }

        .profile-panel {
            overflow: hidden;
        }

        .profile-top {
            padding: 26px 24px 20px;
            text-align: center;
            background: #e69500;
            color: #fff;
        }

        .profile-avatar {
            width: 98px;
            height: 98px;
            border-radius: 999px;
            border: 4px solid rgba(255, 255, 255, 0.24);
            margin: 0 auto 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eef1f6;
            color: #5f6b86;
            font-size: 2.2rem;
            font-weight: 800;
            text-transform: uppercase;
            text-decoration: none;
            line-height: 1;
        }

        .profile-avatar:hover,
        .profile-avatar:focus {
            color: #324263;
            text-decoration: none;
        }

        .profile-top h1 {
            font-size: 1.45rem;
            margin: 0;
            word-break: break-word;
        }

        .profile-menu {
            padding: 18px;
        }

        .profile-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #22345e;
            border: 1px solid var(--border-soft);
            border-radius: 12px;
            padding: 11px 12px;
            font-weight: 700;
            text-decoration: none;
            margin-bottom: 10px;
            transition: all 0.2s ease;
        }

        .profile-menu a:hover,
        .profile-menu a.active {
            color: #fff;
            border-color: transparent;
            background: linear-gradient(90deg, #fea116, #f57f17);
        }

        .account-main-card {
            padding: 22px;
            min-height: 100%;
        }

        .account-heading h2 {
            margin: 0 0 4px;
            color: var(--title-color);
            font-size: clamp(1.3rem, 2vw, 1.9rem);
            font-weight: 800;
        }

        .account-heading p {
            margin: 0 0 18px;
            color: var(--muted-text);
            font-weight: 600;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .field-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .field-group.full {
            grid-column: 1 / -1;
        }

        .field-group label {
            font-size: 0.85rem;
            font-weight: 700;
            color: #445983;
            margin: 0;
        }

        .field-group input,
        .field-group textarea {
            border: 1px solid var(--border-soft);
            border-radius: 12px;
            padding: 10px 14px;
            font-weight: 600;
            color: #1b2e56;
        }

        .field-group input {
            height: 46px;
        }

        .field-group textarea {
            resize: vertical;
            min-height: 110px;
        }

        .field-group input:focus,
        .field-group textarea:focus {
            border-color: #fea116;
            box-shadow: 0 0 0 0.2rem rgba(254, 161, 22, 0.2);
            outline: 0;
        }

        .error-message {
            color: #d93025;
            font-size: 0.8rem;
            min-height: 18px;
        }

        .btn-submit {
            border-radius: 999px;
            font-weight: 700;
            padding: 11px 18px;
        }

        @media (max-width: 575.98px) {
            .account-main-card {
                padding: 16px;
            }

            .form-grid {
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
        <div class="container bootstrap snippets bootdey account-shell">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="profile-panel">
                        <div class="profile-top">
                            <a href="myaccount.php" class="profile-avatar" aria-label="Profile avatar">
                                <?php echo htmlspecialchars($profileInitial); ?>
                            </a>
                            <h1><?php echo htmlspecialchars($name); ?></h1>
                        </div>
                        <div class="profile-menu">
                            <a href="update-account.php" class="active"><i class="fa fa-user-edit"></i> Edit Profile</a>
                            <a href="view-orders.php"><i class="fa fa-shopping-bag"></i> View Orders</a>
                            <a href="update-password.php"><i class="fa fa-lock"></i> Change Password</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="account-main-card">
                        <div class="account-heading">
                            <h2>Edit Profile</h2>
                            <p>Update your profile details and keep your account information current.</p>
                        </div>

                        <form action="" method="POST" id="accountForm">
                            <div class="form-grid">
                                <div class="field-group">
                                    <label for="cus_name">Name</label>
                                    <input type="text" name="cus_name" id="cus_name" value="<?php echo htmlspecialchars($name); ?>">
                                </div>
                                <div class="field-group">
                                    <label for="cus_email">Email</label>
                                    <input type="text" name="cus_email" id="cus_email" value="<?php echo htmlspecialchars($email); ?>">
                                </div>
                                <div class="field-group full">
                                    <label for="cus_add1">Address</label>
                                    <textarea name="cus_add1" id="cus_add1" rows="4"><?php echo htmlspecialchars($add1); ?></textarea>
                                </div>
                                <div class="field-group">
                                    <label for="cus_city">City</label>
                                    <input type="text" name="cus_city" id="cus_city" value="<?php echo htmlspecialchars($city); ?>">
                                </div>
                                <div class="field-group">
                                    <label for="cus_phone">Phone</label>
                                    <input type="text" name="cus_phone" id="cus_phone" value="<?php echo htmlspecialchars($phone); ?>">
                                </div>
                                <div class="field-group full">
                                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
                                    <button type="submit" name="submit" class="btn btn-primary btn-submit">Update Profile</button>
                                </div>
                            </div>
                        </form>

                        <?php
                            if(isset($_POST['submit']))
                            {
                                $username = $_POST['username'];
                                $cus_name = $_POST['cus_name'];
                                $cus_email = $_POST['cus_email'];
                                $cus_add1 = $_POST['cus_add1'];
                                $cus_city = $_POST['cus_city'];
                                $cus_phone = $_POST['cus_phone'];

                                $update_account = "UPDATE tbl_users SET
                                name = '$cus_name',
                                email = '$cus_email',
                                add1 = '$cus_add1',
                                city = '$cus_city',
                                phone = '$cus_phone'
                                WHERE username='$username'
                                ";

                                $res_update_account = mysqli_query($conn, $update_account);
                                if($res_update_account == true)
                                {
                                    $_SESSION['update'] = "<div class='success'>Account Updated Successfully</div>";
                                    header('location:'.SITEURL.'myaccount.php');
                                }
                                else
                                {
                                    $_SESSION['update'] = "<div class='error'>Failed to Update Account</div>";
                                    header('location:'.SITEURL.'myaccount.php');
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("accountForm").addEventListener("submit", function (event) {
        // Clear previous error messages
        document.querySelectorAll(".error-message").forEach(el => el.remove());

        let isValid = true;

        // Get field values
        let name = document.querySelector("[name='cus_name']").value.trim();
        let email = document.querySelector("[name='cus_email']").value.trim();
        let address = document.querySelector("[name='cus_add1']").value.trim();
        let city = document.querySelector("[name='cus_city']").value.trim();
        let phone = document.querySelector("[name='cus_phone']").value.trim();

        // Validation function
        function showError(inputName, message) {
            let inputField = document.querySelector(`[name='${inputName}']`);
            let errorElement = document.createElement("span");
            errorElement.className = "error-message";
            errorElement.style.color = "red";
            errorElement.style.display = "block";
            errorElement.style.marginTop = "5px";
            errorElement.innerText = message;
            inputField.parentNode.appendChild(errorElement);
        }

        // Name validation
        if (name === "") {
            showError("cus_name", "Name is required.");
            isValid = false;
        }

        // Email validation
        let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email === "") {
            showError("cus_email", "Email is required.");
            isValid = false;
        } else if (!emailPattern.test(email)) {
            showError("cus_email", "Enter a valid email.");
            isValid = false;
        }

        // Address validation
        if (address === "") {
            showError("cus_add1", "Address is required.");
            isValid = false;
        }

        // City validation
        if (city === "") {
            showError("cus_city", "City is required.");
            isValid = false;
        }

        // Phone validation
        let phonePattern = /^[0-9]{10}$/;
        if (phone === "") {
            showError("cus_phone", "Phone number is required.");
            isValid = false;
        } else if (!phonePattern.test(phone)) {
            showError("cus_phone", "Enter a valid 10-digit phone number.");
            isValid = false;
        }

        // **Submit form only if all fields are valid**
        if (!isValid) {
            event.preventDefault(); // Stop form submission if there are errors
        }
    });
});

</script>


 
        <!-- Categories End  -->
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


