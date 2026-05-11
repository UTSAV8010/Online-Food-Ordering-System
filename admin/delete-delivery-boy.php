<?php
include('../frontend/config/constants.php'); // Include the database connection file

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Fetch the Aadhaar image path before deleting
    $sql_fetch = "SELECT adhar_image FROM tbl_delivery_boy WHERE id = $id";
    $res_fetch = mysqli_query($conn, $sql_fetch);
    
    if ($res_fetch && mysqli_num_rows($res_fetch) > 0) {
        $row = mysqli_fetch_assoc($res_fetch);
        $adhar_image = $row['adhar_image'];
        $file_path = "../delivery-boy/$adhar_image";
        
        // Delete the file if it exists
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    // Delete the delivery boy record from the database
    $sql = "DELETE FROM tbl_delivery_boy WHERE id = $id";
    $res = mysqli_query($conn, $sql);
    
    if ($res) {
        $_SESSION['success'] = "Delivery boy deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete delivery boy. Please try again.";
    }
    
    header("Location: manage-delivery-boy.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: manage-delivery-boy.php");
    exit();
}
?>
