<?php
require_once("connection.php");
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if the user is not logged in
    header("Location: login.php");
    exit();
}

// Get the user's ID from the session
$userId = $_SESSION['user_id'];

// CSRF token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Fetch recent feedback (limited to 4 entries)
$feedback_sql = "SELECT f.feedback, f.rating, f.created_at, f.user_id, 
                u.firstname, u.lastname
                FROM feedback f 
                LEFT JOIN userinfo u ON f.user_id = u.id 
                WHERE f.rating BETWEEN 1 AND 5 
                ORDER BY f.created_at DESC LIMIT 4";
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
  <title>Feedback - BookItNow</title>
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
    
    .section-title {
        position: relative;
        display: inline-block;
        margin-bottom: 30px;
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

    .feedback-form-container {
        background-color: var(--card-bg);
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 5px 15px var(--shadow-color);
        margin-bottom: 40px;
        color: var(--text-color) !important;
    }
    
    .star-rating {
        font-size: 2rem;
        color: rgba(255, 255, 255, 0.2);
        cursor: pointer;
        margin-bottom: 15px;
    }

    .star-rating .checked {
        color: #ffc107;
    }
    
    .feedback-card {
        background-color: var(--card-bg);
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 5px 15px var(--shadow-color);
        height: 100%;
        position: relative;
        color: var(--text-color) !important;
        margin-bottom: 30px;
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
    
    .feedback-card .user-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 15px;
    }
    
    .feedback-card .user-name {
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--heading-color) !important;
    }
    
    .feedback-card .date {
        font-size: 0.85rem;
        color: var(--muted-text) !important;
    }
    
    .feedback-card .rating {
        margin-bottom: 15px;
        color: #ffc107;
    }
    
    .feedback-card .rating i {
        margin-right: 2px;
    }
    
    .feedback-card .feedback-text {
        font-style: italic;
        color: var(--secondary-text) !important;
    }
    
    .form-label {
        font-weight: 500;
        margin-bottom: 10px;
        color: var(--text-color) !important;
    }
    
    .form-control {
        background-color: var(--input-bg);
        border: 1px solid var(--input-border);
        color: var(--input-text);
        border-radius: 8px;
        padding: 12px 15px;
    }
    
    .form-control:focus {
        background-color: var(--input-bg);
        border-color: var(--primary-color);
        color: var(--input-text);
        box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.1);
    }
    
    .btn-submit {
        background-color: var(--primary-color);
        color: var(--btn-text) !important;
        border: none;
        border-radius: 8px;
        padding: 12px 25px;
        font-size: 1rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-submit:hover {
        background-color: var(--hover-color);
        color: var(--btn-text) !important;
    }
    
    .alert {
        border-radius: 8px;
        padding: 15px 20px;
        margin-bottom: 25px;
    }
    
    .alert-success {
        background-color: rgba(40, 167, 69, 0.1);
        border: 1px solid rgba(40, 167, 69, 0.2);
        color: #28a745;
    }
    
    .alert-danger {
        background-color: rgba(220, 53, 69, 0.1);
        border: 1px solid rgba(220, 53, 69, 0.2);
        color: #dc3545;
    }
  </style>
</head>

<body>
    <?php
    require_once('clinteheader.php');
    ?>

    <!-- Page Header -->
    <div class="page-header text-center">
        <div class="container">
            <h1>Share Your Experience</h1>
            <p>We value your feedback to improve our services and create a better movie experience for everyone</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="row">
            <!-- Feedback Form Section -->
            <div class="col-lg-6 mb-4">
                <h2 class="section-title">Submit Your Feedback</h2>
                
                <?php
                // Feedback message
                if (isset($_GET['success']) && $_GET['success'] == 'true') {
                    echo '<div class="alert alert-success">Thank you for your feedback! Your opinion helps us improve.</div>';
                } elseif (isset($_GET['success']) && $_GET['success'] == 'false') {
                    echo '<div class="alert alert-danger">Something went wrong. Please try again.</div>';
                }
                ?>

                <div class="feedback-form-container" data-aos="fade-up">
                    <form action="submit_feedback.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $userId; ?>">

                        <div class="mb-4">
                            <label for="rating" class="form-label">Your Rating</label>
                            <div class="star-rating">
                                <i class="fas fa-star" id="star1" data-value="1"></i>
                                <i class="fas fa-star" id="star2" data-value="2"></i>
                                <i class="fas fa-star" id="star3" data-value="3"></i>
                                <i class="fas fa-star" id="star4" data-value="4"></i>
                                <i class="fas fa-star" id="star5" data-value="5"></i>
                            </div>
                            <input type="hidden" id="rating" name="rating" required>
                        </div>

                        <div class="mb-4">
                            <label for="feedback" class="form-label">Your Comments</label>
                            <textarea class="form-control" id="feedback" name="feedback" rows="4" placeholder="Tell us about your experience..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-submit">
                            <i class="fas fa-paper-plane me-2"></i> Submit Feedback
                        </button>
                    </form>
                </div>
            </div>

            <!-- Recent Feedback Section -->
            <div class="col-lg-6">
                <h2 class="section-title">Recent Feedback</h2>
                
                <div class="row" data-aos="fade-up" data-aos-delay="100">
                    <?php
                    if ($feedback_result->num_rows > 0) {
                        while ($feedback = $feedback_result->fetch_assoc()) {
                            $date = new DateTime($feedback['created_at']);
                            $formatted_date = $date->format('M d, Y');
                            
                            // Get user name or default to Anonymous if not found
                            $userName = "Anonymous User";
                            if (!empty($feedback['firstname']) || !empty($feedback['lastname'])) {
                                $userName = trim($feedback['firstname'] . ' ' . $feedback['lastname']);
                                if (empty($userName)) {
                                    $userName = "Anonymous User";
                                }
                            }
                            ?>
                            <div class="col-md-6 mb-4">
                                <div class="feedback-card">
                                    <div class="user-info">
                                        <div class="user-name"><?php echo htmlspecialchars($userName); ?></div>
                                        <div class="date"><?php echo $formatted_date; ?></div>
                                    </div>
                                    <div class="rating">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $feedback['rating']) {
                                                echo '<i class="fas fa-star"></i>';
                                            } else {
                                                echo '<i class="far fa-star"></i>';
                                            }
                                        }
                                        ?>
                                    </div>
                                    <div class="feedback-text">
                                        "<?php echo htmlspecialchars($feedback['feedback']); ?>"
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<div class="col-12"><p>No feedback available yet. Be the first to share your experience!</p></div>';
                    }
                    ?>
                </div>
                


            </div>
        </div>
    </div>

    <?php
    require_once('clintefooter.php');
    ?>

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
            
            let stars = $('.star-rating .fas');
            let selectedRating = 0;

            // Handle star hover effect
            stars.on('mouseover', function() {
                let currentStar = parseInt($(this).data('value'));
                
                stars.each(function(index) {
                    if (index < currentStar) {
                        $(this).addClass('checked');
                    } else {
                        $(this).removeClass('checked');
                    }
                });
            });
            
            // Handle mouse leave event
            $('.star-rating').on('mouseleave', function() {
                stars.each(function(index) {
                    if (index < selectedRating) {
                        $(this).addClass('checked');
                    } else {
                        $(this).removeClass('checked');
                    }
                });
            });

            // Handle star click event
            stars.on('click', function() {
                selectedRating = $(this).data('value');
                $('#rating').val(selectedRating);

                // Highlight stars based on selection
                stars.each(function(index) {
                    if (index < selectedRating) {
                        $(this).addClass('checked');
                    } else {
                        $(this).removeClass('checked');
                    }
                });
            });
        });
    </script>
</body>

</html>
