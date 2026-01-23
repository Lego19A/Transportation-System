<?php
// Include session management and database connection
include 'session.php';
include 'db_connection.php';

// Check if user is already logged in
if (isLoggedIn()) {
    header("Location: Home.php");
    exit();
}

// Process registration form submission
$error = "";
$success = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Simple validation
    if ($password != $confirm_password) {
        $error = "Passwords do not match";
    } else {
        // Check if username, email or phone already exists
        $check_sql = "SELECT * FROM Users WHERE Username = '$name' OR Email = '$email' OR Phone = '$phone'";
        $check_result = mysqli_query($conn, $check_sql);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = "An account with this username, email or phone already exists";
        } else {
            // Insert new user
            // In a real application, you would use password_hash() to hash the password
            $insert_sql = "INSERT INTO Users (Username, Password, Email, Phone) 
                          VALUES ('$name', '$password', '$email', '$phone')";
            
            if (mysqli_query($conn, $insert_sql)) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body>
<header>
    <nav>
    <img src="JomBus.png" width="80px" height="80px">
    </nav>
</header>

<main>
    <section class="form-section">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <h1>Sign up</h1>
    <p>Enter your details to create a new account</p>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>
    <br>
    <div class="form-style">
    <input type="text" id="Name" name="name" placeholder="Name" required>
    <br>
    <input type="tel" id="number" name="phone" placeholder="Phone no" required>
    <br>
    <input type="email" id="Email" name="email" placeholder="Email" required>
    <br>
    <input type="password" id="password_new" name="password" placeholder="Password" required>
    <br>
    <input type="password" id="password_confirm" name="confirm_password" placeholder="Confirm Password" required>
    <br>

    <button type="submit" value="Sign-up"> Sign-up </button>
    <p>Already have an account? <a href="Log_in.php">Login</a></p>
    </div>

    </form>
    </section>

</main>



</body>
</html>
