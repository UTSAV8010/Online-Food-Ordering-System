<?php
include('config/constants.php');

// Keep legacy /view route working by forwarding to the themed orders page.
header('Location: ' . SITEURL . 'view-orders.php');
exit;

