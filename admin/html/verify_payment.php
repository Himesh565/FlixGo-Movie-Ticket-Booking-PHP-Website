<?php
session_start();
require_once("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $screen_id = $_SESSION['screen_id'];
    $transaction_id = $_POST['transaction_id'];
    $totalAmount = $_POST['amount'];
    $selectedSeats = $_POST['seats'];

    if (empty($transaction_id)) {
        echo "Transaction ID is required!";
        exit();
    }

    // Store payment in database
    $sql = "INSERT INTO payments (user_id, screen_id, seat_numbers, total_amount, transaction_id) 
            VALUES ('$user_id', '$screen_id', '$selectedSeats', '$totalAmount', '$transaction_id')";
    $conn->query($sql);

    unset($_SESSION['selected_seats'], $_SESSION['screen_id']);
    echo "Payment Successful! Your Booking is Confirmed.";
}
?>
