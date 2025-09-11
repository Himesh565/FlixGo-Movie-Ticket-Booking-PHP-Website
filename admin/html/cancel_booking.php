<?php
session_start();
require_once("connection.php");

// For debugging
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = [
        'type' => 'danger',
        'text' => 'Invalid booking selection.'
    ];
    header("Location: my_booking.php");
    exit();
}

$selection_id = $_GET['id'];

// Direct approach - first check if booking exists
$check_sql = "SELECT id FROM seat_selection WHERE id = $selection_id AND user_id = $user_id AND is_booked = 1";
$check_result = $conn->query($check_sql);

if ($check_result && $check_result->num_rows > 0) {
    // Booking exists, now get details for confirmation message
    $sql_verify = "SELECT s.id, s.seat_number, m.name AS movie_name, sc.show_time 
                  FROM seat_selection s 
                  JOIN screens sc ON s.screen_id = sc.id 
                  JOIN movies m ON sc.movie_id = m.id
                  WHERE s.id = ? AND s.user_id = ? AND s.is_booked = 1";

    $stmt_verify = $conn->prepare($sql_verify);
    $stmt_verify->bind_param('ii', $selection_id, $user_id);
    $stmt_verify->execute();
    $result = $stmt_verify->get_result();
    
    if ($result->num_rows > 0) {
        $booking_details = $result->fetch_assoc();
        $movie_name = $booking_details['movie_name'];
        $show_time = $booking_details['show_time'];
        
        // Now perform the direct update with a simpler query
        $direct_update = "UPDATE seat_selection SET is_booked = 0 WHERE id = $selection_id";
        
        if ($conn->query($direct_update)) {
            // Success
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => "Your booking for \"$movie_name\" at $show_time has been successfully cancelled."
            ];
        } else {
            // Failed
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'Failed to cancel booking: ' . $conn->error
            ];
        }
        
        $stmt_verify->close();
    } else {
        $_SESSION['message'] = [
            'type' => 'warning',
            'text' => 'Booking details not found.'
        ];
    }
} else {
    $_SESSION['message'] = [
        'type' => 'warning',
        'text' => 'Booking not found or already cancelled.'
    ];
}

// Redirect back to bookings page
header("Location: my_booking.php");
exit();
