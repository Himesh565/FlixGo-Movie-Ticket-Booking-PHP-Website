<?php
// Include your database connection
require_once('connection.php');

// Function to get the user's name by ID
function getUserNameById($user_id) {
    global $conn; // Use the global $conn object

    // Ensure that the connection is open before executing the query
    if ($conn->ping()) { // Check if the connection is still open
        $stmt = $conn->prepare("SELECT firstName FROM userinfo WHERE id = ?"); // Use your actual table and column names
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['firstName'];
        }
    } else {
        // Connection has been closed or is invalid
        return "Connection Error";
    }

    return "Unknown User"; // Default name if not found
}
?>
