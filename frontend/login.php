<?php
require_once 'config/constants.php';

if (!function_exists('frontend_clear_login_otp_state')) {
    function frontend_clear_login_otp_state(): void
    {
        unset(
            $_SESSION['login_pending_user'],
            $_SESSION['login_pending_otp'],
            $_SESSION['login_pending_otp_expires_at'],
            $_SESSION['login_pending_email'],
            $_SESSION['login_pending_username']
        );
    }
}

if (!function_exists('frontend_issue_login_otp')) {
    function frontend_issue_login_otp(array $user): array
    {
        $mailResult = app_send_login_otp_email((string) $user['email'], (string) $user['username']);

        if ($mailResult['success']) {
            $_SESSION['login_pending_user'] = array(
                'username' => (string) $user['username'],
                'name' => (string) $user['name'],
            );
            $_SESSION['login_pending_otp'] = (string) $mailResult['otp'];
            $_SESSION['login_pending_otp_expires_at'] = time() + APP_OTP_TTL_SECONDS;
            $_SESSION['login_pending_email'] = (string) $user['email'];
            $_SESSION['login_pending_username'] = (string) $user['username'];
        }

        return $mailResult;
    }
}

$message = '';
$messageClass = 'error';
$username = '';
$flashSuccess = $_SESSION['auth-flash-success'] ?? '';
unset($_SESSION['auth-flash-success']);

if (isset($_GET['restart'])) {
    frontend_clear_login_otp_state();
    header('Location: login.php');
    exit();
}

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$projectPath = preg_replace('#/frontend$#', '', rtrim($scriptDir, '/'));
$assetRoot = ($projectPath === '' ? '' : $projectPath) . '/images';

if (isset($_SESSION['login_pending_username'])) {
    $username = (string) $_SESSION['login_pending_username'];
}

$otpStepActive = isset($_SESSION['login_pending_user'], $_SESSION['login_pending_otp'], $_SESSION['login_pending_otp_expires_at']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['send_login_otp'])) {
        frontend_clear_login_otp_state();

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $message = 'Please fill in username and password.';
        } elseif (!preg_match('/^[a-zA-Z0-9._-]{3,30}$/', $username)) {
            $message = 'Username format is invalid.';
        } else {
            $stmt = $conn->prepare('SELECT username, name, email, password, user_role FROM tbl_users WHERE username = ? LIMIT 1');
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                $message = 'No account found with this username.';
            } else {
                $user = $result->fetch_assoc();

                if ((int) $user['user_role'] === 0) {
                    $message = 'Your account has been blocked by the admin.';
                } elseif (!password_verify($password, $user['password'])) {
                    $message = 'Invalid password. Please try again.';
                } else {
                    $mailResult = frontend_issue_login_otp($user);

                    if ($mailResult['success']) {
                        $otpStepActive = true;
                        $message = 'Login OTP sent to your registered email. It expires in ' . APP_OTP_TTL_SECONDS . ' seconds.';
                        $messageClass = 'success';
                    } else {
                        $message = $mailResult['error'];
                    }
                }
            }
        }
    } elseif (isset($_POST['resend_login_otp'])) {
        if (!isset($_SESSION['login_pending_user'], $_SESSION['login_pending_email'], $_SESSION['login_pending_username'])) {
            $message = 'Login session expired. Please enter username and password again.';
            $otpStepActive = false;
        } else {
            $mailResult = frontend_issue_login_otp(array(
                'username' => (string) $_SESSION['login_pending_username'],
                'name' => (string) ($_SESSION['login_pending_user']['name'] ?? ''),
                'email' => (string) $_SESSION['login_pending_email'],
            ));

            if ($mailResult['success']) {
                $otpStepActive = true;
                $message = 'A new login OTP has been sent. It expires in ' . APP_OTP_TTL_SECONDS . ' seconds.';
                $messageClass = 'success';
            } else {
                $message = $mailResult['error'];
                $otpStepActive = true;
            }
        }
    } elseif (isset($_POST['verify_login_otp'])) {
        $username = (string) ($_SESSION['login_pending_username'] ?? '');
        $otpStepActive = true;
        $otp = trim($_POST['login_otp'] ?? '');

        if (!isset($_SESSION['login_pending_user'], $_SESSION['login_pending_otp'], $_SESSION['login_pending_otp_expires_at'])) {
            $message = 'Login session expired. Please enter username and password again.';
            $otpStepActive = false;
        } elseif ($otp === '') {
            $message = 'OTP is required.';
        } elseif (!preg_match('/^[0-9]{6}$/', $otp)) {
            $message = 'OTP must be exactly 6 digits.';
        } elseif (time() > (int) $_SESSION['login_pending_otp_expires_at']) {
            $message = 'Login OTP expired. Click resend to get a new code.';
        } elseif ($otp !== (string) $_SESSION['login_pending_otp']) {
            $message = 'OTP does not match.';
        } else {
            $_SESSION['user'] = $_SESSION['login_pending_user']['username'];
            $_SESSION['name'] = $_SESSION['login_pending_user']['name'];
            frontend_clear_login_otp_state();
            header('Location: index.php');
            exit();
        }
    }
}

$otpEmail = $_SESSION['login_pending_email'] ?? '';
$otpExpiresAt = (int) ($_SESSION['login_pending_otp_expires_at'] ?? 0);

if ($otpStepActive && $otpExpiresAt > 0 && time() > $otpExpiresAt && $message === '') {
    $message = 'Login OTP expired. Click resend to get a new code.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Pasar-kita</title>
    <link rel="icon" type="image/png" href="<?php echo htmlspecialchars($assetRoot . '/logo2.png', ENT_QUOTES, 'UTF-8'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --auth-bg-1: #0f224a;
            --auth-bg-2: #162f63;
            --auth-accent: #e69500;
            --auth-text: #0f172f;
            --auth-muted: #61708c;
            --auth-border: #d8e0ef;
            --auth-danger: #dc3545;
            --auth-success: #198754;
            --auth-info: #0d6efd;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Nunito", "Segoe UI", sans-serif;
            color: var(--auth-text);
            background:
                radial-gradient(850px 480px at -10% -20%, rgba(230, 149, 0, 0.18), transparent 70%),
                radial-gradient(900px 520px at 110% 120%, rgba(13, 110, 253, 0.2), transparent 70%),
                linear-gradient(135deg, rgba(15, 34, 74, 0.42), rgba(22, 47, 99, 0.42)),
                url('<?php echo htmlspecialchars($assetRoot . '/login-page.jpg', ENT_QUOTES, 'UTF-8'); ?>') center / cover no-repeat fixed;
            display: grid;
            place-items: center;
            padding: 18px;
        }

        .auth-shell {
            width: 100%;
            max-width: 470px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(255, 255, 255, 0.55);
            box-shadow: 0 24px 58px rgba(6, 16, 34, 0.35);
            padding: 28px 24px 22px;
            backdrop-filter: blur(8px);
        }

        .auth-brand {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }

        .auth-brand img {
            width: 44px;
            height: 44px;
            object-fit: contain;
        }

        .auth-title {
            margin: 0 0 4px;
            text-align: center;
            font-size: clamp(1.4rem, 3.6vw, 1.9rem);
            font-weight: 900;
        }

        .auth-subtitle {
            margin: 0 0 14px;
            text-align: center;
            color: var(--auth-muted);
            font-size: .95rem;
        }

        .step-chip {
            margin: 0 auto 14px;
            width: fit-content;
            border-radius: 999px;
            padding: 7px 13px;
            font-size: .8rem;
            font-weight: 800;
            color: #355186;
            background: #f3f7ff;
            border: 1px solid #dbe6fb;
        }

        .server-box {
            border-radius: 10px;
            font-size: .9rem;
            padding: 10px 12px;
            margin-bottom: 12px;
        }

        .server-box.error {
            color: #b42334;
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.25);
        }

        .server-box.success {
            color: #0f5a36;
            background: rgba(25, 135, 84, 0.11);
            border: 1px solid rgba(25, 135, 84, 0.25);
        }

        .server-box.info {
            color: #124b98;
            background: rgba(13, 110, 253, 0.1);
            border: 1px solid rgba(13, 110, 253, 0.18);
        }

        .field-group { margin-bottom: 12px; }

        .field-label {
            font-size: .84rem;
            color: #3e4b66;
            margin-bottom: 6px;
            font-weight: 700;
            display: block;
        }

        .field-input {
            width: 100%;
            border: 1px solid var(--auth-border);
            border-radius: 12px;
            padding: 11px 13px;
            outline: none;
            font-size: .95rem;
            background: #fff;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .field-input:focus {
            border-color: #7aa4ea;
            box-shadow: 0 0 0 4px rgba(64, 124, 224, 0.14);
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
            box-shadow: 0 0 0 4px rgba(64, 124, 224, 0.14);
            transform: translateY(-1px);
        }

        @media (max-width: 520px) {
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
            display: block;
            min-height: 18px;
            font-size: .78rem;
            color: var(--auth-danger);
            margin-top: 4px;
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

        .auth-helpers {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .link-inline {
            color: #1f64d4;
            text-decoration: none;
            font-weight: 700;
        }

        .link-inline:hover { color: #0f4fb5; text-decoration: underline; }

        .auth-btn {
            width: 100%;
            border: 0;
            border-radius: 12px;
            padding: 11px 14px;
            font-size: .96rem;
            font-weight: 800;
            color: #fff;
            background: linear-gradient(135deg, #ffb325, #e69500);
            box-shadow: 0 14px 24px rgba(230, 149, 0, 0.28);
        }

        .auth-btn[disabled] {
            cursor: not-allowed;
            opacity: .68;
            box-shadow: none;
            filter: saturate(.6);
        }

        .auth-footer {
            margin-top: 14px;
            text-align: center;
            font-size: .9rem;
            color: #53627f;
        }

        /* .otp-meta {
            margin-bottom: 12px;
            padding: 14px;
            border-radius: 14px;
            background: #f8fbff;
            border: 1px solid #d9e5f7;
            color: #46607f;
            font-size: .92rem;
            line-height: 1.65;
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
            color: var(--auth-muted);
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
    <div class="auth-shell">
        <div class="auth-brand">
            <img src="<?php echo htmlspecialchars($assetRoot . '/logo2.png', ENT_QUOTES, 'UTF-8'); ?>" alt="Pasar-kita">
        </div>
        <h1 class="auth-title"><?php echo $otpStepActive ? 'Verify Login OTP' : 'Welcome Back'; ?></h1>
        <p class="auth-subtitle"><?php echo $otpStepActive ? 'Your password is correct. Finish login with the email OTP.' : 'Sign in with username and password, then verify the OTP sent to email.'; ?></p>

        <?php if ($flashSuccess !== ''): ?>
            <div class="server-box success"><?php echo htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <?php if ($message !== ''): ?>
            <div class="server-box <?php echo htmlspecialchars($messageClass, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <?php if (!$otpStepActive): ?>
            <div class="step-chip">Step 1 of 2: Credentials</div>
            <form id="loginForm" method="post" novalidate>
                <div class="field-group">
                    <label for="username" class="field-label">Username</label>
                    <input class="field-input" type="text" name="username" id="username" maxlength="30" autocomplete="username" value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>" required>
                    <span class="field-error" id="error_username"></span>
                </div>

                <div class="field-group">
                    <label for="password" class="field-label">Password</label>
                    <input class="field-input" type="password" name="password" id="password" maxlength="72" autocomplete="current-password" required>
                    <span class="field-error" id="error_password"></span>
                </div>

                <div class="auth-helpers">
                    <a class="link-inline" href="forget.php">Forgot password?</a>
                </div>

                <button type="submit" name="send_login_otp" class="auth-btn">Send Login OTP</button>
            </form>
        <?php else: ?>
            <div class="step-chip">Step 2 of 2: OTP Verification</div>
            <form id="otpForm" method="post" novalidate>
                <div class="field-group">
                    <label for="login_otp_digit_1" class="field-label">6-digit OTP</label>
                    <div class="otp-panel">
                        <input class="otp-hidden" type="text" name="login_otp" id="login_otp" maxlength="6" value="<?php echo isset($_POST['login_otp']) ? htmlspecialchars($_POST['login_otp'], ENT_QUOTES, 'UTF-8') : ''; ?>" inputmode="numeric" autocomplete="one-time-code" tabindex="-1" required>
                        <div class="otp-grid" data-otp-group data-target="login_otp">
                            <input class="otp-digit" type="text" id="login_otp_digit_1" inputmode="numeric" maxlength="1" placeholder="0" autocomplete="one-time-code">
                            <input class="otp-digit" type="text" inputmode="numeric" maxlength="1" placeholder="0">
                            <input class="otp-digit" type="text" inputmode="numeric" maxlength="1" placeholder="0">
                            <input class="otp-digit" type="text" inputmode="numeric" maxlength="1" placeholder="0">
                            <input class="otp-digit" type="text" inputmode="numeric" maxlength="1" placeholder="0">
                            <input class="otp-digit" type="text" inputmode="numeric" maxlength="1" placeholder="0">
                        </div>
                    </div>
                    <span class="field-error" id="error_login_otp"></span>
                    <div class="otp-status-row" data-otp-status data-expires-at="<?php echo (int) $otpExpiresAt; ?>">
                        <div class="otp-timer-text">Remaining time: <span class="otp-timer-value" data-otp-timer>01:00</span></div>
                        <div class="otp-resend-wrap">
                            <span>Didn't get code?</span>
                            <button class="otp-link-btn" type="submit" name="resend_login_otp" formnovalidate>Resend</button>
                        </div>
                    </div>
                    <div class="otp-expired-note" id="loginOtpExpiredNote">OTP expired. Click resend to get a new code.</div>
                </div>
                <div class="auth-helpers">
                    <a class="link-inline" href="login.php?restart=1">Use another account</a>
                </div>
                <button type="submit" name="verify_login_otp" class="auth-btn" data-otp-verify>Verify OTP and Login</button>
            </form>
        <?php endif; ?>

        <div class="auth-footer">
            Don't have an account? <a class="link-inline" href="signup.php">Create one</a>
        </div>
    </div>

    <script>
        (function () {
            const loginForm = document.getElementById('loginForm');
            const otpForm = document.getElementById('otpForm');
            const usernamePattern = /^[a-zA-Z0-9._-]{3,30}$/;

            if (loginForm) {
                const username = document.getElementById('username');
                const password = document.getElementById('password');
                const usernameError = document.getElementById('error_username');
                const passwordError = document.getElementById('error_password');

                const validateUsername = () => {
                    const value = username.value.trim();
                    if (!value) {
                        usernameError.textContent = 'Username is required.';
                        return false;
                    }
                    if (!usernamePattern.test(value)) {
                        usernameError.textContent = 'Use 3-30 chars: letters, numbers, dot, underscore or hyphen.';
                        return false;
                    }
                    usernameError.textContent = '';
                    return true;
                };

                const validatePassword = () => {
                    if (!password.value) {
                        passwordError.textContent = 'Password is required.';
                        return false;
                    }
                    passwordError.textContent = '';
                    return true;
                };

                username.addEventListener('input', validateUsername);
                password.addEventListener('input', validatePassword);

                loginForm.addEventListener('submit', (event) => {
                    const ok = validateUsername() & validatePassword();
                    if (!ok) event.preventDefault();
                });
            }

            if (otpForm) {
                const otp = document.getElementById('login_otp');
                const otpError = document.getElementById('error_login_otp');
                const otpGroup = document.querySelector('[data-otp-group][data-target="login_otp"]');
                const otpStatus = otpForm.querySelector('[data-otp-status]');
                const otpTimer = otpForm.querySelector('[data-otp-timer]');
                const expiredNote = document.getElementById('loginOtpExpiredNote');
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
                    if (submitter && submitter.name === 'resend_login_otp') {
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
