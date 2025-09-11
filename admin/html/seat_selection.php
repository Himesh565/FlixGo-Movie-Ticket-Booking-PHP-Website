<?php
session_start();
require_once("connection.php");

// Assuming user_id and screen_id are set
$user_id = $_SESSION['user_id'] ?? null;
$screen_id = $_GET['screen_id'] ?? null;

if (!$user_id || !$screen_id) {
    header("Location: login.php");
    exit();
}

// Fetch booked seats
$sql = "SELECT seat_number FROM seat_selection WHERE screen_id = $screen_id AND is_booked = 1";
$result = $conn->query($sql);
$bookedSeats = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookedSeats[] = $row['seat_number'];
    }
}

// Fetch screen info
$sql_screen = "SELECT s.show_time, s.price, m.name as movie_name, m.poster as movie_poster 
               FROM screens s 
               JOIN movies m ON s.movie_id = m.id 
               WHERE s.id = $screen_id";
$result_screen = $conn->query($sql_screen);
$screen_info = [];
if ($result_screen->num_rows > 0) {
    $screen_info = $result_screen->fetch_assoc();
} else {
    // Handle the case where screen info is not found
    $screen_info = [
        'show_time' => 'Unknown',
        'price' => 0,
        'movie_name' => 'Unknown Movie',
        'movie_poster' => ''
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Your Seats - BookItNow</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .seat-selection-container {
            padding: 80px 0;
            color: var(--text-color) !important;
        }
        
        .seat-selection-header {
            margin-bottom: 40px;
            text-align: center;
        }
        
        .seat-selection-header h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--heading-color) !important;
        }
        
        .seat-selection-header p {
            color: var(--secondary-text) !important;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .movie-info-card {
            background-color: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px var(--shadow-color);
            margin-bottom: 40px;
        }
        
        .movie-info-content {
            display: flex;
            flex-wrap: wrap;
        }
        
        .movie-poster-container {
            flex: 0 0 150px;
            height: 220px;
        }
        
        .movie-poster-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .movie-details {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: var(--text-color) !important;
        }
        
        .movie-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--heading-color) !important;
        }
        
        .movie-info-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            color: var(--secondary-text) !important;
        }
        
        .movie-info-item i {
            width: 20px;
            margin-right: 10px;
            color: var(--highlight-color);
        }
        
        .movie-info-item span {
            color: var(--text-color) !important;
        }
        
        .seating-layout {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .screen {
            height: 10px;
            background: linear-gradient(to right, rgba(229, 9, 20, 0.5), rgba(229, 9, 20, 1), rgba(229, 9, 20, 0.5));
            border-radius: 50%;
            margin-bottom: 40px;
            box-shadow: 0 10px 20px rgba(229, 9, 20, 0.2);
            position: relative;
        }
        
        .screen::before {
            content: 'SCREEN';
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-color);
            opacity: 0.8;
        }
        
        .row-label {
            width: 30px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-color);
            font-weight: 600;
            opacity: 0.8;
        }
        
        .seat-rows {
            margin-bottom: 30px;
        }
        
        .seat-row {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
            align-items: center;
        }

        .seat {
            width: 40px;
            height: 40px;
            margin: 5px;
            background-color: rgba(0, 123, 255, 0.7);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .seat:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 123, 255, 0.3);
        }
        
        .seat.selected {
            background-color: rgba(40, 167, 69, 0.8);
            box-shadow: 0 8px 15px rgba(40, 167, 69, 0.3);
        }
        
        .seat.booked {
            background-color: rgba(108, 117, 125, 0.5);
            cursor: not-allowed;
            opacity: 0.6;
            box-shadow: none;
            transform: none;
        }

        .seat-legend {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            margin: 0 15px 10px;
        }
        
        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            margin-right: 10px;
        }
        
        .available {
            background-color: rgba(0, 123, 255, 0.7);
        }
        
        .selected-legend {
            background-color: rgba(40, 167, 69, 0.8);
        }
        
        .booked-legend {
            background-color: rgba(108, 117, 125, 0.5);
        }
        
        .booking-summary {
            background-color: var(--card-bg);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px var(--shadow-color);
        }
        
        .booking-summary h3 {
            color: var(--heading-color) !important;
            margin-bottom: 20px;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            color: var(--secondary-text) !important;
        }
        
        .summary-label {
            color: var(--secondary-text) !important;
        }
        
        .summary-value {
            color: var(--text-color) !important;
            font-weight: 600;
        }
        
        .summary-total {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
            font-size: 1.2rem;
            font-weight: 700;
        }

        .confirm-btn {
            background-color: var(--primary-color);
            color: var(--btn-text) !important;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: 600;
            width: 100%;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .confirm-btn:hover {
            background-color: var(--hover-color);
            color: var(--btn-text) !important;
        }
        
        .confirm-btn i {
            font-size: 1.2rem;
        }
        
        @media (max-width: 767px) {
            .booking-summary {
                margin-top: 30px;
            }
        }
        
        .aisle {
            width: 20px;
        }
    </style>
</head>
<body>
    <?php require_once("clinteheader.php"); ?>
    
    <div class="container seat-selection-container">
        <div class="seat-selection-header">
            <h2>Select Your Seats</h2>
            <p>Choose the best seats for your movie experience</p>
        </div>
        
        <div class="movie-info-card" data-aos="fade-up">
            <div class="movie-info-content">
                <div class="movie-poster-container">
                    <img src="<?php echo $screen_info['movie_poster']; ?>" alt="<?php echo htmlspecialchars($screen_info['movie_name']); ?>">
                </div>
                <div class="movie-details">
                    <h3 class="movie-title"><?php echo htmlspecialchars($screen_info['movie_name']); ?></h3>
                    <div class="movie-info-item">
                        <i class="far fa-clock"></i>
                        <span>Show Time: <?php echo htmlspecialchars($screen_info['show_time']); ?></span>
                    </div>
                    <div class="movie-info-item">
                        <i class="fas fa-ticket-alt"></i>
                        <span>Price per Seat: $<?php echo number_format($screen_info['price'], 2); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-8">
                <div class="seating-layout" data-aos="fade-up">
                    <div class="screen"></div>
                    
                    <div class="seat-rows">
            <?php
            $seatNumber = 1;
                        $rowLabels = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
                        
            for ($i = 0; $i < 8; $i++): // 8 rows
                            echo '<div class="seat-row">';
                            
                            // Add row label
                            echo '<div class="row-label">' . $rowLabels[$i] . '</div>';
                            
                for ($j = 0; $j < 10; $j++):
                                // Add an aisle in the middle (after 5 seats)
                                if ($j == 5) {
                                    echo '<div class="aisle"></div>';
                                }
                                
                    $isBooked = in_array($seatNumber, $bookedSeats) ? 'booked' : ''; // Check if the seat is booked
                    echo '<div class="seat ' . $isBooked . '" data-seat-id="' . $seatNumber . '">' . $seatNumber . '</div>';
                    $seatNumber++;
                endfor;
                echo '</div>';
            endfor;
            ?>
        </div>
                    
                    <div class="seat-legend">
                        <div class="legend-item">
                            <div class="legend-color available"></div>
                            <span>Available</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color selected-legend"></div>
                            <span>Selected</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color booked-legend"></div>
                            <span>Booked</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="booking-summary" data-aos="fade-left">
                    <h3>Booking Summary</h3>
                    
                    <div class="summary-item">
                        <div class="summary-label">Movie</div>
                        <div class="summary-value"><?php echo htmlspecialchars($screen_info['movie_name']); ?></div>
                    </div>
                    
                    <div class="summary-item">
                        <div class="summary-label">Show Time</div>
                        <div class="summary-value"><?php echo htmlspecialchars($screen_info['show_time']); ?></div>
                    </div>
                    
                    <div class="summary-item">
                        <div class="summary-label">Price per Seat</div>
                        <div class="summary-value">$<?php echo number_format($screen_info['price'], 2); ?></div>
                    </div>
                    
                    <div class="summary-item">
                        <div class="summary-label">Selected Seats</div>
                        <div class="summary-value" id="selected-seats-display">None</div>
                    </div>
                    
                    <div class="summary-item">
                        <div class="summary-label">Number of Seats</div>
                        <div class="summary-value" id="seat-count">0</div>
                    </div>
                    
                    <div class="summary-item summary-total">
                        <div class="summary-label">Total Price</div>
                        <div class="summary-value" id="total-price">$0.00</div>
                    </div>
                    
                    <button id="confirm-selection" class="confirm-btn">
                        <i class="fas fa-check-circle"></i> Confirm Selection
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php require_once("clintefooter.php"); ?>
    
    <!-- Bootstrap & JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        $(document).ready(function() {
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true
            });
            
            let selectedSeats = [];
            const pricePerSeat = <?php echo $screen_info['price']; ?>;
            
            // Function to update the booking summary
            function updateBookingSummary() {
                const seatCount = selectedSeats.length;
                const totalPrice = seatCount * pricePerSeat;
                
                $('#selected-seats-display').text(selectedSeats.length > 0 ? selectedSeats.join(', ') : 'None');
                $('#seat-count').text(seatCount);
                $('#total-price').text('$' + totalPrice.toFixed(2));
            }
            
            // Handle seat selection
            $('.seat').not('.booked').click(function() {
                const seatId = $(this).data('seat-id');

                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                    selectedSeats = selectedSeats.filter(id => id !== seatId);
                } else {
                    $(this).addClass('selected');
                    selectedSeats.push(seatId);
                    
                    // Sort the seats for better display
                    selectedSeats.sort((a, b) => a - b);
                }
                
                updateBookingSummary();
            });

            // Handle confirm button click
            $('#confirm-selection').click(function() {
                if (selectedSeats.length === 0) {
                    alert('Please select at least one seat to continue.');
                    return;
                }

                // Redirect to the payment page with selected seats
                window.location.href = "payment.php?seats=" + selectedSeats.join(",") + "&screen_id=<?php echo $screen_id; ?>&user_id=<?php echo $user_id; ?>";
            });
        });
    </script>
</body>
</html>
