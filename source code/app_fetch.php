<?php
require_once "database.php"; // Include the database connection

// Function to verify if the handshake matches
function verifyHandshake($userId, $handshake)
{
	if($handshake=="")
		return false;
    global $conn;
    $sql = "SELECT handshake FROM users WHERE id = ? AND handshake = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $userId, $handshake);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows === 1;
}

function getDownloadLink()
{
	global $conn;
    $sql = "SELECT value FROM infotable WHERE key_name = 'apk_link'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['value'];
}
// Function to retrieve the version from the infotable
function getVersion()
{
    global $conn;
    $sql = "SELECT value FROM infotable WHERE key_name = 'version'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['value'];
}

// Check if all required POST parameters are set
if (isset($_POST['id'], $_POST['handshake'], $_POST['offline_uses'], $_POST['total_uses'], $_POST['session'])) {
    // Retrieve POST data
    $userId = $_POST['id'];
    $requestHandshake = $_POST['handshake'];
    $offlineUses = $_POST['offline_uses'];
    $totalUses = $_POST['total_uses'];
	$session = $_POST['session'];
	$sql = "SELECT uses_left, handshake FROM users WHERE id = ? AND session_token = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("is", $userId, $session);
	$stmt->execute();
	$result = $stmt->get_result();
	if($result->num_rows>0){
		$row = $result->fetch_assoc();
		$currentUsesLeft = $row['uses_left'];
		$currentHandshake = $row['handshake'];
		$newUsesLeft = $currentUsesLeft;
		
		// Verify handshake and update database accordingly
		if (verifyHandshake($userId, $requestHandshake)) {
			$newUsesLeft=0;
			$response = array(
				'uses_left' => 0,
				'version' => getVersion(),
				'link' => getDownloadLink(),
				'newToken' => "",
				'message' => 'Success'
			);
		} else {
			// Send response with current uses_left
			$response = array(
				'uses_left' => $currentUsesLeft,
				'version' => getVersion(),
				'link' => getDownloadLink(),
				'newToken' => $currentHandshake,
				'message' => 'Success'
			);
		}
		$newOffuses = $offlineUses+$newUsesLeft;
		$sql = "UPDATE users SET uses_left = ?, offline_uses = ?, total_uses = ? WHERE id = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("iiii", $newUsesLeft, $newOffuses, $totalUses, $userId);
		$stmt->execute();
	}else{
		$response = array(
        'error' => $session,
        'message' => 'Failed'
    );
	}
} else {
    // Required POST parameters are missing, send error response
    $response = array(
        'error' => 'Missing POST parameters',
        'message' => 'Failed'
    );
}

// Send the response
header('Content-Type: application/json');
echo json_encode($response);
?>
