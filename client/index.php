<?php
require_once("connection.php");

// FIX: Change 'theater' to 'theaters' on this line
$sql = "SELECT id, name, poster FROM theaters WHERE is_active = 1"; 
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theaters</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Theater Section -->
<div class="container mt-5">
    <h2 class="text-center mb-4">Available Theaters</h2>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        <?php
        if ($result->num_rows > 0) {
            // Loop through each theater and display it
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="col">
                    <div class="card h-100">
                        <!-- Anchor tag to wrap the image and link to the movies page -->
                        <a href="movies.php?theater_id=<?php echo $row['id']; ?>">
                            <img src="/movies_book/<?php echo $row['poster']; ?>" class="card-img-top" alt="Theater Poster" style="height: 400px; object-fit: cover;">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title text-center"><?php echo htmlspecialchars($row['name']); ?></h5>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No theaters available.</p>";
        }
        ?>
    </div>
</div>

<!-- Bootstrap JS (optional but recommended for responsiveness) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
$conn->close();
?>
