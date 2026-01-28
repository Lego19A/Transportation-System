<?php
// Include session management and database connection
include 'session.php';
include 'db_connection.php';

// Check if user is logged in
requireLogin();

$user_id = $_SESSION['user_id'];

// Get user's bookings
$bookings_query = "SELECT b.BookingID, b.BookingDate, b.TotalAmount, 
                  t.TravelDate, t.DepartureTime,
                  s1.StationName AS DepartureStation, 
                  s2.StationName AS ArrivalStation
                  FROM Bookings b
                  JOIN Trips t ON b.TripID = t.TripID
                  JOIN Routes r ON t.RouteID = r.RouteID
                  JOIN Stations s1 ON r.DepartureStationID = s1.StationID
                  JOIN Stations s2 ON r.ArrivalStationID = s2.StationID
                  WHERE b.UserID = '$user_id'
                  ORDER BY b.BookingDate DESC";
$bookings_result = mysqli_query($conn, $bookings_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
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
        <div class="bookings-container">
            <h1>My Bookings</h1>
            
            <?php if (mysqli_num_rows($bookings_result) > 0): ?>
                <?php while ($booking = mysqli_fetch_assoc($bookings_result)): ?>
                    <?php 
                    // Get seats for this booking
                    $booking_id = $booking['BookingID'];
                    $seats_query = "SELECT s.SeatNumber FROM Seats s
                                   JOIN BookingDetails bd ON s.SeatID = bd.SeatID
                                   WHERE bd.BookingID = '$booking_id'";
                    $seats_result = mysqli_query($conn, $seats_query);
                    
                    $booked_seats = [];
                    while ($row = mysqli_fetch_assoc($seats_result)) {
                        $booked_seats[] = $row['SeatNumber'];
                    }
                    
                    // Generate booking reference
                    $booking_reference = "JB" . str_pad($booking_id, 6, "0", STR_PAD_LEFT);
                    ?>
                    
                    <div class="booking-card">
                        <div class="booking-header">
                            <div>
                                <h3>Booking Reference: <?php echo $booking_reference; ?></h3>
                                <p>Booked on: <?php echo date('Y-m-d', strtotime($booking['BookingDate'])); ?></p>
                            </div>
                            <div>
                                <a href="booking_details.php?id=<?php echo $booking_id; ?>">
                                    <button class="view-details-btn">View Details</button>
                                </a>
                            </div>
                        </div>
                        
                        <div class="booking-details">
                            <div>
                                <p><strong>From:</strong> <?php echo $booking['DepartureStation']; ?></p>
                                <p><strong>To:</strong> <?php echo $booking['ArrivalStation']; ?></p>
                                <p><strong>Date:</strong> <?php echo $booking['TravelDate']; ?></p>
                                <p><strong>Time:</strong> <?php echo $booking['DepartureTime']; ?></p>
                            </div>
                            <div>
                                <p><strong>Seats:</strong> <?php echo implode(', ', $booked_seats); ?></p>
                                <p><strong>Total Amount:</strong> RM<?php echo $booking['TotalAmount']; ?></p>
                                <p><strong>Status:</strong> Confirmed</p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-bookings">
                    <h2>No bookings found</h2>
                    <p>You haven't made any bookings yet.</p>
                    <a href="Bookings.php"><button>Book a Trip</button></a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
         <h3 class="footer-style">Contact Us: <br> Anas Ahmed | 01164081225 <br> Elbaraa Taher | 01139941541 <br> Hassan Amer | 01111781601  <br> Wadia Suhaib | 0195570086 </h3>
  <p class="footer-style">&copy;2025 JomBus Travel Sdn Bhd. All rights reserved</p> 
    </footer>
</body>
</html>
