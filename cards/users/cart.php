<?php
include 'auth_session.php';

// Include the database connection file
include 'db.php';

// Error reporting for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// Handle order completion
if (isset($_POST['place_order']) && isset($_SESSION['cart'])) {
    $user_id = $_SESSION['user_id']; // Assuming user ID is stored in session
    $fullname = $_SESSION['fullname']; // Assuming user's fullname is stored in session
    $phone_number = $_SESSION['phone_number']; // Assuming phone number is stored in session
    $total = 0;

    // Begin transaction
    $conn->begin_transaction();
    try {
        foreach ($_SESSION['cart'] as $product_id => $product) {
            $product_total = $product['price'] * $product['quantity'];
            $total += $product_total;

            // Insert order into database
            $sql = "INSERT INTO orders (user_id, fullname, phone_number, product_id, product_title, quantity, total) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('isssisi', $user_id, $fullname, $phone_number, $product_id, $product['title'], $product['quantity'], $product_total);
            $stmt->execute();
        }

        // Commit transaction
        $conn->commit();

        // Clear the cart
        unset($_SESSION['cart']);

        // Redirect to a confirmation page
        header('Location: confirmation.php');
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="../assets/css/includes.css">
    <link rel="stylesheet" href="../assets/css/cart.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <a href="index_user.php" class="brand-logo"><img src="../assets/images/rlogo.png" alt="CafeBristo"></a>
                <div class="navbar-links">
                    <ul class="nav-links">
                        <li><a href="profile.php">Edit Profile</a></li>
                        <li><a href="menu.php">Place Order</a></li>
                        <li><a href="booking.php">Book Table</a></li>
                    </ul>
                    <div class="auth-links">
                        <a href="../users/logout.php" class="login-link"><i class="fas fa-sign-in-alt"></i> Logout</a>
                        <div class="cart">
                            <a href="cart.php"><i class="fas fa-shopping-cart"></i></a>
                        </div>
                    </div>
                </div>
                <a href="#" class="sidenav-trigger menu" onclick="toggleNav()">
                    <i class="fas fa-bars"></i>
                </a>
                <a href="#" class="sidenav-trigger close" onclick="toggleNav()">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </nav>
        <ul class="sidenav" id="mobile-nav">
            <li><a href="profile.php">Edit Profile</a></li>
            <li><a href="menu.php">Place Order</a></li>
            <li><a href="booking.php">Book Table</a></li>
            <li><a href="../users/logout.php">Logout</a></li>
            <li><a href="cart.php"><i class="fas fa-shopping-cart"></i></a></li>
        </ul>
    </header>

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
                                    <form method='POST' action='cart.php'>
                                        <button type='submit' name='place_order' class='order-link'>Order/Payment</button>
                                    </form>
                                </td>
                              </tr>";
                    } else {
                        echo "<tr><td colspan='5'>Your cart is empty.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <section class="order-history">
            <h3>Order History</h3>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Product Title</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch order history for the logged-in user
                    if (isset($_SESSION['user_id'])) {
                        $user_id = $_SESSION['user_id'];
                        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('i', $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($order = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$order['order_id']}</td>
                                        <td>{$order['order_date']}</td>
                                        <td>{$order['product_title']}</td>
                                        <td>{$order['quantity']}</td>
                                        <td>Rs.{$order['total']}</td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No orders found.</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>Please log in to view order history.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
    <script src="../assets/js/script.js"></script>
</body>

</html>
