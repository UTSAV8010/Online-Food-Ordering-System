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

        .field-group input {
            border: 1px solid var(--border-soft);
            border-radius: 12px;
            height: 46px;
            padding: 0 14px;
            font-weight: 600;
            color: #1b2e56;
        }

        .field-group input:focus {
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

        .password-field {
            position: relative;
        }

        .password-field input {
            padding-right: 44px;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            width: 34px;
            height: 34px;
            border: 0;
            padding: 0;
            margin: 0;
            background: transparent;
            color: var(--muted-text, #5f6b86);
            display: grid;
            place-items: center;
            cursor: pointer;
        }

        .password-toggle svg {
            width: 18px;
            height: 18px;
        }

        .password-toggle .eye-closed { display: none; }

        .password-toggle.is-visible .eye-open { display: none; }

        .password-toggle.is-visible .eye-closed { display: block; }

        .password-toggle.is-visible {
            color: #fea116;
        }

        .password-toggle:focus-visible {
            outline: 2px solid rgba(254, 161, 22, 0.4);
            outline-offset: 2px;
            border-radius: 8px;
        }
    </style>

</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
</div>
    <!-- Spinner End -->

        <div class="container-xxl position-relative p-0">
            <?php include('site-hader.php'); ?>
        </div>
    <?php 
    
    // Check if the form is submitted
    if(isset($_POST['submit'])){
       
       $username = $_POST['username'];
       $current_password = $_POST['current_password'];
       $new_password = $_POST['new_password'];
       $confirm_password = $_POST['confirm_password'];
    
       // Fetch the hashed password from the database
       $query = "SELECT password FROM tbl_users WHERE username='$username'";
       $result = mysqli_query($conn, $query);
    
       if ($result && mysqli_num_rows($result) == 1) {
           $row = mysqli_fetch_assoc($result);
           $hashed_password = $row['password'];
    
           // Verify the entered current password with the hashed password
           if (password_verify($current_password, $hashed_password)) {
    
               // Check if new password and confirm password match
               if ($new_password === $confirm_password) {
    
                   // Hash the new password
                   $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
    
                   // Update the password in the database
                   $update_query = "UPDATE tbl_users SET password='$hashed_new_password' WHERE username='$username'";
                   $update_result = mysqli_query($conn, $update_query);
    
                   if ($update_result) {
                       echo "<script>alert('Password Changed Successfully.'); window.location.href = 'myaccount.php';</script>";
                   } else {
                       echo "<script>alert('Failed to Change Password. Try Again.'); window.location.href = 'update-password.php';</script>";
                   }
               } else {
                   echo "<script>alert('Passwords Did Not Match.'); window.location.href = 'update-password.php';</script>";
               }
           } else {
               echo "<script>alert('Incorrect Current Password.'); window.location.href = 'update-password.php';</script>";
           }
       } else {
           echo "<script>alert('User Not Found.'); window.location.href = 'update-password.php';</script>";
       }
    }
    ?>
    
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
                        <a href="update-account.php"><i class="fa fa-user-edit"></i> Edit Profile</a>
                        <a href="view-orders.php"><i class="fa fa-shopping-bag"></i> View Orders</a>
                        <a href="update-password.php" class="active"><i class="fa fa-lock"></i> Change Password</a>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="account-main-card">
                    <div class="account-heading">
                        <h2>Change Password</h2>
                        <p>Use a strong password with uppercase, number and symbol.</p>
                    </div>
                    <form action="" method="POST" id="passwordForm">
                        <div class="form-grid">
                            <div class="field-group">
                                <label for="current_password">Current Password</label>
                                <input type="password" name="current_password" id="current_password">
                                <span class="error-message"></span>
                            </div>
                            <div class="field-group">
                                <label for="new_password">New Password</label>
                                <input type="password" name="new_password" id="new_password">
                                <span class="error-message"></span>
                            </div>
                            <div class="field-group full">
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" name="confirm_password" id="confirm_password">
                                <span class="error-message"></span>
                            </div>
                            <div class="field-group full">
                                <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
                                <button type="submit" name="submit" class="btn btn-primary btn-submit">Change Password</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("passwordForm").addEventListener("submit", function (event) {
            let isValid = true;
    
            // Clear previous error messages
            document.querySelectorAll(".error-message").forEach(el => el.innerText = "");
    
            const currentPasswordInput = document.getElementById("current_password");
            const newPasswordInput = document.getElementById("new_password");
            const confirmPasswordInput = document.getElementById("confirm_password");

            const currentPassword = currentPasswordInput.value.trim();
            const newPassword = newPasswordInput.value.trim();
            const confirmPassword = confirmPasswordInput.value.trim();

            function showError(input, message) {
                const group = input.closest(".field-group");
                const errorElement = group ? group.querySelector(".error-message") : null;
                if (!errorElement) return;
                errorElement.style.color = "red";
                errorElement.innerText = message;
            }
    
            // Validate Current Password
            if (currentPassword === "") {
                showError(currentPasswordInput, "Current password is required.");
                isValid = false;
            }
    
            // Validate New Password
            let passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            if (newPassword === "") {
                showError(newPasswordInput, "New password is required.");
                isValid = false;
            } else if (!passwordPattern.test(newPassword)) {
                showError(newPasswordInput, "Password must be at least 8 characters, contain one uppercase letter, one number, and one special character.");
                isValid = false;
            }
    
            // Validate Confirm Password
            if (confirmPassword === "") {
                showError(confirmPasswordInput, "Confirm password is required.");
                isValid = false;
            } else if (newPassword !== confirmPassword) {
                showError(confirmPasswordInput, "Passwords do not match.");
                isValid = false;
            }
    
            // Prevent form submission if validation fails
            if (!isValid) {
                event.preventDefault();
            }
        });
    });
    </script>
    <script>
    (function () {
        const iconMarkup = `
            <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z"></path>
                <circle cx="12" cy="12" r="3.2"></circle>
            </svg>
            <svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M3 5l18 14"></path>
                <path d="M10.7 10.8a3 3 0 004.2 4.2"></path>
                <path d="M6.2 7.4C4 9.1 2.5 12 2.5 12s3.5 6 9.5 6c2.1 0 3.9-.6 5.4-1.6"></path>
                <path d="M13.3 6.2A9.6 9.6 0 0012 6c-2.1 0-3.9.6-5.5 1.6"></path>
            </svg>
        `;

        const init = () => {
            document.querySelectorAll('input[type="password"]').forEach((input) => {
                if (input.closest('.password-field')) return;

                const wrapper = document.createElement('div');
                wrapper.className = 'password-field';
                input.parentNode.insertBefore(wrapper, input);
                wrapper.appendChild(input);

                const toggle = document.createElement('button');
                toggle.type = 'button';
                toggle.className = 'password-toggle';
                toggle.setAttribute('aria-label', 'Show password');
                toggle.setAttribute('aria-pressed', 'false');
                toggle.innerHTML = iconMarkup;
                wrapper.appendChild(toggle);

                const syncState = () => {
                    const isVisible = input.type === 'text';
                    toggle.classList.toggle('is-visible', isVisible);
                    toggle.setAttribute('aria-label', isVisible ? 'Hide password' : 'Show password');
                    toggle.setAttribute('aria-pressed', isVisible ? 'true' : 'false');
                };

                toggle.addEventListener('click', () => {
                    input.type = input.type === 'password' ? 'text' : 'password';
                    syncState();
                    input.focus({ preventScroll: true });
                });

                syncState();
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })();
    </script>
<div class="bg-dark">
        <?php include('chatbot.php'); ?>

        <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


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


