<body?php
session_start(); // Start the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link rel="stylesheet" href="home.css">

    <style>
        .about {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
            background-color: #ffffff; /* Light background for contrast */
            max-width: 1200px; /* Limit the width for better readability */
            margin: 0 auto; /* Center the section */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        .about img {
            width: 100%;
            max-width: 600px; /* Limit the image width */
            height: auto; /* Maintain aspect ratio */
            border-radius: 10px; /* Rounded corners for the image */
            margin-bottom: 20px; /* Space below the image */
        }

        .box {
            text-align: center; /* Center text */
            max-width: 800px; /* Limit the width of the text box */
        }

        .box h3 {
            font-size: 2rem; /* Larger font size for the heading */
            margin-bottom: 20px; /* Space below the heading */
            color: #333; /* Dark color for better readability */
        }

        .box p {
            font-size: 1.1rem; /* Slightly larger font for paragraphs */
            line-height: 1.6; /* Increase line height for readability */
            color: #555; /* Softer color for text */
            margin-bottom: 15px; /* Space between paragraphs */
        }

        .btn {
            /* inspired form gumroad website */
            --bg: #f39d3a;
            --hover-bg: #ffffff;
            --hover-text: #000;
            color: #fff;
            cursor: pointer;
            border: 1px solid var(--bg);
            border-radius: 4px;
            padding: 0.8em 2em;
            background: var(--bg);
            transition: 0.2s;
        }

        .btn:hover {
            color: var(--hover-text);
            transform: translate(-0.25rem, -0.25rem);
            background: var(--hover-bg);
            box-shadow: 0.25rem 0.25rem var(--bg);
        }

        .btn:active {
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

<!--About-->
<div class="section-title">About Us</div>
<div class="about" >
    <div class="box">
        <h3>Why Choose Us</h3>
        <p>Netralyze, the country's number 1 job site, has always been in the cutting edge of providing cutting edge products and services to both job seekers and employers. 
            In our continuing quest to ensure that our job seekers are given the best information in their job search and at the same time, highlight the strengths of our employers, 
            we are introducing an enhanced edition of our Company Profiles Homepage.</p>
        <p>Netralyze operates market-leading online employment marketplaces, including Jobstreet and Jobsdb in Asia. 
            Netralyze has been helping people live more fulfilling and productive working lives and helping organisations succeed for over 25 years.</p>
        <a href="<?php echo isset($_SESSION['email']) ? 'home.php' : 'register.php'; ?>" class="btn">Join Us!</a>
    </div>
</div>
<!--About-->
<!-- Footer -->
<footer class="footer">
    <section class="grid">
        <div class="box">
            <h3>Quick Links</h3>
            <a href="home.php"><i class="fas fa-angle-right"></i> Home</a>
            <a href="about.php"><i class="fas fa-angle-right"></i> About</a>
            <a href="#"><i class="fas fa-angle-right"></i> Filter Search</a>
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