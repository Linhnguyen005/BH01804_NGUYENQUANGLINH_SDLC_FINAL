<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

// Handle cart actions
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    $action = $_POST["action"];

    if ($action == "add") {
        $id = $_POST["id"] ?? null;
        $name = $_POST["name"] ?? "";
        $price = $_POST["price"] ?? 0;
        $image = $_POST["image"] ?? "";

        if (!$id || !$name || !$price) {
            die("Error: Invalid product data!");
        }

        $found = false;
        foreach ($_SESSION["cart"] as &$item) {
            if ($item["id"] == $id) {
                $item["quantity"] += 1;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION["cart"][] = [
                "id" => $id,
                "name" => $name,
                "price" => $price,
                "quantity" => 1,
                "image" => $image
            ];
        }

        header("Location: cart.php");
        exit;
    }

    if ($action == "remove") {
        $id = $_POST["id"] ?? null;
        if ($id) {
            foreach ($_SESSION["cart"] as $index => $item) {
                if ($item["id"] == $id) {
                    unset($_SESSION["cart"][$index]); // Remove product from cart
                    $_SESSION["cart"] = array_values($_SESSION["cart"]); // Reset array index
                    break;
                }
            }
        }
        header("Location: cart.php");
        exit;
    }

    if ($action == "clear") {
        $_SESSION["cart"] = []; // Clear the entire cart
        header("Location: cart.php");
        exit;
    }

    if ($action == "update") {
        $id = $_POST["id"] ?? null;
        $quantity = $_POST["quantity"] ?? 1;

        if ($id && $quantity > 0) {
            foreach ($_SESSION["cart"] as &$item) {
                if ($item["id"] == $id) {
                    $item["quantity"] = $quantity;
                    break;
                }
            }
        }
        header("Location: cart.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header class="header-container">
    <div class="logo-container">
        <a href="index.php"> 
            <img src="/SDLC/image/20250207_7BX572tW.png" alt="Office Supplies Logo" class="logo">
        </a>
    </div>
    <h1>Your Shopping Cart</h1>
</header>

<section id="cart">
    <h2>Items in Your Cart</h2>
    <div class="cart-container">
        <table border="1">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                if (!empty($_SESSION["cart"])) {
                    foreach ($_SESSION["cart"] as $item) {
                        $itemTotal = $item["price"] * $item["quantity"];
                        $total += $itemTotal;
                        echo "<tr>
                            <td><img src='{$item["image"]}' width='60' height='60'></td>
                            <td>{$item["name"]}</td>
                            <td>" . number_format($item["price"]) . "đ</td>
                            <td>
                                <form method='POST'>
                                    <input type='hidden' name='id' value='{$item["id"]}'>
                                    <input type='number' name='quantity' value='{$item["quantity"]}' min='1' style='width: 50px;'>
                                    <input type='hidden' name='action' value='update'>
                                    <button type='submit'>Update</button>
                                </form>
                            </td>
                            <td>" . number_format($itemTotal) . "đ</td>
                            <td>
                                <form method='POST'>
                                    <input type='hidden' name='id' value='{$item["id"]}'>
                                    <input type='hidden' name='action' value='remove'>
                                    <button type='submit'>Remove</button>
                                </form>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>🛒 Your cart is empty</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
        <?php if (!empty($_SESSION["cart"])): ?>
            <h3>🧾 Total: <?= number_format($total) ?>đ</h3>
            <form method="POST" action="checkout.php">
                <button type="submit">Buy</button>
            </form>
            <form method="POST">
                <input type="hidden" name="action" value="clear">
                <button type="submit">🗑 Clear Cart</button>
            </form>
        <?php endif; ?>
    </div>
</section>

</body>
</html>
