<?php
include('../frontend/config/constants.php');

$message = '';
$values = [
    'full_name' => '',
    'username' => '',
    'email' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values['full_name'] = trim($_POST['full_name'] ?? '');
    $values['username'] = trim($_POST['username'] ?? '');
    $values['email'] = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($values['full_name'] === '' || $values['username'] === '' || $values['email'] === '' || $password === '' || $confirmPassword === '') {
        $message = "<div class='error-box'>Please fill in all fields.</div>";
    } elseif (!preg_match('/^[A-Za-z][A-Za-z\\s]{1,59}$/', $values['full_name'])) {
        $message = "<div class='error-box'>Full name should contain only letters and spaces.</div>";
    } elseif (!preg_match('/^[a-zA-Z0-9._-]{3,30}$/', $values['username'])) {
        $message = "<div class='error-box'>Username must be 3-30 characters using letters, numbers, dot, underscore or hyphen.</div>";
    } elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='error-box'>Please enter a valid email address.</div>";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[^A-Za-z\\d]).{8,64}$/', $password)) {
        $message = "<div class='error-box'>Password must be 8+ chars with uppercase, lowercase, number and special character.</div>";
    } elseif ($password !== $confirmPassword) {
        $message = "<div class='error-box'>Password and confirm password do not match.</div>";
    } else {
        $sql = 'SELECT id FROM tbl_admin WHERE username = ? OR email = ?';
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            $message = "<div class='error-box'>Error preparing query.</div>";
        } else {
            $stmt->bind_param('ss', $values['username'], $values['email']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $message = "<div class='error-box'>Username or Email already exists.</div>";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $insertSql = 'INSERT INTO tbl_admin (full_name, email, username, password) VALUES (?, ?, ?, ?)';
                $insertStmt = $conn->prepare($insertSql);

                if (!$insertStmt) {
                    $message = "<div class='error-box'>Error preparing insert query.</div>";
                } else {
                    $insertStmt->bind_param('ssss', $values['full_name'], $values['email'], $values['username'], $hashedPassword);

                    if ($insertStmt->execute()) {
                        header('Location: login.php');
                        exit();
                    } else {
                        $message = "<div class='error-box'>Error during sign up. Please try again.</div>";
                    }
                }
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
    <title>Admin Sign Up | Pasar-kita</title>
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
                url('../images/login-page.jpg') center / cover no-repeat fixed;
            padding: 18px;
            display: grid;
            place-items: center;
            color: var(--text);
        }

        .auth-shell {
            width: 100%;
            max-width: 720px;
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
            box-shadow: 0 32px 70px rgba(6, 16, 34, .4);
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

        .error-box {
            margin-top: 12px;
            margin-bottom: 10px;
            border: 1px solid rgba(220, 53, 69, .25);
            background: rgba(220, 53, 69, .1);
            color: #b12536;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: .9rem;
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
            <h1 class="auth-title">Create Admin Account</h1>
            <p class="auth-subtitle">Use a strong password and unique username for secure admin access.</p>
        </div>

        <?php if ($message !== '') echo $message; ?>

        <form id="signupForm" method="post" novalidate>
            <div class="row">
                <div class="col-md-6 field-group">
                    <label class="field-label" for="full_name">Full Name</label>
                    <input class="field-input" type="text" id="full_name" name="full_name" maxlength="60" value="<?php echo htmlspecialchars($values['full_name']); ?>" required>
                    <span class="field-error" id="error_full_name"></span>
                </div>
                <div class="col-md-6 field-group">
                    <label class="field-label" for="username">Username</label>
                    <input class="field-input" type="text" id="username" name="username" maxlength="30" value="<?php echo htmlspecialchars($values['username']); ?>" required>
                    <span class="field-error" id="error_username"></span>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 field-group">
                    <label class="field-label" for="email">Email</label>
                    <input class="field-input" type="email" id="email" name="email" maxlength="120" value="<?php echo htmlspecialchars($values['email']); ?>" required>
                    <span class="field-error" id="error_email"></span>
                </div>
                <div class="col-md-6 field-group">
                    <label class="field-label" for="password">Password</label>
                    <input class="field-input" type="password" id="password" name="password" maxlength="64" required>
                    <div class="hint">8+ chars with upper, lower, number and special char.</div>
                    <span class="field-error" id="error_password"></span>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 field-group">
                    <label class="field-label" for="confirm_password">Confirm Password</label>
                    <input class="field-input" type="password" id="confirm_password" name="confirm_password" maxlength="64" required>
                    <span class="field-error" id="error_confirm_password"></span>
                </div>
            </div>

            <button type="submit" class="auth-btn">Sign Up</button>
        </form>

        <div class="auth-footer">
            Already have an account? <a class="auth-link" href="login.php">Log in</a>
        </div>
    </div>

    <script>
        (function () {
            const form = document.getElementById('signupForm');
            const patterns = {
                full_name: /^[A-Za-z][A-Za-z\s]{1,59}$/,
                username: /^[a-zA-Z0-9._-]{3,30}$/,
                email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,64}$/
            };

            const fields = [
                { id: 'full_name', msg: 'Enter valid full name (letters and spaces).' },
                { id: 'username', msg: '3-30 chars: letters, numbers, dot, underscore, hyphen.' },
                { id: 'email', msg: 'Enter valid email address.' },
                { id: 'password', msg: 'Use 8+ chars with upper, lower, number and special char.' },
                { id: 'confirm_password', msg: 'Confirm password is required.' }
            ];

            function errNode(id) {
                return document.getElementById('error_' + id);
            }

            function checkField(id) {
                const el = document.getElementById(id);
                const value = (el.value || '').trim();
                let valid = true;
                let msg = '';

                if (!value) {
                    valid = false;
                    msg = fields.find((f) => f.id === id).msg;
                } else if (patterns[id] && !patterns[id].test(value)) {
                    valid = false;
                    msg = fields.find((f) => f.id === id).msg;
                }

                errNode(id).textContent = valid ? '' : msg;
                return valid;
            }

            function checkConfirm() {
                const pwd = document.getElementById('password').value;
                const cp = document.getElementById('confirm_password').value;
                const node = errNode('confirm_password');

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
            }

            fields.forEach((f) => {
                const input = document.getElementById(f.id);
                input.addEventListener('input', () => {
                    if (f.id === 'confirm_password') {
                        checkConfirm();
                    } else {
                        checkField(f.id);
                        if (f.id === 'password') {
                            checkConfirm();
                        }
                    }
                });
            });

            form.addEventListener('submit', (event) => {
                let ok = true;
                fields.forEach((f) => {
                    if (f.id === 'confirm_password') {
                        return;
                    }
                    ok = checkField(f.id) && ok;
                });
                ok = checkConfirm() && ok;
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
