<?php
require_once "database.php"; // Include the database connection

// Check if user is logged in and is a moderator
if (!isset($_SESSION['user_id']) || !isModerator($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Check if the form is submitted for creating an account
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create_account"])) {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $hashedPassword = hashPassword($password);
    $isModerator = isset($_POST["moderator"]) ? 1 : 0;

    // Insert user into database
    $sql = "INSERT INTO users (username, email, password, is_moderator) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $username, $email, $hashedPassword, $isModerator);
    if ($stmt->execute()) {
        // Account created successfully
        echo "Account created successfully.";
    } else {
        // Error creating account
        echo "Error creating account: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>
<body>
    <h2>Create Account</h2>
    <form action="" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <label for="moderator">Moderator:</label>
        <input type="checkbox" id="moderator" name="moderator" value="1"><br><br>
        <input type="submit" name="create_account" value="Create Account">
    </form>
    <a href="dashboard.php">Back to Dashboard</a> <!-- Link back to dashboard -->
</body>
</html>
