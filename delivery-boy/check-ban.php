<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['banned'])) {
    echo "
    <script>
        alert('" . $_SESSION['banned'] . "');
        // Destroy session via AJAX
        fetch('destroy-session.php').then(() => {
            window.location.reload();
        });
    </script>";
}
?>
