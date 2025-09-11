<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page if they're not logged in
    header("Location: login.php");
    exit();
}

require_once("connection.php");

$user_id = $_SESSION['user_id']; // The logged-in user's ID

// Fetch all bookings for the user with movie posters
$sql = "
    SELECT 
        s.id AS selection_id,
        s.seat_number, 
        m.name AS movie_name, 
        m.poster AS movie_poster,
        t.name AS theater_name, 
        sc.screen_number, 
        sc.show_time, 
        sc.price,
        COALESCE(p.payment_date, NOW()) AS booking_date
    FROM 
        seat_selection s
    JOIN 
        screens sc ON s.screen_id = sc.id
    JOIN 
        movies m ON sc.movie_id = m.id
    JOIN 
        theater t ON m.theater_id = t.id
    LEFT JOIN 
        payments p ON s.screen_id = p.screen_id AND p.user_id = s.user_id
    WHERE 
        s.user_id = $user_id AND s.is_booked = 1
    ORDER BY
        booking_date DESC
";

$result = $conn->query($sql);

// Check for SQL error
if (!$result) {
    die("SQL Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - BookItNow</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #e74c3c;
            --hover-color: #c0392b;
            --card-bg: #1e1e2f;
            --body-bg: #121225;
            --text-color: #ffffff;
            --secondary-text: rgba(255, 255, 255, 0.7);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--body-bg);
            color: var(--text-color);
        }
        
        .bookings-section {
            padding: 80px 0;
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-align: center;
            color: var(--text-color);
        }
        
        .page-subtitle {
            text-align: center;
            color: var(--secondary-text);
            margin-bottom: 40px;
            font-weight: 500;
        }
        
        .booking-card {
            background-color: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .booking-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .booking-content {
            display: flex;
            flex-wrap: wrap;
        }
        
        .booking-poster {
            flex: 0 0 140px;
            max-width: 140px;
            background-color: #000;
            height: 210px;
        }
        
        .booking-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .booking-details {
            flex: 1;
            padding: 20px;
            position: relative;
        }
        
        .booking-date {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 0.85rem;
            color: var(--secondary-text);
            background-color: rgba(0, 0, 0, 0.3);
            padding: 4px 10px;
            border-radius: 4px;
        }
        
        .movie-name {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--text-color);
        }
        
        .booking-info {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            margin-right: 25px;
            margin-bottom: 10px;
        }
        
        .info-item i {
            width: 18px;
            margin-right: 8px;
            color: var(--primary-color);
            font-size: 0.9rem;
        }
        
        .info-label {
            color: var(--secondary-text);
            margin-right: 5px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .info-value {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-color);
        }
        
        .booking-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .booking-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-color);
        }
        
        .actions-right {
            display: flex;
            gap: 10px;
        }
        
        .btn-action {
            padding: 10px 15px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            color: #ffffff !important;
            border: none;
            text-decoration: none;
        }
        
        .btn-action i {
            margin-right: 8px;
        }
        
        .btn-cancel {
            background-color: #e74c3c !important;
        }
        
        .btn-cancel:hover {
            background-color: #c0392b !important;
            transform: translateY(-2px);
        }
        
        .btn-download {
            background-color: #3498db !important;
        }
        
        .btn-download:hover {
            background-color: #2980b9 !important;
            transform: translateY(-2px);
        }
        
        .empty-bookings {
            text-align: center;
            padding: 60px 0;
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }
        
        .empty-icon {
            font-size: 4rem;
            color: rgba(255, 255, 255, 0.2);
            margin-bottom: 20px;
        }
        
        .empty-text {
            font-size: 1.3rem;
            margin-bottom: 20px;
            color: var(--text-color);
            font-weight: 600;
        }
        
        .btn-browse {
            background-color: var(--primary-color);
            color: #fff;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }
        
        .btn-browse:hover {
            background-color: var(--hover-color);
            transform: translateY(-3px);
            color: #fff;
        }
        
        .btn-browse i {
            margin-right: 8px;
        }
        
        @media (max-width: 767px) {
            .booking-poster {
                flex: 0 0 100px;
                max-width: 100px;
            }
            
            .movie-name {
                font-size: 1.3rem;
                padding-right: 60px;
            }
            
            .booking-date {
                top: 15px;
                right: 15px;
                font-size: 0.8rem;
            }
            
            .booking-actions {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .actions-right {
                width: 100%;
                justify-content: space-between;
            }
        }
        
        /* Modal styling */
        .modal-content {
            background-color: var(--card-bg);
            color: var(--text-color);
            border-radius: 12px;
            overflow: hidden;
        }
        
        .modal-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background-color: rgba(231, 76, 60, 0.1);
        }
        
        .modal-title {
            font-weight: 600;
        }
        
        .modal-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        
        .btn-cancel-confirm {
            background-color: #e74c3c;
            color: white;
            border: none;
        }
        
        .btn-cancel-confirm:hover {
            background-color: #c0392b;
        }
        
        /* Alert message styling */
        .alert-box {
            margin-bottom: 30px;
            border-radius: 8px;
            padding: 16px 20px;
        }
        
        .alert-box.alert-success {
            background-color: rgba(25, 135, 84, 0.15);
            border: 1px solid rgba(25, 135, 84, 0.3);
            color: #198754;
        }
        
        .alert-box.alert-danger {
            background-color: rgba(220, 53, 69, 0.15);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #dc3545;
        }
        
        .alert-box.alert-warning {
            background-color: rgba(255, 193, 7, 0.15);
            border: 1px solid rgba(255, 193, 7, 0.3);
            color: #ffc107;
        }
        
        .alert-close {
            float: right;
            color: inherit;
            font-size: 1.2rem;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }
        
        .alert-close:hover {
            opacity: 1;
        }
    </style>
</head>
<body>
    <?php require_once("clinteheader.php"); ?>
    
    <section class="bookings-section">
        <div class="container">
            <h1 class="page-title" data-aos="fade-up">My Bookings</h1>
            <p class="page-subtitle" data-aos="fade-up" data-aos-delay="100">View and manage all your movie ticket bookings</p>
            
            <!-- Add link to direct cancellation page -->
            <div class="text-center mb-4">
                <a href="direct_cancel.php" class="text-muted small">Direct Cancellation Tool</a>
            </div>
            
            <?php
            // Display message if set
            if (isset($_SESSION['message'])) {
                $message_type = $_SESSION['message']['type'];
                $message_text = $_SESSION['message']['text'];
                echo '<div class="alert-box alert-' . $message_type . '" data-aos="fade-up" data-aos-delay="150">';
                echo '<span class="alert-close" onclick="this.parentElement.style.display=\'none\';">&times;</span>';
                
                // Choose icon based on message type
                $icon = 'info-circle';
                if ($message_type == 'success') $icon = 'check-circle';
                if ($message_type == 'danger') $icon = 'exclamation-circle';
                if ($message_type == 'warning') $icon = 'exclamation-triangle';
                
                echo '<i class="fas fa-' . $icon . ' me-2"></i>' . $message_text;
                echo '</div>';
                
                // Clear the message after displaying it
                unset($_SESSION['message']);
            }
            ?>
            
            <?php if ($result->num_rows > 0): ?>
                <div class="row">
                    <?php while ($row = $result->fetch_assoc()): 
                        // Safely format booking date - use current date as fallback
                        try {
                            $booking_date = new DateTime($row['booking_date']);
                            $formatted_date = $booking_date->format('M d, Y');
                        } catch (Exception $e) {
                            $booking_date = new DateTime();
                            $formatted_date = $booking_date->format('M d, Y');
                        }
                    ?>
                        <div class="col-12" data-aos="fade-up">
                            <div class="booking-card">
                                <div class="booking-content">
                                    <div class="booking-poster">
                                        <img src="<?php echo $row['movie_poster']; ?>" alt="<?php echo htmlspecialchars($row['movie_name']); ?>">
                                    </div>
                                    <div class="booking-details">
                                        <div class="booking-date">
                                            <i class="far fa-calendar-alt me-1"></i> <?php echo $formatted_date; ?>
                                        </div>
                                        <h3 class="movie-name"><?php echo htmlspecialchars($row['movie_name']); ?></h3>
                                        
                                        <div class="booking-info">
                                            <div class="info-item">
                                                <i class="fas fa-film"></i>
                                                <span class="info-label">Theater:</span>
                                                <span class="info-value"><?php echo htmlspecialchars($row['theater_name']); ?></span>
                                            </div>
                                            <div class="info-item">
                                                <i class="fas fa-tv"></i>
                                                <span class="info-label">Screen:</span>
                                                <span class="info-value"><?php echo htmlspecialchars($row['screen_number']); ?></span>
                                            </div>
                                            <div class="info-item">
                                                <i class="far fa-clock"></i>
                                                <span class="info-label">Showtime:</span>
                                                <span class="info-value"><?php echo htmlspecialchars($row['show_time']); ?></span>
                                            </div>
                                            <div class="info-item">
                                                <i class="fas fa-couch"></i>
                                                <span class="info-label">Seat:</span>
                                                <span class="info-value"><?php echo htmlspecialchars($row['seat_number']); ?></span>
                                            </div>
                                        </div>
                                        
                                        <div class="booking-actions">
                                            <div class="booking-price">
                                                <span>Price: $<?php echo number_format($row['price'], 2); ?></span>
                                            </div>
                                            <div class="actions-right">
                                                <a href="receipt.php?id=<?php echo $row['selection_id']; ?>" class="btn btn-action btn-download">
                                                    <i class="fas fa-download"></i> Ticket
                                                </a>
                                                <a href="javascript:void(0)" 
                                                   onclick="confirmCancel(<?php echo $row['selection_id']; ?>, '<?php echo htmlspecialchars($row['movie_name']); ?>', '<?php echo htmlspecialchars($row['show_time']); ?>')" 
                                                   class="btn btn-action btn-cancel">
                                                    <i class="fas fa-times"></i> Cancel
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-bookings" data-aos="fade-up">
                    <div class="empty-icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <h3 class="empty-text">You don't have any bookings yet</h3>
                    <a href="index.php" class="btn btn-browse">
                        <i class="fas fa-film"></i> Browse Theaters
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php require_once('clintefooter.php'); ?>

    <!-- Bootstrap & JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true
            });
        });

        function confirmCancel(id, movieName, showTime) {
            if (confirm('Are you sure you want to cancel your booking for "' + movieName + '" at ' + showTime + '?\n\nThis action cannot be undone.')) {
                // Show loading overlay
                let overlay = document.createElement('div');
                overlay.style.position = 'fixed';
                overlay.style.top = 0;
                overlay.style.left = 0;
                overlay.style.width = '100%';
                overlay.style.height = '100%';
                overlay.style.backgroundColor = 'rgba(0,0,0,0.7)';
                overlay.style.display = 'flex';
                overlay.style.justifyContent = 'center';
                overlay.style.alignItems = 'center';
                overlay.style.zIndex = 9999;
                overlay.id = 'cancel-overlay';
                overlay.innerHTML = '<div style="background: white; padding: 20px; border-radius: 5px; color: #333;"><i class="fas fa-spinner fa-spin me-2"></i> Cancelling your booking...</div>';
                document.body.appendChild(overlay);
                
                // Redirect to cancel_booking.php
                window.location.href = 'cancel_booking.php?id=' + id;
                
                return true;
            }
            return false;
        }
    </script>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
