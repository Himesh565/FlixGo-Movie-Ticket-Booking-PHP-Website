<?php
$errors = array('email' => '', 'password' => '', 'loginFlag' => '');
if (isset($_POST['submit'])) {
  if (empty($_POST['email'])) {
    $errors['email'] = "Enter your email address.";
  } elseif (empty($_POST['pwd'])) {
    $errors['password'] = "Enter your password.";
  } else {
    include('loginCheck.php');

    if ($loginFlag == "false") {
      $errors['loginFlag'] = "Invalid email or password. Please try again.";
    }
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Login - BookItNow</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    :root {
      --primary-color: #E50914;
      --secondary-color: #14181c;
      --text-color: #fff;
      --dark-text: #333;
      --light-gray: #f5f5f5;
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

    .login-section {
      padding: 80px 0;
      position: relative;
    }

    .login-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url('images/loginbg.jpg');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      opacity: 0.3;
      z-index: -1;
    }

    .login-container {
      max-width: 900px;
      margin: 0 auto;
      box-shadow: var(--box-shadow);
      border-radius: var(--border-radius);
      overflow: hidden;
      background-color: rgba(20, 24, 28, 0.8);
      backdrop-filter: blur(10px);
    }

    .login-content {
      display: flex;
      flex-wrap: wrap;
    }

    .login-left {
      flex: 1;
      padding: 50px;
      background: linear-gradient(135deg, var(--primary-color) 0%, #9b0009 100%);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      min-width: 300px;
    }

    .login-left h2 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 15px;
      text-align: center;
      color: white;
    }

    .login-left p {
      font-size: 1rem;
      text-align: center;
      color: rgba(255, 255, 255, 0.9);
      margin-bottom: 30px;
    }

    .login-features {
      width: 100%;
      margin-top: 20px;
    }

    .feature-item {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
      color: white;
    }

    .feature-icon {
      width: 40px;
      height: 40px;
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
    }

    .feature-text {
      font-size: 0.9rem;
    }

    .login-right {
      flex: 1;
      padding: 50px;
      background-color: white;
      display: flex;
      flex-direction: column;
      justify-content: center;
      min-width: 300px;
    }

    .login-right h3 {
      font-size: 1.8rem;
      font-weight: 600;
      color: var(--dark-text);
      margin-bottom: 30px;
    }

    .login-form .form-group {
      margin-bottom: 25px;
    }

    .login-form label {
      color: var(--dark-text);
      font-weight: 500;
      margin-bottom: 8px;
      display: block;
    }

    .login-form .form-control {
      height: 50px;
      padding-left: 20px;
      border-radius: 8px;
      border: 1px solid #e1e1e1;
      background-color: #f8f9fa;
      transition: all 0.3s ease;
    }

    .login-form .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.1);
    }

    .login-btn {
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

    .login-btn:hover {
      background-color: var(--hover-color);
    }

    .login-bottom {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 20px;
    }

    .create-account {
      color: var(--dark-text);
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    .create-account:hover {
      color: var(--primary-color);
    }

    .login-divider {
      display: flex;
      align-items: center;
      text-align: center;
      margin: 30px 0;
      color: #ccc;
    }

    .login-divider::before,
    .login-divider::after {
      content: '';
      flex: 1;
      border-bottom: 1px solid #e1e1e1;
    }

    .login-divider::before {
      margin-right: 15px;
    }

    .login-divider::after {
      margin-left: 15px;
    }

    .social-login {
      display: flex;
      justify-content: center;
      gap: 15px;
    }

    .social-btn {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.3rem;
      transition: all 0.3s ease;
      border: none;
    }

    .facebook-btn {
      background-color: #3b5998;
    }

    .google-btn {
      background-color: #db4437;
    }

    .twitter-btn {
      background-color: #1da1f2;
    }

    .social-btn:hover {
      transform: translateY(-3px);
      opacity: 0.9;
    }

    .error-message {
      color: var(--primary-color);
      font-size: 0.85rem;
      margin-top: 5px;
    }

    @media (max-width: 768px) {
      .login-left, .login-right {
        flex: 100%;
      }
      
      .login-left {
        padding: 40px 20px;
      }
      
      .login-right {
        padding: 40px 20px;
      }
    }
  </style>
</head>

<?php include('clinteheader.php') ?>

<body>
  <section class="login-section">
    <div class="container">
      <div class="login-container">
        <div class="login-content">
          <div class="login-left">
            <h2>Welcome Back</h2>
            <p>Log in to your account to book tickets and enjoy the latest movies</p>
            
            <div class="login-features">
              <div class="feature-item">
                <div class="feature-icon">
                  <i class="fas fa-ticket-alt"></i>
                </div>
                <div class="feature-text">Book tickets for the latest movies</div>
              </div>
              <div class="feature-item">
                <div class="feature-icon">
                  <i class="fas fa-history"></i>
                </div>
                <div class="feature-text">View your booking history</div>
              </div>
              <div class="feature-item">
                <div class="feature-icon">
                  <i class="fas fa-star"></i>
                </div>
                <div class="feature-text">Rate movies and share feedback</div>
              </div>
            </div>
          </div>
          
          <div class="login-right">
            <h3>Login to Your Account</h3>
            
            <?php if (!empty($errors['loginFlag'])): ?>
              <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> <?php echo $errors['loginFlag']; ?>
              </div>
            <?php endif; ?>
            
            <form class="login-form" action="login.php" method="POST">
              <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" class="form-control" id="email" placeholder="Enter your email" name="email">
                <?php if (!empty($errors['email'])): ?>
                  <div class="error-message"><?php echo $errors['email']; ?></div>
                <?php endif; ?>
              </div>
              
              <div class="form-group">
                <label for="pwd">Password</label>
                <input type="password" class="form-control" id="pwd" placeholder="Enter your password" name="pwd">
                <?php if (!empty($errors['password'])): ?>
                  <div class="error-message"><?php echo $errors['password']; ?></div>
                <?php endif; ?>
              </div>
              
              <div class="login-bottom">
                <a href="#" class="create-account">Forgot Password?</a>
              </div>
              
              <button type="submit" name="submit" class="login-btn">
                <i class="fas fa-sign-in-alt me-2"></i> Login
              </button>
            </form>
            
            <div class="login-divider">or continue with</div>
            
            <div class="social-login">
              <button class="social-btn facebook-btn" type="button">
                <i class="fab fa-facebook-f"></i>
              </button>
              <button class="social-btn google-btn" type="button">
                <i class="fab fa-google"></i>
              </button>
              <button class="social-btn twitter-btn" type="button">
                <i class="fab fa-twitter"></i>
              </button>
            </div>
            
            <p class="text-center mt-4">
              Don't have an account? <a href="signup.php" class="create-account" style="color: var(--primary-color);">Sign Up</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Include Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

<!-- Footer -->
<?php include('clintefooter.php') ?>

</html>
