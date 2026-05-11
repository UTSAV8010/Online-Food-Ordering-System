<?php
include('../frontend/config/constants.php');

$error_message = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error_message = 'Please fill in all fields.';
    } elseif (!preg_match('/^[a-zA-Z0-9._-]{3,30}$/', $username)) {
        $error_message = 'Username format is invalid.';
    } else {
        $sql = 'SELECT * FROM tbl_delivery_boy WHERE username = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if ((int)$user['user_role'] === 0) {
                $error_message = 'Your account has been blocked by the admin.';
            } elseif (($user['status'] ?? '') !== 'verified') {
                $error_message = 'Your account is not verified by the admin.';
            } elseif (password_verify($password, $user['password'])) {
                $_SESSION['delivery-boy'] = $user['username'];
                header('Location: index.php');
                exit();
            } else {
                $error_message = 'Invalid password. Please try again.';
            }
        } else {
            $error_message = 'No account found with this username.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Login | Pasar-kita</title>
    <link rel="icon" type="image/png" href="../images/logo2.png">
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
                url('../images/login-page.jpg') center / cover no-repeat fixed;
            display: grid;
            place-items: center;
            padding: 18px;
        }

        .auth-shell {
            width: 100%;
            max-width: 460px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(255, 255, 255, 0.55);
            box-shadow: 0 24px 58px rgba(6, 16, 34, 0.35);
            padding: 28px 24px 22px;
            backdrop-filter: blur(8px);
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .auth-shell:hover {
            transform: translateY(-3px);
            box-shadow: 0 30px 64px rgba(6, 16, 34, 0.38);
        }

        .auth-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .auth-brand img {
            width: 42px;
            height: 42px;
            object-fit: contain;
        }

        .auth-title {
            margin: 0 0 4px;
            text-align: center;
            font-size: clamp(1.4rem, 3.6vw, 1.85rem);
            font-weight: 900;
            color: var(--auth-text);
        }

        .auth-subtitle {
            text-align: center;
            color: var(--auth-muted);
            margin-bottom: 18px;
            font-size: .95rem;
        }

        .server-error {
            background: rgba(220, 53, 69, 0.1);
            color: #b42334;
            border: 1px solid rgba(220, 53, 69, 0.25);
            border-radius: 10px;
            font-size: .9rem;
            padding: 9px 10px;
            margin-bottom: 12px;
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
            transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
            background: #fff;
        }

        .field-input:hover { border-color: #b8c6df; }

        .field-input:focus {
            border-color: #7aa4ea;
            box-shadow: 0 0 0 4px rgba(64, 124, 224, 0.14);
        }

        .field-error {
            display: block;
            min-height: 18px;
            font-size: .78rem;
            color: var(--auth-danger);
            margin-top: 4px;
        }

        .auth-helpers {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-top: 2px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .check-wrap {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: .88rem;
            color: #4f5e7a;
            user-select: none;
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
            font-size: .95rem;
            font-weight: 800;
            color: #fff;
            background: linear-gradient(135deg, #ffb325, #e69500);
            box-shadow: 0 14px 24px rgba(230, 149, 0, 0.28);
            transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
        }

        .auth-btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.04);
            box-shadow: 0 18px 30px rgba(230, 149, 0, 0.33);
        }

        .auth-btn:active { transform: translateY(0); }

        .auth-footer {
            margin-top: 14px;
            text-align: center;
            font-size: .9rem;
            color: #53627f;
        }

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
            color: var(--auth-muted, #61708c);
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
            color: var(--auth-accent, #e69500);
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
        <div class="auth-brand">
            <img src="../images/logo2.png" alt="Pasar-kita">
        </div>
        <h1 class="auth-title">Delivery Login</h1>
        <p class="auth-subtitle">Sign in to view and manage your assigned deliveries.</p>

        <?php if ($error_message !== ''): ?>
            <div class="server-error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form id="loginForm" method="post" novalidate>
            <div class="field-group">
                <label for="username" class="field-label">Username</label>
                <input class="field-input" type="text" name="username" id="username" placeholder="Enter your username" maxlength="30" autocomplete="username" value="<?php echo htmlspecialchars($username); ?>" required>
                <span class="field-error" id="error_username"></span>
            </div>

            <div class="field-group">
                <label for="password" class="field-label">Password</label>
                <input class="field-input" type="password" name="password" id="password" placeholder="Enter your password" maxlength="72" autocomplete="current-password" required>
                <span class="field-error" id="error_password"></span>
            </div>

            <div class="auth-helpers">
                <a class="link-inline" href="forget.php">Forgot password?</a>
            </div>

            <button type="submit" class="auth-btn">Login</button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a class="link-inline" href="signup.php">Create one</a>
        </div>
    </div>

    <script>
        (function () {
            const form = document.getElementById('loginForm');
            const username = document.getElementById('username');
            const password = document.getElementById('password');
            const usernameError = document.getElementById('error_username');
            const passwordError = document.getElementById('error_password');

            const usernamePattern = /^[a-zA-Z0-9._-]{3,30}$/;

            function setError(node, text) {
                node.textContent = text;
            }

            function validateUsername() {
                const value = username.value.trim();
                if (!value) {
                    setError(usernameError, 'Username is required.');
                    return false;
                }
                if (!usernamePattern.test(value)) {
                    setError(usernameError, 'Use 3-30 chars: letters, numbers, dot, underscore or hyphen.');
                    return false;
                }
                setError(usernameError, '');
                return true;
            }

            function validatePassword() {
                const value = password.value;
                if (!value) {
                    setError(passwordError, 'Password is required.');
                    return false;
                }
                if (value.length < 6) {
                    setError(passwordError, 'Password must be at least 6 characters.');
                    return false;
                }
                setError(passwordError, '');
                return true;
            }

            username.addEventListener('input', validateUsername);
            password.addEventListener('input', validatePassword);
            form.addEventListener('submit', (event) => {
                const ok = validateUsername() & validatePassword();
                if (!ok) {
                    event.preventDefault();
                }
            });
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
