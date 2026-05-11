<?php
// Include the configuration file for database connection
include('../frontend/config/constants.php');

// Check if the `id` is passed in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // SQL query to update the status of the delivery boy to 'verified'
    $sql = "UPDATE tbl_delivery_boy SET status = 'verified' WHERE id = ?";

    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // On success, redirect back to the manage-delivery-boy.php page with a success message
        $_SESSION['success'] = "Delivery boy verified successfully.";
        header("Location: manage-delivery-boy.php");
        exit();
    } else {
        // On failure, redirect back with an error message
        $_SESSION['error'] = "Failed to verify the delivery boy. Please try again.";
        header("Location: manage-delivery-boy.php");
        exit();
    }
} else {
    // If `id` is not set in the URL, redirect with an error message
    $_SESSION['error'] = "Invalid request. No delivery boy ID provided.";
    header("Location: manage-delivery-boy.php");
    exit();
}
?>
