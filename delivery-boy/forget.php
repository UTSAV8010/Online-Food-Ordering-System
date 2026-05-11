<?php
include('../frontend/config/constants.php');

$message = '';
$showResetKeyField = false;
$showPasswordField = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['verify_email'])) {
        unset($_SESSION['verified_email'], $_SESSION['reset_key'], $_SESSION['reset_key_expires_at'], $_SESSION['reset_verified']);
        $email = trim($_POST['email'] ?? '');

        if ($email === '') {
            $message = "<div class='alert-box error-box'>Email is required.</div>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "<div class='alert-box error-box'>Please enter a valid email address.</div>";
        } else {
            $stmt = $conn->prepare('SELECT * FROM tbl_delivery_boy WHERE email = ?');
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                $message = "<div class='alert-box error-box'>Email not found.</div>";
            } else {
                $resetKey = (string) random_int(100000, 999999);
                $stmt = $conn->prepare('UPDATE tbl_delivery_boy SET reset_key = ? WHERE email = ?');
                $stmt->bind_param('ss', $resetKey, $email);

                if ($stmt->execute()) {
                    $_SESSION['reset_key'] = (string)$resetKey;
                    $_SESSION['verified_email'] = $email;
                    $_SESSION['reset_key_expires_at'] = time() + 600;

                    $mailResult = app_send_password_reset_email($email, $resetKey, 'Delivery');
                    if ($mailResult['success']) {
                        $message = "<div class='alert-box success-box'>Reset key sent to your registered email.</div>";
                        $showResetKeyField = true;
                    } else {
                        $message = "<div class='alert-box error-box'>" . htmlspecialchars($mailResult['error'], ENT_QUOTES, 'UTF-8') . "</div>";
                    }
                } else {
                    $message = "<div class='alert-box error-box'>Unable to generate reset key. Please try again.</div>";
                }
            }
        }
    }

    if (isset($_POST['verify_reset_key'])) {
        $resetKey = trim($_POST['reset_key'] ?? '');
        $showResetKeyField = true;

        if ($resetKey === '') {
            $message = "<div class='alert-box error-box'>Reset key is required.</div>";
        } elseif (!preg_match('/^[0-9]{6}$/', $resetKey)) {
            $message = "<div class='alert-box error-box'>Reset key must be 6 digits.</div>";
        } elseif (!isset($_SESSION['verified_email'], $_SESSION['reset_key'], $_SESSION['reset_key_expires_at'])) {
            $message = "<div class='alert-box error-box'>Session expired. Please verify your email again.</div>";
            $showResetKeyField = false;
        } elseif (time() > (int)$_SESSION['reset_key_expires_at']) {
            $message = "<div class='alert-box error-box'>Reset key expired. Please request a new key.</div>";
            unset($_SESSION['reset_key'], $_SESSION['reset_key_expires_at'], $_SESSION['reset_verified']);
            $showResetKeyField = false;
        } elseif (!isset($_SESSION['reset_key']) || $resetKey !== (string)$_SESSION['reset_key']) {
            $message = "<div class='alert-box error-box'>Reset key does not match.</div>";
        } else {
            $_SESSION['reset_verified'] = true;
            $message = "<div class='alert-box success-box'>Reset key verified. Set your new password.</div>";
            $showResetKeyField = false;
            $showPasswordField = true;
        }
    }

    if (isset($_POST['update_password'])) {
        $newPassword = trim($_POST['new_password'] ?? '');
        $confirmPassword = trim($_POST['confirm_password'] ?? '');
        $showPasswordField = true;

        if ($newPassword === '' || $confirmPassword === '') {
            $message = "<div class='alert-box error-box'>Both password fields are required.</div>";
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[^A-Za-z\\d]).{8,64}$/', $newPassword)) {
            $message = "<div class='alert-box error-box'>Password must be 8+ chars with uppercase, lowercase, number and special character.</div>";
        } elseif ($newPassword !== $confirmPassword) {
            $message = "<div class='alert-box error-box'>Passwords do not match.</div>";
        } elseif (
            !isset($_SESSION['verified_email'], $_SESSION['reset_key_expires_at'], $_SESSION['reset_verified']) ||
            $_SESSION['reset_verified'] !== true
        ) {
            $message = "<div class='alert-box error-box'>Please verify reset key first.</div>";
            $showPasswordField = false;
        } elseif (time() > (int)$_SESSION['reset_key_expires_at']) {
            $message = "<div class='alert-box error-box'>Session expired. Please restart reset process.</div>";
            unset($_SESSION['verified_email'], $_SESSION['reset_key'], $_SESSION['reset_key_expires_at'], $_SESSION['reset_verified']);
            $showPasswordField = false;
        } else {
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $email = $_SESSION['verified_email'];

            $stmt = $conn->prepare('UPDATE tbl_delivery_boy SET password = ?, reset_key = NULL WHERE email = ?');
            $stmt->bind_param('ss', $hashedPassword, $email);

            if ($stmt->execute()) {
                $message = "<div class='alert-box success-box'>Password updated successfully. <a href='login.php'>Log in</a>.</div>";
                unset($_SESSION['verified_email'], $_SESSION['reset_key'], $_SESSION['reset_key_expires_at'], $_SESSION['reset_verified']);
                $showPasswordField = false;
            } else {
                $message = "<div class='alert-box error-box'>Error updating password. Please try again.</div>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Forgot Password | Pasar-kita</title>
    <link rel="icon" type="image/png" href="../images/logo2.png">
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
                radial-gradient(800px 500px at -10% 0%, rgba(230, 149, 0, .18), transparent 70%),
                radial-gradient(900px 540px at 110% 100%, rgba(13, 110, 253, .2), transparent 70%),
                linear-gradient(140deg, rgba(15, 34, 74, .42), rgba(23, 49, 103, .42)),
                url('../images/login-page.jpg') center / cover no-repeat fixed;
            display: grid;
            place-items: center;
            padding: 18px;
            color: var(--text);
        }

        .auth-shell {
            width: 100%;
            max-width: 480px;
            background: rgba(255, 255, 255, .96);
            border: 1px solid rgba(255, 255, 255, .6);
            border-radius: 22px;
            box-shadow: 0 24px 62px rgba(6, 16, 34, .38);
            padding: 28px 24px 22px;
            backdrop-filter: blur(8px);
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .auth-shell:hover {
            transform: translateY(-3px);
            box-shadow: 0 30px 68px rgba(6, 16, 34, .4);
        }

        .auth-head { text-align: center; margin-bottom: 14px; }

        .auth-head img {
            width: 44px;
            height: 44px;
            object-fit: contain;
            margin-bottom: 8px;
        }

        .auth-title {
            margin: 0 0 4px;
            font-size: clamp(1.35rem, 3.5vw, 1.9rem);
            font-weight: 900;
        }

        .auth-subtitle {
            margin: 0;
            color: var(--muted);
            font-size: .94rem;
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

        .alert-box {
            border-radius: 10px;
            padding: 10px 12px;
            font-size: .9rem;
            margin-bottom: 10px;
        }

        .error-box {
            color: #b12536;
            background: rgba(220, 53, 69, .1);
            border: 1px solid rgba(220, 53, 69, .25);
        }

        .success-box {
            color: #0f5a36;
            background: rgba(25, 135, 84, .11);
            border: 1px solid rgba(25, 135, 84, .25);
        }

        .success-box a { color: #0f5a36; font-weight: 800; }

        .field-group { margin-bottom: 10px; }

        .field-label {
            font-size: .83rem;
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
            transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
        }

        .field-input:hover { border-color: #b7c5df; }

        .field-input:focus {
            border-color: #7fa8ea;
            box-shadow: 0 0 0 4px rgba(64, 124, 224, .14);
        }

        .field-error {
            min-height: 17px;
            margin-top: 3px;
            color: var(--danger);
            font-size: .76rem;
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
            transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
            margin-top: 4px;
        }

        .auth-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 30px rgba(230, 149, 0, .33);
            filter: brightness(1.04);
        }

        .footer-link {
            margin-top: 12px;
            text-align: center;
            font-size: .9rem;
            color: #53627f;
        }

        .footer-link a {
            color: #1f64d4;
            text-decoration: none;
            font-weight: 700;
        }

        .footer-link a:hover { text-decoration: underline; color: #0f4fb5; }

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

        .password-toggle.is-visible {
            color: var(--accent, #e69500);
        }

        .password-toggle:focus-visible {
            outline: 2px solid rgba(64, 124, 224, 0.4);
            outline-offset: 2px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="auth-shell">
        <div class="auth-head">
            <img src="../images/logo2.png" alt="Pasar-kita">
            <h1 class="auth-title">Reset Delivery Password</h1>
            <p class="auth-subtitle">Secure 3-step flow to recover access to your account.</p>
        </div>

        <?php echo $message; ?>

        <?php if (!$showResetKeyField && !$showPasswordField): ?>
            <div class="step-chip">Step 1 of 3: Verify Email</div>
            <form method="post" id="emailStepForm" novalidate>
                <div class="field-group">
                    <label class="field-label" for="email">Email Address</label>
                    <input class="field-input" type="email" name="email" id="email" maxlength="120" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                    <span class="field-error" id="error_email"></span>
                </div>
                <button class="auth-btn" type="submit" name="verify_email">Send Reset Key</button>
            </form>
        <?php endif; ?>

        <?php if ($showResetKeyField && !$showPasswordField): ?>
            <div class="step-chip">Step 2 of 3: Verify Reset Key</div>
            <form method="post" id="keyStepForm" novalidate>
                <div class="field-group">
                    <label class="field-label" for="reset_key">6-digit Reset Key</label>
                    <input class="field-input" type="text" name="reset_key" id="reset_key" maxlength="6" value="<?php echo isset($_POST['reset_key']) ? htmlspecialchars($_POST['reset_key']) : ''; ?>" required>
                    <span class="field-error" id="error_reset_key"></span>
                </div>
                <button class="auth-btn" type="submit" name="verify_reset_key">Verify Reset Key</button>
            </form>
        <?php endif; ?>

        <?php if ($showPasswordField): ?>
            <div class="step-chip">Step 3 of 3: Set New Password</div>
            <form method="post" id="passwordStepForm" novalidate>
                <div class="field-group">
                    <label class="field-label" for="new_password">New Password</label>
                    <input class="field-input" type="password" name="new_password" id="new_password" maxlength="64" required>
                    <div class="hint">8+ chars with upper, lower, number and special char.</div>
                    <span class="field-error" id="error_new_password"></span>
                </div>
                <div class="field-group">
                    <label class="field-label" for="confirm_password">Confirm Password</label>
                    <input class="field-input" type="password" name="confirm_password" id="confirm_password" maxlength="64" required>
                    <span class="field-error" id="error_confirm_password"></span>
                </div>
                <button class="auth-btn" type="submit" name="update_password">Update Password</button>
            </form>
        <?php endif; ?>

        <div class="footer-link">
            Back to <a href="login.php">Login</a>
        </div>
    </div>

    <script>
        (function () {
            const emailForm = document.getElementById('emailStepForm');
            const keyForm = document.getElementById('keyStepForm');
            const passwordForm = document.getElementById('passwordStepForm');
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const keyPattern = /^[0-9]{6}$/;
            const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,64}$/;

            if (emailForm) {
                const email = document.getElementById('email');
                const emailError = document.getElementById('error_email');

                const validateEmail = () => {
                    const v = email.value.trim();
                    if (!v) {
                        emailError.textContent = 'Email is required.';
                        return false;
                    }
                    if (!emailPattern.test(v)) {
                        emailError.textContent = 'Please enter a valid email address.';
                        return false;
                    }
                    emailError.textContent = '';
                    return true;
                };

                email.addEventListener('input', validateEmail);
                emailForm.addEventListener('submit', (e) => {
                    if (!validateEmail()) {
                        e.preventDefault();
                    }
                });
            }

            if (keyForm) {
                const key = document.getElementById('reset_key');
                const keyError = document.getElementById('error_reset_key');

                const validateKey = () => {
                    const v = key.value.trim();
                    if (!v) {
                        keyError.textContent = 'Reset key is required.';
                        return false;
                    }
                    if (!keyPattern.test(v)) {
                        keyError.textContent = 'Reset key must be exactly 6 digits.';
                        return false;
                    }
                    keyError.textContent = '';
                    return true;
                };

                key.addEventListener('input', () => {
                    key.value = key.value.replace(/\D/g, '').slice(0, 6);
                    validateKey();
                });

                keyForm.addEventListener('submit', (e) => {
                    if (!validateKey()) {
                        e.preventDefault();
                    }
                });
            }

            if (passwordForm) {
                const newPassword = document.getElementById('new_password');
                const confirmPassword = document.getElementById('confirm_password');
                const newPasswordError = document.getElementById('error_new_password');
                const confirmPasswordError = document.getElementById('error_confirm_password');

                const validatePassword = () => {
                    const v = newPassword.value;
                    if (!v) {
                        newPasswordError.textContent = 'New password is required.';
                        return false;
                    }
                    if (!passwordPattern.test(v)) {
                        newPasswordError.textContent = 'Use 8+ chars with upper, lower, number and special char.';
                        return false;
                    }
                    newPasswordError.textContent = '';
                    return true;
                };

                const validateConfirm = () => {
                    const v = confirmPassword.value;
                    if (!v) {
                        confirmPasswordError.textContent = 'Confirm password is required.';
                        return false;
                    }
                    if (newPassword.value !== v) {
                        confirmPasswordError.textContent = 'Passwords do not match.';
                        return false;
                    }
                    confirmPasswordError.textContent = '';
                    return true;
                };

                newPassword.addEventListener('input', () => {
                    validatePassword();
                    if (confirmPassword.value) {
                        validateConfirm();
                    }
                });

                confirmPassword.addEventListener('input', validateConfirm);
                passwordForm.addEventListener('submit', (e) => {
                    const ok = validatePassword() & validateConfirm();
                    if (!ok) {
                        e.preventDefault();
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
</body>
</html>
