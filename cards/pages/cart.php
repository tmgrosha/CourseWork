<?php
// Include the database connection file
include '../users/db.php';

// Check if an action is provided to add a product
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $product_id = $conn->real_escape_string($_GET['id']);

    // Fetch product details
    $sql = "SELECT * FROM product WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Initialize the cart if not already
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        // Add product to the cart
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity']++;
        } else {
            $_SESSION['cart'][$product_id] = array(
                'title' => $product['title'],
                'price' => $product['price'],
                'quantity' => 1
            );
        }

        // Redirect to cart page
        header('Location: cart.php');
        exit();
    } else {
        echo "Product not found!";
    }
}

// Check if an action is provided to remove a product
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $product_id = $conn->real_escape_string($_GET['id']);

    // Remove product from the cart
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }

    // Redirect to cart page
    header('Location: cart.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cafeBristo | Cart</title>
    <link rel="stylesheet" href="../assets/css/includes.css">
    <link rel="stylesheet" href="../assets/css/cart.css">
</head>
<body>
    <?php include "../includes/header2.php"; ?>

    <main>
        <section class="cart-page">
            <h3>Your Cart</h3>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                        $total = 0;
                        foreach ($_SESSION['cart'] as $product_id => $product) {
                            $product_total = $product['price'] * $product['quantity'];
                            $total += $product_total;
                            echo "<tr>
                                    <td>{$product['title']}</td>
                                    <td>Rs.{$product['price']}</td>
                                    <td>{$product['quantity']}</td>
                                    <td>Rs.{$product_total}</td>
                                    <td><a href='cart.php?action=remove&id={$product_id}' class='remove-item' onclick='return confirm(\"Are you sure you want to remove this item?\")'>Remove</a></td>
                                  </tr>";
                        }
                        echo "<tr>
                                <td colspan='3' class='total-label'>Total</td>
                                <td class='total-amount'>Rs.$total</td>
                                <td class='order-button'>
                                    <a href='payment.php' class='order-link'>Order/Payment</a>
                                </td>
                              </tr>";
                    } else {
                        echo "<tr><td colspan='5'>Your cart is empty.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
