<?php 
include('config/constants.php');

// Unset all user-related session variables
unset($_SESSION['user']);
unset($_SESSION['name']); // If you're storing user name
unset($_SESSION['email']); // If applicable
unset($_SESSION['role']);  // If stored

// Or use session_unset() if it's only used for frontend user
// session_unset(); // Optional - clears all session variables

// Redirect to login page
header('location:'.SITEURL.'login.php');
exit;
?>

