<?php
// Include database connection
// include 'auth_session.php';
include 'db.php';

// Handle Add Product
if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $categories = $_POST['categories'];
    
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = basename($_FILES['image']['name']);
        $fileType = $_FILES['image']['type'];

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($fileType, $allowedTypes)) {
            $image = 'uploads/' . $fileName;
            move_uploaded_file($fileTmpPath, $image);
        } else {
            echo "Invalid file type";
            exit;
        }
    } elseif (!empty($_POST['image_url'])) {
        $image = $_POST['image_url'];
    }

    // Use prepared statement for inserting data
    $stmt = $conn->prepare("INSERT INTO product (title, description, price, categories, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('ssdss', $title, $description, $price, $categories, $image);
    if ($stmt->execute()) {
        echo "New product added successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Handle Edit Product
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $categories = $_POST['categories'];
    
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = basename($_FILES['image']['name']);
        $fileType = $_FILES['image']['type'];

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($fileType, $allowedTypes)) {
            $image = 'uploads/' . $fileName;
            move_uploaded_file($fileTmpPath, $image);
        } else {
            echo "Invalid file type";
            exit;
        }
    } elseif (!empty($_POST['image_url'])) {
        $image = $_POST['image_url'];
    }

    // Use prepared statement for updating data
    $stmt = $conn->prepare("UPDATE product SET title=?, description=?, price=?, categories=?, image=? WHERE id=?");
    $stmt->bind_param('ssdssi', $title, $description, $price, $categories, $image, $id);
    if ($stmt->execute()) {
        echo "Product updated successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Handle Delete Product
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Prepare statement to get image path
    $stmt = $conn->prepare("SELECT image FROM product WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if (file_exists($row['image']) && !empty($row['image'])) {
            unlink($row['image']);
        }
    }
    $stmt->close();
    
    // Prepare statement to delete the product
    $stmt = $conn->prepare("DELETE FROM product WHERE id=?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        echo "Product deleted successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch product and categories for display
$product = $conn->query("SELECT * FROM product");
$categories = $conn->query("SELECT DISTINCT categories FROM product");

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - CafeBristo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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

    <main class="adminpage">
        <h1>Admin Panel - Manage product</h1>
        <div class="function">
            <a href="#updateProduct"><button class="btn">add product</button></a>
            <a href="#list"><button class="btn">edit product</button></a>
           <a href="#"><button class="btn">add categories</button></a>
        </div>

        <form method="post" id="updateProduct" action="admin_panel.php" enctype="multipart/form-data">
            <h2>Add New Product</h2>
            <label>Title:</label>
            <input type="text" name="title" required>
            <label>Description:</label>
            <textarea name="description" required></textarea>
            <label>Price:</label>
            <input type="number" step="0.01" name="price" required>
            <label>Categories:</label>
            <select name="categories" required>
                <?php
                if ($categories->num_rows > 0) {
                    while ($row = $categories->fetch_assoc()) {
                        echo "<option value='{$row['categories']}'>{$row['categories']}</option>";
                    }
                }
                ?>
            </select>
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
                if ($product->num_rows > 0) {
                    while ($row = $product->fetch_assoc()) {
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
                    echo "<tr><td colspan='7'>No product found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <?php
        if (isset($_GET['edit'])) {
            $id = $conn->real_escape_string($_GET['edit']);
            $sql = "SELECT * FROM product WHERE id=$id";
            $result = $conn->query($sql);
            if ($row = $result->fetch_assoc()) {
                ?>
                <form method="post" action="admin_panel.php" enctype="multipart/form-data">
                    <h2>Edit Product</h2>
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <label>Title:</label>
                    <input type="text" name="title" value="<?php echo $row['title']; ?>" required>
                    <label>Description:</label>
                    <textarea name="description" required><?php echo $row['description']; ?></textarea>
                    <label>Price:</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $row['price']; ?>" required>
                    <label>Image (File Upload):</label>
                    <input type="file" name="image" accept="image/*">
                    <label>Or Image URL:</label>
                    <input type="url" name="image_url" value="<?php echo $row['image']; ?>" placeholder="http://example.com/image.jpg">
                    <img src="<?php echo $row['image']; ?>" alt="Product Image" style="max-width:100px;">
                    <label>Categories:</label>
                    <select name="categories" required>
                        <?php
                        $categories->data_seek(0); // Reset the categories result set
                        while ($cat = $categories->fetch_assoc()) {
                            $selected = ($cat['categories'] == $row['categories']) ? 'selected' : '';
                            echo "<option value='{$cat['categories']}' $selected>{$cat['categories']}</option>";
                        }
                        ?>
                    </select>
                    <input type="submit" name="update" value="Update Product">
                </form>
                <?php
            }
        }
        ?>
    </main>

    <script src="../assets/js/script.js"></script>    
</body>
</html>
