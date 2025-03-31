<?php
require_once "admin_check.php";
// Database connection
$connect = mysqli_connect('localhost', 'root', '', 'shop_vpp');
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Error message variable
$error = "";

// Handle "Add New" button click
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = trim($_POST['name']);
    $product_price = trim($_POST['price']);
    $quantity = trim($_POST['quantity']);
    $product_description = trim($_POST['description']);

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir = "uploads/";

        // Check if the folder exists, create if not
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $image_tmp = $_FILES['image']['tmp_name'];
        $image_name = basename($_FILES['image']['name']);
        $new_image_name = time() . "_" . $image_name;
        $target_file = $upload_dir . $new_image_name;

        // Move the uploaded image to the "uploads" folder and save to database
        if (move_uploaded_file($image_tmp, $target_file)) {
            $img_path = $target_file;

            $sql = "INSERT INTO products (product_name, product_price, quantity, product_img, product_description) 
                    VALUES ('$product_name', '$product_price', '$quantity', '$img_path', '$product_description')";

            if (mysqli_query($connect, $sql)) {
                echo "<script>alert('Product added successfully!');</script>";
                exit; // Ensure no further code executes after redirection
            } else {
                $error = "Error: " . mysqli_error($connect);
            }
        } else {
            $error = "Error uploading the image.";
        }
    } else {
        $error = "Please select a product image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header class="header-container">
    <h1>Add Product</h1>
    <div class="logo-container">
        <a href="index.php"> <!-- Add <a> tag to create a link -->
            <img src="/SDLC/image/20250207_7BX572tW.png" alt="Office Supplies Logo" class="logo">
        </a>
    </div>
</header>    
<section class="form-container">
    <h2>Add a New Product</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form action="add_product.php" method="POST" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="price">Product Price:</label>
        <input type="number" id="price" name="price" required>

        <label for="quantity">Product Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>

        <label for="image">Product Image:</label>
        <input type="file" id="image" name="image" accept="image/*" required>

        <label for="description">Product Description:</label>
        <textarea id="description" name="description" rows="3"></textarea>

        <button type="submit">Add Product</button>
    </form>
</section>    

<div class="back-container">
    <a href="admin_dashboard.php" class="back-btn">⬅ Back to Admin Dashboard</a>
</div>

<!-- Display recently added products -->
<?php
$result = mysqli_query($connect, "SELECT * FROM products ORDER BY product_name DESC LIMIT 5");

if (mysqli_num_rows($result) > 0) {
    echo "<h3>Recently Added Products:</h3>";
    echo "<div class='product-list'>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='product-item'>";
        echo "<img src='" . $row['product_img'] . "' alt='Product Image' width='100'>";
        echo "<p><b>" . $row['product_name'] . "</b></p>";
        echo "<p>Price: " . number_format($row['product_price'], 0, ',', '.') . " VND</p>";
        echo "</div>";
    }
    echo "</div>";
}
?>

</body>
</html>
