-- Create the database
CREATE DATABASE IF NOT EXISTS jom_bus;
USE jom_bus;

-- Create Users table
CREATE TABLE IF NOT EXISTS Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Email VARCHAR(100),
    Phone VARCHAR(20),
    RegistrationDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    ProfilePicture VARCHAR(255) DEFAULT 'user.png'
);

-- Create Stations table
CREATE TABLE IF NOT EXISTS Stations (
    StationID INT AUTO_INCREMENT PRIMARY KEY,
    StationName VARCHAR(100) NOT NULL UNIQUE
);

-- Insert the stations from the website
INSERT INTO Stations (StationName) VALUES
    ('Terminal Bersepadu Selatan (TBS)'),
    ('Melaka Sentral'),
    ('Johor Bharu Larkin'),
    ('Sungai Nibong (Pinang)'),
    ('Kuantan Sentral');

-- Create Routes table
CREATE TABLE IF NOT EXISTS Routes (
    RouteID INT AUTO_INCREMENT PRIMARY KEY,
    DepartureStationID INT NOT NULL,
    ArrivalStationID INT NOT NULL,
    Duration VARCHAR(50) NOT NULL,
    FOREIGN KEY (DepartureStationID) REFERENCES Stations(StationID),
    FOREIGN KEY (ArrivalStationID) REFERENCES Stations(StationID),
    UNIQUE KEY unique_route (DepartureStationID, ArrivalStationID)
);

-- Insert the routes from the website (based on travelTimes in Structure.js)
INSERT INTO Routes (DepartureStationID, ArrivalStationID, Duration) VALUES
    (3, 5, '8 hours and 21 minutes'), -- Johor Bharu Larkin to Kuantan Sentral
    (3, 2, '3 hours and 34 minutes'), -- Johor Bharu Larkin to Melaka Sentral
    (3, 1, '3 hours and 55 minutes'), -- Johor Bharu Larkin to TBS
    (3, 4, '9 hours and 34 minutes'), -- Johor Bharu Larkin to Sungai Nibong
    (5, 2, '6 hours and 14 minutes'), -- Kuantan Sentral to Melaka Sentral
    (5, 1, '3 hours and 54 minutes'), -- Kuantan Sentral to TBS
    (5, 4, '9 hours and 18 minutes'), -- Kuantan Sentral to Sungai Nibong
    (2, 1, '2 hours and 4 minutes'),  -- Melaka Sentral to TBS
    (2, 4, '7 hours and 37 minutes'), -- Melaka Sentral to Sungai Nibong
    (1, 4, '5 hours and 18 minutes'), -- TBS to Sungai Nibong
    (1, 3, '3 hours and 50 minutes'), -- TBS to Johor Bharu Larkin
    (1, 5, '3 hours and 40 minutes'), -- TBS to Kuantan Sentral
    (1, 2, '2 hours and 10 minutes'), -- TBS to Melaka Sentral
    (2, 5, '6 hours and 5 minutes'),  -- Melaka Sentral to Kuantan Sentral
    (2, 3, '3 hours and 45 minutes'), -- Melaka Sentral to Johor Bharu Larkin
    (4, 2, '7 hours and 40 minutes'), -- Sungai Nibong to Melaka Sentral
    (4, 3, '9 hours and 45 minutes'), -- Sungai Nibong to Johor Bharu Larkin
    (4, 1, '5 hours and 25 minutes'), -- Sungai Nibong to TBS
    (4, 5, '9 hours and 20 minutes'), -- Sungai Nibong to Kuantan Sentral
    (5, 3, '8 hours and 15 minutes'); -- Kuantan Sentral to Johor Bharu Larkin

-- Create Trips table
CREATE TABLE IF NOT EXISTS Trips (
    TripID INT AUTO_INCREMENT PRIMARY KEY,
    RouteID INT NOT NULL,
    TravelDate DATE NOT NULL,
    DepartureTime VARCHAR(20) NOT NULL,
    Price DECIMAL(10, 2) DEFAULT 15.00,
    FOREIGN KEY (RouteID) REFERENCES Routes(RouteID)
);

-- Create Seats table
CREATE TABLE IF NOT EXISTS Seats (
    SeatID INT AUTO_INCREMENT PRIMARY KEY,
    SeatNumber VARCHAR(5) NOT NULL,
    Status ENUM('available', 'booked') DEFAULT 'available'
);

-- Insert the seats from the website (A1 through C7)
INSERT INTO Seats (SeatNumber) VALUES
    ('A1'), ('B1'), ('C1'),
    ('A2'), ('B2'), ('C2'),
    ('A3'), ('B3'), ('C3'),
    ('A4'), ('B4'), ('C4'),
    ('A5'), ('B5'), ('C5'),
    ('A6'), ('B6'), ('C6'),
    ('A7'), ('B7'), ('C7');

-- Create Bookings table
CREATE TABLE IF NOT EXISTS Bookings (
    BookingID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    TripID INT NOT NULL,
    BookingDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    TotalAmount DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (UserID) REFERENCES Users(UserID),
    FOREIGN KEY (TripID) REFERENCES Trips(TripID)
);

-- Create BookingDetails table
CREATE TABLE IF NOT EXISTS BookingDetails (
    BookingDetailID INT AUTO_INCREMENT PRIMARY KEY,
    BookingID INT NOT NULL,
    SeatID INT NOT NULL,
    FOREIGN KEY (BookingID) REFERENCES Bookings(BookingID),
    FOREIGN KEY (SeatID) REFERENCES Seats(SeatID),
    UNIQUE KEY unique_booking_seat (BookingID, SeatID)
);

-- Create Payments table
CREATE TABLE IF NOT EXISTS Payments (
    PaymentID INT AUTO_INCREMENT PRIMARY KEY,
    BookingID INT NOT NULL,
    CardNumber VARCHAR(255) NOT NULL,
    CardExpiryDate DATE NOT NULL,
    CardCCV VARCHAR(255) NOT NULL,
    PaymentDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (BookingID) REFERENCES Bookings(BookingID)
);

-- Create a view to show available trips
CREATE OR REPLACE VIEW AvailableTrips AS
SELECT 
    t.TripID,
    s1.StationName AS Departure,
    s2.StationName AS Destination,
    t.TravelDate,
    t.DepartureTime,
    r.Duration,
    t.Price
FROM 
    Trips t
JOIN 
    Routes r ON t.RouteID = r.RouteID
JOIN 
    Stations s1 ON r.DepartureStationID = s1.StationID
JOIN 
    Stations s2 ON r.ArrivalStationID = s2.StationID
WHERE 
    t.TravelDate >= CURDATE();
