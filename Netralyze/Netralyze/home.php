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

// Fetch job posts
$sql = "SELECT job_posts.post_id, job_posts.post_title, job_posts.post_description, users.name, users.email, users.profile_image_path 
        FROM job_posts 
        JOIN users ON job_posts.email = users.email 
        ORDER BY job_posts.post_id DESC";
$result = $conn->query($sql);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <link rel="stylesheet" href="home.css">
    <style>

        .profile-image {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 15px;
            flex: 1;
        }

        .profile-image:hover{
            -webkit-filter: brightness(70%);
        }

        .post-details {
            flex: 1;
        }

        .post-title {
            font-size: 18px;
            font-weight: bold;
            color: black;
        }

        .post-description {
            margin-top: 5px;
            color: black;
        }

        .underline:hover{
            text-decoration:underline;
            text-decoration-color:black;
        }

        .poster-name {
            font-size: 16px;
            font-weight: bold;
            color: black; 
        }

        .poster-email {
            font-size: 14px;
            color: #555;
        }

        .job-post {
            display: flex;
            margin: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            position: relative;
            width: 800px;
            margin: 10px auto;
            overflow: visible;
        }

        .Unavail{
            display: flex;
            margin: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            position: relative;
            width: 50%;
            margin: 0% auto;
        }
        
        .post-content {
            display: flex; /* This will allow the image and post details to be side by side */
            flex: 1; /* Allow it to take the full width */
            z-index: 1;
        }

        .dropdown {
            position: absolute;
            right: 15px;
            top: 15px;
        }


        .dropdown-content {
            display: none; /* Hidden by default */
            position: absolute; /* Positioned relative to the nearest positioned ancestor */
            background-color: white; /* Background color for visibility */
            min-width: 160px; /* Minimum width of the dropdown */
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); /* Shadow for depth */
            z-index: 1; /* Ensures it appears above other content */
        }

        .dropdown:hover .dropdown-content {
            display: block; /* Show dropdown on hover */
        }


        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        /* Styling for the job post editing section */
        .editing {
            display: none; /* Keep it hidden by default */
            margin-top: 10px; /* Space above the editing section */
            padding: 10px; /* Padding around the editing section */
            border: 1px solid #ccc; /* Border around the editing section */
            border-radius: 5px; /* Rounded corners */
            background-color: #f7f7f7; /* Light background color */
        }

        /* Input fields styling */
        .editing input[type="text"],
        .editing textarea {
            width: 100%; /* Full width */
            padding: 10px; /* Padding inside the input */
            margin: 5px 0; /* Margin above and below */
            border: 1px solid #ccc; /* Border */
            border-radius: 4px; /* Rounded corners */
            font-size: 16px; /* Font size */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            transition: border-color 0.3s; /* Smooth transition for border color */
        }

        /* Focus effect for input fields */
        .editing input[type="text"]:focus,
        .editing textarea:focus {
            border-color: #007bff; /* Change border color on focus */
            outline: none; /* Remove default outline */
        }

        /* Textarea specific styling */
        .editing textarea {
            height: 100px; /* Fixed height for textarea */
            resize: vertical; /* Allow vertical resizing */
        }
    
        /* Button styling */
        .editing-button {
            background-color: #007bff; /* Button background color */
            color: white; /* Button text color */
            padding: 10px 15px; /* Padding inside the button */
            border: none; /* No border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
            font-size: 16px; /* Font size */
            transition: background-color 0.3s, transform 0.3s; /* Smooth transition */
            margin-right: 5px; /* Space between buttons */
        }

        /* Button hover effect */
        .editing-button:hover {
            background-color: #0056b3; /* Darker blue on hover */
            transform: translateY(-2px); /* Slight lift effect */
        }

        /* Button disabled state */
        .editing-button:disabled {
            background-color: #cccccc; /* Gray background */
            cursor: not-allowed; /* Not allowed cursor */
        }
        
        /* Cancel button styling */
        .editing-button.cancel {
            background-color: #dc3545; /* Red background color for cancel button */
        }

        /* Button hover effect */
        .editing-button:hover {
            background-color: #0056b3; /* Darker blue on hover */
            transform: translateY(-2px); /* Slight lift effect */
        }

        /* Cancel button hover effect */
        .editing-button.cancel:hover {
            background-color: #c82333; /* Darker red on hover */
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

        /* From Uiverse.io by satyamchaudharydev */ 
        /* inspired form gumroad website */
        .btn {
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

        .home-content{
            padding-top: 10px;
            padding-bottom: 10px;
            background: url(images/background.jpg), rgba(0, 0, 0, .7) no-repeat;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-blend-mode: color;
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


<!--Home-->
<div class="home-content">
    <img src="images/about_us.jpg" alt="">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="job-post" id="post-<?php echo $row['post_id']; ?>">
                <div class="post-content">
                <a href="<?php echo (isset($_SESSION['email']) && $_SESSION['email'] === $row['email']) ? 'profile.php' : 'user.php?email=' . urlencode($row['email']); ?>" class="underline">
                        <img src="<?php echo $row['profile_image_path'] ? htmlspecialchars($row['profile_image_path']) : 'https://sbcf.fr/wp-content/uploads/2018/03/sbcf-default-avatar.png'; ?>" alt="Profile Image" class="profile-image">
                  
                    <div class="post-details">
                        <div class="poster-name"><?php echo htmlspecialchars($row['name']); ?></div>
                        <div class="poster-email"><?php echo htmlspecialchars($row['email']); ?></div>
                    </a>
                        <br>
                        <div class="post-title" id="title-<?php echo $row['post_id']; ?>"><?php echo htmlspecialchars($row['post_title']); ?></div>
                        <div class="post-description" id="description-<?php echo $row['post_id']; ?>"><?php echo htmlspecialchars($row['post_description']); ?></div>
                    
                        <!-- Editing Section (Always present but hidden by default) -->
                        <div class="editing" id="edit-section-<?php echo $row['post_id']; ?>" style="display: none;">
                            <input type="text" id="edit-title-<?php echo $row['post_id']; ?>" value="<?php echo htmlspecialchars($row['post_title']); ?>">
                            <textarea id="edit-description-<?php echo $row['post_id']; ?>"><?php echo htmlspecialchars($row['post_description']); ?></textarea>
                            <button class="editing-button" onclick="savePost(<?php echo $row['post_id']; ?>)">Save</button>
                            <button class="editing-button cancel" onclick="cancelEdit(<?php echo $row['post_id']; ?>)">Cancel</button>
                        </div>
                        
                        <!-- Dropdown for Edit/Delete (Only visible to the post owner) -->
                        <?php if (isset($_SESSION['email']) && $_SESSION['email'] === $row['email']): ?>
                            <div class="dropdown">
                                <button class="dropbtn">...</button>
                                <div class="dropdown-content">
                                    <a href="#" onclick="editPost(<?php echo $row['post_id']; ?>)">Edit</a>
                                    <a href="#" onclick="deletePost(<?php echo $row['post_id']; ?>)">Delete</a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="Unavail">
            <h3 style="margin:0% auto;">Nothing is Posted yet : )</h3>
        </div>
    <?php endif; ?>
</div>
<!--Home-->

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
<script>
    function editPost(postId) {
        document.getElementById('edit-section-' + postId).style.display = 'block';
        document.getElementById('title-' + postId).style.display = 'none';
        document.getElementById('description-' + postId).style.display = 'none';
    }

    function savePost(postId) {
        const title = document.getElementById('edit-title-' + postId).value;
        const description = document.getElementById('edit-description-' + postId).value;

        // AJAX call to update the post in the database
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_post.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('title-' + postId).innerText = title;
                document.getElementById('description-' + postId).innerText = description;
                document.getElementById('edit-section-' + postId).style.display = 'none';
                document.getElementById('title-' + postId).style.display = 'block';
                document.getElementById('description-' + postId).style.display = 'block';
            } else {
                console.error('Error updating post:', xhr.responseText);
            }
        };
        xhr.send('post_id=' + postId + '&post_title=' + encodeURIComponent(title) + '&post_description=' + encodeURIComponent(description));
    }

    function deletePost(postId) {
        if (confirm('Are you sure you want to delete this post?')) {
            // AJAX call to delete the post from the database
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete_post.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('post-' + postId).remove();
                } else {
                    console.error('Error deleting post:', xhr.responseText);
                }
            };
            xhr.send('post_id=' + postId);
        }
    }
    function cancelEdit(postId) {
        document.getElementById('edit-section-' + postId).style.display = 'none';
        document.getElementById('title-' + postId).style.display = 'block';
        document.getElementById('description-' + postId).style.display = 'block';
    }

    function toggleDropdown() {
        document.getElementById("dropdown-menu").classList.toggle("show");
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches('#menu-btn')) {
            var dropdowns = document.getElementsByClassName("dropdown-content2");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
</script>
</body>
</html>