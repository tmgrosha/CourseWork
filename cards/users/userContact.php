<?php
// Include database connection
include 'db.php';

// Initialize search query
$searchQuery = "";
$searchTerm = "";
if (isset($_POST['search'])) {
    $searchTerm = trim($_POST['search_term']);
    
    // Validate and sanitize search term (additional security)
    if (!empty($searchTerm)) {
        $searchTerm = htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8');
        
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM user_reg WHERE fullname LIKE ? OR email LIKE ? OR phone LIKE ? ORDER BY fullname ASC");
        $searchTermWildcard = "%{$searchTerm}%";
        $stmt->bind_param('sss', $searchTermWildcard, $searchTermWildcard, $searchTermWildcard);
        
        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        // No search term, show all records
        $stmt = $conn->prepare("SELECT * FROM user_reg ORDER BY fullname ASC");
        $stmt->execute();
        $result = $stmt->get_result();
    }
} else {
    // No search request, show all records
    $stmt = $conn->prepare("SELECT * FROM user_reg ORDER BY fullname ASC");
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Contacts</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .userList {
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
    <h1>User Contacts</h1>
    <section class="userList">

        <form class="search-form" method="post" action="">
            <label for="search_term">Search:</label>
            <input type="text" id="search_term" name="search_term" value="<?php echo htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="submit" name="search" value="Search">
        </form>

        <?php if ($result->num_rows > 0) : ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Privilege</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row["id"], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row["fullname"], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row["email"], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row["phone"], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row["privilege"], ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No results found.</p>
        <?php endif; ?>

        <?php 
        // Close statement and connection
        $stmt->close(); 
        $conn->close(); 
        ?>
    </section>

</body>

</html>
