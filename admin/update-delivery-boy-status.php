<?php
include('../frontend/config/constants.php');
session_start();

if (isset($_GET['id']) && isset($_GET['role'])) {
    $id = $_GET['id'];
    $role = $_GET['role'];

    // Fetch username for that id
    $sql_get = "SELECT username FROM tbl_delivery_boy WHERE id = $id";
    $res_get = mysqli_query($conn, $sql_get);
    $row = mysqli_fetch_assoc($res_get);
    $username = $row['username'];

    // Update role
    $sql = "UPDATE tbl_delivery_boy SET user_role = $role WHERE id = $id";
    $res = mysqli_query($conn, $sql);

    if ($res) {
        // If current logged-in user is being banned
        if (isset($_SESSION['delivery-boy']) && $_SESSION['delivery-boy'] === $username && $role == 0) {
            $_SESSION['banned'] = "Your movement has been banned for unnecessary reasons.";
        }

        $_SESSION['update'] = "Delivery Boy's Status Updated Successfully.";
        header('location: ' . SITEURL . 'manage-delivery-boy.php');
    } else {
        $_SESSION['update'] = "Failed to Update Delivery Boy's Status.";
        header('location: ' . SITEURL . 'manage-delivery-boy.php');
    }
} else {
    header('location: ' . SITEURL . 'manage-delivery-boy.php');
}
?>
