<?php
session_start(); // Start the session
require_once("connection.php");

$theater_id = intval($_GET['theater_id']); // Sanitize theater_id to prevent SQL injection

// Fetch the theater name
$theater_sql = "SELECT name FROM theater WHERE id = $theater_id";
$theater_result = $conn->query($theater_sql);
$theater_name = "Unknown Theater"; // Default in case no theater is found

if ($theater_result->num_rows > 0) {
    $theater_row = $theater_result->fetch_assoc();
    $theater_name = htmlspecialchars($theater_row['name']); // Sanitize output
}

// Fetch movies for the selected theater including trailer links
$sql = "SELECT * FROM movies WHERE theater_id = $theater_id AND is_active = 1";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies at <?php echo $theater_name; ?> - BookItNow</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .page-header {
            background-color: var(--card-bg);
            padding: 30px 0;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
            color: var(--text-color) !important;
        }
        
        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color) 0%, rgba(0,0,0,0) 50%);
            opacity: 0.1;
            z-index: 1;
        }
        
        .page-header h1 {
            position: relative;
            z-index: 2;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--heading-color) !important;
        }
        
        .page-header p {
            position: relative;
            z-index: 2;
            color: var(--secondary-text) !important;
            max-width: 700px;
            margin: 0 auto;
        }
        
        .movie-card {
            background-color: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px var(--shadow-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            position: relative;
            margin-bottom: 20px;
            color: var(--text-color) !important;
        }
        
        .movie-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        
        .movie-poster {
            height: 400px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .movie-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease, filter 0.5s ease;
        }
        
        .movie-card:hover .movie-poster img {
            transform: scale(1.05);
            filter: brightness(70%);
        }
        
        .movie-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 60%);
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .movie-card:hover .movie-overlay {
            opacity: 1;
        }

        .overlay-buttons {
            display: flex;
            gap: 15px;
        }
        
        .movie-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 15px;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            text-decoration: none;
            margin-top: 5px;
        }

        .book-btn {
            background-color: var(--primary-color);
            color: var(--btn-text) !important;
            border: none;
        }
        
        .book-btn:hover {
            background-color: var(--hover-color);
            color: var(--btn-text) !important;
        }

        .trailer-btn {
            background-color: rgba(255,255,255,0.2);
            color: var(--btn-text) !important;
            border: 1px solid rgba(255,255,255,0.4);
        }
        
        .trailer-btn:hover {
            background-color: rgba(255,255,255,0.3);
            color: var(--btn-text) !important;
        }
        
        .movie-content {
            padding: 15px;
            width: 100%;
        }
        
        .movie-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--heading-color) !important;
            text-align: center;
        }
        
        .movie-info {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .movie-badge {
            display: inline-block;
            padding: 3px 8px;
            background-color: rgba(229, 9, 20, 0.1);
            color: var(--primary-color);
            border-radius: 20px;
            font-size: 0.8rem;
            margin-right: 5px;
        }
        
        .movie-description {
            margin-bottom: 15px;
            font-size: 0.9rem;
            color: var(--secondary-text) !important;
            max-height: 60px;
            overflow: hidden;
        }
        
        .movie-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .read-more {
            color: var(--primary-color) !important;
            font-weight: 500;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: underline;
        }
        
        .movie-actions .movie-btn {
            margin-top: 0;
        }
        
        /* Modal Styling */
        .modal-content {
            background-color: var(--card-bg);
            color: var(--text-color) !important;
            border-radius: 15px;
            overflow: hidden;
        }
        
        .modal-header {
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .modal-footer {
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        
        .modal-title {
            font-weight: 600;
            color: var(--heading-color) !important;
        }
        
        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        
        .modal-body {
            padding: 20px;
        }
    </style>
</head>

<body>
    <?php
    require_once("clinteheader.php"); // Include header
    ?>

    <!-- Page Header -->
    <div class="page-header text-center">
        <div class="container">
            <h1>Movies at <?php echo $theater_name; ?></h1>
            <p>Browse and book tickets for the latest movies showing at <?php echo $theater_name; ?></p>
        </div>
    </div>

    <!-- Movies Section -->
    <div class="container mb-5">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
            <?php
            if ($result->num_rows > 0) {
                $delay = 0;
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="col mb-4" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                        <div class="movie-card">
                            <div class="movie-poster">
                                <img src="<?php echo $row['poster']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                                <div class="movie-overlay">
                                    <div class="overlay-buttons">
                                        <a href="screens.php?movie_id=<?php echo $row['id']; ?>" class="movie-btn book-btn">
                                            <i class="fas fa-ticket-alt me-2"></i> Book Tickets
                                        </a>
                                <?php if (!empty($row['trailer_link'])): ?>
                                            <a href="<?php echo htmlspecialchars($row['trailer_link']); ?>" target="_blank" class="movie-btn trailer-btn">
                                                <i class="fas fa-play me-2"></i> Watch Trailer
                                            </a>
                                <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="movie-content">
                                <h3 class="movie-title"><?php echo htmlspecialchars($row['name']); ?></h3>
                                <div class="movie-info">
                                    <?php 
                                    // Get movie duration from database, if available
                                    if (isset($row['duration']) && !empty($row['duration'])) {
                                        echo '<span class="movie-badge"><i class="far fa-clock me-1"></i> ' . htmlspecialchars($row['duration']) . '</span>';
                                    } else {
                                        echo '<span class="movie-badge"><i class="far fa-clock me-1"></i> 2h 30m</span>';
                                    }
                                    
                                    // Get movie language from database, if available
                                    if (isset($row['language']) && !empty($row['language'])) {
                                        echo '<span class="movie-badge">' . htmlspecialchars($row['language']) . '</span>';
                                    }
                                    ?>
                                </div>
                                <div class="movie-description">
                                    <?php echo htmlspecialchars($row['description']); ?>
                                </div>
                                <div class="movie-actions">
                                    <span class="read-more" data-bs-toggle="modal" data-bs-target="#movieModal<?php echo $row['id']; ?>">
                                        Read More
                                    </span>
                                    <a href="screens.php?movie_id=<?php echo $row['id']; ?>" class="movie-btn book-btn">
                                        Book Now
                                    </a>
                                </div>
                            </div>
                            </div>

                        <!-- Movie Details Modal -->
                        <div class="modal fade" id="movieModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="movieModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="movieModalLabel<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <img src="<?php echo $row['poster']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="img-fluid rounded">
                                            </div>
                                            <div class="col-md-8">
                                                <div class="movie-info mb-3">
                                                    <?php 
                                                    // Get movie duration from database, if available
                                                    if (isset($row['duration']) && !empty($row['duration'])) {
                                                        echo '<span class="movie-badge"><i class="far fa-clock me-1"></i> ' . htmlspecialchars($row['duration']) . '</span>';
                                                    } else {
                                                        echo '<span class="movie-badge"><i class="far fa-clock me-1"></i> 2h 30m</span>';
                                                    }
                                                    
                                                    // Get movie language from database, if available
                                                    if (isset($row['language']) && !empty($row['language'])) {
                                                        echo '<span class="movie-badge">' . htmlspecialchars($row['language']) . '</span>';
                                                    }
                                                    ?>
                                                </div>
                                                <p><?php echo htmlspecialchars($row['description']); ?></p>
                                                <div class="mt-4">
                                                    <a href="screens.php?movie_id=<?php echo $row['id']; ?>" class="movie-btn book-btn">
                                                        <i class="fas fa-ticket-alt me-2"></i> Book Tickets
                                                    </a>
                                                    <?php if (!empty($row['trailer_link'])): ?>
                                                        <a href="<?php echo htmlspecialchars($row['trailer_link']); ?>" target="_blank" class="movie-btn trailer-btn ms-2">
                                                            <i class="fas fa-play me-2"></i> Watch Trailer
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $delay += 100; // Increment delay for staggered animation
                }
            } else {
                echo '<div class="col-12 text-center"><p>No movies available for this theater at the moment. Please check back later.</p></div>';
            }
            ?>
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
            
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>

</html>

<?php
$conn->close();
?>
