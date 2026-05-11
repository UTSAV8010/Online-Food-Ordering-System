<?php
include('../frontend/config/constants.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Get the image name to delete from the folder
    $sql = "SELECT image_name FROM tbl_restro_food_item WHERE id = $id";
    $res = mysqli_query($conn, $sql);

    if ($res == true) {
        $row = mysqli_fetch_assoc($res);
        $image_name = $row['image_name'];

        // Remove the image file if it exists
        if ($image_name != "") {
            $image_path = "images/food/" . $image_name;
            if (file_exists($image_path)) {
                unlink($image_path); // Delete the image file
            }
        }
    }

    // Delete food item from the database
    $sql2 = "DELETE FROM tbl_restro_food_item WHERE id = $id";
    $res2 = mysqli_query($conn, $sql2);

    if ($res2 == true) {
        $_SESSION['delete'] = "<div class='success'>Food Item Deleted Successfully.</div>";
        header('location:' . SITEURL . 'manage-food.php');
    } else {
        $_SESSION['delete'] = "<div class='error'>Failed to Delete Food Item.</div>";
        header('location:' . SITEURL . 'manage-food.php');
    }
} else {
    $_SESSION['unauthorized'] = "<div class='error'>Unauthorized Access.</div>";
    header('location:' . SITEURL . 'manage-food.php');
}
?>
