<?php
session_start();
require_once "config.php"; // Connect to the database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);
    $role = "user"; // Default role is user

    if (!empty($username) && !empty($password) && !empty($confirm_password)) {
        if ($password !== $confirm_password) {
            $error = "Password confirmation does not match!";
        } else {
            $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("sss", $username, $hashed_password, $role);
                if ($stmt->execute()) {
                    echo "<script>alert('Registration successful!'); window.location='login.php';</script>";
                } else {
                    $error = "Error during registration!";
                }
                $stmt->close();
            }
        }
    } else {
        $error = "Please fill in all required fields!";
    }
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header class="header-container">
    <div class="logo-container">
        <a href="index.php"> <!-- Add <a> tag to create a link -->
            <img src="/SDLC/image/20250207_7BX572tW.png" alt="Office Supplies Logo" class="logo">
        </a>
    </div>
    <h1>Register</h1>
</header>
<section class="form-container">
    <h2>Register</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>\$error</p>"; ?>
    
    <form method="POST" action="register.php">
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        
        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" required>
        
        <button type="submit">Register</button>
    </form>

    <p>You already have an account <a href="login.php">Login</a></p>
</section>

</body>
</html>
