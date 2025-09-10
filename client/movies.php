<?php
require_once("connection.php");

$theater_id = $_GET['theater_id'];

// Fetch movies for the selected theater
$sql = "SELECT * FROM movies WHERE theater_id = $theater_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS for Hover Effects -->
    <style>
        .card {
            position: relative;
            overflow: hidden;
        }

        .card-img-top {
            transition: 0.5s ease;
        }

        /* Blur the image on hover */
        .card:hover .card-img-top {
            filter: blur(4px);
        }

        /* Show the "Book" button on hover */
        .card:hover .book-btn {
            opacity: 1;
        }

        /* Style for the "Book" button */
        .book-btn {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            opacity: 0;
            transition: 0.5s ease;
            font-size: 18px;
            cursor: pointer;
        }

        /* Prevent the text from selecting on hover */
        .book-btn:focus {
            outline: none;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Movies Playing at the Theater</h2>
    <div class="row">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="col-md-4">
                    <div class="card">
                        <!-- Use a relative URL for the image -->
                        <img src="/movies_book/<?php echo $row['poster']; ?>" class="card-img-top" alt="Movie Poster">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                        </div>
                        <!-- "Book" button that appears on hover -->
                        <button class="book-btn">Book Now</button>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No movies available for this theater.</p>";
        }
        ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
$conn->close();
?>
