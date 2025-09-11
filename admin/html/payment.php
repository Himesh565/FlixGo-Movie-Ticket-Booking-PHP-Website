<?php
session_start();
require_once("connection.php");

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");  // Redirect to login if not logged in
    exit();
}

// Retrieve data from the URL
$selectedSeats = isset($_GET['seats']) ? explode(',', $_GET['seats']) : [];
$screen_id = isset($_GET['screen_id']) ? $_GET['screen_id'] : null;
$user_id = $_SESSION['user_id'];  // Get user ID from session

// Validate the data
if (!$user_id || !$screen_id || empty($selectedSeats)) {
    header("Location: index.php");
    exit();
}

// Fetch movie and screen information
$sql = "SELECT s.price, s.show_time, m.name as movie_name, m.poster as movie_poster, t.name as theater_name 
        FROM screens s 
        JOIN movies m ON s.movie_id = m.id 
        JOIN theater t ON m.theater_id = t.id 
        WHERE s.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $screen_id);  // 'i' for integer
$stmt->execute();
$result = $stmt->get_result();
$movie_info = $result->fetch_assoc();
$stmt->close();

// Store show time in session to pass it to the receipt page
$_SESSION['show_time'] = $movie_info['show_time'];

// Calculate total price
$totalPrice = count($selectedSeats) * $movie_info['price'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - BookItNow</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .payment-section {
            padding: 80px 0;
            color: var(--text-color) !important;
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 40px;
            text-align: center;
            color: var(--heading-color) !important;
        }
        
        .payment-container {
            border-radius: 12px;
            overflow: hidden;
            background-color: var(--card-bg);
            box-shadow: 0 5px 15px var(--shadow-color);
            color: var(--text-color) !important;
        }
        
        .booking-summary {
            padding: 30px;
            background: linear-gradient(135deg, rgba(229, 9, 20, 0.8) 0%, rgba(155, 0, 9, 0.8) 100%);
        }
        
        .booking-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            color: var(--text-color) !important;
        }
        
        .booking-title i {
            margin-right: 10px;
        }
        
        .movie-info {
            display: flex;
            margin-bottom: 30px;
            color: var(--text-color) !important;
        }
        
        .movie-poster {
            width: 120px;
            height: 180px;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            margin-right: 20px;
            flex-shrink: 0;
        }
        
        .movie-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .movie-details {
            flex-grow: 1;
        }
        
        .movie-name {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--text-color) !important;
        }
        
        .movie-meta {
            margin-bottom: 20px;
            color: var(--secondary-text) !important;
        }
        
        .movie-meta-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 0.95rem;
            color: var(--secondary-text) !important;
        }
        
        .movie-meta-item i {
            width: 20px;
            margin-right: 10px;
            font-size: 1rem;
            color: var(--highlight-color);
        }
        
        .movie-meta-item span {
            color: var(--text-color) !important;
        }
        
        .summary-details {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            padding: 20px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 0.95rem;
        }
        
        .summary-label {
            font-weight: 500;
            color: var(--secondary-text) !important;
        }
        
        .summary-value {
            font-weight: 600;
            color: var(--text-color) !important;
        }
        
        .summary-total {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            font-size: 1.1rem;
        }
        
        .payment-form {
            padding: 30px;
        }
        
        .payment-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            color: var(--heading-color) !important;
        }
        
        .payment-title i {
            margin-right: 10px;
            color: var(--primary-color);
        }
        
        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
            color: var(--text-color) !important;
        }
        
        .form-control {
            height: 50px;
            border-radius: 8px;
            background-color: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--text-color);
            padding-left: 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.1);
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        .card-row {
            display: flex;
            gap: 15px;
        }
        
        .expiry-col {
            flex: 1;
        }
        
        .cvc-col {
            flex: 0 0 120px;
        }
        
        .payment-methods {
            margin-bottom: 25px;
        }
        
        .payment-method {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 40px;
            margin-right: 10px;
            border-radius: 6px;
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .payment-method:hover, .payment-method.active {
            border-color: var(--primary-color);
            background-color: rgba(229, 9, 20, 0.1);
        }
        
        .payment-method img {
            max-width: 36px;
            max-height: 24px;
            filter: brightness(0.8);
        }
        
        .payment-method.active img {
            filter: brightness(1);
        }
        
        .pay-btn {
            height: 50px;
            width: 100%;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .pay-btn:hover {
            background-color: var(--hover-color);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(229, 9, 20, 0.3);
        }
        
        .pay-btn i {
            margin-right: 10px;
        }
        
        .secure-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }
        
        .secure-badge i {
            margin-right: 10px;
            color: #4CAF50;
        }
        
        @media (max-width: 767px) {
            .movie-info {
                flex-direction: column;
            }
            
            .movie-poster {
                margin-right: 0;
                margin-bottom: 20px;
                width: 100px;
                height: 150px;
            }
            
            .card-row {
                flex-direction: column;
                gap: 20px;
            }
            
            .cvc-col {
                flex: 0 0 auto;
            }
        }
    </style>
</head>

<body>
    <?php require_once('clinteheader.php'); ?>

    <section class="payment-section">
        <div class="container">
            <h1 class="page-title" data-aos="fade-up">Complete Your Payment</h1>
            
            <div class="row">
                <div class="col-lg-10 col-md-12 mx-auto">
                    <div class="payment-container" data-aos="fade-up" data-aos-delay="100">
                        <div class="row g-0">
                            <div class="col-md-5">
                                <div class="booking-summary">
                                    <h2 class="booking-title"><i class="fas fa-ticket-alt"></i> Booking Summary</h2>
                                    
                                    <div class="movie-info">
                                        <div class="movie-poster">
                                            <img src="<?php echo $movie_info['movie_poster']; ?>" alt="<?php echo htmlspecialchars($movie_info['movie_name']); ?>">
                                        </div>
                                        <div class="movie-details">
                                            <h3 class="movie-name"><?php echo htmlspecialchars($movie_info['movie_name']); ?></h3>
                                            <div class="movie-meta">
                                                <div class="movie-meta-item">
                                                    <i class="fas fa-film"></i>
                                                    <span><?php echo htmlspecialchars($movie_info['theater_name']); ?></span>
                                                </div>
                                                <div class="movie-meta-item">
                                                    <i class="far fa-clock"></i>
                                                    <span><?php echo htmlspecialchars($movie_info['show_time']); ?></span>
                                                </div>
                                                <div class="movie-meta-item">
                                                    <i class="fas fa-couch"></i>
                                                    <span>
                                                        <?php echo count($selectedSeats); ?> Seat<?php echo count($selectedSeats) > 1 ? 's' : ''; ?>:
                                                        <?php echo implode(', ', $selectedSeats); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
        </div>

                                    <div class="summary-details">
                                        <div class="summary-row">
                                            <div class="summary-label">Price per Seat</div>
                                            <div class="summary-value">$<?php echo number_format($movie_info['price'], 2); ?></div>
                                        </div>
                                        <div class="summary-row">
                                            <div class="summary-label">Number of Seats</div>
                                            <div class="summary-value"><?php echo count($selectedSeats); ?></div>
                                        </div>
                                        <div class="summary-row">
                                            <div class="summary-label">Booking Fee</div>
                                            <div class="summary-value">$0.00</div>
                                        </div>
                                        <div class="summary-row summary-total">
                                            <div class="summary-label">Total Amount</div>
                                            <div class="summary-value">$<?php echo number_format($totalPrice, 2); ?></div>
                                        </div>
                                    </div>
                                </div>
            </div>

                            <div class="col-md-7">
                                <div class="payment-form">
                                    <h2 class="payment-title"><i class="fas fa-credit-card"></i> Payment Information</h2>
                                    
                                    <div class="payment-methods">
                                        <div class="payment-method active">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa">
                                        </div>
                                        <div class="payment-method">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard">
                                        </div>
                                        <div class="payment-method">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/3/30/American_Express_logo.svg" alt="American Express">
                                        </div>
                                        <div class="payment-method">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Discover_Card_logo.svg" alt="Discover">
                                        </div>
            </div>

                                    <form id="payment-form" action="process_payment.php" method="POST">
                                        <div class="form-group">
                                            <label for="card-name" class="form-label">Cardholder Name</label>
                                            <input type="text" class="form-control" id="card-name" name="card_name" placeholder="Name on card" required>
            </div>

                                        <div class="form-group">
                                            <label for="card-details" class="form-label">Card Number</label>
                                            <input type="text" class="form-control" id="card-details" name="card_details" placeholder="1234 5678 9012 3456" maxlength="19" required>
                </div>
                                        
                                        <div class="card-row">
                                            <div class="form-group expiry-col">
                                                <label for="expiry-date" class="form-label">Expiry Date</label>
                                                <input type="text" class="form-control" id="expiry-date" name="expiry_date" placeholder="MM/YY" maxlength="5" required>
                </div>
                                            
                                            <div class="form-group cvc-col">
                    <label for="cvc" class="form-label">CVC</label>
                                                <input type="text" class="form-control" id="cvc" name="cvc" placeholder="123" maxlength="3" required>
                                            </div>
                </div>

                <!-- Hidden fields to pass selected seats and price -->
                <input type="hidden" name="selected_seats" value="<?php echo implode(",", $selectedSeats); ?>">
                <input type="hidden" name="total_price" value="<?php echo $totalPrice; ?>">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <input type="hidden" name="screen_id" value="<?php echo $screen_id; ?>">

                                        <button type="submit" class="pay-btn">
                                            <i class="fas fa-lock"></i> Pay $<?php echo number_format($totalPrice, 2); ?>
                                        </button>
                                        
                                        <div class="secure-badge">
                                            <i class="fas fa-shield-alt"></i> Secure Payment
                                        </div>
            </form>
        </div>
    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require_once('clintefooter.php'); ?>

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
            
            // Format credit card number as it's typed
            $('#card-details').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                let formattedValue = '';
                
                for (let i = 0; i < value.length; i++) {
                    if (i > 0 && i % 4 === 0) {
                        formattedValue += ' ';
                    }
                    formattedValue += value[i];
                }
                
                $(this).val(formattedValue);
            });
            
            // Format expiry date as it's typed
            $('#expiry-date').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                let formattedValue = '';
                
                if (value.length > 2) {
                    formattedValue = value.substring(0, 2) + '/' + value.substring(2, 4);
                } else {
                    formattedValue = value;
                }
                
                $(this).val(formattedValue);
            });
            
            // Set payment method as active when clicked
            $('.payment-method').click(function() {
                $('.payment-method').removeClass('active');
                $(this).addClass('active');
            });
        });
    </script>
</body>

</html>
