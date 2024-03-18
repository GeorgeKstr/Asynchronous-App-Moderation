<?php
require_once "database.php"; // Include the database connection
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in and is a moderator
if (!isset($_SESSION['user_id']) || !isModerator($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Function to generate a random string (password/email)
function generateRandomString($length = 8) {
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

// Function to check if username or email already exists in the database
function isUnique($username, $email) {
    global $conn;
    $sql = "SELECT COUNT(*) AS count FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'] == 0;
}

// Generate accounts
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numAccounts = isset($_POST['num_accounts']) ? intval($_POST['num_accounts']) : 0;

    // Generate non-moderator accounts
    $accountsCreated = 0;
    while ($accountsCreated < $numAccounts) {
        $username = generateRandomString(8);
        $email = $username . '@example.com'; // Random email (change domain if needed)
        $password = generateRandomString(10); // Random password
        $hashedPassword = hashPassword($password);

        // Check if username and email are unique
        if (isUnique($username, $email)) {
            // Insert user into database
            $sql = "INSERT INTO users (username, email, password, is_moderator) VALUES (?, ?, ?, 0)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $email, $hashedPassword);
            if ($stmt->execute()) {
                $accountsCreated++;
            }
        }
    }

    // Redirect back to dashboard after generating accounts
    header("Location: list_users.php");
    exit();
}
?>


