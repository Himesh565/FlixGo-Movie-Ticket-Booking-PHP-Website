<?php
session_start(); // Start the session to access session variables
require_once("connection.php"); // Include your DB connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "not_logged_in";
    exit();
}

// Get the logged-in user's information from the session
$user_id = $_SESSION['user_id'];

// Fetch user data from the database
$sql = "SELECT password FROM userinfo WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

// Check if old password is correct
if (isset($_POST['old_password'])) {
    $old_password = $_POST['old_password'];

    if ($old_password === $user_data['password']) {
        echo "valid";  // Old password is correct
    } else {
        echo "invalid";  // Old password is incorrect
    }
}
?>
