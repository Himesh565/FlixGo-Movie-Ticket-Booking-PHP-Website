<?php
session_start();
require_once("connection.php");

// Set error reporting to maximum
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['loggedIn'] !== true) {
    echo "Not logged in";
    exit();
}

$user_id = $_SESSION['user_id'];

// Show active bookings first
echo "<h2>Active Bookings</h2>";
$sql_bookings = "SELECT s.id, s.seat_number, s.user_id, s.is_booked, m.name as movie_name 
                FROM seat_selection s
                JOIN screens sc ON s.screen_id = sc.id
                JOIN movies m ON sc.movie_id = m.id
                WHERE s.user_id = ? AND s.is_booked = 1";

$stmt_bookings = $conn->prepare($sql_bookings);
$stmt_bookings->bind_param('i', $user_id);
$stmt_bookings->execute();
$result_bookings = $stmt_bookings->get_result();

if ($result_bookings->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Seat Number</th><th>Movie</th><th>Action</th></tr>";
    
    while ($row = $result_bookings->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['seat_number'] . "</td>";
        echo "<td>" . $row['movie_name'] . "</td>";
        echo "<td><a href='debug_cancel.php?action=cancel&id=" . $row['id'] . "'>Cancel</a></td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "<p>No active bookings found</p>";
}

// Process cancellation if requested
if (isset($_GET['action']) && $_GET['action'] == 'cancel' && isset($_GET['id'])) {
    $selection_id = $_GET['id'];
    
    echo "<h2>Attempting to Cancel Booking ID: $selection_id</h2>";
    
    // 1. Verify the booking
    echo "<h3>Step 1: Verifying booking ownership</h3>";
    $sql_verify = "SELECT s.id, s.user_id, s.is_booked FROM seat_selection s WHERE s.id = ? AND s.user_id = ?";
    $stmt_verify = $conn->prepare($sql_verify);
    $stmt_verify->bind_param('ii', $selection_id, $user_id);
    $stmt_verify->execute();
    $result_verify = $stmt_verify->get_result();
    
    if ($result_verify->num_rows > 0) {
        $verification = $result_verify->fetch_assoc();
        echo "<p>Booking found: ID = " . $verification['id'] . ", User ID = " . $verification['user_id'] . ", Is Booked = " . $verification['is_booked'] . "</p>";
        
        // 2. Perform the update
        echo "<h3>Step 2: Performing cancellation</h3>";
        $sql_update = "UPDATE seat_selection SET is_booked = 0 WHERE id = ? AND user_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param('ii', $selection_id, $user_id);
        
        if ($stmt_update->execute()) {
            echo "<p>Update query executed successfully</p>";
            echo "<p>Affected rows: " . $stmt_update->affected_rows . "</p>";
            
            if ($stmt_update->affected_rows > 0) {
                echo "<p style='color: green;'>Booking successfully cancelled!</p>";
            } else {
                echo "<p style='color: red;'>No rows were updated. The booking may already be cancelled.</p>";
            }
        } else {
            echo "<p style='color: red;'>Error executing update: " . $stmt_update->error . "</p>";
        }
        
        $stmt_update->close();
    } else {
        echo "<p style='color: red;'>Verification failed: Booking not found or doesn't belong to you</p>";
    }
    
    $stmt_verify->close();
    
    echo "<p><a href='debug_cancel.php'>Refresh booking list</a></p>";
}

// Close connection
$stmt_bookings->close();
$conn->close();
?> 