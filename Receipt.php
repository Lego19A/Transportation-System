<?php
// Include session management and database connection
include 'session.php';
include 'db_connection.php';

// Check if user is logged in
requireLogin();

// Check if booking ID exists in session
if (!isset($_SESSION['booking_id'])) {
    header("Location: MyBookings.php");
    exit();
}

$booking_id = $_SESSION['booking_id'];

// Get booking details
$booking_query = "SELECT b.*, t.TravelDate, t.DepartureTime, t.Price, 
                 r.Duration, 
                 s1.StationName AS DepartureStation, 
                 s2.StationName AS ArrivalStation,
                 u.Username
                 FROM Bookings b
                 JOIN Trips t ON b.TripID = t.TripID
                 JOIN Routes r ON t.RouteID = r.RouteID
                 JOIN Stations s1 ON r.DepartureStationID = s1.StationID
                 JOIN Stations s2 ON r.ArrivalStationID = s2.StationID
                 JOIN Users u ON b.UserID = u.UserID
                 WHERE b.BookingID = '$booking_id'";
$booking_result = mysqli_query($conn, $booking_query);

if (mysqli_num_rows($booking_result) == 0) {
    header("Location: MyBookings.php");
    exit();
}

$booking = mysqli_fetch_assoc($booking_result);

// Get booked seats
$seats_query = "SELECT s.SeatNumber FROM Seats s
               JOIN BookingDetails bd ON s.SeatID = bd.SeatID
               WHERE bd.BookingID = '$booking_id'";
$seats_result = mysqli_query($conn, $seats_query);

$booked_seats = [];
while ($row = mysqli_fetch_assoc($seats_result)) {
    $booked_seats[] = $row['SeatNumber'];
}

// Generate booking reference (simple format for student project)
$booking_reference = "JB" . str_pad($booking_id, 6, "0", STR_PAD_LEFT);

// Clear booking ID from session after displaying receipt
unset($_SESSION['booking_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link rel="stylesheet" href="Style.css">
    
</head>
<body>
    <header>
        <nav>
            <img src="JomBus.png" width="80px" height="80px">
            <div class="nav-links">
                <a href="Home.php">Home</a>
                <a href="MyBookings.php">My Bookings</a>
                <a href="Bookings.php">Bookings</a>
            </div>
            <div class="nav-right">
                <a id="P1" href="Profile.php">
                    <img src="user.png" width="40px" height="40px" alt="Profile">
                </a>
            </div>
        </nav>
    </header>

    <main>
        <section class="receipt">
            <div class="receipt-header">
                <h1>Booking Confirmation</h1>
                <h2>JomBus</h2>
                <p>Booking Reference: <?php echo $booking_reference; ?></p>
                <p>Date: <?php echo date('Y-m-d H:i:s'); ?></p>
            </div>
            
            <div class="receipt-details">
                <h2>Passenger Details</h2>
                <p><strong>Name:</strong> <?php echo $booking['Username']; ?></p>
                
                <h2>Trip Details</h2>
                <p><strong>From:</strong> <?php echo $booking['DepartureStation']; ?></p>
                <p><strong>To:</strong> <?php echo $booking['ArrivalStation']; ?></p>
                <p><strong>Date:</strong> <?php echo $booking['TravelDate']; ?></p>
                <p><strong>Time:</strong> <?php echo $booking['DepartureTime']; ?></p>
                <p><strong>Duration:</strong> <?php echo $booking['Duration']; ?></p>
                <p><strong>Seats:</strong> <?php echo implode(', ', $booked_seats); ?></p>
                
                <h2>Payment Details</h2>
                <p><strong>Price per seat:</strong> RM<?php echo $booking['Price']; ?></p>
                <p><strong>Number of seats:</strong> <?php echo count($booked_seats); ?></p>
                <p><strong>Total Amount:</strong> RM<?php echo $booking['TotalAmount']; ?></p>
                <p><strong>Payment Status:</strong> Paid</p>
            </div>
            
            <div class="receipt-footer">
                <p>Thank you for choosing JomBus for your journey!</p>
                <p>For any inquiries, please contact our customer service.</p>
                <a href="MyBookings.php"><button>View My Bookings</button></a>
            </div>
        </section>
    </main>

    <footer>
         <h3 class="footer-style">Contact Us: <br> Anas Ahmed | 01164081225 <br> Elbaraa Taher | 01139941541 <br> Hassan Amer | 01111781601  <br> Wadia Suhaib | 0195570086 </h3>
  <p class="footer-style">&copy;2025 JomBus Travel Sdn Bhd. All rights reserved</p> 
    </footer>
</body>
</html>
