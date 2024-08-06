<?php
// Include database connection
include 'db.php';

// Initialize search query
$searchQuery = "";
$searchTerm = "";

if (isset($_POST['search'])) {
    // Get the search term and sanitize it
    $searchTerm = trim($_POST['search_term']);
    
    // Sanitize and validate the search term
    if (!empty($searchTerm)) {
        $searchTerm = htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8');
        
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM orders WHERE fullname LIKE ? OR phone_number LIKE ? OR product_title LIKE ? ORDER BY order_date DESC");
        $searchTermWildcard = "%{$searchTerm}%";
        $stmt->bind_param('sss', $searchTermWildcard, $searchTermWildcard, $searchTermWildcard);
        
        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        // If no search term, show all records
        $stmt = $conn->prepare("SELECT * FROM orders ORDER BY order_date DESC");
        $stmt->execute();
        $result = $stmt->get_result();
    }
} else {
    // If no search request, show all records
    $stmt = $conn->prepare("SELECT * FROM orders ORDER BY order_date DESC");
    $stmt->execute();
    $result = $stmt->get_result();
}
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
                        
                    </div>
                </div>
                <a href="#" class="sidenav-trigger menu" onclick="toggleNav()">
                    <i class="fas fa-bars"></i>
                </a>
                <a href="#" class="s
