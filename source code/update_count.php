<?php
require_once "database.php"; // Include the database connection

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if user ID and research count are set
    if (isset($_POST['user_id']) && isset($_POST['research_count'])) {
        // Get user ID and research count from POST data
        $userId = $_POST['user_id'];
        $researchCount = $_POST['research_count'];

        // Update research count in the database
        $updateSql = "UPDATE users SET uses_left = ?, handshake = ? WHERE id = ?";
        $stmt = $conn->prepare($updateSql);
		$newHandshake = $sessionToken = bin2hex(random_bytes(8));
        $stmt->bind_param("isi", $researchCount, $newHandshake, $userId);
        
        if ($stmt->execute()) {
            // Research count updated successfully
            echo json_encode(array('success' => true));
        } else {
            // Error updating research count
            echo json_encode(array('success' => false, 'error' => 'Failed to update research count'));
        }
    } else {
        // Required parameters not provided
        echo json_encode(array('success' => false, 'error' => 'Missing user ID or research count'));
    }
} else {
    // Invalid request method
    echo json_encode(array('success' => false, 'error' => 'Invalid request method'));
}
?>
