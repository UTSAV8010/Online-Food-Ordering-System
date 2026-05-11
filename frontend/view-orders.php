<?php 
include('config/constants.php');

include('config/blocked-check.php'); 
// Include Composer's autoload file to load TCPDF
require_once 'vendor/autoload.php';

// Check if the form to download receipt is submitted
if (isset($_POST['download_receipt'])) {
    $order_id = (int) ($_POST['order_id'] ?? 0);
    if ($order_id <= 0) {
        exit('Invalid order.');
    }

    date_default_timezone_set('Asia/Dhaka');

    $query = "SELECT * FROM `order_manager` WHERE order_id = {$order_id} LIMIT 1";
    $order_result = mysqli_query($conn, $query);
    $order_data = $order_result ? mysqli_fetch_assoc($order_result) : null;

    $items_query = "SELECT * FROM `online_orders_new` WHERE `order_id` = {$order_id} ORDER BY `Item_Name` ASC";
    $items_result = mysqli_query($conn, $items_query);

    if (!$order_data || !$items_result) {
        exit('Unable to generate receipt for this order.');
    }

    $escape = static function ($value): string {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    };

    $formatMoney = static function ($amount): string {
        return 'Rs. ' . number_format((float) $amount, 2);
    };

    $normalizeStatus = static function ($value): string {
        return strtolower(str_replace(array(' ', '_', '-'), '', trim((string) $value)));
    };

    $formatOrderStatus = static function ($value) use ($normalizeStatus): string {
        $normalized = $normalizeStatus($value);
        $map = array(
            'ontheway' => 'On The Way',
            'delivered' => 'Delivered',
            'processing' => 'Processing',
            'pending' => 'Pending',
            'cancelled' => 'Cancelled'
        );

        if (isset($map[$normalized])) {
            return $map[$normalized];
        }

        $value = preg_replace('/(?<!^)([A-Z])/', ' $1', trim((string) $value));
        $value = str_replace(array('_', '-'), ' ', $value);
        return ucwords(strtolower($value));
    };

    $formatPaymentStatus = static function ($value) use ($normalizeStatus): string {
        $normalized = $normalizeStatus($value);
        $map = array(
            'successful' => 'Paid Online',
            'cod' => 'Cash on Delivery',
            'upi' => 'UPI Transfer',
            'refunded' => 'Refunded'
        );

        return $map[$normalized] ?? ucwords(strtolower(trim((string) $value)));
    };

    $itemsHtml = '';
    $subtotalAmount = 0.0;
    $totalQuantity = 0;
    $itemCount = 0;

    while ($item = mysqli_fetch_assoc($items_result)) {
        $itemCount++;
        $unitPrice = (float) ($item['Price'] ?? 0);
        $quantity = (int) ($item['Quantity'] ?? 0);
        $lineTotal = $unitPrice * $quantity;
        $subtotalAmount += $lineTotal;
        $totalQuantity += $quantity;

        $itemsHtml .= '
            <tr>
                <td width="56%" style="padding: 10px 12px; border-bottom: 1px solid #DFDFDF; color: #111111; font-size: 10.8px;">' . $escape($item['Item_Name'] ?? '') . '</td>
                <td width="14%" style="padding: 10px 12px; border-bottom: 1px solid #DFDFDF; color: #111111; text-align: center; font-size: 10.8px;">' . $quantity . '</td>
                <td width="15%" style="padding: 10px 12px; border-bottom: 1px solid #DFDFDF; color: #111111; text-align: right; font-size: 10.8px;">' . $escape($formatMoney($unitPrice)) . '</td>
                <td width="15%" style="padding: 10px 12px; border-bottom: 1px solid #DFDFDF; color: #111111; text-align: right; font-size: 10.8px;">' . $escape($formatMoney($lineTotal)) . '</td>
            </tr>';
    }

    if ($itemsHtml === '') {
        $itemsHtml = '
            <tr>
                <td colspan="4" style="padding: 14px 12px; border-bottom: 1px solid #DFDFDF; color: #333333; text-align: center; font-size: 10.8px;">
                    No order items found for this invoice.
                </td>
            </tr>';
    }

    $grandTotal = (float) ($order_data['total_amount'] ?? 0);
    $discountAmount = max(0, $subtotalAmount - $grandTotal);
    $extraChargeAmount = max(0, $grandTotal - $subtotalAmount);
    $orderStatus = $formatOrderStatus($order_data['order_status'] ?? '');
    $paymentStatus = $formatPaymentStatus($order_data['payment_status'] ?? '');
    $receiptNumber = 'PK-' . str_pad((string) $order_id, 6, '0', STR_PAD_LEFT);
    $formattedOrderDate = !empty($order_data['order_date']) ? date('d M Y', strtotime((string) $order_data['order_date'])) : date('d M Y');
    $generatedAt = date('d M Y, h:i A');
    $dueDate = !empty($order_data['order_date']) ? date('d M Y', strtotime((string) $order_data['order_date'] . ' +2 days')) : date('d M Y', strtotime('+2 days'));
    $customerAddress = trim(implode(', ', array_filter(array(
        $order_data['cus_add1'] ?? '',
        $order_data['cus_city'] ?? ''
    ))));
    $customerAddress = $customerAddress !== '' ? $customerAddress : 'Not available';
    $customerLocation = trim((string) ($order_data['location'] ?? ''));
    $customerLocation = $customerLocation !== '' ? $customerLocation : 'Not available';
    $transactionId = trim((string) ($order_data['transaction_id'] ?? ''));
    $transactionId = $transactionId !== '' ? $transactionId : 'Not available';
    $upiId = '';
    $upiRef = '';
    if (preg_match('/UPI\\s*:\\s*([^|]+)\\|\\s*UTR\\s*:\\s*(.+)$/i', $transactionId, $upiMatch)) {
        $upiId = trim($upiMatch[1] ?? '');
        $upiRef = trim($upiMatch[2] ?? '');
    }
    $deliveryBoy = trim((string) ($order_data['delivery_boy_name'] ?? ''));
    $deliveryBoy = $deliveryBoy !== '' ? $deliveryBoy : 'Not assigned';
    $customerEmail = trim((string) ($order_data['cus_email'] ?? ''));
    $customerEmail = $customerEmail !== '' ? $customerEmail : 'Not available';
    $customerPhone = trim((string) ($order_data['cus_phone'] ?? ''));
    $customerPhone = $customerPhone !== '' ? $customerPhone : 'Not available';
    $usernameLabel = trim((string) ($order_data['username'] ?? ''));
    $usernameLabel = $usernameLabel !== '' ? $usernameLabel : 'N/A';
    $wrapForPdf = static function ($value, $width = 32) use ($escape): string {
        $text = trim((string) $value);
        if ($text === '') {
            return 'N/A';
        }
        return nl2br($escape(wordwrap($text, $width, "\n", true)));
    };
    $customerAddressPdf = $wrapForPdf($customerAddress, 35);
    $customerEmailPdf = $wrapForPdf($customerEmail, 30);
    $customerLocationPdf = $wrapForPdf($customerLocation, 32);
    $transactionLabel = $upiRef !== '' ? $upiRef : $transactionId;
    $transactionIdPdf = $wrapForPdf($transactionLabel, 24);
    $upiIdPdf = $wrapForPdf($upiId, 24);
    $upiRefPdf = $wrapForPdf($upiRef, 24);
    $upiReceiptBlock = '';
    if ($upiId !== '' || $upiRef !== '') {
        $upiReceiptBlock = '<br><strong>UPI ID:</strong> ' . $upiIdPdf . '<br><strong>UPI Ref:</strong> ' . $upiRefPdf;
    }

    $logoPath = realpath(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'logo2.png');
    $logoDataUri = '';
    if ($logoPath && is_file($logoPath)) {
        $logoDataUri = 'data:image/png;base64,' . base64_encode((string) file_get_contents($logoPath));
    }

    $vatRate = 0;
    $vatAmount = 0.00;

    $adjustmentRows = '';
    if ($discountAmount > 0) {
        $adjustmentRows .= '
            <tr>
                <td class="summary-label summary-discount">Discount</td>
                <td class="summary-value summary-discount">- ' . $escape($formatMoney($discountAmount)) . '</td>
            </tr>';
    }
    if ($extraChargeAmount > 0) {
        $adjustmentRows .= '
            <tr>
                <td class="summary-label summary-extra">Other Charges</td>
                <td class="summary-value summary-extra">' . $escape($formatMoney($extraChargeAmount)) . '</td>
            </tr>';
    }
    $companyPhone = '+91 9978043407';
    $companyEmail = 'Pasar-kita@gmail.com';
    $companyAddress = 'Surat, Ahmedabad, Baroda';
    $invoiceSerial = str_pad((string) $order_id, 6, '0', STR_PAD_LEFT);

    $html = '
    <style>
        .invoice-shell {
            background-color: #FFFFFF;
            color: #1A1A1A;
            padding: 22px 20px 20px;
            font-family: helvetica;
        }
        .top-left {
            font-size: 11px;
            color: #454545;
            line-height: 1.5;
        }
        .top-right {
            font-size: 11px;
            color: #454545;
            text-align: right;
            letter-spacing: 0.6px;
        }
        
        .date-row {
            margin-bottom: 24px;
            font-size: 13px;
            color: #2D2D2D;
        }
        .party-title {
            font-size: 12px;
            font-weight: bold;
            color: #1B1B1B;
            margin-bottom: 6px;
        }
        .party-text {
            font-size: 11px;
            color: #303030;
            line-height: 1.55;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 26px;
        }
        .items-head td {
            background-color: #EFEFEF;
            color: #2A2A2A;
            font-size: 11px;
            letter-spacing: 0.2px;
            padding: 10px 12px;
            border-bottom: 1px solid #D8D8D8;
        }
        .items-total-row td {
            border-top: 1px solid #D4D4D4;
            padding: 11px 12px;
            font-size: 12px;
            font-weight: bold;
            color: #1A1A1A;
        }
        .payment-row {
            margin-top: 22px;
            font-size: 11.5px;
            color: #2E2E2E;
            line-height: 1.75;
        }
        .payment-row strong {
            color: #111111;
        }
    </style>

    <div class="invoice-shell">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="58%" valign="top">
                    <div class="top-left">
                        ' . ($logoDataUri !== '' ? '<img src="' . $escape($logoDataUri) . '" style="width:44px; height:auto; display:block; margin-bottom:8px;" alt="Logo">' : '') . '
                    </div>
                </td>
                <td width="42%" valign="top">
                    <div class="top-right">NO. ' . $escape($invoiceSerial) . '</div>
                </td>
            </tr>
        </table>

       

        <div class="date-row"><strong>Date:</strong> ' . $escape($formattedOrderDate) . '</div>

        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="50%" valign="top" style="padding-right:18px;">
                    <div class="party-title">Billed to:</div>
                    <div class="party-text">
                        ' . $escape($order_data['cus_name'] ?? '') . '<br>
                        ' . $customerAddressPdf . '<br>
                        ' . $customerEmailPdf . '
                    </div>
                </td>
                <td width="50%" valign="top" style="padding-left:18px;">
                    <div class="party-title">From:</div>
                    <div class="party-text">
                        Pasar Kita<br>
                        ' . $escape($companyAddress) . '<br>
                        ' . $escape($companyEmail) . '
                    </div>
                </td>
            </tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0" class="items-table">
            <tr class="items-head">
                <td width="56%">Item</td>
                <td width="14%" style="text-align:center;">Quantity</td>
                <td width="15%" style="text-align:right;">Price</td>
                <td width="15%" style="text-align:right;">Amount</td>
            </tr>
            ' . $itemsHtml . '
            <tr class="items-total-row">
                <td colspan="3" style="text-align:right;">Total</td>
                <td style="text-align:right;">' . $escape($formatMoney($grandTotal)) . '</td>
            </tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:18px;">
            <tr>
                <td width="50%" valign="top">
                    <div class="payment-row">
                        <strong>Payment method:</strong> ' . $escape($paymentStatus) . '<br>
                        <strong>Note:</strong> Thank you for choosing us!
                    </div>
                </td>
                <td width="50%" valign="top" style="text-align:right;">
                    <div class="payment-row">
                        <strong>Order ID:</strong> #' . $order_id . '<br>
                        <strong>Transaction:</strong> ' . $transactionIdPdf . $upiReceiptBlock . '
                    </div>
                </td>
            </tr>
        </table>
    </div>';

    $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetCreator('Pasar Kita');
    $pdf->SetAuthor('Pasar Kita');
    $pdf->SetTitle('Invoice #' . $receiptNumber);
    $pdf->SetSubject('Pasar Kita Invoice');
    $pdf->SetKeywords('Pasar Kita, Invoice');
    $pdf->SetMargins(14, 14, 14);
    $pdf->SetAutoPageBreak(true, 14);
    $pdf->SetFont('helvetica', '', 10.5);
    $pdf->AddPage();

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetY(10);
    $pdf->writeHTML($html, true, false, true, false, '');
    if (ob_get_length()) {
        ob_end_clean();
    }
    $pdf->Output('Invoice_' . $order_id . '.pdf', 'D');
    exit;
}
?>


<?php 
 date_default_timezone_set('Asia/Dhaka');
 if(!isset($_SESSION['user'])) //If user session is not set
{
    //User is not logged in
    //Redirect to login page with message

    $_SESSION['no-login-message'] = "<div class='error'>Please login to access Admin Panel</div>";
    header('location:'.SITEURL.'login.php');
}

    if(isset($_SESSION['user']))
    {
       $username = $_SESSION['user'];

       $fetch_user = "SELECT * FROM tbl_users WHERE username = '$username'";

       $res_fetch_user = mysqli_query($conn, $fetch_user);

       while($rows=mysqli_fetch_assoc($res_fetch_user))
       {
           $id = $rows['id'];
           $name = $rows['name'];
           $email = $rows['email'];
           $add1 = $rows['add1'];
           $city = $rows['city'];
           $phone = $rows['phone'];
           $username = $rows['username'];
           $password = $rows['password'];

       }
    }

    $displayName = trim((string) ($name ?? ''));
    $displayUsername = trim((string) ($username ?? ''));
    $avatarName = $displayName !== '' ? $displayName : $displayUsername;
    if ($avatarName === '') {
        $avatarName = 'U';
    }
    if (function_exists('mb_substr') && function_exists('mb_strtoupper')) {
        $profileInitial = mb_strtoupper(mb_substr($avatarName, 0, 1, 'UTF-8'), 'UTF-8');
    } else {
        $profileInitial = strtoupper(substr($avatarName, 0, 1));
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Pasar-kita.com</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="images/logo2.png">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&family=Pacifico&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <style>
        .container-xxl {
            max-width: 100%;
        }

        :root {
            --orders-bg: linear-gradient(180deg, #f3f7ff 0%, #fef7ef 100%);
            --card-bg: #ffffff;
            --title-color: #0f214a;
            --muted-text: #5f6b86;
            --border-soft: #e6eaf4;
            --accent: #fea116;
            --accent-dark: #e08a05;
        }

        .scroll-top-button:hover {
            background: #e69500;
        }

        .back-to-top {
            right: 0 !important;
            bottom: 27px !important;
        }

        .orders-shell {
            padding-top: 12px;
            padding-bottom: 28px;
        }

        .profile-panel,
        .orders-main-card {
            border: 1px solid var(--border-soft);
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 14px 36px rgba(15, 33, 74, 0.08);
        }

        .profile-panel {
            overflow: hidden;
        }

        .profile-top {
            padding: 26px 24px 20px;
            text-align: center;
            background: #e69500;
            color: #fff;
        }

        .profile-avatar {
            width: 98px;
            height: 98px;
            border-radius: 999px;
            border: 4px solid rgba(255, 255, 255, 0.24);
            margin: 0 auto 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eef1f6;
            color: #5f6b86;
            font-size: 2.2rem;
            font-weight: 800;
            text-transform: uppercase;
            text-decoration: none;
            line-height: 1;
        }

        .profile-avatar:hover,
        .profile-avatar:focus {
            color: #324263;
            text-decoration: none;
        }

        .profile-top h1 {
            font-size: 1.45rem;
            margin: 0;
            word-break: break-word;
        }

        .profile-menu {
            padding: 18px;
        }

        .profile-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #22345e;
            border: 1px solid var(--border-soft);
            border-radius: 12px;
            padding: 11px 12px;
            font-weight: 700;
            text-decoration: none;
            margin-bottom: 10px;
            transition: all 0.2s ease;
        }

        .profile-menu a:hover,
        .profile-menu a.active {
            color: #fff;
            border-color: transparent;
            background: linear-gradient(90deg, #fea116, #f57f17);
        }

        .orders-main-card {
            padding: 22px;
            min-height: 100%;
        }

        .orders-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .orders-heading h2 {
            margin: 0;
            color: var(--title-color);
            font-size: clamp(1.3rem, 2vw, 1.9rem);
            font-weight: 800;
        }

        .orders-heading p {
            margin: 0;
            color: var(--muted-text);
            font-weight: 600;
        }

        .order-card {
            border: 1px solid var(--border-soft);
            border-radius: 16px;
            background: #fff;
            padding: 16px;
            margin-bottom: 14px;
        }

        .order-top {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 12px;
        }

        .meta-label {
            display: block;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #7d88a6;
            margin-bottom: 2px;
            font-weight: 700;
        }

        .meta-value {
            font-size: 1rem;
            color: #1b2e56;
            font-weight: 800;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 8px;
            font-weight: 500;
            line-height: 1;
            border: 1px solid transparent;
            text-transform: capitalize;
        }

        .status-success {
            color: #146c43;
            background: #d1f7e4;
            border-color: #a9ebcd;
        }

        .status-warning {
            color: #7b5800;
            background: #fff4cf;
            border-color: #ffe49c;
        }

        .status-info {
            color: #0d5e8c;
            background: #d9f1ff;
            border-color: #addfff;
        }

        .status-danger {
            color: #91293c;
            background: #ffe2e8;
            border-color: #ffc5d1;
        }

        .status-neutral {
            color: #4e5a78;
            background: #eef2fb;
            border-color: #d9e1f2;
        }

        .order-items-wrap {
            border: 1px solid var(--border-soft);
            border-radius: 12px;
            overflow: hidden;
        }

        .order-items-table {
            margin-bottom: 0;
        }

        .order-items-table thead th {
            background: #f7faff;
            color: #263a67;
            border-bottom: 1px solid var(--border-soft);
            font-size: 0.87rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .order-items-table td {
            color: #304570;
            font-weight: 600;
        }

        .action-wrap {
            margin-top: 14px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn-action {
            border-radius: 999px;
            font-weight: 700;
            padding: 9px 16px;
        }

        .alert-modern {
            border-radius: 14px;
            border-width: 1px;
            font-weight: 600;
            margin-bottom: 18px;
        }

        .no-orders {
            text-align: center;
            padding: 34px 18px;
            border: 1px dashed #ced9ef;
            border-radius: 16px;
            color: #607095;
            font-weight: 700;
            background: #f9fbff;
        }

        @media (max-width: 991.98px) {
            .order-top {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 575.98px) {
            .orders-main-card {
                padding: 16px;
            }

            .profile-top {
                padding: 22px 16px 18px;
            }

            .order-card {
                padding: 13px;
            }

            .order-top {
                grid-template-columns: 1fr;
                gap: 9px;
            }

            .btn-action {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <!-- <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
</div> -->
        <!-- Spinner End -->

        <div class="container-xxl position-relative p-0">
            <?php include('site-hader.php'); ?>
        </div>
        <?php
 


// Cancel order functionality remains the same as in your original code.
$alert_message = '';
$alert_type = 'success';
if (isset($_POST['cancel_order'])) {
    // Cancel order logic
    $order_id = $_POST['order_id'];
    $query = "SELECT cus_name, payment_status FROM order_manager WHERE order_id = '$order_id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $user_fetch = mysqli_fetch_assoc($result);
        $cus_name = $user_fetch['cus_name'];
        $payment_status = $user_fetch['payment_status'];

        $aamarpay_query = "SELECT id FROM aamarpay WHERE order_id = '$order_id' LIMIT 1";
        $aamarpay_result = mysqli_query($conn, $aamarpay_query);

        if ($aamarpay_result) {
            $aamarpay_fetch = mysqli_fetch_assoc($aamarpay_result);
            $aamarpay_id = $aamarpay_fetch['id'];

            // Check payment status
            if ($payment_status === 'successful' || $payment_status === 'upi') {
                $update_payment_status = "Refunded";
            } else {
                $update_payment_status = "cod";
            }

            $update_order_query = "UPDATE order_manager SET order_status = 'Cancelled', payment_status = '$update_payment_status' WHERE order_id = '$order_id'";
            $update_order_result = mysqli_query($conn, $update_order_query);

            if ($update_order_result) {
                $update_aamarpay_query = "UPDATE aamarpay SET status = 'Cancelled' WHERE order_id = '$order_id'";
                $update_aamarpay_result = mysqli_query($conn, $update_aamarpay_query);

                if ($update_aamarpay_result) {
                    $alert_message = "Order has been successfully cancelled. Payment status updated to '$update_payment_status'.";
                    $alert_type = 'success';
                } else {
                    $alert_message = "Failed to update payment status in aamarpay.";
                    $alert_type = 'danger';
                }
            } else {
                $alert_message = "Failed to update order status and payment status in order_manager.";
                $alert_type = 'danger';
            }
        } else {
            $alert_message = "Error: Unable to fetch aamarpay details for the order.";
            $alert_type = 'danger';
        }
    } else {
        $alert_message = "Error: Unable to fetch order details.";
        $alert_type = 'danger';
    }
}

?>

        <div class="container bootstrap snippets bootdey orders-shell">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="profile-panel">
                        <div class="profile-top">
                            <a href="myaccount.php" class="profile-avatar" aria-label="Profile avatar">
                                <?php echo htmlspecialchars($profileInitial); ?>
                            </a>
                            <h1><?php echo htmlspecialchars($name); ?></h1>
                        </div>
                        <div class="profile-menu">
                            <a href="update-account.php"><i class="fa fa-user-edit"></i> Edit Profile</a>
                            <a href="view-orders.php" class="active"><i class="fa fa-shopping-bag"></i> View Orders</a>
                            <a href="update-password.php"><i class="fa fa-lock"></i> Change Password</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="orders-main-card">
                        <div class="orders-heading">
                            <h2>Recent Orders</h2>
                            <p>Sorted by latest order ID</p>
                        </div>
                        <?php if (!empty($alert_message)) { ?>
                            <div class="alert alert-<?php echo $alert_type === 'success' ? 'success' : 'danger'; ?> alert-modern" role="alert">
                                <?php echo htmlspecialchars($alert_message); ?>
                            </div>
                        <?php } ?>
                        <?php
                        $query = "SELECT * FROM `order_manager` WHERE username='$username' ORDER BY order_id DESC";
                        $user_result = mysqli_query($conn, $query);
                        if ($user_result && mysqli_num_rows($user_result) > 0) {
                            while ($user_fetch = mysqli_fetch_assoc($user_result)) {
                                $order_id = $user_fetch['order_id'];
                                $payment_status = $user_fetch['payment_status'];
                                $order_status = $user_fetch['order_status'];
                                $total_amount = $user_fetch['total_amount'];

                                $payment_class = 'status-neutral';
                                if ($payment_status == "successful" || $payment_status == "upi") {
                                    $payment_class = 'status-success';
                                } elseif ($payment_status == "Refunded") {
                                    $payment_class = 'status-danger';
                                } elseif ($payment_status == "cod") {
                                    $payment_class = 'status-info';
                                }

                                $order_class = 'status-neutral';
                                if ($order_status == "Pending") {
                                    $order_class = 'status-warning';
                                } elseif ($order_status == "Processing") {
                                    $order_class = 'status-info';
                                } elseif ($order_status == "OnTheWay" || $order_status == "Delivered") {
                                    $order_class = 'status-success';
                                } elseif ($order_status == "Cancelled") {
                                    $order_class = 'status-danger';
                                }
                        ?>
                            <div class="order-card">
                                <div class="order-top">
                                    <div>
                                        <span class="meta-label">Order ID</span>
                                        <span class="meta-value">#<?php echo htmlspecialchars($order_id); ?></span>
                                    </div>
                                    <div>
                                        <span class="meta-label">Payment</span>
                                        <span class="status-pill <?php echo $payment_class; ?>"><?php echo htmlspecialchars($payment_status); ?></span>
                                    </div>
                                    <div>
                                        <span class="meta-label">Order Status</span>
                                        <span class="status-pill <?php echo $order_class; ?>"><?php echo htmlspecialchars($order_status); ?></span>
                                    </div>
                                    <div>
                                        <span class="meta-label">Total</span>
                                        <span class="meta-value">BDT <?php echo htmlspecialchars($total_amount); ?></span>
                                    </div>
                                </div>
                                <div class="order-items-wrap table-responsive">
                                    <table class="table order-items-table">
                                        <thead>
                                            <tr>
                                                <th>Item Name</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $order_query = "SELECT * FROM `online_orders_new` WHERE `order_id`='$user_fetch[order_id]' ORDER BY order_id DESC";
                                            $order_result = mysqli_query($conn, $order_query);
                                            while ($order_fetch = mysqli_fetch_assoc($order_result)) {
                                            ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($order_fetch['Item_Name']); ?></td>
                                                    <td><?php echo htmlspecialchars($order_fetch['Price']); ?></td>
                                                    <td><?php echo htmlspecialchars($order_fetch['Quantity']); ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="action-wrap">
                                    <?php
                                    $review_query = "SELECT * FROM tbl_review WHERE order_id = '$order_id' LIMIT 1";
                                    $review_result = mysqli_query($conn, $review_query);
                                    $review_exists = mysqli_num_rows($review_result) > 0;

                                    if ($order_status == 'Delivered') {
                                        if (!$review_exists) {
                                    ?>
                                            <form action="review-rider" method="post">
                                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
                                                <button type="submit" name="review_rider" class="btn btn-primary btn-action">Delivery Boy Review</button>
                                            </form>
                                    <?php
                                        }
                                    ?>
                                        <form action="view-orders" method="post">
                                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
                                            <button type="submit" name="download_receipt" class="btn btn-success btn-action">Download Receipt</button>
                                        </form>
                                    <?php
                                    } elseif ($order_status == 'Cancelled') {
                                    ?>
                                        <span class="status-pill status-neutral">No action available</span>
                                    <?php
                                    } else {
                                        if ($payment_status != 'Refunded') {
                                    ?>
                                            <form action="view-orders" method="post">
                                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
                                                <button type="submit" name="cancel_order" class="btn btn-danger btn-action">Cancel Order</button>
                                            </form>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php
                            }
                        } else {
                        ?>
                            <div class="no-orders">No orders found for your account yet.</div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <?php include('chatbot.php'); ?>

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries --> 


    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
<?php include('site-footer.php'); ?>
</body>

</html>

