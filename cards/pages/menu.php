<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CaféBristo | Menu</title>
    <link rel="stylesheet" href="../assets/css/includes.css">
    <link rel="stylesheet" href="../assets/css/styles2.css">
</head>

<body>
    <?php include "../includes/header2.php"; ?>

    <main>
        <section class="menu-page">
            <h3>CaféBristo MENU</h3>
            <form method="get" action="menu.php" class="search-form">
                <label for="search">Search Menu:</label>
                <input type="search" id="search" name="search" placeholder="Search for items..." value="<?php echo htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8'); ?>">
                <button type="submit" class="btn">Search</button>
            </form>
            <?php
            // Include the database connection file
            include '../users/db.php';

            // Error handling: Ensure this is only enabled in development environments
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);

            // Initialize search query
            $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

            // Fetch product categories
            $categories = ['hot', 'cold', 'alternative_drink', 'light_meal', 'nepali']; // Modify or fetch dynamically if necessary

            foreach ($categories as $category) {
                echo "<h1 id='categories'>" . htmlspecialchars(ucfirst($category), ENT_QUOTES, 'UTF-8') . "</h1>";
                echo "<div class='menu-cards'>";

                // Prepare SQL query with search filter
                $sql = "SELECT * FROM product WHERE categories = ? AND (title LIKE ? OR description LIKE ?)";
                if ($stmt = $conn->prepare($sql)) {
                    $searchTerm = "%$searchQuery%";
                    $stmt->bind_param('sss', $category, $searchTerm, $searchTerm);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Check if the result set is valid
                    if ($result === FALSE) {
                        echo "<!-- Debug: SQL Error - " . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8') . " -->";
                    }

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $id = htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8');
                            $title = htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8');
                            $description = htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8');
                            $price = htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8');
                            $image = htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8');

                            // Determine the image source safely
                            if (preg_match('/^(http:\/\/|https:\/\/)/', $image)) {
                                // If the image URL starts with http:// or https://
                                $imageSrc = htmlspecialchars($image, ENT_QUOTES, 'UTF-8');
                            } elseif (preg_match('/^uploads\//', $image)) {
                                // If the image URL starts with uploads/
                                $imageSrc = htmlspecialchars("../users/" . $image, ENT_QUOTES, 'UTF-8');
                            } else {
                                // Handle other cases (e.g., relative paths)
                                $imageSrc = htmlspecialchars($image, ENT_QUOTES, 'UTF-8');
                            }

                            echo "<div class='card'>
                                    <img src='$imageSrc' alt='$title'>
                                    <div class='card-info'>
                                        <h2>$title</h2>
                                        <p>$description</p>
                                        <span class='price'>Rs.$price</span>
                                        <a href='cart.php?action=add&id=$id' class='add-to-cart'>Add to Cart</a>
                                    </div>
                                  </div>";
                        }
                    } else {
                        echo "<p>No products found in this category.</p>";
                    }

                    $stmt->close();
                } else {
                    echo "<!-- Debug: SQL Preparation Error - " . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8') . " -->";
                }

                echo "</div>";
            }

            // Close the database connection
            $conn->close();
            ?>

        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>

</html>
