<?php
session_start(); // Start the session

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

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['pass'];
    $confirmPassword = $_POST['c_pass'];

    // Check if fields are not empty
    if (!empty($name) && !empty($email) && !empty($password)) {
        // Check if passwords match
        if ($password === $confirmPassword) {
            // Check if the email already exists
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $existingUser   = $stmt->fetch();

            if ($existingUser ) {
                // Email already exists, set an error message
                $error = "An account with this email already exists.";
            } else {
                // Hash the password before storing it
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insert the new user into the database with the hashed password
                $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
                $stmt->execute([$name, $email, $hashedPassword]);
                echo "Registration successful!";
                
                // Fetch the newly created user
                $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                $_SESSION['email'] = $user['email']; // Store email in session
                header('Location: profile.php'); // Redirect to profile page
                exit; // Ensure no further code is executed
            }
        } else {
            $error = "Passwords do not match.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="home.css">
    <style>
        .hidden {
            display: none;
        }

        .error {
            color: red;
        }

        /* From Uiverse.io by satyamchaudharydev */ 
        /* inspired form gumroad website */
        .register-button {
          --bg2: #000;
          --hover-bg2: #f39d3a;
          --hover-text2: #000;
          color: #000;
          cursor: pointer;
          border: 1px solid var(--bg);
          border-radius: 4px;
          padding: 0.8em 2em;
          background: var(--bg);
          transition: 0.2s;
          font-size: 16px;
        }

        .register-button:hover {
          color: var(--hover-text2);
          transform: translate(-0.25rem, -0.25rem);
          background: var(--hover-bg2);
          box-shadow: 0.25rem 0.25rem var(--bg2);
        }

        .register-button:active {
          transform: translate(0);
          box-shadow: none;
        }

        /* From Uiverse.io by satyamchaudharydev */ 
        /* inspired form gumroad website */
        .header-button {
        --bg:  #f39d3a;
          --hover-bg:rgb(255, 255, 255);
          --hover-text: #000;
          color: #fff;
          cursor: pointer;
          border: 1px solid var(--bg);
          border-radius: 4px;
          padding: 0.8em 2em;
          background: var(--bg);
          transition: 0.2s;
          font-size: 1.6rem; /* Adjust font size to match header */
        }

        .header-button:hover {
          color: var(--hover-text);
          transform: translate(-0.25rem, -0.25rem);
          background: var(--hover-bg);
          box-shadow: 0.25rem 0.25rem var(--bg);
        }

        .header-button:active {
          transform: translate(0);
          box-shadow: none;
        }

        .dropdown-content2 {
        display: none; /* Initially hide the dropdown */
        position: absolute; /* Position the dropdown */
        background-color: #f9f9f9; /* Background color */
        min-width: 160px; /* Minimum width */
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); /* Shadow */
        z-index: 1; /* Sit on top */
        top: 100%; /* Position it below the button */
        left: 0; /* Align it to the left of the button */
        margin-top: 5px; /* Optional: Add some space between the button and dropdown */
    }
    .dropdown-content2 a {
        color: black; /* Text color */
        padding: 12px 16px; /* Padding */
        text-decoration: none; /* No underline */
        display: block; /* Make links block elements to stack vertically */
    }
    .dropdown-content2 a:hover {
        background-color: #f1f1f1; /* Change color on hover */
    }
    .show {
        display: block; /* Show the dropdown */
    }
    </style>
</head>
<body>

<!--header-->
<header class="header">
    <section class="flex">
        <div id="menu-btn" class="fas fa-bars-staggered" onclick="toggleDropdown()"></div>
        <div id="dropdown-menu" class="dropdown-content2">
            <a href="home.php">Home</a>
            <a href="about.php">About</a>
            <a href="<?php echo isset($_SESSION['email']) ? 'profile.php' : 'login.php'; ?>">Account</a>
        </div>
        <a href="home.php" class="logo"><i class="fa fa-snowflake" aria-hidden="true"></i>Netralyze</a>

        <nav class="navbar">
            <a href="home.php">Home</a>
            <a href="about.php">About</a>
            <a href="<?php echo isset($_SESSION['email']) ? 'profile.php' : 'login.php'; ?>">Account</a>
        </nav>
        <a href="<?php echo isset($_SESSION['email']) ? 'createpost.php' : 'login.php'; ?>" class="btn" style="margin-top: 0;">Post</a>
    </section>
</header>
<!--header-->

<!-- Account Form -->
<div class="account-form-container">
    <section class="account-form">
        <form id="register-form" action="" method="post">
            <h3>Create a New Account!</h3>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <input type="text" required name="name" maxlength="50" placeholder="Enter your Name" class="input">
            <input type="email" required name="email" maxlength="50" placeholder="Enter your Email" class="input">
            <input type="password" required id="pass" name="pass" maxlength="20" placeholder="Enter your Password" class="input">
            <input type="password" required id="c_pass" name="c_pass" maxlength="20" placeholder="Confirm your Password" class="input">
            <p id="match" class="hidden error">Passwords must match</p>

            <p>Have an Account already? <a href="login.php">Log In Now</a></p>
            <input type="submit" value="Register Now" name="submit" class="btn">
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
<script>
    const password = document.getElementById("pass");
    const confirmPassword = document.getElementById("c_pass");
    const matchPassword = document.getElementById("match");
    const form = document.getElementById("register-form");

    confirmPassword.addEventListener("input", () => {
        if (confirmPassword.value !== password.value) {
            matchPassword.classList.remove('hidden');
        } else {
            matchPassword.classList.add('hidden');
        }
    });

    form.addEventListener("submit", (event) => {
        if (confirmPassword.value !== password.value) {
            // Prevent form submission
            event.preventDefault();
            alert("Passwords do not match! Please try again.");
        }
    });

    // Check if there's a success message from PHP
    <?php if (isset($successMessage)): ?>
        alert("<?php echo htmlspecialchars($successMessage); ?>");
    <?php endif; ?>

    // Check if there's an error message from PHP
    <?php if (isset($error)): ?>
        alert("<?php echo htmlspecialchars($error); ?>");
    <?php endif; ?>

    function toggleDropdown() {
        document.getElementById("dropdown-menu").classList.toggle("show");
    }
    window.onclick = function(event) {
        // If the click target is NOT the menu button or the dropdown menu, hide the dropdown
        var menuBtn = document.getElementById("menu-btn");
        var dropdown = document.getElementById("dropdown-menu");
        if (event.target !== menuBtn && !dropdown.contains(event.target)) {
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        }
    }
</script>
</body>
</html>