<?php
// Include session management and database connection
include 'session.php';
include 'db_connection.php';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $departure_id = mysqli_real_escape_string($conn, $_POST['departure']);
    $destination_id = mysqli_real_escape_string($conn, $_POST['destination']);
    $travel_date = mysqli_real_escape_string($conn, $_POST['travelDate']);
    
    // Validate input - Check if departure and destination are the same
    if ($departure_id == $destination_id) {
        $_SESSION['error'] = "Departure and destination cannot be the same.";
        header("Location: Bookings.php");
        exit();
    }
    
    // Validate input - Check if the travel date is in the past
    $today = date('Y-m-d');
    if ($travel_date < $today) {
        $_SESSION['error'] = "Please select a future date for travel.";
        header("Location: Bookings.php");
        exit();
    }
    
    // Check if route exists
    $route_query = "SELECT * FROM Routes WHERE DepartureStationID = '$departure_id' AND ArrivalStationID = '$destination_id'";
    $route_result = mysqli_query($conn, $route_query);
    
    if (mysqli_num_rows($route_result) == 0) {
        $_SESSION['error'] = "No routes found for this selection.";
        header("Location: Bookings.php");
        exit();
    }
    
    $route = mysqli_fetch_assoc($route_result);
    $route_id = $route['RouteID'];
    $duration = $route['Duration'];
    
    // Get station names for display
    $departure_query = "SELECT StationName FROM Stations WHERE StationID = '$departure_id'";
    $destination_query = "SELECT StationName FROM Stations WHERE StationID = '$destination_id'";
    
    $departure_result = mysqli_query($conn, $departure_query);
    $destination_result = mysqli_query($conn, $destination_query);
    
    $departure_name = mysqli_fetch_assoc($departure_result)['StationName'];
    $destination_name = mysqli_fetch_assoc($destination_result)['StationName'];
    
    // Check if trips exist for this route and date
    $trips_query = "SELECT * FROM Trips WHERE RouteID = '$route_id' AND TravelDate = '$travel_date'";
    $trips_result = mysqli_query($conn, $trips_query);
    
    // If no trips found, create some default ones (for demonstration purposes)
    if (mysqli_num_rows($trips_result) == 0) {
        $times = ["9:00 AM", "4:00 PM", "10:00 PM"];
        $price = 15.00;
        
        foreach ($times as $time) {
            $insert_trip = "INSERT INTO Trips (RouteID, TravelDate, DepartureTime, Price) 
                           VALUES ('$route_id', '$travel_date', '$time', '$price')";
            mysqli_query($conn, $insert_trip);
        }
        
        // Query again to get the newly created trips
        $trips_result = mysqli_query($conn, "SELECT * FROM Trips WHERE RouteID = '$route_id' AND TravelDate = '$travel_date'");
    }
    
    // Get current time plus one hour to filter trips
    $current_time = time();
    $one_hour_later = $current_time + 3600; // Add 3600 seconds (1 hour)
    
    // Prepare search results for display
    $search_results = [];
    while ($trip = mysqli_fetch_assoc($trips_result)) {
        // Convert trip time to a comparable format
        $trip_datetime = strtotime($travel_date . ' ' . $trip['DepartureTime']);
        
        // Skip trips that are less than one hour from now
        if ($travel_date == $today && $trip_datetime < $one_hour_later) {
            continue; // Skip this trip
        }
        
        $search_results[] = [
            'trip_id' => $trip['TripID'],
            'departure' => $departure_name,
            'destination' => $destination_name,
            'travel_date' => $travel_date,
            'departure_time' => $trip['DepartureTime'],
            'duration' => $duration,
            'price' => $trip['Price']
        ];
    }
    
    // Check if we have any valid trips after filtering
    if (empty($search_results)) {
        $_SESSION['error'] = "No trips available for the selected date and time. Please select a different date or check back later.";
        header("Location: Bookings.php");
        exit();
    }
    
    // Store search results in session
    $_SESSION['search_results'] = $search_results;
    
    // Redirect back to bookings page to display results
    header("Location: Bookings.php");
    exit();
} else {
    // If accessed directly without form submission, redirect to bookings page
    header("Location: Bookings.php");
    exit();
}
?>
