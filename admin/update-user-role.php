<?php
// Include database connection
include('../frontend/config/constants.php');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if ID and role are passed
if (isset($_GET['id']) && isset($_GET['role'])) {
    $id = $_GET['id'];
    $role = $_GET['role'];

    // Retrieve username of the user by ID
    $sqlUser = "SELECT username FROM tbl_users WHERE id = ?";
    $stmtUser = $conn->prepare($sqlUser);
    $stmtUser->bind_param('i', $id);
    $stmtUser->execute();
    $stmtUser->bind_result($usernameFromDB);
    $stmtUser->fetch();
    $stmtUser->close();

    // SQL query to update user role
    $sql = "UPDATE tbl_users SET user_role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $role, $id);

    // Execute the query
    if ($stmt->execute()) {
        $_SESSION['success'] = "User role updated successfully.";

        // Check if the updated user is the current session user
        if (isset($_SESSION['user']) && $_SESSION['user'] === $usernameFromDB) {
            // Set a temporary cookie for block message (expires in 10 seconds)
            setcookie('blocked_message', 'Your movement has been blocked for unnecessary reasons.', time() + 10, '/');
        
            // Proper session cleanup
            session_unset();
            session_destroy();
        
            // Redirect to the frontend
            header("Location: manage-ei-order.php");
            exit();
        }
        
        
        
    } else {
        $_SESSION['error'] = "Failed to update user role. Please try again.";
    }

    // Redirect back to manage users page
    header("Location: manage-ei-order.php");
    exit();
} else {
    // Redirect if required data is not provided
    $_SESSION['error'] = "Invalid request.";
    header("Location: manage-ei-order.php");
    exit();
}
?>
