<?php
require_once("connection.php");
session_start(); // Start the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - BookItNow</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        body {
            color: #ffffff !important;
        }
        
        .page-header {
            background-color: var(--card-bg);
            padding: 60px 0;
            margin-bottom: 60px;
            position: relative;
            overflow: hidden;
            color: #ffffff !important;
        }
        
        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color) 0%, rgba(0,0,0,0) 70%);
            opacity: 0.15;
            z-index: 1;
        }
        
        .page-header h1 {
            position: relative;
            z-index: 2;
            font-weight: 700;
            margin-bottom: 15px;
            color: #ffffff !important;
            font-size: 2.5rem;
        }
        
        .page-header p {
            position: relative;
            z-index: 2;
            color: #e6e6e6 !important;
            max-width: 700px;
            margin: 0 auto;
            font-size: 1.1rem;
        }
        
        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: 30px;
            font-weight: 700;
            color: #ffffff !important;
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
        
        .about-section {
            padding: 40px 0;
            position: relative;
            color: #ffffff !important;
        }
        
        .about-card {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 15px var(--shadow-color);
            height: 100%;
            position: relative;
            color: #ffffff !important;
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .about-card h3 {
            font-weight: 600;
            margin-bottom: 20px;
            color: #ffffff !important;
            position: relative;
        }
        
        .about-card p, .about-card ul, .about-card li {
            color: #e6e6e6 !important;
            font-size: 1rem;
            line-height: 1.6;
        }
        
        .feature-card {
            padding: 25px;
            border-radius: 12px;
            background-color: var(--card-bg);
            margin-bottom: 30px;
            box-shadow: 0 5px 15px var(--shadow-color);
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
            color: #ffffff !important;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background-color: var(--primary-color);
        }
        
        .feature-card .icon {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: var(--primary-color);
        }
        
        .feature-card h3 {
            font-size: 1.3rem;
            margin-bottom: 15px;
            font-weight: 600;
            color: #ffffff !important;
        }
        
        .feature-card p {
            color: #e6e6e6 !important;
            margin-bottom: 0;
        }
        
        .team-member {
            text-align: center;
            margin-bottom: 40px;
            color: #ffffff !important;
            background-color: var(--card-bg);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px var(--shadow-color);
        }
        
        .team-member .member-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(229, 9, 20, 0.1);
            border: 3px solid #E50914;
            font-size: 3.5rem;
            color: #E50914;
        }
        
        .team-member .member-img i {
            line-height: 1;
        }
        
        .team-member h4 {
            font-weight: 600;
            margin-bottom: 8px;
            color: #ffffff !important;
            font-size: 1.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .team-member p {
            color: #E50914 !important;
            margin-bottom: 15px;
            font-weight: 500;
            font-size: 1.1rem;
        }
        
        .team-member .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(229, 9, 20, 0.1);
            border-radius: 50%;
            color: #ffffff !important;
            margin: 0 5px;
            transition: all 0.3s ease;
            font-size: 1.2rem;
        }
        
        .team-member .social-links a:hover {
            background-color: #E50914;
            color: #ffffff !important;
            transform: translateY(-3px);
        }
        
        .stats-container {
            padding: 40px 30px;
            background-color: var(--card-bg);
            border-radius: 12px;
            margin-bottom: 60px;
            box-shadow: 0 5px 15px var(--shadow-color);
            color: #ffffff !important;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-item .number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .stat-item .label {
            font-size: 1.1rem;
            color: #e6e6e6 !important;
        }
        
        .highlight {
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .divider {
            width: 100%;
            height: 1px;
            background: linear-gradient(to right, rgba(229, 9, 20, 0.1), var(--primary-color), rgba(229, 9, 20, 0.1));
            margin: 60px 0;
        }
        
        /* Ensure text is visible in the list items */
        ul li {
            color: #e6e6e6 !important;
        }
        
        .text-center {
            color: #ffffff !important;
        }
    </style>
</head>
<body>
    <?php require_once('clinteheader.php'); ?>

    <!-- Page Header -->
    <div class="page-header text-center">
        <div class="container">
            <h1>About BookItNow</h1>
            <p>Your premier destination for booking movie tickets with ease and comfort</p>
        </div>
    </div>

    <div class="container mb-5">
        <!-- Our Story Section -->
        <div class="about-section" data-aos="fade-up">
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <h2 class="section-title">Our Story</h2>
                    <div class="about-card">
                        <h3>How It All Started</h3>
                        <p>BookItNow was founded in 2020 with a simple mission: to make movie ticket booking seamless and enjoyable. What began as a small project by a group of movie enthusiasts has grown into a platform serving thousands of cinephiles every day.</p>
                        <p>Our journey started when we noticed how complicated it was to book movie tickets online. Long queues, confusing interfaces, and limited options inspired us to create a solution that puts the user experience first.</p>
                        <p>Today, BookItNow partners with hundreds of theaters nationwide to provide instant access to the latest blockbusters, indie gems, and timeless classics.</p>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <h2 class="section-title">Our Mission</h2>
                    <div class="about-card">
                        <h3>What Drives Us</h3>
                        <p>At BookItNow, we believe that everyone deserves a hassle-free movie experience. Our mission is to connect movie lovers with their perfect cinema experience through technology that's both powerful and simple to use.</p>
                        <p>We're committed to:</p>
                        <ul>
                            <li>Making booking movie tickets as simple as possible</li>
                            <li>Providing the widest selection of movies and showtimes</li>
                            <li>Offering exclusive deals and discounts for our loyal users</li>
                            <li>Continuously improving our platform based on user feedback</li>
                            <li>Supporting the film industry and local theaters</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="about-section" data-aos="fade-up" data-aos-delay="100">
            <div class="stats-container">
                <div class="row">
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <div class="number">500+</div>
                            <div class="label">Theaters</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <div class="number">10K+</div>
                            <div class="label">Daily Users</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <div class="number">1M+</div>
                            <div class="label">Tickets Sold</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <div class="number">4.8</div>
                            <div class="label">User Rating</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Features Section -->
        <div class="about-section" data-aos="fade-up">
            <h2 class="section-title text-center mb-5">Why Choose BookItNow</h2>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h3>Lightning Fast Booking</h3>
                        <p>Book tickets in under 30 seconds with our streamlined process. No more waiting in long queues.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="icon">
                            <i class="fas fa-tags"></i>
                        </div>
                        <h3>Exclusive Deals</h3>
                        <p>Get access to special discounts, combo offers, and early bird pricing available only to our members.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="icon">
                            <i class="fas fa-couch"></i>
                        </div>
                        <h3>Seat Selection</h3>
                        <p>Choose your perfect seat with our interactive seating chart. Preview your view before booking.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h3>Movie Reminders</h3>
                        <p>Set reminders for upcoming releases and never miss a movie you've been waiting for.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="icon">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <h3>Digital Tickets</h3>
                        <p>Go paperless with our digital tickets. Just show your mobile device at the theater entrance.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h3>Ratings & Reviews</h3>
                        <p>Make informed decisions with our community-driven ratings and reviews for every movie.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Team Section -->
        <div class="about-section" data-aos="fade-up">
            <h2 class="section-title text-center mb-5">Meet Our Team</h2>
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6">
                    <div class="team-member">
                        <div class="member-img">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <h4>Parth Rathod</h4>
                        <p>Developer</p>
                        <div class="social-links">
                            <a href="mailto:parthmax360@gmail.com"><i class="fas fa-envelope"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="team-member">
                        <div class="member-img">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h4>Yash Javiya</h4>
                        <p>Developer</p>
                        <div class="social-links">
                            <a href="mailto:yashjaviya3@gmail.com"><i class="fas fa-envelope"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="team-member">
                        <div class="member-img">
                            <i class="fas fa-user-ninja"></i>
                        </div>
                        <h4>Sahil Patel</h4>
                        <p>Developer</p>
                        <div class="social-links">
                            <a href="mailto:sahiljp01@gmail.com"><i class="fas fa-envelope"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
        });
    </script>
</body>
</html>
