<?php
// Include constants.php for database connection
include('../frontend/config/constants.php');

// Check whether the `id` is set in the URL
if (isset($_GET['id'])) {
    // Get the ID of the coupon to be deleted
    $id = $_GET['id'];

    // Debug: Check if ID is retrieved correctly
    if (empty($id)) {
        $_SESSION['delete'] = "<div class='error'>Invalid Coupon ID</div>";
        header('location: manage-fest-coupon.php');
        exit();
    }

    // Create SQL query to delete the coupon
    $sql = "DELETE FROM tbl_fest_coupon WHERE id='$id'";

    // Execute the query
    $res = mysqli_query($conn, $sql);

    // Debug: Check if query executed successfully
    if ($res) {
        // Coupon deleted successfully
        $_SESSION['delete'] = "<div class='success'>Festival Coupon Deleted Successfully</div>";
    } else {
        // Failed to delete coupon
        $_SESSION['delete'] = "<div class='error'>Failed to Delete Festival Coupon. Error: " . mysqli_error($conn) . "</div>";
    }

    // Redirect to manage festival coupons page
    header('location: manage-fest-coupon.php');
} else {
    // Redirect to manage festival coupons page if `id` is not set
    $_SESSION['delete'] = "<div class='error'>Unauthorized Access</div>";
    header('location: manage-fest-coupon.php');
}
?>
