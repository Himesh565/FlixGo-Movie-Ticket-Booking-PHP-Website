<?php
session_start(); // Start the session

$inputEmail = $_POST['email'];
$inputPassword = $_POST['pwd'];

include('connection.php'); // Include your database connection

// Query to fetch the user details
$sql = "SELECT * FROM userinfo WHERE email = ?"; // Use prepared statements to avoid SQL injection
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $inputEmail);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the user data
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Get the user data

    // Check if the password matches
    if ($inputPassword == $user['password']) {
        // Set session variables after successful login
        $_SESSION['user'] = $user['name']; // Store the user's name
        $_SESSION['user_id'] = $user['id']; // Store the user's ID
        $_SESSION['loggedIn'] = true; // Set logged in status to true

        // Redirect to the homepage or another page after successful login
        echo "<script type='text/javascript'>
                    alert('Login successfully!');
                    window.location.href = 'index.php'; // Redirect to login page after alert
                  </script>";
        exit();
    } else {
        // Invalid password
        $loginFlag = "false";
    }
} else {
    // No user found with the given email
    $loginFlag = "false";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>