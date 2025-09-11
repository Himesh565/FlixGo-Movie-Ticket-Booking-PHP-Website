<?php
require_once("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the screen_id from the POST request
    $screen_id = $_POST['screen_id'];

    // Check if the connection was successful
    if (!$conn) {
        echo "Connection failed!";
        exit;
    }

    // Query to get the current status of the screen
    $query = "SELECT is_active FROM screens WHERE id = '$screen_id'";
    $result = mysqli_query($conn, $query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $current_status = $row['is_active'];

        // Toggle status based on current status
        $new_status = ($current_status == 1) ? 0 : 1;

        // Update the status in the database
        $update_query = "UPDATE screens SET is_active = '$new_status' WHERE id = '$screen_id'";

        if (mysqli_query($conn, $update_query)) {
            // Redirect back to the previous page (or another page after updating)
            header("Location:screentable.php"); // Replace 'your_page.php' with the correct page
            exit;
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>
