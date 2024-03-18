<?php
require_once "database.php"; // Include the database connection


if (!isset($_SESSION['user_id']) || !isModerator($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Check if user_id is provided and is numeric
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $user_id = $_POST['id'];

    // Delete the user from the database
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        // User removed successfully
        header("Location: list_users.php"); // Redirect back to user list
        exit();
    } else {
        // Error removing user
        echo "Error removing user: " . $conn->error;
    }
} else {
    // Invalid or missing user ID
    echo "Invalid user ID.";
}

// Add a link back to the user list page
echo '<br><br><a href="list_users.php">Back to User List</a>';
?>
