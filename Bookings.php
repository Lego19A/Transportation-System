<?php
// Include session management and database connection
include 'session.php';
include 'db_connection.php';

// Require login to access this page
requireLogin();

// Get all stations for the dropdown menus
$stations_query = "SELECT * FROM Stations ORDER BY StationName";
$stations_result = mysqli_query($conn, $stations_query);
$stations = [];
while ($row = mysqli_fetch_assoc($stations_result)) {
    $stations[] = $row;
}

// Get today's date for the date input min attribute
$today = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings</title>
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
            <div class="form-style">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="error-message" style="color: red; margin-bottom: 15px; text-align: center;">
                        <?php 
                        echo $_SESSION['error']; 
                        unset($_SESSION['error']); 
                        ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="search_trips.php" id="bookingForm">
                    <h2>Departure</h2>
                    <select class="drop-down" id="departure" name="departure">
                        <?php foreach ($stations as $station): ?>
                            <option value="<?php echo $station['StationID']; ?>"><?php echo $station['StationName']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    
                    <h2>Destination</h2>
                    <select class="drop-down" id="destination" name="destination">
                        <?php foreach ($stations as $station): ?>
                            <option value="<?php echo $station['StationID']; ?>"><?php echo $station['StationName']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    
                    <h2>Date</h2>
                    <input type="date" id="travelDate" name="travelDate" min="<?php echo $today; ?>" required>
                    <br>
                    <button type="submit" id="searchBtn">Search</button>
                </form>
            </div>
        </section>
        
        <br>
        
        <?php
        // Display search results if they exist in the session
        if (isset($_SESSION['search_results']) && !empty($_SESSION['search_results'])) {
            echo '<section id="availableTrips">';
            echo '<h1>Available Trips</h1>';
            echo '<div class="cards" id="cardsContainer">';
            
            foreach ($_SESSION['search_results'] as $trip) {
                echo '<div class="cards">';
                echo '<p class="card-text"><strong>Departure:</strong> ' . $trip['departure'] . '</p>';
                echo '<p class="card-text"><strong>Destination:</strong> ' . $trip['destination'] . '</p>';
                echo '<p class="card-text"><strong>Travel Date:</strong> ' . $trip['travel_date'] . '</p>';
                echo '<p class="card-text"><strong>Departure Time:</strong> ' . $trip['departure_time'] . '</p>';
                echo '<p class="card-text"><strong>Duration:</strong> ' . $trip['duration'] . '</p>';
                echo '<p class="card-text"><strong>Price:</strong> ' . $trip['price'] . 'RM</p>';
                echo '<a href="Seat.php?trip_id=' . $trip['trip_id'] . '"><button type="submit">Book</button></a>';
                echo '</div>';
            }
            
            echo '</div>';
            echo '</section>';
            
            // Clear the search results from the session
            unset($_SESSION['search_results']);
        }
        ?>
    </main>

    <footer>
         <h3 class="footer-style">Contact Us: <br> Anas Ahmed | 01164081225 <br> Elbaraa Taher | 01139941541 <br> Hassan Amer | 01111781601  <br> Wadia Suhaib | 0195570086 </h3>
  <p class="footer-style">&copy;2025 JomBus Travel Sdn Bhd. All rights reserved</p> 
    </footer>

    <script>
    document.getElementById('bookingForm').addEventListener('submit', function(event) {
        const departure = document.getElementById('departure').value;
        const destination = document.getElementById('destination').value;
        const travelDate = document.getElementById('travelDate').value;
        
        // Check if departure and destination are the same
        if (departure === destination) {
            event.preventDefault();
            alert('Departure and destination cannot be the same!');
            return false;
        }
        
        // Check if the selected date is in the past
        const selectedDate = new Date(travelDate);
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Reset time part to compare dates only
        
        if (selectedDate < today) {
            event.preventDefault();
            alert('Please select a future date for travel!');
            return false;
        }
        
        return true;
    });
    </script>
</body>
</html>
