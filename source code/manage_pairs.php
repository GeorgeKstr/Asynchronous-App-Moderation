<?php
require_once "database.php"; // Include the database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted for adding a pair
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_pair"])) {
    $key_name = $_POST["key_name"];
    $value = $_POST["value"];

    // Check if the key already exists in the database
    $sql = "SELECT * FROM infotable WHERE key_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $key_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Key already exists, update the value
        $sql = "UPDATE infotable SET value = ? WHERE key_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $value, $key_name);
        if ($stmt->execute()) {
            // Value updated successfully
            echo "Value updated successfully.";
        } else {
            // Error updating value
            echo "Error updating value: " . $conn->error;
        }
    } else {
        // Key does not exist, insert a new pair
        $sql = "INSERT INTO infotable (key_name, value) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $key_name, $value);
        if ($stmt->execute()) {
            // Pair added successfully
            echo "Pair added successfully.";
        } else {
            // Error adding pair
            echo "Error adding pair: " . $conn->error;
        }
    }
}

// Check if the form is submitted for removing a pair
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_pair"])) {
    $pair_id = $_POST["pair_id"];

    // Delete the pair from the database
    $sql = "DELETE FROM infotable WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pair_id);
    if ($stmt->execute()) {
        // Pair removed successfully
        echo "Pair removed successfully.";
    } else {
        // Error removing pair
        echo "Error removing pair: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Key-Value Pairs</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
    <style>
        .container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
            padding: 20px;
        }

        .pair {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    

    <a href="dashboard.php">Go Back to Dashboard</a>
    

    <h3>Existing Pairs:</h3>

    <!-- Display existing pairs and provide option to remove them -->
    <div class="container">
        <?php
        $sql = "SELECT id, key_name, value FROM infotable";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='pair'>";
                echo "<p><strong>Key:</strong> " . $row["key_name"] . "</p>";
                echo "<p><strong>Value:</strong> " . $row["value"] . "</p>";
                echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
                echo "<input type='hidden' name='pair_id' value='" . $row["id"] . "'>";
                echo "<button type='submit' name='remove_pair'>Remove</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "No pairs found.";
        }
        ?>
    </div>
	<h2>Manage Key-Value Pairs</h2>

    <!-- Form to add a new pair -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="key_name">Key:</label>
        <input type="text" id="key_name" name="key_name" required><br><br>
        <label for="value">Value:</label>
        <input type="text" id="value" name="value" required><br><br>
        <button type="submit" name="add_pair">Add/Update Pair</button>
    </form>

    
</body>
</html>
