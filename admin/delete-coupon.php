<?php
// Include constants.php for SITEURL and database connection
include('../frontend/config/constants.php');

// Check whether the `id` is set in the URL
if (isset($_GET['id'])) {
    // Get the ID of the coupon to be deleted
    $id = $_GET['id'];

    // Create SQL query to delete the coupon
    $sql = "DELETE FROM tbl_coupon WHERE id=$id";

    // Execute the query
    $res = mysqli_query($conn, $sql);

    // Check whether the query executed successfully
    if ($res == true) {
        // Coupon deleted successfully
        $_SESSION['delete'] = "<div class='success'>Coupon Deleted Successfully</div>";
        // Redirect to manage-coupons.php
        header('location:' . SITEURL . 'manage-coupons.php');
    } else {
        // Failed to delete coupon
        $_SESSION['delete'] = "<div class='error'>Failed to Delete Coupon</div>";
        // Redirect to manage-coupons.php
        header('location:' . SITEURL . 'manage-coupons.php');
    }
} else {
    // Redirect to manage-coupons.php if `id` is not set
    $_SESSION['delete'] = "<div class='error'>Unauthorized Access</div>";
    header('location:' . SITEURL . 'manage-coupons.php');
}
?>
