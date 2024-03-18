<?php
require_once "database.php"; // Include the database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Check if user is a moderator
if (!isModerator($_SESSION['user_id'])) {
    echo "You don't have permission to purge users.";
    exit();
}

// Purge non-moderator accounts
$sql = "DELETE FROM users WHERE is_moderator = 0";
if ($conn->query($sql) === TRUE) {
    echo "Non-moderator accounts have been purged successfully.";
} else {
    echo "An error occurred while purging non-moderator accounts.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purge Users</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>
<body>
    <br>
    <a href="list_users.php">Go back to the users list</a> <!-- Go back to dashboard link -->
</body>
</html>
