<?php
session_start();

// Include the database connection file
include '../users/db.php';

// Check if the cart is not empty
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    echo "<p>Your cart is empty. <a href='cart.php'>Return to cart</a></p>";
    exit();
}

// Handle form submission if a payment method is chosen
if (isset($_POST['payment_method'])) {
    $payment_method = $_POST['payment_method'];
    
    // Redirect to the respective payment gateway based on the user's choice
    switch ($payment_method) {
        case 'esewa':
            header('Location: esewa_payment.php');
            break;
        case 'phonepay':
            header('Location: phonepay_payment.php');
            break;
        case 'bankpay':
            header('Location: bankpay_payment.php');
            break;
        case 'khalti':
            header('Location: khalti_payment.php');
            break;
        default:
            echo "<p>Invalid payment method selected. Please try again.</p>";
            break;
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="../assets/css/includes.css">
    <link rel="stylesheet" href="../assets/css/cart.css">
</head>
<body>
    <?php include "../includes/header2.php"; ?>

    <main>
        <section class="payment-page">
            <h3>Choose Your Payment Method</h3>
            <form method="post" action="payment.php">
                <div class="payment-option">
                    <input type="radio" id="esewa" name="payment_method" value="esewa" required>
                    <label for="esewa">eSewa</label>
                </div>
                <div class="payment-option">
                    <input type="radio" id="phonepay" name="payment_method" value="phonepay">
                    <label for="phonepay">Phone Pay</label>
                </div>
                <div class="payment-option">
                    <input type="radio" id="bankpay" name="payment_method" value="bankpay">
                    <label for="bankpay">Bank Pay</label>
                </div>
                <div class="payment-option">
                    <input type="radio" id="khalti" name="payment_method" value="khalti">
                    <label for="khalti">Khalti</label>
                </div>
                <button type="submit" class="submit-btn">Proceed to Payment</button>
            </form>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
