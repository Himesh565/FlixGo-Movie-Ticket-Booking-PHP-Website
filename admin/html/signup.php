<?php
$errors = array('email' => '', 'password' => '', 'firstName' => '', 'lastName' => '');

if (isset($_POST['submit'])) {
    if (empty($_POST['first_name'])) {
        $errors['firstName'] = "Enter your First Name.";
    }
    if (empty($_POST['last_name'])) {
        $errors['lastName'] = "Enter your Last Name.";
    }
    if (empty($_POST['email'])) {
        $errors['email'] = "Enter your Email.";
    }
    if (empty($_POST['pwd'])) {
        $errors['password'] = "Enter your password.";
    }

    // Only run if there are no errors
    if (!array_filter($errors)) {
        $firstnameSign = $_POST['first_name'];
        $lastnameSign = $_POST['last_name'];
        $emailSign = $_POST['email'];
        $passwordSign = $_POST['pwd'];

        // Include the connection file
        include('connection.php');

        // Use prepared statements to prevent SQL Injection
        $stmt = $conn->prepare("INSERT INTO userinfo (firstName, lastName, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstnameSign, $lastnameSign, $emailSign, $passwordSign);

        if ($stmt->execute()) {
            // Redirect to login page after successful signup
            echo "<script type='text/javascript'>
                    alert('Signup successfully!');
                    window.location.href = 'login.php'; // Redirect to login page after alert
                  </script>";
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up - BookItNow</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #E50914;
            --secondary-color: #14181c;
            --text-color: #fff;
            --dark-text: #333;
            --hover-color: #ff3333;
            --border-radius: 12px;
            --box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--body-bg, #0a0e14);
            color: var(--text-color);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .signup-section {
            padding: 80px 0;
            position: relative;
        }

        .signup-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('images/signupbg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            opacity: 0.3;
            z-index: -1;
        }

        .signup-container {
            max-width: 900px;
            margin: 0 auto;
            box-shadow: var(--box-shadow);
            border-radius: var(--border-radius);
            overflow: hidden;
            background-color: rgba(20, 24, 28, 0.8);
            backdrop-filter: blur(10px);
        }

        .signup-content {
            display: flex;
            flex-wrap: wrap;
        }

        .signup-left {
            flex: 1;
            padding: 50px;
            background-color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 300px;
        }

        .signup-right {
            flex: 1;
            padding: 50px;
            background: linear-gradient(135deg, var(--primary-color) 0%, #9b0009 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-width: 300px;
        }

        .signup-right h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-align: center;
            color: white;
        }

        .signup-right p {
            font-size: 1rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 30px;
        }

        .benefit-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            color: white;
        }

        .benefit-icon {
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .benefit-text {
            font-size: 0.9rem;
        }

        .signup-left h3 {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 30px;
        }

        .signup-form .form-group {
            margin-bottom: 25px;
        }

        .signup-form label {
            color: var(--dark-text);
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }

        .signup-form .form-control {
            height: 50px;
            padding-left: 20px;
            border-radius: 8px;
            border: 1px solid #e1e1e1;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }

        .signup-form .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.1);
        }

        .signup-btn {
            height: 50px;
            background-color: var(--primary-color);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 10px;
        }

        .signup-btn:hover {
            background-color: var(--hover-color);
            transform: translateY(-3px);
        }

        .error-message {
            color: var(--primary-color);
            font-size: 0.85rem;
            margin-top: 5px;
        }

        .have-account {
            text-align: center;
            margin-top: 20px;
            color: var(--dark-text);
        }

        .have-account a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .signup-left, .signup-right {
                flex: 100%;
            }
            
            .signup-left {
                order: 2;
                padding: 40px 20px;
            }
            
            .signup-right {
                order: 1;
                padding: 40px 20px;
            }
        }
    </style>
</head>

<body>
    <?php include('clinteheader.php'); ?>

    <section class="signup-section">
        <div class="container">
            <div class="signup-container" data-aos="fade-up">
                <div class="signup-content">
                    <div class="signup-left">
                        <h3>Create an Account</h3>
                        
                        <form class="signup-form" action="signup.php" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name">First Name</label>
                                        <input type="text" class="form-control" id="first_name" placeholder="Enter your first name" name="first_name">
                                        <?php if (!empty($errors['firstName'])): ?>
                                            <div class="error-message"><?php echo $errors['firstName']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" placeholder="Enter your last name" name="last_name">
                                        <?php if (!empty($errors['lastName'])): ?>
                                            <div class="error-message"><?php echo $errors['lastName']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control" id="email" placeholder="Enter your email" name="email">
                                <?php if (!empty($errors['email'])): ?>
                                    <div class="error-message"><?php echo $errors['email']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="pwd">Password</label>
                                <input type="password" class="form-control" id="pwd" placeholder="Create a password" name="pwd">
                                <?php if (!empty($errors['password'])): ?>
                                    <div class="error-message"><?php echo $errors['password']; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <button type="submit" name="submit" class="signup-btn">
                                <i class="fas fa-user-plus me-2"></i> Create Account
                            </button>
                            
                            <div class="have-account">
                                Already have an account? <a href="login.php">Log In</a>
                            </div>
                        </form>
                    </div>
                    
                    <div class="signup-right">
                        <h2>Join BookItNow</h2>
                        <p>Sign up today and enjoy these benefits:</p>
                        
                        <div class="w-100">
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="fas fa-ticket-alt"></i>
                                </div>
                                <div class="benefit-text">Book tickets for any movie, anytime</div>
                            </div>
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="fas fa-gift"></i>
                                </div>
                                <div class="benefit-text">Exclusive offers and discounts</div>
                            </div>
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="fas fa-bell"></i>
                                </div>
                                <div class="benefit-text">Notifications for new releases</div>
                            </div>
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="fas fa-history"></i>
                                </div>
                                <div class="benefit-text">View and manage your booking history</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include('clintefooter.php'); ?>
    
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
