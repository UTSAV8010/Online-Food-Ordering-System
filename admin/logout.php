<?php 
include('../frontend/config/constants.php'); // This must have session_start()
unset($_SESSION['user-admin']); // Only unset admin session
header('location:login.php');
exit;
?>
