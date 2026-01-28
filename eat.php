<?php
// Include session management and database connection
include 'session.php';
include 'db_connection.php';

// Check if user is logged in
requireLogin();

// Check if trip_id is provided
if (!isset($_GET['trip_id'])) {
    header("Location: Bookings.php");
    exit();
}

$trip_id = mysqli_real_escape_string($conn, $_GET['trip_id']);

// Get trip details
$trip_query = "SELECT t.*, r.Duration, 
               s1.StationName AS DepartureStation, 
               s2.StationName AS ArrivalStation 
               FROM Trips t
               JOIN Routes r ON t.RouteID = r.RouteID
               JOIN Stations s1 ON r.DepartureStationID = s1.StationID
               JOIN Stations s2 ON r.ArrivalStationID = s2.StationID
               WHERE t.TripID = '$trip_id'";
$trip_result = mysqli_query($conn, $trip_query);

if (mysqli_num_rows($trip_result) == 0) {
    header("Location: Bookings.php");
    exit();
}

$trip = mysqli_fetch_assoc($trip_result);

// Get booked seats for this trip
$booked_seats_query = "SELECT s.SeatNumber FROM Seats s
                      JOIN BookingDetails bd ON s.SeatID = bd.SeatID
                      JOIN Bookings b ON bd.BookingID = b.BookingID
                      WHERE b.TripID = '$trip_id'";
$booked_seats_result = mysqli_query($conn, $booked_seats_query);

$booked_seats = [];
while ($row = mysqli_fetch_assoc($booked_seats_result)) {
    $booked_seats[] = $row['SeatNumber'];
}

// Store trip details in session for checkout
$_SESSION['selected_trip'] = [
    'trip_id' => $trip_id,
    'departure' => $trip['DepartureStation'],
    'destination' => $trip['ArrivalStation'],
    'travel_date' => $trip['TravelDate'],
    'departure_time' => $trip['DepartureTime'],
    'duration' => $trip['Duration'],
    'price' => $trip['Price']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Seat</title>
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
        <section class="form-section">
            <h2>Trip Details</h2>
            <p><strong>From:</strong> <?php echo $trip['DepartureStation']; ?></p>
            <p><strong>To:</strong> <?php echo $trip['ArrivalStation']; ?></p>
            <p><strong>Date:</strong> <?php echo $trip['TravelDate']; ?></p>
            <p><strong>Time:</strong> <?php echo $trip['DepartureTime']; ?></p>
            <p><strong>Duration:</strong> <?php echo $trip['Duration']; ?></p>
            <p><strong>Price per seat:</strong> RM<?php echo $trip['Price']; ?></p>
</section>
<br>
<section class="form-section">
            <h2>Select Your Seats</h2>
            <form method="post" action="process_booking.php">
                <input type="hidden" name="trip_id" value="<?php echo $trip_id; ?>">
                <div class="bus-container">
                    <!-- Row 1 -->
                    <div class="seat">
                        <input type="checkbox" id="seat1" name="seats[]" value="A1" <?php if (in_array('A1', $booked_seats)) echo 'disabled'; ?>>
                        <label for="seat1">A1</label>
                    </div>
                    <div class="seat">
                        <input type="checkbox" id="seat2" name="seats[]" value="B1" <?php if (in_array('B1', $booked_seats)) echo 'disabled'; ?>>
                        <label for="seat2">B1</label>
                    </div>
                    <div class="seat">
                        <input type="checkbox" id="seat3" name="seats[]" value="C1" <?php if (in_array('C1', $booked_seats)) echo 'disabled'; ?>>
                        <label for="seat3">C1</label>
                    </div>

                    <!-- Row 2 -->
                    <div class="seat">
                        <input type="checkbox" id="seat4" name="seats[]" value="A2" <?php if (in_array('A2', $booked_seats)) echo 'disabled'; ?>>
                        <label for="seat4">A2</label>
                    </div>
                    <div class="seat">
                        <input type="checkbox" id="seat5" name="seats[]" value="B2" <?php if (in_array('B2', $booked_seats)) echo 'disabled'; ?>>
                        <label for="seat5">B2</label>
                    </div>
                    <div class="seat">
                        <input type="checkbox" id="seat6" name="seats[]" value="C2" <?php if (in_array('C2', $booked_seats)) echo 'disabled'; ?>>
                        <label for="seat6">C2</label>
                    </div>

                    <!-- Row 3 -->
                    <div class="seat">
                        <input type="checkbox" id="seat7" name="seats[]" value="A3" <?php if (in_array('A3', $booked_seats)) echo 'disabled'; ?>>
                        <label for="seat7">A3</label>
                    </div>
                    <div class="seat">
                        <input type="checkbox" id="seat8" name="seats[]" value="B3" <?php if (in_array('B3', $booked_seats)) echo 'disabled'; ?>>
                        <label for="seat8">B3</label>
                    </div>
                    <div class="seat">
                        <input type="checkbox" id="seat9" name="seats[]" value="C3" <?php if (in_array('C3', $booked_seats)) echo 'disabled'; ?>>
                        <label for="seat9">C3</label>
                    </div>

                    <!-- Row 4 -->
                    <div class="seat">
                        <input type="checkbox" id="seat10" name="seats[]" value="A4" <?php if (in_array('A4', $booked_seats)) echo 'disabled'; ?>>
                        <label for="seat10">A4</label>
                    </div>
                    <div class="seat">
                        <input type="checkbox" id="seat11" name="seats[]" value="B4" <?php if (in_array('B4', $booked_seats)) echo 'disabled'; ?>>
                        <label for="seat11">B4</label>
                    </div>
                    <div class="seat">
                        <input type="checkbox" id="seat12" name="seats[]" value="C4" <?php if (in_array('C4', $booked_seats)) echo 'disabled'; ?>>
                        <label for="seat12">C4</label>
                    </div>

                    <!-- Row 5 -->
                    <div class="seat">
                        <input type="checkbox" id="seat13" name="seats[]" value="A5" <?php if (in_array('A5', $booked_seats)) echo 'disabled'; ?>>
                        <label for="seat13">A5</label>
                    </div>
                    <div class="seat">
                        <input type="checkbox" id="seat14" name="seats[]" value="B5" <?php if (in_array('B5', $booked_seats)) echo 'disabled'; ?>>
                        <label for="seat14">B5</label>
                    </div>
                    <div class="seat">
                        <input type="checkbox" id="seat15" name="seats[]" value="C5" <?php if (in_array('C5', $booked_seats)) echo 'disabled'; ?>>
                        <label for="seat15">C5</label>
                    </div>

                    <!-- Row 6 -->
                    <div class="seat">
                        <input type="checkbox" id="seat16" name="seats[]" value="A6" <?php if (in_array('A6', $booked_seats)) echo 'disabled'; ?>>
                        <label for="seat16">A6</label>
                    </div>
                    <div class="seat">
                        <input type="checkbox" id="seat17" name="seats[]" value="B6" <?php if (in_array('B6', $booked_seats)) echo 'disabled'; ?>>
                        <label for="seat17">B6</label>
                    </div>
                    <div class="seat">
                        <input type="checkbox" id="seat18" name="seats[]" value="C6" <?php if (in_array('C6', $booked_seats)) echo 'disabled'; ?>>
                        <label for="seat18">C6</label>
                    </div>

                    <!-- Row 7 -->
                    <div class="seat">
                        <input type="checkbox" id="seat19" name="seats[]" value="A7" <?php if (in_array('A7', $booked_seats)) echo 'disabled'; ?>>
                        <label for="seat19">A7</label>
                    </div>
                    <div class="seat">
                        <input type="checkbox" id="seat20" name="seats[]" value="B7" <?php if (in_array('B7', $booked_seats)) echo 'disabled'; ?>>
                        <label for="seat20">B7</label>
                    </div>
                    <div class="seat">
                        <input type="checkbox" id="seat21" name="seats[]" value="C7" <?php if (in_array('C7', $booked_seats)) echo 'disabled'; ?>>
                        <label for="seat21">C7</label>
                    </div>
                </div>
                <p id="totalPrice"></p>
                <button type="submit" class="button">Book</button>
            </form>
        </section>
    </main>

    <footer>
        <h3 class="footer-style">Contact Us: <br> Anas Ahmed | 01164081225 <br> Elbaraa Taher | 01139941541 <br> Hassan Amer | 01111781601  <br> Wadia Suhaib | 0195570086 </h3>
  <p class="footer-style">&copy;2025 JomBus Travel Sdn Bhd. All rights reserved</p> 
    </footer>

    <script>
        // Calculate total price when seats are selected
        const checkboxes = document.querySelectorAll('.bus-container input[type="checkbox"]');
        const totalPriceElement = document.getElementById('totalPrice');
        const seatPrice = <?php echo $trip['Price']; ?>;
        
        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateTotal);
        });
        
        function updateTotal() {
            let selectedSeats = [];
            let count = 0;
            
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    const label = document.querySelector(`label[for="${cb.id}"]`);
                    selectedSeats.push(label.textContent);
                    count++;
                }
            });
            
            const total = count * seatPrice;
            
            if (count > 0) {
                totalPriceElement.textContent = `Selected Seats: ${selectedSeats.join(", ")}\nTotal Price: RM ${total}`;
            } else {
                totalPriceElement.textContent = '';
            }
        }
    </script>
</body>
</html>
