<?php
require('fpdf/fpdf.php'); // Include FPDF library

// Database connection
include('db_connection.php'); // Replace with your connection file

if (isset($_POST['download_receipt'])) {
    $order_id = $_POST['order_id'];

    // Fetch order details
    $order_query = "SELECT * FROM order_manager WHERE order_id = '$order_id'";
    $order_result = mysqli_query($conn, $order_query);
    $order_data = mysqli_fetch_assoc($order_result);

    // Fetch ordered items
    $items_query = "SELECT * FROM online_orders_new WHERE order_id = '$order_id'";
    $items_result = mysqli_query($conn, $items_query);

    // Create PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Add Title
    $pdf->Cell(190, 10, "Receipt for Order ID: $order_id", 0, 1, 'C');
    $pdf->Ln(10);

    $transaction_id = trim((string) ($order_data['transaction_id'] ?? ''));
    $upi_id = '';
    $upi_ref = '';
    if (preg_match('/UPI\\s*:\\s*([^|]+)\\|\\s*UTR\\s*:\\s*(.+)$/i', $transaction_id, $upiMatch)) {
        $upi_id = trim($upiMatch[1] ?? '');
        $upi_ref = trim($upiMatch[2] ?? '');
    }

    // Add Order Details
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(50, 10, "Customer Name: " . $order_data['cus_name'], 0, 1);
    $pdf->Cell(50, 10, "Payment Status: " . $order_data['payment_status'], 0, 1);
    $pdf->Cell(50, 10, "Transaction: " . ($upi_ref !== '' ? $upi_ref : ($transaction_id !== '' ? $transaction_id : 'N/A')), 0, 1);
    if ($upi_id !== '' || $upi_ref !== '') {
        $pdf->Cell(50, 10, "UPI ID: " . ($upi_id !== '' ? $upi_id : 'N/A'), 0, 1);
        $pdf->Cell(50, 10, "UPI Ref: " . ($upi_ref !== '' ? $upi_ref : 'N/A'), 0, 1);
    }
    $pdf->Cell(50, 10, "Order Status: " . $order_data['order_status'], 0, 1);
    $pdf->Cell(50, 10, "Total Amount: " . $order_data['total_amount'], 0, 1);
    $pdf->Ln(10);

    // Add Ordered Items
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 10, "Item Name", 1);
    $pdf->Cell(40, 10, "Price", 1);
    $pdf->Cell(40, 10, "Quantity", 1);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    while ($item = mysqli_fetch_assoc($items_result)) {
        $pdf->Cell(60, 10, $item['Item_Name'], 1);
        $pdf->Cell(40, 10, $item['Price'], 1);
        $pdf->Cell(40, 10, $item['Quantity'], 1);
        $pdf->Ln();
    }

    // Output PDF
    $pdf->Output("D", "Receipt_Order_$order_id.pdf");
}
?>

