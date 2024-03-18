<?php
require_once "database.php"; // Include the database connection

// Check if email and password are provided
if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate email and password (you may need to modify this based on your hashing method)
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Password is correct, generate and return session token
            $sessionToken = bin2hex(random_bytes(32)); // Generate a random session token
            $userId = $row['id'];

            // Store session token in database
            $sql = "UPDATE users SET session_token = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $sessionToken, $userId);
            if ($stmt->execute()) {
                // Return session token as JSON response
                echo json_encode(array("success" => true, "session_token" => $sessionToken, "user_id" => $userId));
            } else {
                // Error updating session token
                echo json_encode(array("success" => false, "message" => "Failed to update session token"));
            }
        } else {
            // Invalid password
            echo json_encode(array("success" => false, "message" => "Invalid email or password"));
        }
    } else {
        // User not found
        echo json_encode(array("success" => false, "message" => "User not found"));
    }
} else {
    // Email or password not provided
    echo json_encode(array("success" => false, "message" => "Email or password not provided"));
}
?>
