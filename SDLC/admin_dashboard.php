<?php
require_once "admin_check.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Connect CSS -->
</head>
<body>

<header class="header-container">
    <div class="logo-container">
        <a href="index.php"> <!-- Add <a> tag to create a link -->
            <img src="/SDLC/image/20250207_7BX572tW.png" alt="Office Supplies Logo" class="logo">
        </a>
    </div>
    <h1>Administrator: <?php echo $_SESSION["username"]; ?> 👑</h1>
    <nav>
        <ul>
            <li><a href="manage_users.php">👥 Manage Users</a></li>
            <li><a href="manage_products.php">📦 Manage Products</a></li>
            <li><a href="add_product.php">➕ Add Product</a></li>
            <li><a href="logout.php">📦 Logout</a></li>
        </ul>
    </nav>
</header>

<section class="admin-container">
    <h2>Welcome to the Admin Dashboard</h2>
    <p>Select a function from the menu to continue.</p>
</section>

</body>
</html>
