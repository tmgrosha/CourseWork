<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome-CaféBristo</title>
    <link rel="icon" href="../assets/images/rlogo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/includes.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <?php include "includes/header.php"; ?>

    <main>
        <section class="hero">
            <div class="hero-content">
                <h2>Welcome to CaféBirsto</h2>
                <p>Your favorite place for refreshing coffee and delicious snacks!</p>
                <a href="pages/menu.php" class="btn">ORDER</a>
            </div>
        </section>
        <section class="about" id="about">
            <h1 class="heading"> <span>About</span> Us</h1>
            <div class="about-contents">
                <div class="image">
                    <img src="assets/images/others/background.jpg" alt="" class="img-fluid">
                </div>
                <div class="content">
                    <p>
                        At CaféBirsto Coffee Shop, we are passionate about coffee and believe
                        that every cup tells a story. We are a cozy coffee shop located
                        in the heart of the city, dedicated to providing an exceptional
                        coffee experience to our customers. Our love for coffee has led
                        us on a voyage of exploration and discovery, as we travel the
                        world in search of the finest coffee beans, carefully roasted
                        and brewed to perfection.
                    </p>
                    <p>
                        But coffee is not just a drink, it's an experience. Our warm and
                        inviting atmosphere at CaféBirsto is designed to be a haven
                        for coffee lovers, where they can relax, connect, and embark
                        on their own coffee voyages.
                    </p>
                    <a href="./pages/about.php" class="text-decoration-none"><button class="btn ">Learn More</button></a>
                </div>
            </div>
        </section>
        <hr>
        <!-- menu section -->
        <div class="menu-container">
            <h1>Menu</h1>
            <div class="menu">
                <?php
                // Include the database connection file
                include 'users/db.php'; // Adjust the path if necessary
                include 'users/product.php'; // Adjust the path if necessary

                // Fetch all products
                $sql = "SELECT * FROM product ORDER BY RAND() LIMIT 8";
                $result = $conn->query($sql);

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
                            $imageSrc = "users/" . $image; // Adjust path if needed
                        } else {
                            // Handle other cases (e.g., relative paths)
                            $imageSrc = $image;
                        }

                        echo "<div class='card'>
                                <img src='$imageSrc' alt='$title'>
                                <div class='card-info'>
                                    <h2>$title</h2>
                                    <p>$description</p>
                                    <span class='price'>Rs.$price</span>
                                    <a href='pages/cart.php?action=add&id=$id' class='add-to-cart'>Add to Cart</a>
                                </div>
                              </div>";
                    }
                } else {
                    echo "<p>No products available at the moment.</p>";
                }

                // Close the database connection
                $conn->close();
                ?>
            </div>
        </div>
    </main>
    <?php include "includes/footer.php"; ?>
</body>

</html>