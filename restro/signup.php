<?php
include('../frontend/config/constants.php');

$message = '';
$values = [
    'name' => '',
    'username' => '',
    'email' => '',
    'mobile_number' => '',
    'address' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values['name'] = trim($_POST['name'] ?? '');
    $values['username'] = trim($_POST['username'] ?? '');
    $values['email'] = trim($_POST['email'] ?? '');
    $values['mobile_number'] = trim($_POST['mobile_number'] ?? '');
    $values['address'] = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $restroImage = $_FILES['restro_image'] ?? null;
    $licenceImage = $_FILES['licence_image'] ?? null;

    if (
        $values['name'] === '' || $values['username'] === '' || $values['email'] === '' ||
        $values['mobile_number'] === '' || $values['address'] === '' ||
        $password === '' || $confirmPassword === ''
    ) {
        $message = "<div class='error-box'>Please fill in all fields.</div>";
    } elseif (!preg_match('/^[A-Za-z0-9][A-Za-z0-9\\s&().,\'-]{1,79}$/', $values['name'])) {
        $message = "<div class='error-box'>Restaurant name format is invalid.</div>";
    } elseif (!preg_match('/^[a-zA-Z0-9._-]{3,30}$/', $values['username'])) {
        $message = "<div class='error-box'>Username must be 3-30 characters using letters, numbers, dot, underscore or hyphen.</div>";
    } elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='error-box'>Please enter a valid email address.</div>";
    } elseif (!preg_match('/^[0-9]{10,15}$/', $values['mobile_number'])) {
        $message = "<div class='error-box'>Mobile number must be 10 to 15 digits.</div>";
    } elseif (strlen($values['address']) < 6 || strlen($values['address']) > 150) {
        $message = "<div class='error-box'>Address must be between 6 and 150 characters.</div>";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[^A-Za-z\\d]).{8,64}$/', $password)) {
        $message = "<div class='error-box'>Password must be 8+ chars with uppercase, lowercase, number and special character.</div>";
    } elseif ($password !== $confirmPassword) {
        $message = "<div class='error-box'>Password and confirm password do not match.</div>";
    } elseif (!$restroImage || ($restroImage['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        $message = "<div class='error-box'>Please upload restaurant image.</div>";
    } elseif (!$licenceImage || ($licenceImage['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        $message = "<div class='error-box'>Please upload license image.</div>";
    } else {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];

        if (!in_array(mime_content_type($restroImage['tmp_name']), $allowedMimes, true)) {
            $message = "<div class='error-box'>Restaurant image must be JPG, JPEG, PNG, or WEBP.</div>";
        } elseif (!in_array(mime_content_type($licenceImage['tmp_name']), $allowedMimes, true)) {
            $message = "<div class='error-box'>License image must be JPG, JPEG, PNG, or WEBP.</div>";
        } else {
            $checkQuery = 'SELECT id FROM tbl_restro WHERE username = ? OR email = ?';
            $stmtCheck = $conn->prepare($checkQuery);
            $stmtCheck->bind_param('ss', $values['username'], $values['email']);
            $stmtCheck->execute();
            $result = $stmtCheck->get_result();

            if ($result->num_rows > 0) {
                $message = "<div class='error-box'>Username or Email already exists.</div>";
            } else {
                $uploadDirRestro = 'uploads/restro-img/';
                $uploadDirLicence = 'uploads/licence/';

                if (!is_dir($uploadDirRestro)) {
                    mkdir($uploadDirRestro, 0755, true);
                }
                if (!is_dir($uploadDirLicence)) {
                    mkdir($uploadDirLicence, 0755, true);
                }

                $restroExt = strtolower(pathinfo($restroImage['name'], PATHINFO_EXTENSION));
                $licenceExt = strtolower(pathinfo($licenceImage['name'], PATHINFO_EXTENSION));
                $restroFile = $uploadDirRestro . 'restro_' . time() . '_' . random_int(1000, 9999) . '.' . $restroExt;
                $licenceFile = $uploadDirLicence . 'licence_' . time() . '_' . random_int(1000, 9999) . '.' . $licenceExt;

                if (!move_uploaded_file($restroImage['tmp_name'], $restroFile)) {
                    $message = "<div class='error-box'>Failed to upload restaurant image.</div>";
                } elseif (!move_uploaded_file($licenceImage['tmp_name'], $licenceFile)) {
                    $message = "<div class='error-box'>Failed to upload license image.</div>";
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                    $userRole = 1;
                    $status = 'not_approved';

                    $sql = 'INSERT INTO tbl_restro (restro_name, username, email, password, mobile_no, restro_address, restro_image, food_licence_image, user_role, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('ssssssssis', $values['name'], $values['username'], $values['email'], $hashedPassword, $values['mobile_number'], $values['address'], $restroFile, $licenceFile, $userRole, $status);

                    if ($stmt->execute()) {
                        header('Location: login.php');
                        exit();
                    }
                    $message = "<div class='error-box'>Error in registration. Please try again.</div>";
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
<title>Restaurant Sign Up | Pasar-kita</title>
<link rel="icon" type="image/png" href="../images/logo2.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root{--bg-1:#0f224a;--bg-2:#173167;--accent:#e69500;--text:#121d35;--muted:#60708d;--border:#d6dfef;--danger:#dc3545;}*{box-sizing:border-box;}body{margin:0;min-height:100vh;font-family:"Nunito","Segoe UI",sans-serif;background:radial-gradient(850px 520px at -10% 0%,rgba(230,149,0,.18),transparent 70%),radial-gradient(950px 560px at 115% 100%,rgba(13,110,253,.22),transparent 70%),linear-gradient(140deg,rgba(15,34,74,.42),rgba(23,49,103,.42)),url('../images/login-page.jpg') center/cover no-repeat fixed;padding:18px;display:grid;place-items:center;color:var(--text);} .auth-shell{width:100%;max-width:780px;background:rgba(255,255,255,.96);border:1px solid rgba(255,255,255,.6);border-radius:22px;box-shadow:0 24px 62px rgba(6,16,34,.38);padding:28px 24px 22px;backdrop-filter:blur(8px);} .auth-head{text-align:center;margin-bottom:14px;} .auth-head img{width:44px;height:44px;object-fit:contain;margin-bottom:8px;} .auth-title{margin:0 0 4px;font-size:clamp(1.4rem,3.5vw,2rem);font-weight:900;} .auth-subtitle{margin:0;color:var(--muted);font-size:.95rem;} .error-box{margin:12px 0 10px;border:1px solid rgba(220,53,69,.25);background:rgba(220,53,69,.1);color:#b12536;padding:10px 12px;border-radius:10px;font-size:.9rem;} .field-group{margin-bottom:10px;} .field-label{font-size:.82rem;font-weight:700;color:#3f4f6e;margin-bottom:6px;display:block;} .field-input{width:100%;border:1px solid var(--border);border-radius:12px;padding:11px 12px;outline:none;font-size:.95rem;background:#fff;} textarea.field-input{min-height:84px;resize:vertical;} .field-error{min-height:17px;margin-top:3px;color:var(--danger);font-size:.76rem;display:block;} .hint{font-size:.76rem;color:#6b7892;margin-top:-2px;margin-bottom:4px;} .auth-btn{width:100%;border:0;border-radius:12px;padding:11px 14px;font-size:.96rem;font-weight:800;color:#fff;background:linear-gradient(135deg,#ffb326,#e69500);box-shadow:0 14px 24px rgba(230,149,0,.28);margin-top:4px;} .auth-footer{margin-top:12px;text-align:center;font-size:.9rem;color:#53627f;} .auth-link{color:#1f64d4;text-decoration:none;font-weight:700;}

.password-field{position:relative;}
.password-field .field-input,.password-field input{padding-right:44px;}
.password-toggle{position:absolute;top:50%;right:10px;transform:translateY(-50%);width:34px;height:34px;border:0;padding:0;margin:0;background:transparent;color:var(--muted,#60708d);display:grid;place-items:center;cursor:pointer;}
.password-toggle svg{width:18px;height:18px;}
.password-toggle .eye-closed{display:none;}
.password-toggle.is-visible .eye-open{display:none;}
.password-toggle.is-visible .eye-closed{display:block;}
.password-toggle.is-visible{color:var(--accent,#e69500);}
.password-toggle:focus-visible{outline:2px solid rgba(64,124,224,.4);outline-offset:2px;border-radius:8px;}
</style>
</head>
<body>
<div class="auth-shell">
<div class="auth-head"><img src="../images/logo2.png" alt="Pasar-kita"><h1 class="auth-title">Register Your Restaurant</h1><p class="auth-subtitle">Create your restaurant account to manage orders and menu online.</p></div>
<?php if ($message !== '') echo $message; ?>
<form id="signupForm" method="post" enctype="multipart/form-data" novalidate>
<div class="row"><div class="col-md-6 field-group"><label class="field-label" for="name">Restaurant Name</label><input class="field-input" type="text" id="name" name="name" maxlength="80" value="<?php echo htmlspecialchars($values['name']); ?>" required><span class="field-error" id="error_name"></span></div><div class="col-md-6 field-group"><label class="field-label" for="username">Username</label><input class="field-input" type="text" id="username" name="username" maxlength="30" value="<?php echo htmlspecialchars($values['username']); ?>" required><span class="field-error" id="error_username"></span></div></div>
<div class="row"><div class="col-md-6 field-group"><label class="field-label" for="email">Email</label><input class="field-input" type="email" id="email" name="email" maxlength="120" value="<?php echo htmlspecialchars($values['email']); ?>" required><span class="field-error" id="error_email"></span></div><div class="col-md-6 field-group"><label class="field-label" for="mobile_number">Mobile Number</label><input class="field-input" type="tel" id="mobile_number" name="mobile_number" maxlength="15" value="<?php echo htmlspecialchars($values['mobile_number']); ?>" required><span class="field-error" id="error_mobile_number"></span></div></div>
<div class="row"><div class="col-md-6 field-group"><label class="field-label" for="password">Password</label><input class="field-input" type="password" id="password" name="password" maxlength="64" required><div class="hint">8+ chars with upper, lower, number and special char.</div><span class="field-error" id="error_password"></span></div><div class="col-md-6 field-group"><label class="field-label" for="confirm_password">Confirm Password</label><input class="field-input" type="password" id="confirm_password" name="confirm_password" maxlength="64" required><span class="field-error" id="error_confirm_password"></span></div></div>
<div class="row"><div class="col-md-6 field-group"><label class="field-label" for="address">Address</label><textarea class="field-input" id="address" name="address" maxlength="150" required><?php echo htmlspecialchars($values['address']); ?></textarea><span class="field-error" id="error_address"></span></div><div class="col-md-6 field-group"><label class="field-label" for="restro_image">Restaurant Image</label><input class="field-input" type="file" id="restro_image" name="restro_image" accept="image/*" required><span class="field-error" id="error_restro_image"></span><label class="field-label mt-2" for="licence_image">License Image</label><input class="field-input" type="file" id="licence_image" name="licence_image" accept="image/*" required><span class="field-error" id="error_licence_image"></span></div></div>
<button type="submit" class="auth-btn">Sign Up</button></form>
<div class="auth-footer">Already have an account? <a class="auth-link" href="login.php">Log in</a></div>
</div>
<script>
(function(){const form=document.getElementById('signupForm');const patterns={name:/^[A-Za-z0-9][A-Za-z0-9\s&().,'-]{1,79}$/,username:/^[a-zA-Z0-9._-]{3,30}$/,email:/^[^\s@]+@[^\s@]+\.[^\s@]+$/,mobile_number:/^[0-9]{10,15}$/,password:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,64}$/};function setError(id,t){document.getElementById('error_'+id).textContent=t;}function checkField(id){const el=document.getElementById(id);const v=(el.value||'').trim();if(!v){setError(id,'This field is required.');return false;}if(id==='address'){if(v.length<6||v.length>150){setError(id,'Address must be 6 to 150 characters.');return false;}}else if(patterns[id]&&!patterns[id].test(v)){const m={name:'Restaurant name format is invalid.',username:'3-30 chars: letters, numbers, dot, underscore, hyphen.',email:'Enter valid email address.',mobile_number:'Mobile number must be 10 to 15 digits.',password:'Use 8+ chars with upper, lower, number and special char.'};setError(id,m[id]);return false;}setError(id,'');return true;}function checkConfirm(){const p=document.getElementById('password').value;const c=document.getElementById('confirm_password').value;if(!c.trim()){setError('confirm_password','Confirm password is required.');return false;}if(p!==c){setError('confirm_password','Passwords do not match.');return false;}setError('confirm_password','');return true;}function checkFile(id,label){const f=document.getElementById(id).files[0];if(!f){setError(id,'Please upload '+label+'.');return false;}if(!['image/jpeg','image/png','image/jpg','image/webp'].includes(f.type)){setError(id,label+' must be JPG, JPEG, PNG, or WEBP.');return false;}setError(id,'');return true;}['name','username','email','mobile_number','address','password'].forEach(id=>document.getElementById(id).addEventListener('input',()=>{checkField(id);if(id==='password')checkConfirm();}));document.getElementById('confirm_password').addEventListener('input',checkConfirm);document.getElementById('restro_image').addEventListener('change',()=>checkFile('restro_image','restaurant image'));document.getElementById('licence_image').addEventListener('change',()=>checkFile('licence_image','license image'));form.addEventListener('submit',e=>{let ok=true;['name','username','email','mobile_number','address','password'].forEach(id=>ok=checkField(id)&&ok);ok=checkConfirm()&&ok;ok=checkFile('restro_image','restaurant image')&&ok;ok=checkFile('licence_image','license image')&&ok;if(!ok)e.preventDefault();});})();
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
