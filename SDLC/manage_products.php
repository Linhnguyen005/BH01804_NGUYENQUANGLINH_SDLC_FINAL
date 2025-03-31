<?php
require_once "admin_check.php";
$connect = mysqli_connect('localhost', 'root', '', 'shop_vpp');

if (!$connect) {
    die("Connection error: " . mysqli_connect_error());
}

// Handle product update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_product'])) {
    if (!empty($_POST['id'])) {
        $id = intval($_POST['id']); // Ensure ID is an integer
        $name = mysqli_real_escape_string($connect, $_POST['name']);
        $price = floatval($_POST['price']);
        $quantity = intval($_POST['quantity']);

        $sql = "UPDATE products SET product_name='$name', product_price='$price', quantity='$quantity' WHERE product_id=$id";
        if (mysqli_query($connect, $sql)) {
            echo "<script>alert('Update successful!'); window.location='manage_products.php';</script>";
        } else {
            echo "Error: " . mysqli_error($connect);
        }
    } else {
        echo "<script>alert('Error: Invalid product ID!');</script>";
    }
}

// Fetch product list
$result = mysqli_query($connect, "SELECT * FROM products ORDER BY product_id DESC");
if (!$result) {
    die("Query error: " . mysqli_error($connect));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>

    <link rel="stylesheet" href="styles.css">
</head>
<body>


<h2>Manage Products</h2>
<table border='1'>
    <tr><th>ID</th><th>Product Name</th><th>Price</th><th>Quantity</th><th>Actions</th></tr>

    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <form method='POST'>
                <td><?= $row['product_id'] ?> <input type='hidden' name='id' value='<?= $row['product_id'] ?>'></td>
                <td><input type='text' name='name' value='<?= htmlspecialchars($row['product_name']) ?>' required></td>
                <td><input type='number' name='price' value='<?= $row['product_price'] ?>' required></td>
                <td><input type='number' name='quantity' value='<?= $row['quantity'] ?>' required></td>
                <td>
                    <button type='submit' name='update_product'>💾 Save</button>
                    <a href='delete_product.php?id=<?= $row['product_id'] ?>'>🗑️ Delete</a>
                </td>
            </form>
        </tr>
    <?php endwhile; ?>
</table>

<div class="back-container">
    <a href="admin_dashboard.php" class="back-btn">⬅ Back to Admin Dashboard</a>
</div>

</body>
</html>