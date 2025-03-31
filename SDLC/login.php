<?php
session_start();
session_destroy(); // Destroy old session
session_start(); // Start new session
require_once "config.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (!empty($username) && !empty($password)) {
        $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $username, $hashed_password, $role);
                $stmt->fetch();

                if (password_verify($password, $hashed_password)) {
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $id;
                    $_SESSION["username"] = $username;
                    $_SESSION["is_admin"] = ($role === 'admin'); // Store admin role

                    // Redirect based on role
                    if ($role === 'admin') {
                        header("location: admin_dashboard.php");
                    } else {
                        header("location: index.php");
                    }
                    exit;
                } else {
                    $error = "Incorrect password!";
                }
            } else {
                $error = "Username does not exist!";
            }
            $stmt->close();
        }
    } else {
        $error = "Please enter all required information!";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header class="header-container">
    <div class="logo-container">
        <a href="index.php"> <!-- Add <a> tag to create a link -->
            <img src="/SDLC/image/20250207_7BX572tW.png" alt="Office Supplies Logo" class="logo">
        </a>
    </div>
    <h1>Login</h1>
</header>
<section class="form-container">
    <h2>Login</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>\$error</p>"; ?>
    
    <form method="POST" action="login.php">
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>
</section>

</body>
</html>
