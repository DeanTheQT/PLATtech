<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit;
}

// Database connection
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

// Fetch user data for displaying in the form using email
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
$stmt->execute([$_SESSION['email']]);
$user = $stmt->fetch();

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy(); // Destroy the session
    header('Location: login.php'); // Redirect to login page
    exit;
}

// Handle profile update via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $profile_picture = $_FILES['profile_picture'];

    // Validate input
    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Name and description cannot be empty.']);
        exit;
    }

    // Initialize profile picture path
    $profile_picture_path = null;

    // Handle file upload only if a file is chosen
    if ($profile_picture['error'] === UPLOAD_ERR_OK) {
        // Validate file type (optional)
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($profile_picture['type'], $allowed_types)) {
            echo json_encode(['success' => false, 'message' => 'Invalid file type.']);
            exit;
        }

        // Move the uploaded file to the desired directory
        $upload_dir = 'uploads/';
        $file_name = basename($profile_picture['name']);
        $target_file = $upload_dir . uniqid() . '-' . $file_name; // Use a unique name to avoid conflicts

        if (move_uploaded_file($profile_picture['tmp_name'], $target_file)) {
            $profile_picture_path = $target_file; // Save the file path for database update
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload file.']);
            exit;
        }
    }

    // Update user information in the database
    $stmt = $pdo->prepare("UPDATE users SET name = ?, description = ?" . ($profile_picture_path ? ", profile_image_path = ?" : "") . " WHERE email = ?");
    
    // Prepare the parameters for the update
    if ($profile_picture_path) {
        $stmt->execute([$name, $description, $profile_picture_path, $_SESSION['email']]);
    } else {
        $stmt->execute([$name, $description, $_SESSION['email']]);
    }

    // Update the job_posts table with new information
    $stmt = $pdo->prepare("UPDATE job_posts SET UserName = ?" . ($profile_picture_path ? ", profile_image_path = ?" : "") . " WHERE email = ?");
    
    // Prepare the parameters for the job_posts update
    if ($profile_picture_path) {
        $stmt->execute([$name, $profile_picture_path, $_SESSION['email']]);
    } else {
        $stmt->execute([$name, $_SESSION['email']]);
    }
    
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
    exit;
}
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
            max-width: 800px;
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

        /* From Uiverse.io by satyamchaudharydev */ 
        /* inspired form gumroad website */
        .profile-button {
          --bg: #000;
          --hover-bg: #f39d3a;
          --hover-text: #000;
          color: #fff;
          cursor: pointer;
          border: 1px solid var(--bg);
          border-radius: 4px;
          padding: 0.8em 2em;
          background: var(--bg);
          transition: 0.2s;
          font-size: 16px;
        }

        .profile-button:hover {
          color: var(--hover-text);
          transform: translate(-0.25rem, -0.25rem);
          background: var(--hover-bg);
          box-shadow: 0.25rem 0.25rem var(--bg);
        }

        .profile-button:active {
          transform: translate(0);
          box-shadow: none;
        }

        /* From Uiverse.io by satyamchaudharydev */ 
        /* inspired form gumroad website */
        .logout-button {
          --bg: #000;
          --hover-bg: #f39d3a;
          --hover-text: #000;
          color: #fff;
          cursor: pointer;
          border: 1px solid var(--bg);
          border-radius: 4px;
          padding: 0.8em 2em;
          background: var(--bg);
          transition: 0.2s;
          margin-top: 20px;
          font-size: 16px;
        }

        .logout-button:hover {
          color: var(--hover-text);
          transform: translate(-0.25rem, -0.25rem);
          background: var(--hover-bg);
          box-shadow: 0.25rem 0.25rem var(--bg);
        }

        .logout-button:active {
          transform: translate(0);
          box-shadow: none;
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

        .logout-btn {
            margin-top: 20px;
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
            <!-- Set enctype to handle file uploads -->
            <form id="profileForm" onsubmit="updateProfile(event)" enctype="multipart/form-data">
                <h3>Your Profile</h3>
                <input type="text" required name="name" maxlength="50" placeholder="Enter your Name" class="input" value="<?php echo htmlspecialchars($user['name']); ?>">
                <textarea name="description" placeholder="Enter your Description" class="input"><?php echo htmlspecialchars($user['description'] ?? ''); ?></textarea>
                <input type="file" name="profile_picture" class="input">
                <input type="hidden" name="update_profile" value="1">
                <input type="submit" value="Update Profile" class="profile-button">
            </form>
            <form action="" method="post" style="margin-top: 20px;">
                <input type="submit" name="logout" value="Logout" class="logout-button">
            </form>
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