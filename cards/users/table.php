<?php
// Start session and check if user is logged in
include 'auth_session.php';

// Include the database connection file
include '../users/db.php';

// Initialize error handling (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize search query
$searchQuery = "";
if (isset($_POST['search'])) {
    $searchTerm = trim($_POST['search_term']);
    $searchTerm = $conn->real_escape_string($searchTerm);
    $searchQuery = "WHERE fullname LIKE '%$searchTerm%' OR phone_number LIKE '%$searchTerm%' OR product_title LIKE '%$searchTerm%'";
}

// Fetch data from the database
$sql = "SELECT * FROM orders $searchQuery ORDER BY order_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .orderList {
            margin: 0 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .search-form {
            margin-bottom: 20px;
            width: 20%;
        }

        .search-form input[type="text"] {
            padding: 5px;
            font-size: 16px;
        }

        .search-form input[type="submit"] {
            padding: 5px 10px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" href="../assets/css/adminIndex.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <a href="index.php" class="brand-logo"><img src="../assets/images/rlogo.png" alt="CafeBristo"></a>
                <div class="navbar-links">
                    <ul class="nav-links">
                        <li><a href="admin_panel.php">Menu</a></li>
                        <li><a href="userContact.php">Contact User</a></li>
                        <li><a href="table.php">Book Table</a></li>
                    </ul>
                    <div class="auth-links">
                        <a href="logout.php" class="login-link"><i class="fas fa-sign-in-alt"></i> Log Out</a>
                        <div class="cart">
                            <a href="pages/cart.php"><i class="fas fa-shopping-cart"></i></a>
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
            <li><a href="#updateProduct">Menu</a></li>
            <li><a href="userContact.php">Contact User</a></li>
            <li><a href="table.php">Book Table</a></li>
            <li><a href="logout.php">Log Out</a></li>
        </ul>
    </header>
    <h1>Order Details</h1>
    <section class="orderList">

        <form class="search-form" method="post" action="">
            <label for="search_term">Search:</label>
            <input type="text" id="search_term" name="search_term" value="<?php echo isset($_POST['search_term']) ? htmlspecialchars($_POST['search_term'], ENT_QUOTES, 'UTF-8') : ''; ?>">
            <input type="submit" name="search" value="Search">
        </form>

        <?php if ($result && $result->num_rows > 0) : ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User ID</th>
                        <th>Full Name</th>
                        <th>Phone Number</th>
                        <th>Product ID</th>
                        <th>Product Title</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row["order_id"], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row["user_id"], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row["fullname"], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row["phone_number"], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row["product_id"], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row["product_title"], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row["quantity"], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row["total"], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row["order_date"], ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No results found.</p>
        <?php endif; ?>

        <?php $conn->close(); ?>
    </section>

</body>

</html>
