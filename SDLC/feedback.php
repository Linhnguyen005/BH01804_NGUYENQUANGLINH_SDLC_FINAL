<?php
session_start();
$connect = new mysqli('localhost', 'root', '', 'shop_vpp');

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Handle feedback submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = trim($_POST["customer_name"]);
    $feedback_text = trim($_POST["feedback_text"]);

    if (!empty($customer_name) && !empty($feedback_text)) {
        $stmt = $connect->prepare("INSERT INTO shop_feedback (customer_name, feedback_text, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $customer_name, $feedback_text);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('Thank you for your feedback!'); window.location.href='feedback.php';</script>";
    } else {
        echo "<script>alert('Please fill in all required fields!');</script>";
    }
}

// Retrieve the latest feedback
$feedback_query = "SELECT customer_name, feedback_text, created_at FROM shop_feedback ORDER BY created_at DESC LIMIT 10";
$feedback_result = $connect->query($feedback_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Feedback</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header class="header-container">
    <div class="logo-container">
        <a href="index.php">
            <img src="/SDLC/image/20250207_7BX572tW.png" alt="Office Supplies Logo" class="logo">
        </a>
    </div>
    <h1>Customer Feedback</h1>
</header>

<section class="feedback-container">
    <h2>Submit Your Feedback</h2>
    <form method="POST">
        <input type="text" name="customer_name" required placeholder="Your name">
        <textarea name="feedback_text" required placeholder="Enter your feedback about the store..."></textarea>
        <button type="submit">Submit Feedback</button>
    </form>
</section>

<section class="feedback-section">
    <h2>Latest Feedback</h2>
    <div class="feedback-list">
        <?php while ($row = mysqli_fetch_assoc($feedback_result)): ?>
            <div class="feedback-item">
                <p class="customer-name"><b><?= htmlspecialchars($row['customer_name']) ?>:</b></p>
                <p class="feedback-text"><?= htmlspecialchars($row['feedback_text']) ?></p>
                <small class="feedback-time"><?= date("d/m/Y H:i:s", strtotime($row['created_at'])) ?></small>
            </div>
        <?php endwhile; ?>
    </div>
</section>

</body>
</html>
