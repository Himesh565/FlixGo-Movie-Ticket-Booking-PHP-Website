<?php
// No need to start session here as it's already done in the main file
require_once("functions.php"); // Include functions.php here

// Assuming 'loggedIn' is set to true when the user logs in, you can set this in your login logic.
$isLoggedIn = isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;

// Get the user's name if logged in
if ($isLoggedIn) {
    $user_name = getUserNameById($_SESSION['user_id']); // Assuming user_id is stored in session
} else {
    $user_name = ""; // Empty if not logged in
}

// Get the current page file name to highlight active nav link
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookItNow</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style>
        :root {
            --primary-color: #E50914;
            --secondary-color: #14181c;
            --text-color: #fff;
            --hover-color: #ff3333;
            --card-bg: #1f2327;
            --body-bg: #0a0e14;
            --icon-color: #ffffff;
            --border-color: rgba(255, 255, 255, 0.1);
            --dropdown-bg: #1f2327;
            --dropdown-text: #ffffff;
            --dropdown-hover: #2a2e36;
            --shadow-color: rgba(0, 0, 0, 0.2);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--body-bg);
            color: var(--text-color);
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: var(--secondary-color);
            box-shadow: 0 2px 10px var(--shadow-color);
            padding: 15px 0;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color) !important;
            letter-spacing: 1px;
        }

        .nav-link {
            color: var(--text-color) !important;
            font-weight: 500;
            margin: 0 10px;
            position: relative;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary-color) !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--primary-color);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after, .nav-link.active::after {
            width: 100%;
        }

        .user-profile {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .user-profile .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--primary-color);
            color: #fff;
            font-size: 20px;
            margin-right: 10px;
        }

        .user-profile .user-name {
            font-weight: 500;
            font-size: 0.9rem;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: var(--text-color);
        }

        .user-dropdown {
            min-width: 200px;
            background-color: var(--dropdown-bg);
            border-color: var(--border-color);
        }
        
        .dropdown-item {
            color: var(--dropdown-text);
        }
        
        .dropdown-item:hover {
            background-color: var(--dropdown-hover);
        }
        
        .dropdown-divider {
            border-color: var(--border-color);
        }

        @media (max-width: 992px) {
            .navbar-collapse {
                background-color: var(--secondary-color);
                padding: 20px;
                border-radius: 8px;
                margin-top: 10px;
            }
            
            .nav-link {
                margin: 10px 0;
            }
            
            .user-profile {
                margin-top: 15px;
            }
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white !important;
        }

        .btn-primary:hover {
            background-color: var(--hover-color);
            border-color: var(--hover-color);
            color: white !important;
        }

        .btn-outline-primary {
            color: var(--primary-color) !important;
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white !important;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">BookItNow</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">
                            <i class="fas fa-film me-1"></i> Theaters
                        </a>
                    </li>
                    <?php if ($isLoggedIn): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'my_booking.php') ? 'active' : ''; ?>" href="my_booking.php">
                                <i class="fas fa-ticket-alt me-1"></i> My Bookings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'feedback.php') ? 'active' : ''; ?>" href="feedback.php">
                                <i class="fas fa-comment me-1"></i> Feedback
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page == 'aboutus.php') ? 'active' : ''; ?>" href="aboutus.php">
                                <i class="fas fa-info-circle me-1"></i> About Us
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
                
                <div class="d-flex align-items-center">
                    <?php if ($isLoggedIn): ?>
                        <div class="dropdown ms-3">
                            <div class="user-profile" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="user-name"><?php echo htmlspecialchars($user_name); ?></div>
                                <i class="fas fa-chevron-down ms-2"></i>
                            </div>
                            <ul class="dropdown-menu user-dropdown">
                                <li><a class="dropdown-item" href="userprofil.php"><i class="fas fa-user-circle me-2"></i> My Profile</a></li>
                                <li><a class="dropdown-item" href="my_booking.php"><i class="fas fa-ticket-alt me-2"></i> My Bookings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-primary me-2">Login</a>
                        <a href="signup.php" class="btn btn-primary">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
