<?php
// delete_user.php
require_once("connection.php");

if (isset($_POST['id'])) {
    $userId = $_POST['id'];

    // Prepare and execute the delete query
    $q = "DELETE FROM userinfo WHERE id = ?";
    $stmt = $conn->prepare($q);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        echo "success"; // Send success response
    } else {
        echo "error"; // Send error response
    }

    $stmt->close();
    $conn->close();
}
?>
