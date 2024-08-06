<?php
include 'db.php';

// Directory where uploaded images will be saved
$upload_dir = 'uploads/';

// Ensure the uploads directory exists
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Handle Add Product
if (isset($_POST['add'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['price']);
    $categories = $conn->real_escape_string($_POST['categories']);
    $image = '';

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $image = $upload_dir . basename($file_name);

        // Move uploaded file to the uploads directory
        if (move_uploaded_file($file_tmp, $image)) {
            // Success
        } else {
            echo "<p style='color:red;'>Error uploading file.</p>";
        }
    }

    // Handle image URL
    if (!empty($_POST['image_url'])) {
        $image = $conn->real_escape_string($_POST['image_url']);
    }

    // Insert product into database
    $sql = "INSERT INTO product (title, description, price, image, categories) VALUES ('$title', '$description', '$price', '$image', '$categories')";
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>New product added successfully</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

// Handle Update Product
if (isset($_POST['update'])) {
    $id = $conn->real_escape_string($_POST['id']);
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['price']);
    $categories = $conn->real_escape_string($_POST['categories']);
    $image = '';

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $image = $upload_dir . basename($file_name);

        // Move uploaded file to the uploads directory
        if (move_uploaded_file($file_tmp, $image)) {
            // Success
        } else {
            echo "<p style='color:red;'>Error uploading file.</p>";
        }
    }

    // Handle image URL
    if (!empty($_POST['image_url'])) {
        $image = $conn->real_escape_string($_POST['image_url']);
    }

    // Update product in database
    $sql = "UPDATE product SET title='$title', description='$description', price='$price', image='$image', categories='$categories' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>Product updated successfully</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

// Handle Delete Product
if (isset($_GET['delete'])) {
    $id = $conn->real_escape_string($_GET['delete']);

    // Get image path before deleting
    $sql = "SELECT image FROM product WHERE id=$id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $image_path = $product['image'];

        // Delete the product from database
        $sql = "DELETE FROM product WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            // Remove image file from server if it is a local file
            if (file_exists($image_path) && strpos($image_path, 'http') === false) {
                unlink($image_path);
            }
            echo "<p style='color:green;'>Product deleted successfully</p>";
        } else {
            echo "<p style='color:red;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color:red;'>Product not found.</p>";
    }
}

// Fetch all products for display
function get_products() {
    global $conn;
    $sql = "SELECT * FROM product";
    return $conn->query($sql);
}

// Fetch categories for dropdown
function get_categories() {
    global $conn;
    $sql = "SELECT catagories FROM category";
    return $conn->query($sql);
}
?>
