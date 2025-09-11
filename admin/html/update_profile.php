<?php
session_start();
require_once("connection.php");

// Check if the user is logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

// Get the input values from the request
$first_name = $data['first_name'] ?? '';
$last_name = $data['last_name'] ?? '';
$new_password = $data['new_password'] ?? '';

$user_id = $_SESSION['user_id']; // Get the user ID from session

// Prepare the update SQL query
$sql = "UPDATE userinfo SET firstName = ?, lastName = ?";
$params = [$first_name, $last_name];

// If a new password is provided, update it too
if ($new_password) {
    $sql .= ", password = ?";
    $params[] = $new_password;  // Save new password as plaintext (or hash it for better security)
}

$sql .= " WHERE id = ?";
$params[] = $user_id;

// Update the database
$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('s', count($params)-1) . 'i', ...$params); // Bind all parameters dynamically
$success = $stmt->execute();

// Close the statement and connection
$stmt->close();
$conn->close();

// Return success or failure response
if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update profile']);
}
?>
