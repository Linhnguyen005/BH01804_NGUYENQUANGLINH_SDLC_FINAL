<?php
session_start();

$connect = mysqli_connect('localhost', 'root', '', 'shop_vpp');
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch product list
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sql = "SELECT * FROM products";
if (!empty($search)) {
    $sql .= " WHERE product_name LIKE '%" . mysqli_real_escape_string($connect, $search) . "%'";
}
$result = mysqli_query($connect, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Office Supplies Store</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header class="header-container">
    <div class="logo-container">
        <a href="index.php">
            <img src="/SDLC/image/20250207_7BX572tW.png" alt="Office Supplies Logo" class="logo">
        </a>
        <h1>Office Supplies Store</h1>
    </div>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="#products">Products</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
            <li><a href="feedback.php">Feedback</a></li>
        </ul>
    </nav>
</header>

<section id="home">
    <h2>Welcome to Our Store</h2>
    <p>We offer a full range of high-quality office supplies.</p>
</section>

<section>
    <h2>Products</h2>
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Enter product name..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
    </form>

    <div class="product-list">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="product">
                <img src="<?= !empty($row['product_img']) ? htmlspecialchars($row['product_img']) : 'uploads/default.jpg' ?>" 
                     alt="<?= htmlspecialchars($row['product_name']) ?>">
                <p><?= htmlspecialchars($row['product_name']) ?></p>
                <p>Price: <?= number_format($row['product_price'], 0, ',', '.') ?>đ</p>
            
                <form method="POST" action="cart.php">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="id" value="<?= $row['product_id'] ?>">
                    <input type="hidden" name="name" value="<?= htmlspecialchars($row['product_name']) ?>">
                    <input type="hidden" name="price" value="<?= $row['product_price'] ?>">
                    <input type="hidden" name="image" value="<?= htmlspecialchars($row['product_img']) ?>">
                    <button type="submit">Add to Cart</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<script>
    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    function addToCart(id, name, price, img) {
        let existingItem = cart.find(item => item.id === id);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({ id, name, price, img, quantity: 1 });
        }
        localStorage.setItem("cart", JSON.stringify(cart));
        alert("Added to cart!");
    }

    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function () {
            addToCart(this.dataset.id, this.dataset.name, this.dataset.price, this.dataset.img);
        });
    });
</script>

<footer>
    <div class="footer-container">
        <div class="footer-section">
            <h4>Office Supplies</h4>
            <p>Headquarters: Trinh Van Bo, Nam Tu Liem, Hanoi</p>
            <p>Email: <b>nguyenquanglinh01121999@gmail.com</b></p>
            <p>Hotline: <b>0367450056</b></p>
        </div>
        <div class="footer-section">
            <h4>Customer Support</h4>
            <a href="#">Shopping Guide</a>
            <a href="#">Office Supplies Pricing</a>
            <a href="#">Special Policies</a>
        </div>
        <div class="footer-section">
            <h4>Policies</h4>
            <a href="#">Privacy Policy</a>
            <a href="#">Return Policy</a>
        </div>
    </div>
    <p class="copyright">Copyright © 2025</p>
</footer>

</body>
</html>
