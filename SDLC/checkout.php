<?php
session_start();
require_once "config.php"; // Connect to the database

// Check if the $conn variable exists
if (!isset($conn)) {
    die("Error: Unable to connect to the database. Please check 'config.php'.");
}

// Check if the cart is empty
if (empty($_SESSION["cart"])) {
    echo "<script>alert('Cart is empty!'); window.location='index.php';</script>";
    exit;
}

// Process order submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST["customer_name"] ?? "Guest";
    $customer_phone = $_POST["customer_phone"] ?? "No phone number";
    $customer_address = $_POST["customer_address"] ?? "No address";

    $total_price = array_sum(array_map(fn($item) => $item["price"] * $item["quantity"], $_SESSION["cart"]));

    // Insert order into database
    $query = "INSERT INTO orders (customer_name, customer_phone, customer_address, total_price, order_date) 
              VALUES ('$customer_name', '$customer_phone', '$customer_address', '$total_price', NOW())";

    if (mysqli_query($conn, $query)) {
        $_SESSION["cart"] = []; // Clear the cart after successful order
        echo "<script>alert('Order placed successfully!'); window.location='index.php';</script>";
    } else {
        die("Order error: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
</head>
<body>
    <h2>Order Information</h2>
    <form method="POST">
        <input type="text" name="customer_name" required placeholder="Full Name">
        <input type="text" name="customer_phone" required placeholder="Phone Number">
        <textarea name="customer_address" required placeholder="Address"></textarea>
        <button type="submit">Confirm Order</button>
    </form>
</body>
</html>