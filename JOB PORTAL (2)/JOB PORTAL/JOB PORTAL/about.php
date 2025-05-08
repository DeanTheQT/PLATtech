<?php
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
            background-color: #f9f9f9; /* Light background for contrast */
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
            display: inline-block; /* Make the button inline-block */
            padding: 12px 20px; /* Padding for the button */
            background-color: #007bff; /* Primary color */
            color: #fff; /* White text */
            border: none; /* Remove border */
            border-radius: 5px; /* Rounded corners */
            text-decoration: none; /* Remove underline */
            font-size: 1rem; /* Font size */
            transition: background-color 0.3s ease; /* Smooth transition */
        }

        .btn:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }
    </style>
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

<!--About-->
<div class="section-title">About Us</div>
<div class="about">
    <img src="images/about_us.jpg" alt="">
    <div class="box">
        <h3>Why Choose Us</h3>
        <p>JobHunt, the country's number 1 job site, has always been in the cutting edge of providing cutting edge products and services to both job seekers and employers. 
            In our continuing quest to ensure that our job seekers are given the best information in their job search and at the same time, highlight the strengths of our employers, 
            we are introducing an enhanced edition of our Company Profiles Homepage.</p>
        <p>JobHunt operates market-leading online employment marketplaces, including Jobstreet and Jobsdb in Asia. 
            JobHunt has been helping people live more fulfilling and productive working lives and helping organisations succeed for over 25 years.</p>
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
<script src="script.js"></script>
</body>
</html>