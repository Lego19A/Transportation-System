<?php
// Include session management and database connection
include 'session.php';
include 'db_connection.php';

// Check if user is logged in
requireLogin();

// Check if booking information exists in session
if (!isset($_SESSION['booking']) || !isset($_SESSION['selected_trip'])) {
    header("Location: bookings.php");
    exit();
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $card_num = mysqli_real_escape_string($conn, $_POST['card_num']);
    $card_date = mysqli_real_escape_string($conn, $_POST['card_date']);
    $card_ccv = mysqli_real_escape_string($conn, $_POST['card_ccv']);
    
    // Get booking information from session
    $booking = $_SESSION['booking'];
    $trip = $_SESSION['selected_trip'];
    $user_id = $_SESSION['user_id'];
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Create booking record
        $insert_booking = "INSERT INTO Bookings (UserID, TripID, TotalAmount) 
                          VALUES ('$user_id', '{$booking['trip_id']}', '{$booking['total_price']}')";
        
        if (!mysqli_query($conn, $insert_booking)) {
            throw new Exception("Error creating booking: " . mysqli_error($conn));
        }
        
        $booking_id = mysqli_insert_id($conn);
        
        // Add booking details (seats)
        foreach ($booking['selected_seats'] as $seat_number) {
            // Get seat ID
            $seat_query = "SELECT SeatID FROM Seats WHERE SeatNumber = '$seat_number'";
            $seat_result = mysqli_query($conn, $seat_query);
            
            if (mysqli_num_rows($seat_result) == 0) {
                throw new Exception("Invalid seat selected: $seat_number");
            }
            
            $seat_id = mysqli_fetch_assoc($seat_result)['SeatID'];
            
            // Insert booking detail
            $insert_detail = "INSERT INTO BookingDetails (BookingID, SeatID) 
                             VALUES ('$booking_id', '$seat_id')";
            
            if (!mysqli_query($conn, $insert_detail)) {
                throw new Exception("Error adding seat to booking: " . mysqli_error($conn));
            }
            
            // Update seat status to booked
            $update_seat = "UPDATE Seats SET Status = 'booked' WHERE SeatID = '$seat_id'";
            
            if (!mysqli_query($conn, $update_seat)) {
                throw new Exception("Error updating seat status: " . mysqli_error($conn));
            }
        }
        
        // Create payment record
        $insert_payment = "INSERT INTO Payments (BookingID, CardNumber, CardExpiryDate, CardCCV) 
                          VALUES ('$booking_id', '$card_num', '$card_date', '$card_ccv')";
        
        if (!mysqli_query($conn, $insert_payment)) {
            throw new Exception("Error processing payment: " . mysqli_error($conn));
        }
        
        // Commit transaction
        mysqli_commit($conn);
        
        // Store booking ID in session for receipt
        $_SESSION['booking_id'] = $booking_id;
        
        // Clear booking data from session
        unset($_SESSION['booking']);
        
        // Redirect to receipt page
        header("Location: receipt.php");
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        
        $_SESSION['error'] = $e->getMessage();
        header("Location: checkout.php");
        exit();
    }
} else {
    // If accessed directly without form submission, redirect to bookings page
    header("Location: bookings.php");
    exit();
}
?>
