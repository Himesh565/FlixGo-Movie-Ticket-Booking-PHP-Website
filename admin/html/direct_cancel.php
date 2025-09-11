<?php
session_start();
require_once("connection.php");

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['loggedIn'] !== true) {
    die("You must be logged in to access this page.");
}

$user_id = $_SESSION['user_id'];

// Handle direct cancellation
if (isset($_GET['cancel_id']) && !empty($_GET['cancel_id'])) {
    $id = $_GET['cancel_id'];
    
    // Direct SQL query to cancel booking
    $sql = "UPDATE seat_selection SET is_booked = 0 WHERE id = $id AND user_id = $user_id";
    
    if ($conn->query($sql)) {
        echo "<div style='color:green;'>Successfully cancelled booking #$id</div>";
    } else {
        echo "<div style='color:red;'>Failed to cancel booking: " . $conn->error . "</div>";
    }
}

// Show bookings
$sql = "SELECT s.id, s.seat_number, m.name AS movie_name, sc.show_time 
        FROM seat_selection s 
        JOIN screens sc ON s.screen_id = sc.id
        JOIN movies m ON sc.movie_id = m.id
        WHERE s.user_id = $user_id AND s.is_booked = 1";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Direct Booking Cancellation</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { background-color: #f44336; color: white; padding: 5px 10px; text-decoration: none; }
        .back { margin-top: 20px; display: block; }
    </style>
</head>
<body>
    <h1>Direct Booking Cancellation</h1>
    <p>This page allows direct cancellation of bookings in case the regular cancel button is not working.</p>
    
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Movie</th>
                <th>Seat</th>
                <th>Showtime</th>
                <th>Action</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['movie_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['seat_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['show_time']); ?></td>
                    <td>
                        <a href="direct_cancel.php?cancel_id=<?php echo $row['id']; ?>" 
                           class="btn" 
                           onclick="return confirm('Are you sure you want to cancel this booking?')">
                            Cancel
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No active bookings found.</p>
    <?php endif; ?>
    
    <a href="my_booking.php" class="back">Return to My Bookings</a>
</body>
</html>

<?php
$conn->close();
?> 