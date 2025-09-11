<?php
session_start(); // Start the session
require_once("connection.php");

// Check if movie_id is provided
if (!isset($_GET['movie_id']) || empty($_GET['movie_id'])) {
    header("Location: index.php");
    exit();
}

$movie_id = intval($_GET['movie_id']); // Sanitize input

// Get movie details
$movie_sql = "SELECT m.*, t.name AS theater_name 
             FROM movies m 
             JOIN theater t ON m.theater_id = t.id
             WHERE m.id = $movie_id";
$movie_result = $conn->query($movie_sql);

if ($movie_result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$movie = $movie_result->fetch_assoc();

// Fetch available screens for the selected movie
$sql = "SELECT * FROM screens WHERE movie_id = $movie_id AND is_active = 1 ORDER BY show_time";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Screens - <?php echo htmlspecialchars($movie['name']); ?> - BookItNow</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="theme.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #E50914;
            --hover-color: #ff3333;
            --card-bg: #1f2327;
            --body-bg: #0a0e14;
            --text-color: #ffffff;
            --secondary-text: rgba(255, 255, 255, 0.85);
            --muted-text: rgba(255, 255, 255, 0.7);
            --heading-color: #ffffff;
            --highlight-color: #ff6b6b;
            --icon-color: #ffffff;
            --card-border: rgba(255, 255, 255, 0.05);
            --shadow-color: rgba(0, 0, 0, 0.15);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--body-bg);
            color: var(--text-color);
            font-size: 16px;
            line-height: 1.6;
        }
        
        .screens-section {
            padding: 80px 0;
        }
        
        .page-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-align: center;
            color: var(--heading-color);
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .page-subtitle {
            text-align: center;
            color: var(--secondary-text);
            margin-bottom: 40px;
            font-weight: 500;
            font-size: 1.1rem;
        }
        
        .page-subtitle strong {
            color: var(--highlight-color);
            font-weight: 600;
        }
        
        .movie-info {
            margin-bottom: 50px;
        }
        
        .movie-card {
            background-color: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px var(--shadow-color);
            display: flex;
            margin-bottom: 30px;
            position: relative;
        }
        
        .movie-poster {
            flex: 0 0 220px;
            background-color: #000;
            height: 320px;
            position: relative;
            overflow: hidden;
        }
        
        .movie-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .movie-details {
            flex: 1;
            padding: 25px;
            position: relative;
            color: var(--text-color) !important;
        }
        
        .movie-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--heading-color) !important;
            text-shadow: 0 1px 3px rgba(0,0,0,0.2);
            letter-spacing: 0.5px;
        }
        
        .movie-meta {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            margin-right: 25px;
            margin-bottom: 10px;
        }
        
        .meta-item i {
            width: 18px;
            margin-right: 8px;
            color: var(--highlight-color);
            font-size: 1rem;
        }
        
        .meta-label {
            color: var(--muted-text) !important;
            margin-right: 5px;
            font-size: 0.95rem;
            font-weight: 500;
        }
        
        .meta-value {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text-color) !important;
        }
        
        .screen-card {
            background-color: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px var(--shadow-color);
            margin-bottom: 30px;
            height: 100%;
            position: relative;
            border: 1px solid var(--card-border);
        }
        
        .screen-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
            border-color: var(--primary-color);
        }
        
        .screen-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, rgba(231, 76, 60, 0.7) 100%);
            padding: 15px;
            text-align: center;
            font-weight: 700;
            font-size: 1.2rem;
            position: relative;
            overflow: hidden;
            color: white !important;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        
        .screen-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: rgba(0,0,0,0.2);
        }
        
        .screen-body {
            padding: 25px;
            text-align: center;
            display: flex;
            flex-direction: column;
            height: calc(100% - 52px);
            color: var(--text-color) !important;
        }
        
        .screen-time {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--text-color) !important;
            position: relative;
            display: inline-block;
            padding-bottom: 12px;
            text-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
        
        .screen-time::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 3px;
            background-color: var(--primary-color);
            border-radius: 3px;
        }
        
        .screen-price {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 25px;
            color: var(--highlight-color) !important;
        }
        
        .screen-info {
            margin-bottom: 25px;
            color: var(--secondary-text) !important;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        
        .screen-info i {
            color: var(--highlight-color);
        }
        
        .btn-book {
            background-color: var(--primary-color);
            color: white !important;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            width: 100%;
            justify-content: center;
            font-size: 1rem;
            letter-spacing: 0.5px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        
        .btn-book:hover {
            background-color: var(--hover-color);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
            color: white !important;
        }
        
        .btn-book i {
            margin-right: 8px;
        }
        
        .seats-left {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            font-size: 0.85rem;
            padding: 5px 12px;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .empty-screens {
            text-align: center;
            padding: 60px 0;
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }
        
        .empty-icon {
            font-size: 4rem;
            color: var(--muted-text);
            margin-bottom: 20px;
        }
        
        .empty-text {
            font-size: 1.4rem;
            margin-bottom: 20px;
            color: var(--heading-color) !important;
            font-weight: 600;
        }
        
        .btn-back {
            background-color: #3498db;
            color: white !important;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            letter-spacing: 0.5px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        
        .btn-back:hover {
            background-color: #2980b9;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
            color: white !important;
        }
        
        .btn-back i {
            margin-right: 8px;
        }
        
        @media (max-width: 767px) {
            .movie-card {
                flex-direction: column;
            }
            
            .movie-poster {
                width: 100%;
                flex: auto;
                height: 350px;
                max-width: none;
            }
            
            .screen-card {
                height: auto;
            }
            
            .screen-time {
                font-size: 1.6rem;
            }
            
            .screen-price {
                font-size: 1.1rem;
            }
        }
        
        @media (max-width: 576px) {
            .movie-poster {
                height: 280px;
            }
        }
        
        .movie-description {
            margin-bottom: 25px;
            line-height: 1.7;
            color: var(--secondary-text) !important;
            font-size: 1rem;
        }
        
        .btn-trailer {
            display: inline-flex;
            align-items: center;
            background-color: #3498db;
            color: white !important;
            padding: 10px 18px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
            letter-spacing: 0.5px;
        }
        
        .btn-trailer:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            color: white !important;
        }
        
        .btn-trailer i {
            margin-right: 8px;
            font-size: 1.1rem;
        }
        
        .section-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--heading-color);
            margin-bottom: 35px;
            position: relative;
            text-shadow: 0 1px 3px rgba(0,0,0,0.2);
            letter-spacing: 0.5px;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background-color: var(--primary-color);
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <?php require_once("clinteheader.php"); ?>

    <section class="screens-section">
        <div class="container">
            <h1 class="page-title" data-aos="fade-up">Available Screens</h1>
            <p class="page-subtitle" data-aos="fade-up" data-aos-delay="100">
                Select a showtime for <strong><?php echo htmlspecialchars($movie['name']); ?></strong>
            </p>
            
            <!-- Movie Information Card -->
            <div class="movie-info" data-aos="fade-up" data-aos-delay="200">
                <div class="movie-card">
                    <div class="movie-poster">
                        <img src="<?php echo $movie['poster']; ?>" alt="<?php echo htmlspecialchars($movie['name']); ?>">
                    </div>
                    <div class="movie-details">
                        <h2 class="movie-title"><?php echo htmlspecialchars($movie['name']); ?></h2>
                        
                        <div class="movie-meta">
                            <div class="meta-item">
                                <i class="fas fa-film"></i>
                                <span class="meta-label">Theater:</span>
                                <span class="meta-value"><?php echo htmlspecialchars($movie['theater_name']); ?></span>
                            </div>
                            
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                <span class="meta-label">Duration:</span>
                                <span class="meta-value"><?php echo isset($movie['duration']) ? htmlspecialchars($movie['duration']) : '2h 30m'; ?></span>
                            </div>
                            
                            <div class="meta-item">
                                <i class="fas fa-star"></i>
                                <span class="meta-label">Rating:</span>
                                <span class="meta-value"><?php echo isset($movie['rating']) ? htmlspecialchars($movie['rating']) : '8.5/10'; ?></span>
                            </div>
                            
                            <?php if(isset($movie['genre']) && !empty($movie['genre'])): ?>
                            <div class="meta-item">
                                <i class="fas fa-tags"></i>
                                <span class="meta-label">Genre:</span>
                                <span class="meta-value"><?php echo htmlspecialchars($movie['genre']); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if(isset($movie['release_date']) && !empty($movie['release_date'])): ?>
                            <div class="meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span class="meta-label">Release:</span>
                                <span class="meta-value"><?php echo htmlspecialchars($movie['release_date']); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="movie-description">
                            <?php echo isset($movie['description']) ? nl2br(htmlspecialchars($movie['description'])) : 'No description available.'; ?>
                        </div>
                        
                        <?php if(isset($movie['trailer_url']) && !empty($movie['trailer_url'])): ?>
                        <a href="<?php echo htmlspecialchars($movie['trailer_url']); ?>" target="_blank" class="btn-trailer">
                            <i class="fas fa-play-circle"></i> Watch Trailer
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Available Screens -->
            <?php if ($result->num_rows > 0): ?>
                <h3 class="section-title text-center mb-4" data-aos="fade-up" data-aos-delay="250">Select Showtime</h3>
                <div class="row">
                    <?php 
                    $delay = 300;
                    while ($row = $result->fetch_assoc()): 
                        $delay += 100;
                        
                        // Format the time to be more readable
                        $formattedTime = date('g:i A', strtotime($row['show_time']));
                    ?>
                        <div class="col-md-4 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                            <div class="screen-card">
                                <div class="screen-header">
                                    <span>Screen <?php echo htmlspecialchars($row['screen_number']); ?></span>
                                </div>
                                <div class="screen-body">
                                    <div class="screen-time">
                                        <?php echo htmlspecialchars($formattedTime); ?>
                                    </div>
                                    <div class="screen-price">
                                        $<?php echo number_format($row['price'], 2); ?>
                                    </div>
                                    <div class="screen-info">
                                        <p><i class="fas fa-info-circle me-2"></i> Select this showtime to choose your seats</p>
                                    </div>
                                    <div class="mt-auto">
                                        <a href="seat_selection.php?screen_id=<?php echo $row['id']; ?>" class="btn btn-book">
                                            <i class="fas fa-ticket-alt"></i> Select Seats
                                        </a>
                                    </div>
                                    <?php if (isset($row['seats_available'])): ?>
                                    <div class="seats-left">
                                        <i class="fas fa-couch me-1"></i> <?php echo $row['seats_available']; ?> seats left
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-screens" data-aos="fade-up" data-aos-delay="300">
                    <div class="empty-icon">
                        <i class="fas fa-film"></i>
                    </div>
                    <h3 class="empty-text">No screens available for this movie</h3>
                    <a href="javascript:history.back()" class="btn btn-back">
                        <i class="fas fa-arrow-left"></i> Go Back
                    </a>
                </div>
            <?php endif; ?>
            
            <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="400">
                <a href="javascript:history.back()" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Back to Movies
                </a>
            </div>
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
    </script>
</body>
</html>

<?php
$conn->close();
?>
