<?php 

//Starting the Session
ob_start();

$sessionLifetime = 60 * 60 * 24; // 24 hours
$secureCookie = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
$cookieParams = session_get_cookie_params();
$cookieParams = array(
    'lifetime' => $sessionLifetime,
    'path' => $cookieParams['path'] ?? '/',
    'domain' => $cookieParams['domain'] ?? '',
    'secure' => $secureCookie,
    'httponly' => true,
    'samesite' => 'Lax',
);

ini_set('session.gc_maxlifetime', (string) $sessionLifetime);
ini_set('session.cookie_lifetime', (string) $sessionLifetime);
session_set_cookie_params($cookieParams);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Creating constant to store non-repeating values
$basePath = '/r-management/';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
if (strpos($scriptName, '/admin/') !== false) {
    $basePath = '/r-management/admin/';
} elseif (strpos($scriptName, '/restro/') !== false) {
    $basePath = '/r-management/restro/';
} elseif (strpos($scriptName, '/delivery-boy/') !== false) {
    $basePath = '/r-management/delivery-boy/';
}
define('SITEURL', $basePath);

define('LOCALHOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'f_management');              

// SMTP mail settings.
// For Gmail: use your full Gmail address and a Google App Password.
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_ENCRYPTION', 'tls');

define('MAIL_USERNAME', 'utsavsarvaliya27@gmail.com');
define('MAIL_PASSWORD', 'vedmjmfeekiwpdmw');

define('MAIL_FROM_EMAIL', 'utsavsarvaliya27@gmail.com');
define('MAIL_FROM_NAME', 'Pasar-kita');

define('MAIL_REPLY_TO_EMAIL', 'utsavsarvaliya27@gmail.com');

define('MAIL_TIMEOUT', 20);
define('MAIL_VERIFY_PEER', false);
define('APP_OTP_TTL_SECONDS', 60);

// Low stock threshold used in restro panels and notifications.
$low_stock_threshold = 3;

// Google Maps API key (keep it in server-side config, not in JS files)
define('GOOGLE_MAPS_API_KEY', 'AIzaSyDYSBlQ9HF7MqndLVihj3QTJKh6tHbBOUQ');
// Create connection
$conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

require_once __DIR__ . '/mail.php';
?>
