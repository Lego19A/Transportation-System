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

$booking = $_SESSION['booking'];
$trip = $_SESSION['selected_trip'];
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Get user details
$user_query = "SELECT * FROM Users WHERE UserID = '$user_id'";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body>
    <header>
        <nav>
            <img src="JomBus.png" width="80px" height="80px">
            <div class="nav-links">
                <a href="home.php">Home</a>
                <a href="myBookings.php">My Bookings</a>
                <a href="bookings.php">Bookings</a>
            </div>
            <div class="nav-right">
                <a id="P1" href="profile.php">
                    <img src="user.png" width="40px" height="40px" alt="Profile">
                </a>
            </div>
        </nav>
    </header>

    <main>
        <section>
            <h1>Checkout</h1>
            <h2>Ticket Details</h2>
            <h3>Name: <?php echo $username; ?></h3>
            <h3>Departure: <?php echo $trip['departure']; ?></h3>
            <h3>Destination: <?php echo $trip['destination']; ?></h3>
            <h3>Time: <?php echo $trip['departure_time']; ?></h3>
            <h3>Date: <?php echo $trip['travel_date']; ?></h3>
            <h3>Selected Seats: <?php echo implode(', ', $booking['selected_seats']); ?></h3>
            <h3>Amount: RM<?php echo $booking['total_price']; ?></h3>
        </section>
        <br>
        <section>
            <h2>Payment</h2>
            <form method="post" action="process_payment.php">
                <h3>Card Number</h3>
                <input type="number" id="card_num" name="card_num" placeholder="Card Number" required maxlength="16" 
           minlength="16" >
                <br>
                <h3>Card Expiry Date</h3>
                <input type="date" id="card_date" name="card_date" required>
                <h3>Card Security Number (CCV)</h3>
                <input type="number" id="card_ccv" name="card_ccv" placeholder="CCV" required maxlength="3">
                <button type="submit">Pay</button>
            </form>
        </section>
    </main>
    
    <footer>
       <h3 class="footer-style">Contact Us: <br> Anas Ahmed | 01164081225 <br> Elbaraa Taher | 01139941541 <br> Abdelrahman Amr | 0175351418  <br> Hasinou Said | 01139864462 </h3>
  <p class="footer-style">&copy;2025 JomBus Travel Sdn Bhd. All rights reserved</p> 
    </footer>
</body>
</html>
