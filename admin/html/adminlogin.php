<?php
session_start();
include_once 'connection.php'; // Include your database connection file

// Initialize error message variable
$error_msg = "";

// Check if the login form was submitted
if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // SQL query to fetch admin details based on email
    $sql = "SELECT * FROM admins WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Check if the password matches
        if ($row['password'] === $password) {
            // Login successful, set session variables
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_email'] = $row['email'];

            // Redirect to the dashboard or any other page
            header("Location: adminindex.php");
            exit();
        } else {
            $error_msg = "Incorrect password.";
        }
    } else {
        $error_msg = "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        body {
            /* Animated gradient background */
            background: linear-gradient(to right, #4a90e2, #9013fe, #e94e77, #f9d423);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0; /* Remove default margin */
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            transition: transform 0.3s ease; /* Animation for scaling effect */
        }
        .login-form {
            padding: 30px;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.9); /* White with transparency */
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease; /* Animation for scaling effect */
        }
        .form-control {
            background-color: #f0f8ff; /* Light background color for input fields */
            border: 1px solid #007bff; /* Border color */
        }
        .form-control:focus {
            border-color: #0056b3; /* Darker border on focus */
            box-shadow: 0 0 5px rgba(0, 91, 179, 0.5); /* Blue shadow effect */
        }
        .form-control:hover {
            transform: scale(1.05); /* Scale on hover */
        }
        .submit-btn {
            background-color: #007bff; /* Button color */
            color: white;
            border: none;
            transition: background-color 0.3s, transform 0.3s; /* Transition for background and scale */
        }
        .submit-btn:hover {
            background-color: #0056b3; /* Darker blue on hover */
            transform: scale(1.1); /* Scale on hover */
        }

        /* Hover effect for the login container */
        .login-container:hover {
            transform: translateY(-10px); /* Move the container up */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h3 class="text-center mb-4">Admin Login</h3>
        <div class="login-form">
            <!-- Display error message if login failed -->
            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_msg; ?>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" onsubmit="return validateForm()">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn submit-btn" name="submit">Login</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Client-side validation for better UX
        function validateForm() {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();

            if (!email) {
                alert("Email is required.");
                return false;
            }

            const emailPattern = /^[^@]+@[^@]+\.[a-zA-Z]{2,}$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }

            if (!password) {
                alert("Password is required.");
                return false;
            }

            return true;
        }
    </script>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
