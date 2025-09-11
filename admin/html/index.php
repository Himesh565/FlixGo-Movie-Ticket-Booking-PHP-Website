<?php  
session_start(); // Start the session
require_once("connection.php");
require_once("functions.php"); // Include functions.php here

// Fetch active theaters from the database
$sql = "SELECT id, name, poster FROM theater WHERE is_active = 1";
$result = $conn->query($sql);

// Fetch the latest 6 feedback entries with ratings between 1 and 5
$feedback_sql = "SELECT feedback, user_id, rating FROM feedback WHERE rating BETWEEN 1 AND 5 ORDER BY created_at DESC LIMIT 6"; // Limit to 6 entries
$feedback_result = $conn->query($feedback_sql);

// Check for errors in the feedback query
if (!$feedback_result) {
    die("Error fetching feedback: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookItNow - Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        /* These styles will supplement those in the header */
        .hero-carousel .carousel-item {
            height: 70vh;
            max-height: 600px;
        }
        
        .hero-carousel .carousel-item img {
            object-fit: cover;
            object-position: center;
            height: 100%;
            width: 100%;
            filter: brightness(0.7);
        }
        
        .hero-carousel .carousel-caption {
            bottom: 20%;
            z-index: 2;
            text-align: center;
        }
        
        .hero-carousel .carousel-caption h2 {
            font-size: 3rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
            margin-bottom: 20px;
        }
        
        .hero-carousel .carousel-caption p {
            font-size: 1.25rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.8);
            max-width: 700px;
            margin: 0 auto;
        }
        
        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: 40px;
            font-weight: 700;
            color: var(--heading-color) !important;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -10px;
            width: 50px;
            height: 3px;
            background-color: var(--primary-color);
        }
        
        .theater-card {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px var(--shadow-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: var(--card-bg);
            height: 100%;
            cursor: pointer;
            color: var(--text-color) !important;
        }
        
        .theater-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        
        .theater-card .card-img-top {
            height: 250px;
            object-fit: cover;
        }
        
        .theater-card .card-body {
            padding: 20px;
        }
        
        .theater-card .card-title {
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .view-btn {
            background-color: var(--primary-color);
            color: var(--btn-text) !important;
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 500;
        }
        
        .view-btn:hover {
            background-color: var(--hover-color);
            color: var(--btn-text) !important;
        }
        
        .feedback-card {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px var(--shadow-color);
            height: 100%;
            position: relative;
            color: var(--text-color) !important;
        }
        
        .feedback-card::before {
            content: """;
            position: absolute;
            top: 15px;
            left: 20px;
            font-size: 60px;
            color: rgba(229, 9, 20, 0.1);
            font-family: serif;
            line-height: 1;
        }
        
        .feedback-card .user-name {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }
        
        .feedback-card .rating {
            margin-bottom: 15px;
        }
        
        .feedback-card .feedback-text {
            font-style: italic;
            color: var(--secondary-text) !important;
        }
        
        .fa-star.checked {
            color: #ffc107;
        }
        
        /* Custom animation for AOS */
        [data-aos="zoom-in-up"] {
            transform: translate3d(0, 30px, 0) scale(0.95);
            opacity: 0;
            transition-property: transform, opacity;
        }
        
        [data-aos="zoom-in-up"].aos-animate {
            transform: translate3d(0, 0, 0) scale(1);
            opacity: 1;
        }
        
        .text-muted {
            color: var(--secondary-text) !important;
        }
    </style>
</head>
<body>
    <?php
    require_once("clinteheader.php"); // Include header
    ?>

    <!-- Hero Carousel -->
    <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="uploads/aaa.jpg" class="d-block w-100" alt="Movie Experience">
                <div class="carousel-caption">
                    <h2>Experience Movies Like Never Before</h2>
                    <p>Book tickets for the best theaters and the latest movies all in one place</p>
                    <a href="#theaters" class="btn view-btn mt-3">Explore Theaters</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="uploads/slider2.jpg" class="d-block w-100" alt="Premium Theaters">
                <div class="carousel-caption">
                    <h2>Premium Theaters, Ultimate Comfort</h2>
                    <p>Enjoy movies in state-of-the-art theaters with comfortable seating</p>
                    <a href="#theaters" class="btn view-btn mt-3">Browse Now</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="uploads/slider1.jpg" class="d-block w-100" alt="Latest Movies">
                <div class="carousel-caption">
                    <h2>Latest Movies, One Click Away</h2>
                    <p>Book tickets for the hottest new releases and blockbusters</p>
                    <a href="#theaters" class="btn view-btn mt-3">Book Now</a>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Theater Section -->
    <div class="container py-5" id="theaters">
        <div class="text-center mb-5">
            <h2 class="section-title">Available Theaters</h2>
            <p class="text-muted">Find the best theaters and experience movies in comfort</p>
        </div>
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                $delay = 0;
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="zoom-in-up" data-aos-delay="<?php echo $delay; ?>">
                        <a href="redirect.php?theater_id=<?php echo $row['id']; ?>" style="text-decoration: none; color: inherit;">
                            <div class="theater-card">
                                <div class="position-relative overflow-hidden">
                                    <img src="<?php echo $row['poster']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['name']); ?>">
                                    <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                        <h5 class="text-white"><?php echo htmlspecialchars($row['name']); ?></h5>
                                    </div>
                                </div>
                                <div class="card-body text-center">
                                    <button class="btn view-btn w-100">
                                        <i class="fas fa-film me-2"></i> View Movies
                                    </button>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
                    $delay += 100; // Increment delay for staggered animation
                }
            } else {
                echo '<div class="col-12 text-center"><p>No theaters available at the moment. Please check back later.</p></div>';
            }
            ?>
        </div>
    </div>

    <!-- Feedback Section -->
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="section-title">What Our Users Say</h2>
            <p class="text-muted">Read reviews from movie enthusiasts like you</p>
        </div>
        <div class="row">
            <?php
            if ($feedback_result->num_rows > 0) {
                $delay = 0;
                while ($feedback = $feedback_result->fetch_assoc()) {
                    // Get the user's name from the user ID
                    $user_name = getUserNameById($feedback['user_id']);
                    ?>
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                        <div class="feedback-card">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="user-name m-0"><?php echo htmlspecialchars($user_name); ?></h5>
                                <div class="rating">
                                    <?php
                                    // Get the rating from the feedback data
                                    $rating = $feedback['rating'];

                                    // Loop to display filled stars
                                    for ($i = 1; $i <= $rating; $i++) {
                                        echo '<i class="fa fa-star checked"></i>'; // Filled star
                                    }

                                    // Loop to display remaining empty stars (if less than 5)
                                    for ($i = $rating + 1; $i <= 5; $i++) {
                                        echo '<i class="fa fa-star" style="color: #ddd;"></i>'; // Empty star
                                    }
                                    ?>
                                </div>
                            </div>
                            <p class="feedback-text"><?php echo htmlspecialchars($feedback['feedback']); ?></p>
                        </div>
                    </div>
                    <?php
                    $delay += 100;
                }
            } else {
                echo '<div class="col-12 text-center"><p>No feedback available yet. Be the first to share your experience!</p></div>';
            }
            ?>
        </div>
    </div>

    <!-- Why Choose Us Section -->
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="section-title">Why Choose BookItNow</h2>
            <p class="text-muted">The best platform for movie ticket booking</p>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4" data-aos="fade-up">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-ticket-alt fa-3x" style="color: var(--primary-color);"></i>
                    </div>
                    <h4>Easy Booking</h4>
                    <p class="text-muted">Book your tickets in seconds with our simple, intuitive interface.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-film fa-3x" style="color: var(--primary-color);"></i>
                    </div>
                    <h4>Latest Movies</h4>
                    <p class="text-muted">Get access to the latest blockbusters and exclusive premieres.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-couch fa-3x" style="color: var(--primary-color);"></i>
                    </div>
                    <h4>Best Seats</h4>
                    <p class="text-muted">Choose your preferred seats from our interactive seating chart.</p>
                </div>
            </div>
        </div>
    </div>

    <?php
    include_once("clintefooter.php"); // Include footer
    ?>

    <!-- Bootstrap & AOS JS -->
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
    </script>
</body>
</html>

<?php
$conn->close();
?>
