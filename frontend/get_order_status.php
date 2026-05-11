<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$database = "f_management"; // Your database name

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);

    // Select only the columns you need
    $sql = "SELECT order_id, username, cus_name, total_amount, order_status 
            FROM order_manager 
            WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            "success"       => true,
            "order_id"      => $row["order_id"],
            "username"      => $row["username"],
            "cus_name"      => $row["cus_name"],
            "total_price"   => $row["total_amount"],
            "order_status"  => $row["order_status"]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Order not found"]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}

$conn->close();
?>

