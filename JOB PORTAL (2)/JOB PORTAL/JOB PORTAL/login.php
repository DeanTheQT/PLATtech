<?php
session_start(); // Start the session to store user data

$host = 'localhost';
$db = 'accounts';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (isset($_POST['login_submit'])) {
    $email = $_POST['email'];
    $password = $_POST['pass'];

    // Fetch user by email
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Check if user exists and verify the password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['email'] = $user['email']; // Store email in session
        header('Location: profile.php'); // Redirect to profile page
        exit; // Ensure no further code is executed
    } else {
        echo "<script>alert('Login failed! Invalid email or password.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>

<!--header-->
<header class="header">
    <section class="flex">
        <div id="menu-btn" class="fas fa-bars-staggered"></div>
        <a href="home.php" class="logo"><i class="fas fa-briefcase"></i>JobHunt</a>

        <nav class="navbar">
            <a href="home.php">Home</a>
            <a href="about.php">About</a>
            <a href="<?php echo isset($_SESSION['email']) ? 'profile.php' : 'login.php'; ?>">Account</a>
        </nav>
        <a a href="<?php echo isset($_SESSION['email']) ? 'createpost.php' : 'login.php'; ?>" class="btn" style="margin-top: 0;">Post Job</a>
    </section>
</header>
<!--header-->

<!-- Account Form -->
<div class="account-form-container">
    <section class="account-form">
        <form action="" method="post">
            <h3>Welcome Back!</h3>
            <input type="email" required name="email" maxlength="50" placeholder="Enter your Email" class="input">
            <input type="password" required name="pass" maxlength="20" placeholder="Enter your Password" class="input">
            <p>Don't have an Account? <a href="register.php">Register Now</a></p>
            <input type="submit" value="Login Now" name="login_submit" class="btn">
        </form>
    </section>
</div>
<!-- Account Form -->

<!-- Footer -->
<footer class="footer">
    <section class="grid">
        <div class="box">
            <h3>Quick Links</h3>
            <a href="home.php"><i class="fas fa-angle-right"></i> Home</a>
            <a href="about.php"><i class="fas fa-angle-right"></i> About</a>
            <a href="#">< <i class="fas fa-angle-right"></i> Filter Search</a>
        </div>

        <div class="box">
            <h3>Follow Us</h3>
            <a href="#"><i class="fab fa-facebook"></i> Facebook</a>
            <a href="#"><i class="fab fa-twitter"></i> Twitter</a>
            <a href="#"><i class="fab fa-instagram"></i> Instagram</a>
            <a href="#"><i class="fab fa-linkedin"></i> LinkedIn</a>
            <a href="#"><i class="fab fa-youtube"></i> YouTube</a>
        </div>
    </section>

    <div class="credit">&copy; Copyright @ 2024 by <span>Group #</span> | All Rights Reserved</div>
</footer>
<!-- Footer -->

<!-- JS -->
<script src="script.js"></script>
</body>
</html>