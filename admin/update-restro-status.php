<?php
include('../frontend/config/constants.php');

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    // Fetch username of the restro being acted upon
    $sql_get = "SELECT restro_name FROM tbl_restro WHERE id = $id";
    $res_get = mysqli_query($conn, $sql_get);
    $row = mysqli_fetch_assoc($res_get);
    $username = $row['restro_name'];

    if ($action == 'approve') {
        $sql = "UPDATE tbl_restro SET status = 'approved' WHERE id = $id";
    } elseif ($action == 'block') {
        $sql = "UPDATE tbl_restro SET user_role = 0 WHERE id = $id";
        $res = mysqli_query($conn, $sql); // Execute block query early

        // If the blocked restro is currently logged in
        if (isset($_SESSION['restro-name']) && $_SESSION['restro-name'] === $username) {
            // Clear all session variables
            session_unset();
        
            // Destroy session
            session_destroy();
        
            // Optional: Clear session cookie for full cleanup
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
        
            // Stop further execution
            exit();
        }
        
        

        // If restro wasn't logged in, just continue back
        header("Location: manage-restro.php?success=blocked");
        exit();
    } elseif ($action == 'unblock') {
        $sql = "UPDATE tbl_restro SET user_role = 1 WHERE id = $id";
    } else {
        die("Invalid action.");
    }

    $res = mysqli_query($conn, $sql);

    if ($res) {
        header("Location: manage-restro.php?success=updated");
        exit();
    } else {
        header("Location: manage-restro.php?error=update_failed");
        exit();
    }
} else {
    header("Location: manage-restro.php?error=invalid_request");
    exit();
}
?> 
