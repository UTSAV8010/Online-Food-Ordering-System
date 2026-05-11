<?php
include('../frontend/config/constants.php');

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    // Ensure ID is a valid integer
    if (!is_numeric($id) || empty($id)) {
        $_SESSION['update'] = "<div class='error'>Invalid Category ID</div>";
        header('location:' . SITEURL . 'manage-restro-category.php');
        exit();
    }

    if ($action == 'approve') {
        // Update the status to "approved"
        $sql = "UPDATE tbl_rcategory_notapproved SET status='approved' WHERE cid=$id";
        $res = mysqli_query($conn, $sql);

        if ($res) {
            $_SESSION['update'] = "<div class='success'>Category Approved Successfully</div>";
        } else {
            $_SESSION['update'] = "<div class='error'>Failed to Approve Category. Error: " . mysqli_error($conn) . "</div>";
        }
    } elseif ($action == 'delete') {
        // Delete the category from the database
        $sql = "DELETE FROM tbl_rcategory_notapproved WHERE id=$id";
        $res = mysqli_query($conn, $sql);

        if ($res) {
            $_SESSION['delete'] = "<div class='success'>Category Deleted Successfully</div>";
        } else {
            $_SESSION['delete'] = "<div class='error'>Failed to Delete Category. Error: " . mysqli_error($conn) . "</div>";
        }
    }

    // Redirect back to the category management page
    header('location:' . SITEURL . 'manage-restro-category.php');
    exit();
} else {
    // Redirect if the request is invalid
    $_SESSION['update'] = "<div class='error'>Invalid Request</div>";
    header('location:' . SITEURL . 'manage-restro-category.php');
    exit();
}


?>
