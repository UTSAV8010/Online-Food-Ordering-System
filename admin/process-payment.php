<?php
include('../frontend/config/constants.php');

// Check if ID is passed
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Update the payment status in the database
    $update_sql = "UPDATE tbl_delivery_payment SET payment_status = 'Paid' WHERE id = $id";
    $update_res = mysqli_query($conn, $update_sql);

    if ($update_res) {
        header('location:manage-delivery-payment.php');
    } else {
        echo "Failed to update payment status.";
    }
} else {
    echo "No payment ID provided.";
}
?>
