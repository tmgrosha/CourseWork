<?php
require('db.php');

// Validate session
session_start();
if (!isset($_SESSION['username'])) {
    die('Unauthorized access');
}

// Get order details from POST request
$orderDetails = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die('Invalid JSON input');
}

$user = $_SESSION['username'];

// Fetch user ID from database
$stmt_user = $con->prepare("SELECT id FROM users WHERE username = ?");
$stmt_user->bind_param("s", $user);
$stmt_user->execute();
$stmt_user->bind_result($userId);
$stmt_user->fetch();
$stmt_user->close();

if (!$userId) {
    die('User not found');
}

// Insert order details into database
$date = date('Y-m-d');
$stmt_insert = $con->prepare("INSERT INTO orders (price, title, quantity, subtotal_amount, date, invoice_number, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");

if (!$stmt_insert) {
    die('Prepare failed: ' . $con->error);
}

foreach ($orderDetails as $order) {
    $price = $order['price'];
    $title = $order['title'];
    $quantity = $order['quantity'];
    $subtotalAmount = $order['subtotal_amount'];
    $invoiceNumber = $order['invoice_number'];
    
    $stmt_insert->bind_param("dssdssi", $price, $title, $quantity, $subtotalAmount, $date, $invoiceNumber, $userId);
    
    if (!$stmt_insert->execute()) {
        die('Execute failed: ' . $stmt_insert->error);
    }
}

$stmt_insert->close();
$con->close();
?>
