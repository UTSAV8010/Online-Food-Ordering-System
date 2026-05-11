<?php 
include('../frontend/config/constants.php');
unset($_SESSION['restro-name']); // Only unset restro session
header('location:login.php');
exit;
?>
