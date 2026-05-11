<?php 
include('../frontend/config/constants.php'); // Ensure database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Secure password hashing

    // Check if username already exists
    $check_duplicate = "SELECT username FROM tbl_admin WHERE username = ?";
    $stmt_check = $conn->prepare($check_duplicate);
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $res_check_duplicate = $stmt_check->get_result();

    if ($res_check_duplicate->num_rows > 0) {
        echo "exists"; // Username exists
    } else {
        // Insert data securely
        $sql = "INSERT INTO tbl_admin (full_name, email, username, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $full_name, $email, $username, $password);
        $res = $stmt->execute();

        if ($res) {
            echo "success"; // Admin added
        } else {
            echo "error"; // Insert failed
        }
    }
}
?>
