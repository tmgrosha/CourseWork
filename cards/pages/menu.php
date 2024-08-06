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
                <input type="search" id="search" name="search" placeholder="Search for items...">
                <button type="submit" class="btn">Search</button>
            </form>
            <?php
            // Include the database connection file
            include '../users/db.php';

            // Display errors for debugging
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);

            $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
            // Fetch product categories
            $categories = ['hot', 'cold', 'alternative_drink', 'light_meal', 'nepali']; // Modify or fetch dynamically if necessary

            foreach ($categories as $category) {
                echo "<h1 id='categories'>" . htmlspecialchars(ucfirst($category)) . "</h1>";
                echo "<div class='menu-cards'>";


                // Prepare SQL query with search filter
                $sql = "SELECT * FROM product WHERE categories = ? AND (title LIKE ? OR description LIKE ?)";
                $stmt = $conn->prepare($sql);
                $searchTerm = "%$searchQuery%";
                $stmt->bind_param('sss', $category, $searchTerm, $searchTerm);
                $stmt->execute();
                $result = $stmt->get_result();

                // Debug: Print SQL query and results
                echo "<!-- Debug: SQL - $sql -->";
                if ($result === FALSE) {
                    echo "<!-- Debug: Error - " . $conn->error . " -->";
                }

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $id = htmlspecialchars($row['id']);
                        $title = htmlspecialchars($row['title']);
                        $description = htmlspecialchars($row['description']);
                        $price = htmlspecialchars($row['price']);
                        $image = htmlspecialchars($row['image']);

                        // Determine the image source
                        if (preg_match('/^(http:\/\/|https:\/\/)/', $image)) {
                            // If the image URL starts with http:// or https://
                            $imageSrc = $image;
                        } elseif (preg_match('/^uploads\//', $image)) {
                            // If the image URL starts with uploads/
                            $imageSrc = "../users/" . $image;
                        } else {
                            // Handle other cases (e.g., relative paths)
                            $imageSrc = $image;
                        }

                        // Debug: Print image path
                        echo "<!-- Debug: Image path - $imageSrc -->";

                        echo "<div class='card'>
                                <img src='$imageSrc' alt='$title' >
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