<?php
// Include database connection file
include('../frontend/config/constants.php');
session_start();

// Check if the order ID is passed via GET
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
    $delivery_boy_name = $_SESSION['delivery-boy']; // Delivery boy's username from the session

    // Begin transaction
    mysqli_begin_transaction($conn);

    try {
        // Fetch the total order value from the `order_manager` table
        $fetch_query = "SELECT total_amount FROM order_manager WHERE order_id = '$order_id'";
        $result = mysqli_query($conn, $fetch_query);

        if (!$result || mysqli_num_rows($result) == 0) {
            throw new Exception("Order not found.");
        }

        $row = mysqli_fetch_assoc($result);
        $total_amount = $row['total_amount'];

        // Calculate salary based on total order value
        if ($total_amount < 250) {
            $salary = 20;
        } elseif ($total_amount < 500) {
            $salary = 25;
        } elseif ($total_amount < 1000) {
            $salary = 30;
        } elseif ($total_amount < 1500) {
            $salary = 40;
        } elseif ($total_amount < 2000) {
            $salary = 50;
        } else {
            $salary = 60; // For orders of 2000 or more
        }

        // Update the order status to 'Delivered'
        $update_query = "UPDATE order_manager SET order_status = 'Delivered' WHERE order_id = '$order_id'";
        if (!mysqli_query($conn, $update_query)) {
            throw new Exception("Failed to update order status.");
        }

        // Insert a record into the `tbl_delivery_payment` table
        $created_at = date("Y-m-d H:i:s"); // Current timestamp
        $payment_status = 'unpaid'; // Default payment status
        $insert_query = "INSERT INTO tbl_delivery_payment (username, salary, order_id, created_at, payment_status) 
                         VALUES ('$delivery_boy_name', '$salary', '$order_id', '$created_at', '$payment_status')";

        if (!mysqli_query($conn, $insert_query)) {
            throw new Exception("Failed to insert delivery payment record.");
        }

        // Commit transaction
        mysqli_commit($conn);

        $_SESSION['success'] = "Order has been marked as delivered, and payment has been recorded!";
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        $_SESSION['error'] = $e->getMessage();
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
