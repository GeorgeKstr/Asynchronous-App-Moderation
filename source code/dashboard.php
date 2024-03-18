<?php
require_once "database.php"; // Include the database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Get user ID from session
$userID = $_SESSION['user_id'];

// Check if user is a moderator
$isModerator = isModerator($userID);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>
<body>
    <h2>Διαχειριστικό</h2>
    <p>You are logged in.</p>

    <?php if ($isModerator): ?>
        <!-- Links for moderator -->
        <a href="create_account.php">Create Account</a><br><br>
        <a href="list_users.php">List Non-Moderator Users</a><br><br>
        <a href="purge_users.php">Purge Non-Moderator Accounts</a><br><br>
		<a href="manage_pairs.php">Manage Key-Value Pairs</a><br><br>
    <?php endif; ?>

    <a href="logout.php">Logout</a> <!-- Logout link -->
</body>
</html>
