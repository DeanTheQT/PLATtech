<?php
session_start(); // Start the session

// Database connection
$servername = "localhost"; // Your server name
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "accounts"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the email from the URL
if (isset($_GET['email'])) {
    $email = $conn->real_escape_string($_GET['email']); // Sanitize the email input

    // Fetch user details
    $sql = "SELECT name, email, profile_image_path, description FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        $user = null; // No user found
    }
} else {
    $user = null; // No email provided
}

$conn->close(); // Close the database connection
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="home.css">
    <style>

        .profile-form-container {
            max-width: 500px;
            max-height: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .profile-form {
            display: flex;
            align-items: flex-start;
        }

        .profile-picture {
            flex: 0 0 50%; /* Fix the width to 50% */
            margin-right: 20px;
        }

        .profile-picture img {
            max-width: 100%; /* Make the image responsive */
            border-radius: 50%; /* Make it circular */
            border: 2px solid #ccc; /* Add a border */
        }

        .profile-details {
            flex: 1; /* Take the remaining space */
        }

        .profile-form h3 {
            margin-bottom: 20px;
            text-align: center;
        }

        .profile-form input,
        .profile-form textarea {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            width: 100%; /* Full width for inputs */
        }

        .profile-form input[type="file"] {
            padding: 3px;
        }

        .profile-form .btn {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        .profile-form .btn:hover {
            background-color: #218838;
        }

        .logout-btn {
            margin-top: 20px;
        }

        /* From Uiverse.io by satyamchaudharydev */ 
        /* inspired form gumroad website */
        .header-button {
          --bg:  #f39d3a;
          --hover-bg: #ffffff;
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
    <script>
        async function updateProfile(event) {
            event.preventDefault(); // Prevent the default form submission

            const formData = new FormData(document.getElementById('profileForm')); // Get form data
            try {
                const response = await fetch('profile.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    alert(result.message); // Show success message
                    location.reload(); // Reload the page to reflect changes
                } else {
                    alert(result.message); // Show error message
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while updating the profile.');
            }
        }
    </script>

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

<!--Profile Form-->
<div class="profile-form-container">
    <section class="profile-form">
        <div class="profile-picture">
            <!-- Display the user's current profile picture if available -->
            <img src="<?php echo !empty($user['profile_image_path']) ? htmlspecialchars($user['profile_image_path']) : 'https://sbcf.fr/wp-content/uploads/2018/03/sbcf-default-avatar.png'; ?>" alt="Profile Picture">
        </div>
        <div class="profile-details">
            <h3>Profile:</h3>
            <div class="profile-info">
                <strong>Name:</strong>
                <span class="profile-text"><?php echo htmlspecialchars($user['name']); ?></span>
            </div>
            <div class="profile-info">
                <strong>Description:</strong>
                <span class="profile-text"><?php echo nl2br(htmlspecialchars($user['description'] ?? '')); ?></span>
            </div>
        </div>
    </section>
</div>


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
<script>
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