<?php
include('../frontend/config/constants.php');

if(isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];

    if($action == "approve") {
        // Approve food item
        $sql = "UPDATE tbl_restro_food_item SET status='approved' WHERE id=$id";
        $res = mysqli_query($conn, $sql);

        if($res) {
            $_SESSION['success'] = "<div class='success'>Food item approved successfully!</div>";
        } else {
            $_SESSION['error'] = "<div class='error'>Failed to approve food item.</div>";
        }
        header("Location: manage-restro-food.php");
    }
    elseif($action == "delete") {
        // Fetch the image name to delete from the folder
        $sql = "SELECT image_name FROM tbl_restro_food_item WHERE id=$id";
        $res = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($res);
        $image_name = $row['image_name'];

        if($image_name != "") {
            $image_path = "../restro/uploads/food/" . $image_name;
            if(file_exists($image_path)) {
                unlink($image_path); // Delete image
            }
        }

        // Delete food item from database
        $sql = "DELETE FROM tbl_restro_food_item WHERE id=$id";
        $res = mysqli_query($conn, $sql);

        if($res) {
            $_SESSION['success'] = "<div class='success'>Food item deleted successfully!</div>";
        } else {
            $_SESSION['error'] = "<div class='error'>Failed to delete food item.</div>";
        }
        header("Location: manage-restro-food.php");
    }
} else {
    $_SESSION['error'] = "<div class='error'>Unauthorized access!</div>";
    header("Location: manage-restro-food.php");
}
?>
