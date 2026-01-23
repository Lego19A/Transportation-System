<?php
// Include session management and database connection
include 'session.php';
include 'db_connection.php';

// Require login to access this page
requireLogin();

// Get user information from database
$user_id = $_SESSION['user_id'];
$user_query = "SELECT * FROM Users WHERE UserID = '$user_id'";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

// Process form submission for profile update
$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Check if current password is correct
    if (!empty($current_password)) {
        if ($current_password == $user['Password']) { // Simple comparison for student project
            // Check if new password fields match
            if (!empty($new_password) && $new_password == $confirm_password) {
                // Update password
                $update_password_sql = "UPDATE Users SET Password = '$new_password' WHERE UserID = '$user_id'";
                if (mysqli_query($conn, $update_password_sql)) {
                    $success_message = "Password updated successfully!";
                } else {
                    $error_message = "Error updating password: " . mysqli_error($conn);
                }
            } elseif (!empty($new_password)) {
                $error_message = "New passwords do not match.";
            }
        } else {
            $error_message = "Current password is incorrect.";
        }
    }
    
    // Update profile information (even if password update fails)
    if (empty($error_message)) {
        $update_profile_sql = "UPDATE Users SET Username = '$username', Email = '$email', Phone = '$phone' WHERE UserID = '$user_id'";
        if (mysqli_query($conn, $update_profile_sql)) {
            // Update session username
            $_SESSION['username'] = $username;
            $success_message = "Profile updated successfully!";
            
            // Refresh user data
            $user_result = mysqli_query($conn, $user_query);
            $user = mysqli_fetch_assoc($user_result);
        } else {
            $error_message = "Error updating profile: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
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
                <a id="P1" href="Profile.php">
                    <img src="<?php echo $user['ProfilePicture']; ?>" width="40px" height="40px" alt="Profile">
                </a>
            </div>
        </nav>
    </header>
    
    <main>
        
        <div class="profile-container">
            <div class="profile-header">
                <h1>User Profile</h1>
            </div>
            
            <?php if (!empty($success_message)): ?>
                <div class="message success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
                <div class="message error"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <div class="profile-picture">
                <img src="<?php echo $user['ProfilePicture']; ?>" width="100px" height="100px" alt="Profile Picture">
            </div>
            
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-row">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo $user['Username']; ?>" required>
                </div>
                
                <div class="form-row">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $user['Email']; ?>">
                </div>
                
                <div class="form-row">
                    <label for="phone">Phone:</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo $user['Phone']; ?>">
                </div>
                
                <div class="password-section">
                    <h3>Change Password</h3>
                    
                    <div class="form-row">
                        <label for="current_password">Current Password:</label>
                        <input type="password" id="current_password" name="current_password">
                    </div>
                    
                    <div class="form-row">
                        <label for="new_password">New Password:</label>
                        <input type="password" id="new_password" name="new_password">
                    </div>
                    
                    <div class="form-row">
                        <label for="confirm_password">Confirm New Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password">
                    </div>
                </div>
                
                <div class="button-row">
                    <button type="submit">Update Profile</button>
                    <a href="logout.php"><button type="button">Logout</button></a>
                </div>
            </form>
        </div>
            
    </main>
    
    <footer>
         <h3 class="footer-style">Contact Us: <br> Anas Ahmed | 01164081225 <br> Elbaraa Taher | 01139941541 <br> Abdelrahman Amr | 0175351418  <br> Hasinou Said | 01139864462 </h3>
  <p class="footer-style">&copy;2025 JomBus Travel Sdn Bhd. All rights reserved</p> 
    </footer>
</body>
</html>