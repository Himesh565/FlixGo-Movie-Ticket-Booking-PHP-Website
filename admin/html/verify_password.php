<?php
session_start();
require_once("connection.php");

$data = json_decode(file_get_contents("php://input"), true);
$inputPassword = $data['password'];
$user_id = $_SESSION['user_id']; // Get the user_id from session

// Fetch the user from the database
$sql = "SELECT password FROM userinfo WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Directly compare the input password with the stored password (plain text)
    if ($inputPassword === $user['password']) {
        echo json_encode(["valid" => true]);
    } else {
        echo json_encode(["valid" => false]);
    }
} else {
    echo json_encode(["valid" => false]);
}

$stmt->close();
$conn->close();
?>
