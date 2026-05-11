<?php 
include('../frontend/config/constants.php');
unset($_SESSION['delivery-boy']); // Only unset delivery-boy session
header('location:login.php');
exit;
?>
