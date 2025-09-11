<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookItNow</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #E50914;
            --secondary-color: #14181c;
            --text-color: #fff;
            --hover-color: #ff3333;
        }
        
        footer {
            background-color: var(--secondary-color);
            color: var(--text-color);
            padding: 60px 0 30px;
            margin-top: 0;
        }
        
        .footer-brand {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 20px;
            display: block;
        }
        
        .footer-text {
            margin-bottom: 25px;
            font-size: 0.95rem;
            opacity: 0.8;
        }
        
        .footer-heading {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-heading::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 30px;
            height: 2px;
            background-color: var(--primary-color);
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-links li {
            margin-bottom: 12px;
        }
        
        .footer-links a {
            color: var(--text-color);
            opacity: 0.8;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .footer-links a:hover {
            opacity: 1;
            color: var(--primary-color);
        }
        
        .footer-links a i {
            margin-right: 10px;
            font-size: 14px;
        }
        
        .footer-contact-info {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .footer-contact-info i {
            width: 35px;
            height: 35px;
            background-color: rgba(229, 9, 20, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--primary-color);
        }
        
        .social-icons {
            margin-top: 20px;
        }
        
        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--text-color);
            margin-right: 10px;
            transition: all 0.3s ease;
        }
        
        .social-icons a:hover {
            background-color: var(--primary-color);
            transform: translateY(-3px);
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 30px;
            margin-top: 40px;
        }
        
        .footer-bottom p {
            margin-bottom: 0;
            opacity: 0.7;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .footer-column {
                margin-bottom: 30px;
            }
        }
    </style>
</head>
<body>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 footer-column">
                    <a href="index.php" class="footer-brand">BookItNow</a>
                    <p class="footer-text">
                        Your premier platform for booking movie tickets online. Experience the best theaters and latest films with just a few clicks.
                    </p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 footer-column">
                    <h4 class="footer-heading">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="index.php"><i class="fas fa-chevron-right"></i> Theaters</a></li>
                        <li><a href="aboutus.php"><i class="fas fa-chevron-right"></i> About Us</a></li>
                        <li><a href="feedback.php"><i class="fas fa-chevron-right"></i> Feedback</a></li>
                        <li><a href="login.php"><i class="fas fa-chevron-right"></i> Login</a></li>
                        <li><a href="signup.php"><i class="fas fa-chevron-right"></i> Sign Up</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6 footer-column">
                    <h4 class="footer-heading">Contact Info</h4>
                    <div class="footer-contact-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>123 Movie Street, Cinema City</span>
                    </div>
                    <div class="footer-contact-info">
                        <i class="fas fa-phone-alt"></i>
                        <a href="tel:+919081219005" style="color: var(--text-color);">+91 9081219005</a>
                    </div>
                    <div class="footer-contact-info">
                        <i class="fas fa-envelope"></i>
                        <span>info@bookitnow.com</span>
                    </div>
                    <div class="footer-contact-info">
                        <i class="fab fa-whatsapp"></i>
                        <a href="https://api.whatsapp.com/send?phone=919081219005&text=Hello,%20I%20would%20like%20to%20inquire%20about%20your%20services." target="_blank" style="color: var(--text-color);">
                            WhatsApp Support
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 footer-column">
                    <h4 class="footer-heading">Download Our App</h4>
                    <p class="footer-text">Get the BookItNow mobile app for a seamless booking experience on the go.</p>
                    <div class="app-download">
                        <a href="#" class="me-2 mb-2 d-inline-block">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/78/Google_Play_Store_badge_EN.svg/2560px-Google_Play_Store_badge_EN.svg.png" alt="Google Play" width="120">
                        </a>
                        <a href="#" class="mb-2 d-inline-block">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/3c/Download_on_the_App_Store_Badge.svg/1200px-Download_on_the_App_Store_Badge.svg.png" alt="App Store" width="120">
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="row footer-bottom">
                <div class="col-md-12 text-center">
                    <p>&copy; 2025 BookItNow. All Rights Reserved by BookItNow Team.</p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
