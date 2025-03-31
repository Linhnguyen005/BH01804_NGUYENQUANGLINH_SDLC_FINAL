<?php
require_once "admin_check.php";
$connect = mysqli_connect('localhost', 'root', '', 'shop_vpp');

if (!$connect) {
    die("Connection error: " . mysqli_connect_error());
}

// Handle user addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Encrypt password
    $role = $_POST['role'];

    if (!empty($username) && !empty($_POST['password'])) {
        $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        if ($stmt = $connect->prepare($sql)) {
            $stmt->bind_param("sss", $username, $password, $role);
            if ($stmt->execute()) {
                echo "<script>alert('User added successfully!'); window.location='manage_users.php';</script>";
            } else {
                echo "<script>alert('Error adding user.');</script>";
            }
            $stmt->close();
        }
    } else {
        echo "<script>alert('Please enter all required fields!');</script>";
    }
}

$result = mysqli_query($connect, "SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header class="header-container">
    <h1>Manage Users</h1>
</header>

<section class="user-list-container">
    <h2>User List</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Action</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td><a href="delete_user.php?id=<?= $row['id'] ?>" class="delete-btn">🗑 Delete</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
</section>

<section class="add-user-container">
    <h2>Add New User</h2>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <label for="role">Role:</label>
        <select name="role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>

        <button type="submit" name="add_user">➕ Add User</button>
    </form>
</section>

<!-- Back to Admin Dashboard Button -->
<div class="back-container">
    <a href="admin_dashboard.php" class="back-btn">⬅ Back to Admin Dashboard</a>
</div>

</body>
</html>
