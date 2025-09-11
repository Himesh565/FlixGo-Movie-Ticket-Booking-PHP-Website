<?php
session_start();  // Start session to track logged-in user

// Ensure the user is logged in, else redirect to login page
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");  // Redirect to login if not logged in
    exit();
}

// Fetch POST data from the payment form
$selectedSeats = isset($_POST['selected_seats']) ? explode(',', $_POST['selected_seats']) : [];  // Convert to array
$totalPrice = isset($_POST['total_price']) ? $_POST['total_price'] : '';
$user_id = $_SESSION['user_id'];  // Get the user ID from session
$screen_id = isset($_POST['screen_id']) ? $_POST['screen_id'] : '';

// Ensure necessary data is available
if (empty($selectedSeats) || !$totalPrice || !$user_id || !$screen_id) {
    echo "Missing payment data.";
    exit();
}

require_once("connection.php");  // Include the database connection

// Step 1: Store payment data into payments table
$sql = "INSERT INTO payments (user_id, screen_id, seats, total_price) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('Error preparing the query: ' . $conn->error);  // Check for query preparation errors
}

// Bind parameters for the query
$selectedSeatsStr = implode(',', $selectedSeats);  // Convert the selected seats array to a comma-separated string
$stmt->bind_param("iisi", $user_id, $screen_id, $selectedSeatsStr, $totalPrice);

// Execute the query to insert payment data
if (!$stmt->execute()) {
    echo "Error executing query: " . $stmt->error;
    $stmt->close();
    exit();
}

$stmt->close();

// Step 2: Insert selected seats into the seat_selection table
$stmt = $conn->prepare("INSERT INTO seat_selection (screen_id, user_id, seat_number, is_booked) VALUES (?, ?, ?, ?)");
$isBooked = 1;  // Mark the seats as booked (1)

$selection_id = 0; // Variable to store the last inserted ID
foreach ($selectedSeats as $seat) {
    $stmt->bind_param("iiii", $screen_id, $user_id, $seat, $isBooked);
    if (!$stmt->execute()) {
        echo "Error inserting seat " . $seat . " into the database: " . $stmt->error;
        $stmt->close();
        exit();
    }
    
    // Get the last inserted ID (for the first seat only, as we'll need one for the redirect)
    if ($selection_id === 0) {
        $selection_id = $conn->insert_id;
    }
}

// Step 3: Redirect to the receipt page with the selection_id parameter
header("Location: receipt.php?id=$selection_id");
exit();

// Close the prepared statement
$stmt->close();
$conn->close();
?>
