<?php
// Include session management and database connection
include 'session.php';
include 'db_connection.php';

// Check if user is logged in
requireLogin();

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $trip_id = mysqli_real_escape_string($conn, $_POST['trip_id']);
    
    // Check if seats were selected
    if (!isset($_POST['seats']) || empty($_POST['seats'])) {
        $_SESSION['error'] = "Please select at least one seat.";
        header("Location: Seat.php?trip_id=$trip_id");
        exit();
    }
    
    $selected_seats = $_POST['seats'];
    
    // Get trip details
    $trip_query = "SELECT * FROM Trips WHERE TripID = '$trip_id'";
    $trip_result = mysqli_query($conn, $trip_query);
    
    if (mysqli_num_rows($trip_result) == 0) {
        $_SESSION['error'] = "Invalid trip selected.";
        header("Location: bookings.php");
        exit();
    }
    
    $trip = mysqli_fetch_assoc($trip_result);
    $price_per_seat = $trip['Price'];
    $total_price = count($selected_seats) * $price_per_seat;
    
    // Store booking information in session for checkout
    $_SESSION['booking'] = [
        'trip_id' => $trip_id,
        'selected_seats' => $selected_seats,
        'price_per_seat' => $price_per_seat,
        'total_price' => $total_price
    ];
    
    // Redirect to checkout
    header("Location: checkout.php");
    exit();
} else {
    // If accessed directly without form submission, redirect to bookings page
    header("Location: bookings.php");
    exit();
}
?>
