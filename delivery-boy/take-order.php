<?php
include('../frontend/config/constants.php');
session_start();

// Check if the order ID is passed via GET
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Get the username of the delivery boy from the session
    $delivery_boy_name = $_SESSION['delivery-boy'];

    // Update the order to assign the delivery boy and set the status to 'Processing'
    $query = "UPDATE order_manager SET delivery_boy_name = '$delivery_boy_name', order_status = 'OnTheWay' WHERE order_id = '$order_id'";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Order has been taken successfully!";
    } else {
        $_SESSION['error'] = "Failed to take the order. Please try again.";
    }

    // Redirect back to the orders page
    header("Location: manage-online-order.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: manage-online-order.php");
    exit();
}
?>
