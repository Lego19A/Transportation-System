<?php
// Include session management and database connection
include 'session.php';
include 'db_connection.php';

// Check if user is already logged in
if (isLoggedIn()) {
    header("Location: home.php");
    exit();
}

// Process login form submission
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_id = mysqli_real_escape_string($conn, $_POST['login_id']);
    $password = $_POST['password'];
    
    // Query to check user credentials (by email or phone)
    $sql = "SELECT * FROM Users WHERE Email = '$login_id' OR Phone = '$login_id' OR Username = '$login_id'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        // Verify password (in a real application, you would use password_verify with hashed passwords)
        if ($password == $user['Password']) { // Simple comparison for student project
            // Set session variables
            $_SESSION['user_id'] = $user['UserID'];
            $_SESSION['username'] = $user['Username'];
            
            // Redirect to home page
            header("Location: home.php");
            exit();
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "Account not found";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
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
    <h1>Log in</h1>
    <p class="L1">Enter your details to Log in to your account</p>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <br>
    <div class="form-style">
    <input type="text" id="Email" name="login_id" placeholder="Email / Phone Number" required>
    <br>
    <input type="password" id="Password" name="password" placeholder="Password" required>
    <br>

    <button type="submit" value="Log in"> Login </button>
    <p class="form-style">Don't have an account? <a href="Sign_up.php"><b>Sign In</b></a></p>
    </div>

    </form>
    </section>

</main>



</body>
</html>
