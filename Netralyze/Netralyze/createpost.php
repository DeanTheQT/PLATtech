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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the current user's email from session
    $email = $_SESSION['email'];

    // Fetch user details from the users table
    $sql = "SELECT name, password, profile_image_path FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the user details
        $user = $result->fetch_assoc();
        $name = $user['name'];
        $profile_image_path = $user['profile_image_path'];

        // Get post details from the form
        $post_title = $_POST['title'];
        $post_description = $_POST['description'];

        // Insert into job_posts table
        $insert_sql = "INSERT INTO job_posts (email, UserName, profile_image_path, post_title, post_description) VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("sssss", $email, $name, $profile_image_path, $post_title, $post_description);

        if ($insert_stmt->execute()) {
            echo "New job post created successfully!";
            header('Location: home.php');
        } else {
            echo "Error: " . $insert_stmt->error;
        }

        $insert_stmt->close();
    } else {
        echo "User  not found.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Something</title>
    <link rel="stylesheet" href="home.css">
    <style>
        .create-post-container {
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

        .create-post-form {
            display: flex;
            align-items: flex-start;
        }


        .create-post-details {
            flex: 1; /* Take the remaining space */
        }

        .create-post-form h3 {
            margin-bottom: 20px;
            text-align: center;
        }

        .create-post-form input,
        .create-post-form textarea {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            width: 100%; /* Full width for inputs */
        }

        .create-post-conainer .btn {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        .post-button {
            width: 100%; /* Make the button full width */
            padding: 10px; /* Add some padding to the button */
            background-color: #007bff; /* Button background color */
            color: white; /* Button text color */
            border: none; /* Remove default border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Change cursor to pointer on hover */
            font-size: 16px; /* Increase font size for better visibility */
        }

        .post-button:hover {
            background-color: #0056b3; /* Darker blue on hover */
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

<!--Create post-->
<div class="create-post-container">
    <section class="create-post-form">
        <form action="" method="post">
            <h3>Create New Job Offer</h3>
            <p>Job Title</p>
            <input type="text" name="title" placeholder="keyword, category or company" required maxlength="50" class="input">
            <p>Job Description</p>
            <textarea name="description" placeholder="Job description" class="input" required maxlength="500"></textarea>
            <button type="submit" class="post-button">Post</button>
        </form>
    </section>
</div>
<!--Create post-->

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

<!--JS-->
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