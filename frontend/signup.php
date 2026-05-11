<?php
require_once 'config/constants.php';

if (!function_exists('frontend_clear_signup_otp_state')) {
    function frontend_clear_signup_otp_state(): void
    {
        unset(
            $_SESSION['signup_pending_data'],
            $_SESSION['signup_pending_otp'],
            $_SESSION['signup_pending_otp_expires_at']
        );
    }
}

if (!function_exists('frontend_signup_identity_exists')) {
    function frontend_signup_identity_exists(mysqli $conn, string $username, string $email): bool
    {
        $stmt = $conn->prepare('SELECT id FROM tbl_users WHERE username = ? OR email = ? LIMIT 1');
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
}

if (!function_exists('frontend_issue_signup_otp')) {
    function frontend_issue_signup_otp(string $email): array
    {
        $mailResult = app_send_signup_otp_email($email);

        if ($mailResult['success']) {
            $_SESSION['signup_pending_otp'] = (string) $mailResult['otp'];
            $_SESSION['signup_pending_otp_expires_at'] = time() + APP_OTP_TTL_SECONDS;
        }

        return $mailResult;
    }
}

$message = '';
$messageClass = 'error';
$values = array(
    'name' => '',
    'username' => '',
    'email' => '',
    'phone' => '',
    'city' => '',
    'address' => '',
);

if (isset($_GET['restart'])) {
    frontend_clear_signup_otp_state();
    header('Location: signup.php');
    exit();
}

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$projectPath = preg_replace('#/frontend$#', '', rtrim($scriptDir, '/'));
$assetRoot = ($projectPath === '' ? '' : $projectPath) . '/images';

if (isset($_SESSION['signup_pending_data']) && is_array($_SESSION['signup_pending_data'])) {
    foreach ($values as $key => $value) {
        if (isset($_SESSION['signup_pending_data'][$key])) {
            $values[$key] = (string) $_SESSION['signup_pending_data'][$key];
        }
    }
}

$otpStepActive = isset($_SESSION['signup_pending_data'], $_SESSION['signup_pending_otp'], $_SESSION['signup_pending_otp_expires_at']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['send_signup_otp'])) {
        frontend_clear_signup_otp_state();

        $values['name'] = trim($_POST['name'] ?? '');
        $values['username'] = trim($_POST['username'] ?? '');
        $values['email'] = trim($_POST['email'] ?? '');
        $values['phone'] = trim($_POST['phone'] ?? '');
        $values['city'] = trim($_POST['city'] ?? '');
        $values['address'] = trim($_POST['address'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (
            $values['name'] === '' || $values['username'] === '' || $values['email'] === '' ||
            $values['phone'] === '' || $values['city'] === '' || $values['address'] === '' ||
            $password === '' || $confirmPassword === ''
        ) {
            $message = 'Please fill in all fields.';
        } elseif (!preg_match('/^[A-Za-z][A-Za-z\\s]{1,59}$/', $values['name'])) {
            $message = 'Name should contain only letters and spaces.';
        } elseif (!preg_match('/^[a-zA-Z0-9._-]{3,30}$/', $values['username'])) {
            $message = 'Username must be 3-30 characters using letters, numbers, dot, underscore or hyphen.';
        } elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
            $message = 'Please enter a valid email address.';
        } elseif (!preg_match('/^[0-9]{10,15}$/', $values['phone'])) {
            $message = 'Phone number must be 10 to 15 digits.';
        } elseif (!preg_match('/^[A-Za-z\\s]{2,50}$/', $values['city'])) {
            $message = 'Please enter a valid city name.';
        } elseif (strlen($values['address']) < 6 || strlen($values['address']) > 120) {
            $message = 'Address must be between 6 and 120 characters.';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[^A-Za-z\\d]).{8,64}$/', $password)) {
            $message = 'Password must be 8+ chars with uppercase, lowercase, number and special character.';
        } elseif ($password !== $confirmPassword) {
            $message = 'Password and confirm password do not match.';
        } elseif (frontend_signup_identity_exists($conn, $values['username'], $values['email'])) {
            $message = 'Username or email already exists.';
        } else {
            $mailResult = frontend_issue_signup_otp($values['email']);

            if ($mailResult['success']) {
                $_SESSION['signup_pending_data'] = array(
                    'name' => $values['name'],
                    'username' => $values['username'],
                    'email' => $values['email'],
                    'phone' => $values['phone'],
                    'city' => $values['city'],
                    'address' => $values['address'],
                    'password_hash' => password_hash($password, PASSWORD_BCRYPT),
                );
                $otpStepActive = true;
                $message = 'Signup OTP sent to your email. Verify it within ' . APP_OTP_TTL_SECONDS . ' seconds to create the account.';
                $messageClass = 'success';
            } else {
                $message = $mailResult['error'];
            }
        }
    } elseif (isset($_POST['resend_signup_otp'])) {
        if (!isset($_SESSION['signup_pending_data']['email'])) {
            $message = 'Signup session expired. Please fill the form again.';
            $otpStepActive = false;
        } else {
            $mailResult = frontend_issue_signup_otp((string) $_SESSION['signup_pending_data']['email']);

            if ($mailResult['success']) {
                $otpStepActive = true;
                $message = 'A new signup OTP has been sent. It expires in ' . APP_OTP_TTL_SECONDS . ' seconds.';
                $messageClass = 'success';
            } else {
                $message = $mailResult['error'];
                $otpStepActive = true;
            }
        }
    } elseif (isset($_POST['verify_signup_otp'])) {
        $otpStepActive = true;
        $otp = trim($_POST['signup_otp'] ?? '');

        if (!isset($_SESSION['signup_pending_data'], $_SESSION['signup_pending_otp'], $_SESSION['signup_pending_otp_expires_at'])) {
            $message = 'Signup session expired. Please fill the form again.';
            $otpStepActive = false;
        } elseif ($otp === '') {
            $message = 'OTP is required.';
        } elseif (!preg_match('/^[0-9]{6}$/', $otp)) {
            $message = 'OTP must be exactly 6 digits.';
        } elseif (time() > (int) $_SESSION['signup_pending_otp_expires_at']) {
            $message = 'Signup OTP expired. Click resend to get a new code.';
        } elseif ($otp !== (string) $_SESSION['signup_pending_otp']) {
            $message = 'OTP does not match.';
        } else {
            $pending = $_SESSION['signup_pending_data'];

            if (frontend_signup_identity_exists($conn, (string) $pending['username'], (string) $pending['email'])) {
                frontend_clear_signup_otp_state();
                $message = 'Username or email already exists. Please try another one.';
                $otpStepActive = false;
            } else {
                $stmt = $conn->prepare('INSERT INTO tbl_users (name, username, email, password, phone, add1, city) VALUES (?, ?, ?, ?, ?, ?, ?)');
                $stmt->bind_param(
                    'sssssss',
                    $pending['name'],
                    $pending['username'],
                    $pending['email'],
                    $pending['password_hash'],
                    $pending['phone'],
                    $pending['address'],
                    $pending['city']
                );

                if ($stmt->execute()) {
                    frontend_clear_signup_otp_state();
                    $_SESSION['auth-flash-success'] = 'Signup complete. OTP verified. You can log in now.';
                    header('Location: login.php');
                    exit();
                }

                $message = 'Error during signup. Please try again.';
            }
        }
    }
}

$pendingEmail = $_SESSION['signup_pending_data']['email'] ?? '';
$otpExpiresAt = (int) ($_SESSION['signup_pending_otp_expires_at'] ?? 0);

if ($otpStepActive && $otpExpiresAt > 0 && time() > $otpExpiresAt && $message === '') {
    $message = 'Signup OTP expired. Click resend to get a new code.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Pasar-kita</title>
    <link rel="icon" type="image/png" href="<?php echo htmlspecialchars($assetRoot . '/logo2.png', ENT_QUOTES, 'UTF-8'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-1: #0f224a;
            --bg-2: #173167;
            --accent: #e69500;
            --text: #121d35;
            --muted: #60708d;
            --border: #d6dfef;
            --danger: #dc3545;
            --success: #198754;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Nunito", "Segoe UI", sans-serif;
            background:
                radial-gradient(850px 520px at -10% 0%, rgba(230, 149, 0, .18), transparent 70%),
                radial-gradient(950px 560px at 115% 100%, rgba(13, 110, 253, .22), transparent 70%),
                linear-gradient(140deg, rgba(15, 34, 74, .42), rgba(23, 49, 103, .42)),
                url('<?php echo htmlspecialchars($assetRoot . '/login-page.jpg', ENT_QUOTES, 'UTF-8'); ?>') center / cover no-repeat fixed;
            padding: 18px;
            display: grid;
            place-items: center;
            color: var(--text);
        }

        .auth-shell {
            width: 100%;
            max-width: 780px;
            background: rgba(255, 255, 255, .96);
            border: 1px solid rgba(255, 255, 255, .6);
            border-radius: 22px;
            box-shadow: 0 24px 62px rgba(6, 16, 34, .38);
            padding: 28px 24px 22px;
            backdrop-filter: blur(8px);
        }

        .auth-shell.otp-stage {
            max-width: 560px;
        }

        .auth-head {
            text-align: center;
            margin-bottom: 14px;
        }

        .auth-head img {
            width: 44px;
            height: 44px;
            object-fit: contain;
            margin-bottom: 8px;
        }

        .auth-title {
            margin: 0 0 4px;
            font-size: clamp(1.4rem, 3.5vw, 2rem);
            font-weight: 900;
        }

        .auth-subtitle {
            margin: 0;
            color: var(--muted);
            font-size: .95rem;
        }

        .step-chip {
            margin: 12px auto 14px;
            background: #f3f7ff;
            border: 1px solid #dbe6fb;
            color: #355186;
            border-radius: 999px;
            width: fit-content;
            padding: 6px 11px;
            font-size: .8rem;
            font-weight: 800;
        }

        .server-box {
            margin-top: 12px;
            margin-bottom: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: .9rem;
        }

        .server-box.error {
            border: 1px solid rgba(220, 53, 69, .25);
            background: rgba(220, 53, 69, .1);
            color: #b12536;
        }

        .server-box.success {
            border: 1px solid rgba(25, 135, 84, .25);
            background: rgba(25, 135, 84, .11);
            color: #0f5a36;
        }

        .field-group { margin-bottom: 10px; }

        .field-label {
            font-size: .82rem;
            font-weight: 700;
            color: #3f4f6e;
            margin-bottom: 6px;
            display: block;
        }

        .field-input {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 11px 12px;
            outline: none;
            font-size: .95rem;
            background: #fff;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .field-input:focus {
            border-color: #7fa8ea;
            box-shadow: 0 0 0 4px rgba(64, 124, 224, .14);
        }

        .otp-hidden {
            position: absolute;
            opacity: 0;
            pointer-events: none;
            width: 1px;
            height: 1px;
        }

        .otp-panel {
            max-width: 432px;
            margin: 0 auto;
            padding: 8px 0 2px;
        }

        .otp-grid {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .otp-digit {
            width: 58px;
            min-width: 58px;
            flex: 0 0 58px;
            height: 58px;
            border: 1px solid #cfdaf0;
            border-radius: 14px;
            text-align: center;
            font-size: 1.35rem;
            font-weight: 800;
            color: #142b5f;
            background: #fff;
            outline: none;
            transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
        }

        .otp-digit::placeholder {
            color: #bcc8de;
            opacity: 1;
        }

        .otp-digit:focus {
            border-color: #7aa4ea;
            box-shadow: 0 0 0 4px rgba(64, 124, 224, .14);
            transform: translateY(-1px);
        }

        @media (max-width: 520px) {
            .auth-shell.otp-stage {
                max-width: 100%;
            }

            .otp-panel {
                max-width: 100%;
            }

            .otp-grid {
                gap: 8px;
            }

            .otp-digit {
                width: calc((100% - 40px) / 6);
                min-width: 0;
                flex: 1 1 0;
                height: 52px;
                font-size: 1.1rem;
            }
        }

        .field-error {
            min-height: 17px;
            margin-top: 3px;
            color: var(--danger);
            font-size: .76rem;
            display: block;
        }

        .otp-status-row {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            font-size: .82rem;
            color: #5e6d89;
        }

        .otp-timer-text {
            font-weight: 700;
        }

        .otp-timer-value {
            color: #173167;
            font-weight: 900;
        }

        .otp-resend-wrap {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .otp-link-btn {
            border: 0;
            padding: 0;
            background: transparent;
            color: #1f64d4;
            font-size: .82rem;
            font-weight: 800;
            cursor: pointer;
        }

        .otp-link-btn:hover {
            color: #0f4fb5;
            text-decoration: underline;
        }

        .otp-expired-note {
            display: none;
            margin-top: 10px;
            border-radius: 12px;
            padding: 10px 12px;
            background: rgba(220, 53, 69, 0.08);
            border: 1px solid rgba(220, 53, 69, 0.18);
            color: #b42334;
            font-size: .84rem;
            font-weight: 700;
        }

        .otp-expired-note.is-visible {
            display: block;
        }

        .hint {
            font-size: .76rem;
            color: #6b7892;
            margin-top: -2px;
            margin-bottom: 4px;
        }

        .auth-btn {
            width: 100%;
            border: 0;
            border-radius: 12px;
            padding: 11px 14px;
            font-size: .96rem;
            font-weight: 800;
            color: #fff;
            background: linear-gradient(135deg, #ffb326, #e69500);
            box-shadow: 0 14px 24px rgba(230, 149, 0, .28);
            margin-top: 4px;
        }

        .auth-btn[disabled] {
            cursor: not-allowed;
            opacity: .68;
            box-shadow: none;
            filter: saturate(.6);
        }

        .auth-footer {
            margin-top: 12px;
            text-align: center;
            font-size: .9rem;
            color: #53627f;
        }

        .auth-link {
            color: #1f64d4;
            text-decoration: none;
            font-weight: 700;
        }

        .auth-link:hover { text-decoration: underline; color: #0f4fb5; }

        /* .otp-meta {
            margin: 8px 0 14px;
            border-radius: 14px;
            padding: 14px;
            background: #f8fbff;
            border: 1px solid #d9e5f7;
            color: #46607f;
            font-size: .92rem;
            line-height: 1.7;
        } */

        .password-field {
            position: relative;
        }

        .password-field .field-input,
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
            color: var(--muted, #60708d);
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
    </style>
</head>
<body>
    <div class="auth-shell<?php echo $otpStepActive ? ' otp-stage' : ''; ?>">
        <div class="auth-head">
            <img src="<?php echo htmlspecialchars($assetRoot . '/logo2.png', ENT_QUOTES, 'UTF-8'); ?>" alt="Pasar-kita">
            <h1 class="auth-title"><?php echo $otpStepActive ? 'Verify Signup OTP' : 'Create Your Account'; ?></h1>
            <p class="auth-subtitle"><?php echo $otpStepActive ? 'Your account will be created only after OTP verification.' : 'Fill the form, receive OTP on email, and verify it before account creation.'; ?></p>
        </div>

        <?php if ($message !== ''): ?>
            <div class="server-box <?php echo htmlspecialchars($messageClass, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <?php if (!$otpStepActive): ?>
            <div class="step-chip">Step 1 of 2: Account Details</div>
            <form id="signupForm" method="post" novalidate>
                <div class="row">
                    <div class="col-md-6 field-group">
                        <label class="field-label" for="name">Full Name</label>
                        <input class="field-input" type="text" id="name" name="name" maxlength="60" value="<?php echo htmlspecialchars($values['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        <span class="field-error" id="error_name"></span>
                    </div>
                    <div class="col-md-6 field-group">
                        <label class="field-label" for="username">Username</label>
                        <input class="field-input" type="text" id="username" name="username" maxlength="30" value="<?php echo htmlspecialchars($values['username'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        <span class="field-error" id="error_username"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 field-group">
                        <label class="field-label" for="email">Email</label>
                        <input class="field-input" type="email" id="email" name="email" maxlength="120" value="<?php echo htmlspecialchars($values['email'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        <span class="field-error" id="error_email"></span>
                    </div>
                    <div class="col-md-6 field-group">
                        <label class="field-label" for="phone">Phone</label>
                        <input class="field-input" type="tel" id="phone" name="phone" maxlength="15" value="<?php echo htmlspecialchars($values['phone'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        <span class="field-error" id="error_phone"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 field-group">
                        <label class="field-label" for="city">City</label>
                        <input class="field-input" type="text" id="city" name="city" maxlength="50" value="<?php echo htmlspecialchars($values['city'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        <span class="field-error" id="error_city"></span>
                    </div>
                    <div class="col-md-6 field-group">
                        <label class="field-label" for="address">Address</label>
                        <input class="field-input" type="text" id="address" name="address" maxlength="120" value="<?php echo htmlspecialchars($values['address'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        <span class="field-error" id="error_address"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 field-group">
                        <label class="field-label" for="password">Password</label>
                        <input class="field-input" type="password" id="password" name="password" maxlength="64" required>
                        <div class="hint">8+ chars with upper, lower, number and special char.</div>
                        <span class="field-error" id="error_password"></span>
                    </div>
                    <div class="col-md-6 field-group">
                        <label class="field-label" for="confirm_password">Confirm Password</label>
                        <input class="field-input" type="password" id="confirm_password" name="confirm_password" maxlength="64" required>
                        <span class="field-error" id="error_confirm_password"></span>
                    </div>
                </div>

                <button type="submit" name="send_signup_otp" class="auth-btn">Send Signup OTP</button>
            </form>
        <?php else: ?>
            <div class="step-chip">Step 2 of 2: OTP Verification</div>
            <form id="signupOtpForm" method="post" novalidate>
                <div class="field-group">
                    <label class="field-label" for="signup_otp_digit_1">6-digit OTP</label>
                    <div class="otp-panel">
                        <input class="otp-hidden" type="text" id="signup_otp" name="signup_otp" maxlength="6" value="<?php echo isset($_POST['signup_otp']) ? htmlspecialchars($_POST['signup_otp'], ENT_QUOTES, 'UTF-8') : ''; ?>" inputmode="numeric" autocomplete="one-time-code" tabindex="-1" required>
                        <div class="otp-grid" data-otp-group data-target="signup_otp">
                            <input class="otp-digit" type="text" id="signup_otp_digit_1" inputmode="numeric" maxlength="1" placeholder="0" autocomplete="one-time-code">
                            <input class="otp-digit" type="text" inputmode="numeric" maxlength="1" placeholder="0">
                            <input class="otp-digit" type="text" inputmode="numeric" maxlength="1" placeholder="0">
                            <input class="otp-digit" type="text" inputmode="numeric" maxlength="1" placeholder="0">
                            <input class="otp-digit" type="text" inputmode="numeric" maxlength="1" placeholder="0">
                            <input class="otp-digit" type="text" inputmode="numeric" maxlength="1" placeholder="0">
                        </div>
                    </div>
                    <span class="field-error" id="error_signup_otp"></span>
                    <div class="otp-status-row" data-otp-status data-expires-at="<?php echo (int) $otpExpiresAt; ?>">
                        <div class="otp-timer-text">Remaining time: <span class="otp-timer-value" data-otp-timer>01:00</span></div>
                        <div class="otp-resend-wrap">
                            <span>Didn't get code?</span>
                            <button class="otp-link-btn" type="submit" name="resend_signup_otp" formnovalidate>Resend</button>
                        </div>
                    </div>
                    <div class="otp-expired-note" id="signupOtpExpiredNote">OTP expired. Click resend to get a new code.</div>
                </div>
                <div class="auth-footer" style="margin: 0 0 12px;">
                    Need to change details? <a class="auth-link" href="signup.php?restart=1">Start again</a>
                </div>
                <button type="submit" name="verify_signup_otp" class="auth-btn" data-otp-verify>Verify OTP and Create Account</button>
            </form>
        <?php endif; ?>

        <div class="auth-footer">
            Already have an account? <a class="auth-link" href="login.php">Log in</a>
        </div>
    </div>

    <script>
        (function () {
            const signupForm = document.getElementById('signupForm');
            const otpForm = document.getElementById('signupOtpForm');

            if (signupForm) {
                const patterns = {
                    name: /^[A-Za-z][A-Za-z\s]{1,59}$/,
                    username: /^[a-zA-Z0-9._-]{3,30}$/,
                    email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                    phone: /^[0-9]{10,15}$/,
                    city: /^[A-Za-z\s]{2,50}$/,
                    password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,64}$/
                };

                const fields = [
                    { id: 'name', msg: 'Enter valid full name (letters and spaces).' },
                    { id: 'username', msg: '3-30 chars: letters, numbers, dot, underscore, hyphen.' },
                    { id: 'email', msg: 'Enter valid email address.' },
                    { id: 'phone', msg: 'Phone must be 10 to 15 digits.' },
                    { id: 'city', msg: 'Enter valid city name.' },
                    { id: 'address', msg: 'Address must be 6 to 120 characters.' },
                    { id: 'password', msg: 'Use 8+ chars with upper, lower, number and special char.' }
                ];

                const errNode = (id) => document.getElementById('error_' + id);

                const checkField = (id) => {
                    const el = document.getElementById(id);
                    const value = (el.value || '').trim();
                    let valid = true;
                    let msg = '';

                    if (!value) {
                        valid = false;
                        msg = fields.find((f) => f.id === id).msg;
                    } else if (id === 'address') {
                        if (value.length < 6 || value.length > 120) {
                            valid = false;
                            msg = 'Address must be 6 to 120 characters.';
                        }
                    } else if (patterns[id] && !patterns[id].test(value)) {
                        valid = false;
                        msg = fields.find((f) => f.id === id).msg;
                    }

                    errNode(id).textContent = valid ? '' : msg;
                    return valid;
                };

                const checkConfirm = () => {
                    const pwd = document.getElementById('password').value;
                    const cp = document.getElementById('confirm_password').value;
                    const node = document.getElementById('error_confirm_password');

                    if (!cp.trim()) {
                        node.textContent = 'Confirm password is required.';
                        return false;
                    }
                    if (pwd !== cp) {
                        node.textContent = 'Password and confirm password do not match.';
                        return false;
                    }
                    node.textContent = '';
                    return true;
                };

                fields.forEach((field) => {
                    const input = document.getElementById(field.id);
                    input.addEventListener('input', () => {
                        checkField(field.id);
                        if (field.id === 'password') checkConfirm();
                    });
                });

                document.getElementById('confirm_password').addEventListener('input', checkConfirm);

                signupForm.addEventListener('submit', (event) => {
                    let ok = true;
                    fields.forEach((field) => {
                        ok = checkField(field.id) && ok;
                    });
                    ok = checkConfirm() && ok;
                    if (!ok) event.preventDefault();
                });
            }

            if (otpForm) {
                const otp = document.getElementById('signup_otp');
                const otpError = document.getElementById('error_signup_otp');
                const otpGroup = document.querySelector('[data-otp-group][data-target="signup_otp"]');
                const otpStatus = otpForm.querySelector('[data-otp-status]');
                const otpTimer = otpForm.querySelector('[data-otp-timer]');
                const expiredNote = document.getElementById('signupOtpExpiredNote');
                const verifyButton = otpForm.querySelector('[data-otp-verify]');
                let timerExpired = false;
                let countdownHandle = null;

                const validateOtp = () => {
                    const value = otp.value.trim();
                    if (!value) {
                        otpError.textContent = 'OTP is required.';
                        return false;
                    }
                    if (!/^[0-9]{6}$/.test(value)) {
                        otpError.textContent = 'OTP must be exactly 6 digits.';
                        return false;
                    }
                    otpError.textContent = '';
                    return true;
                };

                const setExpiredState = (expired) => {
                    timerExpired = expired;
                    if (verifyButton) {
                        verifyButton.disabled = expired;
                    }
                    if (expiredNote) {
                        expiredNote.classList.toggle('is-visible', expired);
                    }
                };

                const updateCountdown = () => {
                    if (!otpStatus || !otpTimer) {
                        return;
                    }

                    const expiresAt = Number(otpStatus.dataset.expiresAt || 0);
                    if (!expiresAt) {
                        return;
                    }

                    const remaining = expiresAt - Math.floor(Date.now() / 1000);
                    if (remaining <= 0) {
                        otpTimer.textContent = '00:00';
                        setExpiredState(true);
                        if (countdownHandle) {
                            clearInterval(countdownHandle);
                            countdownHandle = null;
                        }
                        return;
                    }

                    const minutes = String(Math.floor(remaining / 60)).padStart(2, '0');
                    const seconds = String(remaining % 60).padStart(2, '0');
                    otpTimer.textContent = `${minutes}:${seconds}`;
                    setExpiredState(false);
                };

                if (otpGroup) {
                    const digits = Array.from(otpGroup.querySelectorAll('.otp-digit'));
                    const focusDigit = (index) => {
                        if (digits[index]) {
                            digits[index].focus();
                            digits[index].select();
                        }
                    };

                    const syncDigitsFromValue = (value) => {
                        const sanitized = (value || '').replace(/\D/g, '').slice(0, digits.length);
                        digits.forEach((digit, index) => {
                            digit.value = sanitized[index] || '';
                        });
                        otp.value = sanitized;
                        return sanitized;
                    };

                    const focusFirstEmptyDigit = () => {
                        const nextIndex = digits.findIndex((input) => input.value === '');
                        focusDigit(nextIndex === -1 ? digits.length - 1 : nextIndex);
                    };

                    const syncOtpValue = () => {
                        otp.value = digits.map((input) => input.value).join('');
                    };

                    syncDigitsFromValue(otp.value);

                    otp.addEventListener('input', () => {
                        syncDigitsFromValue(otp.value);
                        focusFirstEmptyDigit();
                        validateOtp();
                    });

                    otpGroup.addEventListener('click', (event) => {
                        if (!event.target.classList.contains('otp-digit')) {
                            focusFirstEmptyDigit();
                        }
                    });

                    digits.forEach((input, index) => {
                        input.addEventListener('focus', () => {
                            input.select();
                        });

                        input.addEventListener('input', () => {
                            input.value = input.value.replace(/\D/g, '').slice(0, 1);
                            syncOtpValue();
                            if (input.value && digits[index + 1]) {
                                digits[index + 1].focus();
                            }
                            validateOtp();
                        });

                        input.addEventListener('keydown', (event) => {
                            if (event.key === 'Backspace' && input.value === '' && digits[index - 1]) {
                                digits[index - 1].focus();
                            }
                            if (event.key === 'ArrowLeft' && digits[index - 1]) {
                                event.preventDefault();
                                digits[index - 1].focus();
                            }
                            if (event.key === 'ArrowRight' && digits[index + 1]) {
                                event.preventDefault();
                                digits[index + 1].focus();
                            }
                        });

                        input.addEventListener('paste', (event) => {
                            event.preventDefault();
                            const pasted = (event.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, digits.length);
                            syncDigitsFromValue(pasted);
                            const focusIndex = pasted.length < digits.length ? pasted.length : digits.length - 1;
                            focusDigit(focusIndex);
                            validateOtp();
                        });
                    });
                }

                updateCountdown();
                if (otpStatus && otpTimer) {
                    countdownHandle = window.setInterval(updateCountdown, 1000);
                }

                otpForm.addEventListener('submit', (event) => {
                    const submitter = event.submitter;
                    if (submitter && submitter.name === 'resend_signup_otp') {
                        return;
                    }

                    if (timerExpired) {
                        otpError.textContent = 'OTP expired. Click resend to get a new code.';
                        event.preventDefault();
                        return;
                    }

                    if (!validateOtp()) {
                        event.preventDefault();
                    }
                });
            }
        })();
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
                toggle.innerHTML = iconMarkup;
                wrapper.appendChild(toggle);

                const sync = () => {
                    const visible = input.type === 'text';
                    toggle.classList.toggle('is-visible', visible);
                };

                toggle.addEventListener('click', () => {
                    input.type = input.type === 'password' ? 'text' : 'password';
                    sync();
                    input.focus({ preventScroll: true });
                });

                sync();
            });
        })();
    </script>
</body>
</html>
