<?php
if (isset($_COOKIE['blocked_message'])) {
    $message = $_COOKIE['blocked_message'];

    // Clear the cookie immediately so it doesn't repeat
    setcookie('blocked_message', '', time() - 3600, '/');

    // Output JS alert
    echo "<script>
        alert('$message');
    </script>";
}
?>
