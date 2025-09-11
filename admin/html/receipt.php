<?php
session_start();
require_once("connection.php"); // Database connection

// Ensure the user is logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];  // Get the user ID from session

// Get selection_id from URL parameter if available (for accessing from my bookings)
if (isset($_GET['id'])) {
    $selection_id = $_GET['id'];
    
    // Fetch booking details from seat_selection table
    $sql = "
        SELECT 
            s.id AS selection_id,
            s.seat_number, 
            s.screen_id,
            COALESCE(p.payment_date, NOW()) AS booking_date,
            m.name AS movie_name, 
            m.poster AS movie_poster,
            t.name AS theater_name, 
            sc.screen_number, 
            sc.show_time, 
            sc.price
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
            s.id = ? AND s.is_booked = 1
    ";
    
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Error preparing the query: ' . $conn->error);
    }
    
    $stmt->bind_param("i", $selection_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the booking details
        $booking = $result->fetch_assoc();
        $selectedSeats = $booking['seat_number'];
        $seatPrice = $booking['price'];
        $totalPrice = $booking['price']; // Assuming price is per seat
        $showTime = $booking['show_time'];
        $movieName = $booking['movie_name'];
        $theaterName = $booking['theater_name'];
        $screenNumber = $booking['screen_number'];
        $moviePoster = $booking['movie_poster'];
        $bookingDate = new DateTime($booking['booking_date']);
        $formattedBookingDate = $bookingDate->format('F j, Y, g:i a');
    } else {
        echo "Booking not found.";
        exit();
    }
} else {
    // Fetch the most recent booking for this user
    $sql = "
        SELECT 
            s.id AS selection_id,
            s.seat_number, 
            s.screen_id,
            COALESCE(p.payment_date, NOW()) AS booking_date,
            m.name AS movie_name, 
            m.poster AS movie_poster,
            t.name AS theater_name, 
            sc.screen_number, 
            sc.show_time, 
            sc.price
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
            s.user_id = ? AND s.is_booked = 1
        ORDER BY 
            s.id DESC
        LIMIT 1
    ";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Error preparing the query: ' . $conn->error);  // Check for query preparation errors
    }

    $stmt->bind_param("i", $user_id);  // Bind user_id to the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the booking details
        $booking = $result->fetch_assoc();
        $selectedSeats = $booking['seat_number'];
        $seatPrice = $booking['price']; 
        $totalPrice = $booking['price']; // Assuming price is per seat
        $showTime = $booking['show_time'];
        $movieName = $booking['movie_name'];
        $theaterName = $booking['theater_name'];
        $screenNumber = $booking['screen_number'];
        $moviePoster = $booking['movie_poster'];
        $bookingDate = new DateTime($booking['booking_date']);
        $formattedBookingDate = $bookingDate->format('F j, Y, g:i a');
        $screen_id = $booking['screen_id'];
    } else {
        echo "No booking found.";
        exit();
    }
}

// Fetch user's name from the users table
$sql_user = "SELECT firstname, lastname, email FROM userinfo WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
if ($stmt_user === false) {
    die('Error preparing the query: ' . $conn->error);  // Check for query preparation errors
}
$stmt_user->bind_param("i", $user_id);  // Bind user_id to the query
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
    $userName = $user['firstname'] . ' ' . $user['lastname']; // Full name
    $userEmail = $user['email'];
} else {
    $userName = "Guest"; // Default name if not found
    $userEmail = "guest@example.com";
}

// Generate a unique ticket number (can be based on booking ID or timestamp)
$ticketNumber = "BIN" . date('Ymd') . sprintf("%06d", $booking['selection_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Print-specific CSS -->
    <style>
        @media print {
            /* Hide elements that shouldn't appear in the print */
            body * {
                visibility: hidden; /* Hide everything */
            }

            #print-content, #print-content * {
                visibility: visible; /* Show only the content inside the #print-content div */
            }

            /* Make the printed page take up more space */
            .container {
                width: 100%;
                max-width: none;
            }

            /* Hide the print button */
            #print-button {
                display: none;
            }
        }

        /* Custom Styles */
        .card {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #f8f9fa;
            font-size: 1.25rem;
            font-weight: 500;
        }

        .section-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .section-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #007bff;
        }

        .btn-success,
        .btn-primary {
            font-size: 1.1rem;
            padding: 10px 20px;
            width: 45%; /* Make buttons take equal width */
        }

        .action-buttons {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 40px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        @media (max-width: 767px) {
            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }
            
            .action-buttons .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }

        .ticket-footer {
            padding: 25px 20px;
            background-color: #151522;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }
        
        .ticket-note {
            font-size: 0.9rem;
            color: #ffffff;
            font-weight: 600;
            width: 100%;
            text-align: left;
            padding: 10px;
            border-left: 3px solid var(--primary-color);
            background-color: rgba(231, 76, 60, 0.1);
        }
        
        .ticket-note i {
            color: var(--primary-color);
            margin-right: 5px;
        }
        
        .ticket-actions {
            display: flex;
            gap: 15px;
            width: 100%;
            justify-content: center;
        }
        
        .btn-ticket-action {
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 140px;
            box-shadow: 0 4px 0 rgba(0, 0, 0, 0.2);
            color: #ffffff !important;
            text-transform: uppercase;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-ticket-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 0 rgba(0, 0, 0, 0.2);
        }
        
        .btn-ticket-action i {
            margin-right: 8px;
        }
        
        .btn-print {
            background-color: #e74c3c !important;
        }
        
        .btn-print:hover {
            background-color: #c0392b !important;
        }
        
        .btn-download {
            background-color: #27ae60 !important;
        }
        
        .btn-download:hover {
            background-color: #219955 !important;
        }
        
        .btn-back {
            background-color: #3498db !important;
        }
        
        .btn-back:hover {
            background-color: #2980b9 !important;
        }

    </style>
</head>
<body>
    <?php require_once('clinteheader.php'); ?>

    <div class="container mt-5">
        <div class="section-header">
            <h2>Your Movie Ticket</h2>
        </div>

        <div class="card p-4" id="print-content">
            <div class="card-header">
                <h4>Booking Information</h4>
            </div>

            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3 text-center">
                        <img src="<?php echo $moviePoster; ?>" alt="<?php echo htmlspecialchars($movieName); ?>" class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                    <div class="col-md-9">
                        <h3><?php echo htmlspecialchars($movieName); ?></h3>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <p><strong>Theater:</strong> <?php echo htmlspecialchars($theaterName); ?></p>
                                <p><strong>Screen:</strong> <?php echo htmlspecialchars($screenNumber); ?></p>
                                <p><strong>Seat(s):</strong> <?php echo htmlspecialchars($selectedSeats); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Show Time:</strong> <?php echo htmlspecialchars($showTime); ?></p>
                                <p><strong>Booking Date:</strong> <?php echo $formattedBookingDate; ?></p>
                                <p><strong>Ticket #:</strong> <?php echo $ticketNumber; ?></p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <p><strong>Customer:</strong> <?php echo htmlspecialchars($userName); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($userEmail); ?></p>
                            <p><strong>Price:</strong> $<?php echo number_format($totalPrice, 2); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <div class="qr-code bg-white d-inline-block p-2 rounded">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo urlencode($ticketNumber); ?>" alt="QR Code">
                    </div>
                    <p class="mt-2">Scan this QR code at the theater entrance for quick check-in</p>
                </div>

                <div class="alert alert-info mt-4" role="alert">
                    <i class="fas fa-info-circle me-2"></i> Please arrive at least 15 minutes before the show time. This ticket is non-refundable after showtime.
                </div>
            </div>
        </div>
    </div>

    <!-- Action buttons outside the container -->
    <div class="action-buttons mt-4 mb-5">
        <!-- Print Ticket Button -->
        <button id="print-button" class="btn btn-success btn-lg" onclick="window.print();" style="background-color: #27ae60; color: white; font-weight: bold; min-width: 160px;">
            <i class="fas fa-print me-2"></i> Print Ticket
        </button>

        <!-- Download Ticket Button -->
        <button type="button" class="btn btn-lg" onclick="downloadTicket()" style="background-color: #3498db; color: white; font-weight: bold; min-width: 160px;">
            <i class="fas fa-download me-2"></i> Download Ticket
        </button>
        
        <!-- Back Button -->
        <a href="my_booking.php" class="btn btn-lg" style="background-color: #e74c3c; color: white; font-weight: bold; min-width: 160px;">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        // Function to download ticket as image
        function downloadTicket() {
            const ticketElement = document.getElementById('print-content');
            
            // Show loading indicator
            const loadingDiv = document.createElement('div');
            loadingDiv.style.position = 'fixed';
            loadingDiv.style.top = '0';
            loadingDiv.style.left = '0';
            loadingDiv.style.width = '100%';
            loadingDiv.style.height = '100%';
            loadingDiv.style.backgroundColor = 'rgba(0,0,0,0.7)';
            loadingDiv.style.display = 'flex';
            loadingDiv.style.justifyContent = 'center';
            loadingDiv.style.alignItems = 'center';
            loadingDiv.style.zIndex = '9999';
            loadingDiv.innerHTML = '<div style="color: white; font-size: 20px;"><i class="fas fa-spinner fa-spin me-2"></i> Generating ticket image...</div>';
            document.body.appendChild(loadingDiv);
            
            // Use setTimeout to allow the loading indicator to render
            setTimeout(() => {
                html2canvas(ticketElement, {
                    scale: 2,
                    logging: false,
                    useCORS: true,
                    backgroundColor: '#ffffff'
                }).then(canvas => {
                    // Create image
                    const image = canvas.toDataURL("image/png");
                    
                    // Create temporary link
                    const tmpLink = document.createElement('a');
                    tmpLink.download = 'BookItNow-Ticket-<?php echo $ticketNumber; ?>.png';
                    tmpLink.href = image;
                    
                    // Temporarily add link to document and trigger download
                    document.body.appendChild(tmpLink);
                    tmpLink.click();
                    document.body.removeChild(tmpLink);
                    
                    // Remove loading indicator
                    document.body.removeChild(loadingDiv);
                });
            }, 100);
        }
    </script>

    <?php require_once('clintefooter.php'); ?>

</body>
</html>
