<?php
session_start();
require_once("connection.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get all active bookings for the user
$sql = "SELECT 
            s.id AS selection_id,
            s.seat_number, 
            m.name AS movie_name, 
            t.name AS theater_name, 
            sc.screen_number, 
            sc.show_time
        FROM 
            seat_selection s
        JOIN 
            screens sc ON s.screen_id = sc.id
        JOIN 
            movies m ON sc.movie_id = m.id
        JOIN 
            theater t ON m.theater_id = t.id
        WHERE 
            s.user_id = ? AND s.is_booked = 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Booking Cancellation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Test Booking Cancellation</h1>
        
        <?php if(isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message']['type']; ?>" role="alert">
                <?php 
                    echo $_SESSION['message']['text']; 
                    unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if($result->num_rows > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Movie</th>
                        <th>Theater</th>
                        <th>Screen</th>
                        <th>Seat</th>
                        <th>Show Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['selection_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['movie_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['theater_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['screen_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['seat_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['show_time']); ?></td>
                            <td>
                                <a href="cancel_booking.php?id=<?php echo $row['selection_id']; ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Are you sure you want to cancel this booking?')">
                                    Cancel Booking
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">You don't have any active bookings.</div>
        <?php endif; ?>
        
        <a href="my_booking.php" class="btn btn-primary">Back to My Bookings</a>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?> 