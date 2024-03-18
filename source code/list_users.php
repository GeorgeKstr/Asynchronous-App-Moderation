<?php
require_once "database.php"; // Include the database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Get user ID from session
$userID = $_SESSION['user_id'];

// Check if user is a moderator
$isModerator = isModerator($userID);

// Retrieve non-moderator user accounts
$accounts = array();
$sql = "SELECT id, username, email, uses_left, offline_uses, total_uses, is_moderator FROM users";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $accounts[] = $row;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Users</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
    <style>
        .user-list-container {
            height: 400px;
            overflow-y: auto;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
        }
        .user-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .user-item {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            position: relative;
        }
        .user-details {
            display: flex;
            flex-direction: column;
        }
        .increment-decrement-container {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            align-items: center;
        }
		.moderator-indicator {
			color: green; /* Set text color to green */
			font-weight: bold; /* Make text bold */
		}

		.incdec-number {
			margin: 5px;
		}
        .remove-user-container {
            margin-top: 30px;
        }
        .remove-user {
			float: right;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>List of Users</h2>

    <div class="user-list-container">
        <?php if (!empty($accounts)): ?>
            <!-- Display user accounts -->
            <ul class="user-list">
                <?php foreach ($accounts as $account): ?>
                    <li class="user-item">
                        <div class="user-details">
                            <div>
                                <strong>Username:</strong>
                                <?php echo $account['username']; ?>
                                <?php if ($account['is_moderator']): ?>
                                    <span class="moderator-indicator">(Moderator)</span>
                                <?php endif; ?>
                                <br>
                                <strong>Email:</strong> <?php echo $account['email']; ?><br>
                                <strong>Offline υπόλοιπο:</strong> <?php echo $account['offline_uses']; ?><br>
                                <strong>Συνολικές έρευνες χρήστη:</strong> <?php echo $account['total_uses']; ?>
                            </div>
                            <div class="increment-decrement-container">
                                <strong>Υπόλοιπο ερευνών:</strong>
								<button class="decrement-research" data-user-id="<?php echo $account['id']; ?>" data-increment="-1">-</button>
                                <span class="incdec-number" id="research-count-<?php echo $account['id']; ?>"><?php echo $account['uses_left']; ?></span>
                                <button class="increment-research" data-user-id="<?php echo $account['id']; ?>" data-increment="1">+</button>
                            </div>
                            <?php if (!$account['is_moderator']): ?>
                                <div class="remove-user-container">
                                    <button class="remove-user" data-user-id="<?php echo $account['id']; ?>">Remove User</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No user accounts found.</p>
        <?php endif; ?>
    </div>

    <div class="form-container">
        <!-- Button for account creation -->
        <a href="create_account.php" class="button">Create Account</a>
    </div>

    <form action="generate_accounts.php" method="POST" class="form-container">
        <label for="num_accounts">Number of Accounts:</label>
        <input type="number" id="num_accounts" name="num_accounts" min="1" required><br><br>
        <input type="submit" value="Generate Accounts">
    </form>

    <!-- Button for purging users -->
    <form action="purge_users.php" method="POST" class="form-container">
        <button type="submit">Purge Accounts</button>
    </form>

    <a href="dashboard.php">Back to Dashboard</a> <!-- Link back to dashboard -->
    
    <script>
    // Increment and decrement research count
		document.querySelectorAll('.increment-research, .decrement-research').forEach(button => {
			button.addEventListener('click', () => {
				const userId = button.dataset.userId;
				const increment = parseInt(button.dataset.increment);
				const researchCountElement = document.getElementById(`research-count-${userId}`);
				let researchCount = parseInt(researchCountElement.textContent) + increment;

				// Ensure count doesn't go negative
				researchCount = Math.max(researchCount, 0);

				researchCountElement.textContent = researchCount;

				// Send AJAX request to update research count in the database
				updateResearchCount(userId, researchCount);
			});
		});

		// Remove user
		document.querySelectorAll('.remove-user').forEach(button => {
			button.addEventListener('click', () => {
				const userId = button.dataset.userId;

				// Send AJAX request to remove user from the database
				removeUser(userId);
			});
		});

		// Function to update research count in database
		function updateResearchCount(userId, researchCount) {
			const formData = new FormData();
			formData.append('user_id', userId);
			formData.append('research_count', researchCount);

			fetch('update_count.php', {
				method: 'POST',
				body: formData
			}).then(response => {
				if (!response.ok) {
					console.error('Failed to update research count');
				}
			}).catch(error => {
				console.error('Error:', error);
			});
		}

		// Function to remove user from database
		function removeUser(userId) {
			const formData = new FormData();
			formData.append('id', userId);

			fetch('remove_user.php', {
				method: 'POST',
				body: formData
			}).then(response => {
				if (!response.ok) {
					console.error('Failed to remove user');
				} else {
					// Reload the page after successful removal
					location.reload();
				}
			}).catch(error => {
				console.error('Error:', error);
			});
		}
	</script>


</body>
</html>
