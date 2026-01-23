<?php
// Include session management
include 'session.php';

// Require login to access this page
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="Style.css">
    <a href="https://www.flaticon.com/free-icons/user" title="user icons">User icons created by Freepik - Flaticon</a>
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
   <section class="content-grid">
      <div class="text-block">
        <p>Welcome to JomBus, Malaysia's trusted online platform for seamless bus travel and hassle-free bookings. Designed with convenience in mind, JomBus connects travelers to five major bus stations across Malaysia,
             ensuring safe, comfortable, and affordable journeys for commuters and tourists alike. Whether you're planning a quick weekend getaway, a business trip, or a family vacation, 
            JomBus offers an intuitive interface that allows users to browse schedules, compare ticket prices, and reserve seats with just a few clicks.</p>
      </div>
     
     
      <div class="text-block">
        <p>Our routes span key destinations, making it easy for passengers to travel between cities like Kuala Lumpur, Penang, Johor Bahru, Melaka, and Kuantan. Each booking is secured with instant confirmation, and passengers can choose their preferred seats for maximum comfort.
             JomBus is committed to providing reliable information on bus operators, travel times, amenities on board, and safety measures, empowering travelers to make informed decisions. We also offer exclusive promotions and discounts, making intercity travel even more budget-friendly.</p>
      </div>
      <div class="text-block">
        <p>With JomBus, passengers can avoid long queues at ticket counters, manage their itineraries online, and receive real-time updates about their trips.
             Whether you're a frequent commuter or a first-time visitor exploring Malaysia, JomBus is your trusted travel companion, dedicated to delivering a smooth, enjoyable journey every time.
             Start your adventure today and discover how simple and convenient bus travel can be with JomBus!</p>
      </div>
     
    </section>
  </main>

<footer>

  <h3 class="footer-style">Contact Us: <br> Anas Ahmed | 01164081225 <br> Elbaraa Taher | 01139941541 <br> Abdelrahman Amr | 0175351418  <br> Hasinou Said | 01139864462 </h3>
  <p class="footer-style">&copy;2025 JomBus Travel Sdn Bhd. All rights reserved</p> 
</footer>
</body>
</html>
