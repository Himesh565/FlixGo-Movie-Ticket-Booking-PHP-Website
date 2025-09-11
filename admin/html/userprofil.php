<?php
// Include the database connection
require_once("connection.php");
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("location:login.php");
}

$user_id = $_SESSION['user_id']; // Get the user_id from session

// Fetch user details from the database
$sql = "SELECT * FROM userinfo WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Use integer for user_id
$stmt->execute();
$user_result = $stmt->get_result();

// Check if the user was found
if ($user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();  // Fetch user data
} else {
    die("User not found.");
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .profile-container {
            max-width: 900px;
            margin: auto;
            padding: 40px;
        }

        .user-icon {
            font-size: 120px;
            color: #E50914;
            border-radius: 50%;
        }

        .user-info .mb-3 {
            font-size: 1.1rem;
        }

        .update-btn,
        .logout-btn {
            margin-top: 20px;
        }

        .modal-body {
            padding: 40px;
        }

        .modal-title {
            font-weight: bold;
        }

        .update-fields {
            display: none;
        }

        #password-error {
            color: red;
            display: none;
        }

        .container {
            max-width: 800px;
        }

        /* Adjust profile section for better display */
        .user-info-container {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <?php require_once("clinteheader.php"); ?>

    <div class="container profile-container bg-light rounded shadow-sm">
        <!-- Header: Profile Image -->
        <div class="text-center mb-4">
            <img src="uploads/usericon.jpg" alt="User Icon" class="user-icon" width="120" height="120">
            <h2>Your Profile</h2>
        </div>

        <!-- User Info Display Below the Image -->
        <div class="user-info-container">
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>First Name:</strong>
                </div>
                <div class="col-md-8">
                    <?php echo isset($user['firstName']) ? htmlspecialchars($user['firstName']) : 'N/A'; ?>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Last Name:</strong>
                </div>
                <div class="col-md-8">
                    <?php echo isset($user['lastName']) ? htmlspecialchars($user['lastName']) : 'N/A'; ?>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Email:</strong>
                </div>
                <div class="col-md-8">
                    <?php echo isset($user['email']) ? htmlspecialchars($user['email']) : 'N/A'; ?>
                </div>
            </div>
        </div>

        <!-- Update Button -->
        <div class="text-center">
            <button class="btn btn-primary update-btn" data-bs-toggle="modal" data-bs-target="#updateModal">Update Profile</button>
        </div>

        <!-- Logout Button -->
        <div class="text-center mt-3">
            <form action="logout.php" method="POST">
                <button type="submit" class="btn btn-danger logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <!-- Modal for Updating Profile -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Password Field to Verify User -->
                    <form id="updateForm" method="POST">
                        <div class="mb-3">
                            <label for="password" class="form-label">Enter Your Password</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>

                        <!-- Error Message for Incorrect Password -->
                        <div class="mb-3" id="password-error">
                            Password incorrect. Please try again.
                        </div>

                        <div class="mb-3 text-center">
                            <!-- Button to Check Password -->
                            <button type="button" class="btn btn-warning" id="check-password">Check Password</button>
                        </div>
                    </form>

                    <!-- Fields to Update User Profile, initially hidden -->
                    <div class="update-fields">
                        <form id="updateFieldsForm">
                            <div class="mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" id="first_name" name="first_name" class="form-control"
                                       value="<?php echo isset($user['firstName']) ? htmlspecialchars($user['firstName']) : ''; ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" id="last_name" name="last_name" class="form-control"
                                       value="<?php echo isset($user['lastName']) ? htmlspecialchars($user['lastName']) : ''; ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" id="new_password" name="new_password" class="form-control" disabled>
                            </div>

                            <!-- Button to Update Profile -->
                            <div class="text-center">
                                <button type="button" class="btn btn-success" id="update-profile" disabled>Update Profile</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // JS to handle password verification
        document.getElementById('check-password').addEventListener('click', function (e) {
            e.preventDefault();

            const password = document.getElementById('password').value;

            // Send password to verify it matches the stored password (plaintext)
            fetch('verify_password.php', {
                method: 'POST',
                body: JSON.stringify({ password: password }),
                headers: { 'Content-Type': 'application/json' }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.valid) {
                        // If password is correct, show the update fields
                        document.querySelector('.update-fields').style.display = 'block';
                        document.getElementById('password-error').style.display = 'none';  // Hide the error message

                        // Enable the fields for update
                        document.getElementById('first_name').disabled = false;
                        document.getElementById('last_name').disabled = false;
                        document.getElementById('new_password').disabled = false;

                        // Enable the update profile button
                        document.getElementById('update-profile').disabled = false;

                    } else {
                        // Show error message if password is incorrect
                        document.getElementById('password-error').style.display = 'block';
                    }
                })
                .catch(error => console.error("Error:", error));
        });

        // JS to handle profile update submission
        document.getElementById('update-profile').addEventListener('click', function (e) {
            e.preventDefault();

            const first_name = document.getElementById('first_name').value;
            const last_name = document.getElementById('last_name').value;
            const new_password = document.getElementById('new_password').value;

            const data = {
                first_name: first_name,
                last_name: last_name,
                new_password: new_password
            };

            fetch('update_profile.php', {
                method: 'POST',
                body: JSON.stringify(data),
                headers: { 'Content-Type': 'application/json' }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Profile updated successfully!');
                        window.location.reload();  // Reload the page to show updated details
                    } else {
                        alert('An error occurred while updating your profile.');
                    }
                })
                .catch(error => console.error("Error:", error));
        });
    </script>

    <?php include_once("clintefooter.php"); ?>
</body>

</html>
