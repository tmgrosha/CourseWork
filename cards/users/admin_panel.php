<!DOCTYPE html>
<html>

<head>
    <title>Admin Panel - CafeBristo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/adminIndex.css">
    <script>
        function toggleNav() {
            const nav = document.getElementById('mobile-nav');
            nav.style.display = (nav.style.display === 'block' || !nav.style.display) ? 'none' : 'block';
        }
    </script>
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <a href="index.php" class="brand-logo"><img src="../assets/images/rlogo.png" alt="CafeBristo"></a>
                <div class="navbar-links">
                    <ul class="nav-links">
                        <li><a href="#updateProduct">Menu</a></li>
                        <li><a href="create_user.php">Contact User</a></li>
                        <li><a href="pages/booking.php">Book Table</a></li>
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
            <li><a href="pages/about.php">About</a></li>
            <li><a href="pages/contact.php">Contact</a></li>
            <li><a href="pages/booking.php">Book Table</a></li>
            <li><a href="logout.php">Log Out</a></li>
        </ul>
    </header>

    <?php
    include 'product.php'; // Include the product handling logic

    // Fetch products and categories
    $products = get_products();
    $categories = get_categories();
    ?>

    <main class="adminpage">
        <h1>Admin Panel - Manage Products</h1>

        <form method="post" id="updateProduct" action="admin_panel.php" enctype="multipart/form-data">
            <h2>Add New Product</h2>
            <label>Title:</label>
            <input type="text" name="title" required>
            <label>Description:</label>
            <textarea name="description" required></textarea>
            <label>Price:</label>
            <input type="number" step="0.01" name="price" required>
            <label>Categories:</label>
            <select name="categories" id="categorySelect" required>
                <?php
                if ($categories->num_rows > 0) {
                    while ($row = $categories->fetch_assoc()) {
                        echo "<option value='{$row['catagories']}'>{$row['catagories']}</option>";
                    }
                }
                ?>
                <option value="new">Add New</option>
            </select>
            <div id="newCategory" style="display: none;">
                <label>New Category:</label>
                <input type="text" name="new_category" placeholder="Enter new category">
            </div>
            <label>Image (File Upload):</label>
            <input type="file" name="image" accept="image/*">
            <label>Or Image URL:</label>
            <input type="url" name="image_url" placeholder="http://example.com/image.jpg">
            <br>
            <input type="submit" name="add" value="Add Product">
        </form>

        <h2>Product List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Categories</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($products->num_rows > 0) {
                    while ($row = $products->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['title']}</td>
                            <td>{$row['description']}</td>
                            <td>{$row['price']}</td>
                            <td><img src='{$row['image']}' alt='Product Image' style='max-width:100px;'></td>
                            <td>{$row['categories']}</td>
                            <td>
                                <a href='?edit={$row['id']}'>Edit</a> | 
                                <a href='?delete={$row['id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                            </td>
                          </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No products found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <?php
        // Handle Edit Product
        if (isset($_GET['edit'])) {
            $id = $conn->real_escape_string($_GET['edit']);
            $sql = "SELECT * FROM product WHERE id=$id";
            $result = $conn->query($sql);
            $product = $result->fetch_assoc();
        ?>
            <form method="post" action="admin_panel.php" enctype="multipart/form-data">
                <h2>Edit Product</h2>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
                <label>Title:</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($product['title']); ?>" required>
                <label>Description:</label>
                <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                <label>Price:</label>
                <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                <label>Image (File Upload):</label>
                <input type="file" name="image" accept="image/*">
                <label>Or Image URL:</label>
                <input type="url" name="image_url" value="<?php echo htmlspecialchars($product['image']); ?>" placeholder="http://example.com/image.jpg">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" style="max-width:100px;">
                <label>Categories:</label>
                <select name="categories" required>
                    <?php
                    // Fetch categories again for the dropdown
                    $categories->data_seek(0); // Reset the result set pointer
                    while ($row = $categories->fetch_assoc()) {
                        $selected = ($row['catagories'] === $product['categories']) ? 'selected' : '';
                        echo "<option value='{$row['catagories']}' $selected>{$row['catagories']}</option>";
                    }
                    ?>
                </select>
                <input type="submit" name="update" value="Update Product">
            </form>
        <?php
        }
        ?>
    </main>

    <?php include "../includes/footer.php"; ?>
    <?php $conn->close(); ?>
    
    <script>
        document.getElementById('categorySelect').addEventListener('change', function() {
            const newCategory = document.getElementById('newCategory');
            if (this.value === 'new') {
                newCategory.style.display = 'block';
            } else {
                newCategory.style.display = 'none';
            }
        });
    </script>

</body>

</html>