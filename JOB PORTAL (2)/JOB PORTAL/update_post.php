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

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['post_id'];
    $post_title = $_POST['post_title'];
    $post_description = $_POST['post_description'];

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE job_posts SET post_title = ?, post_description = ? WHERE post_id = ?");
    $stmt->bind_param("ssi", $post_title, $post_description, $post_id);

    if ($stmt->execute()) {
        echo "Post updated successfully.";
    } else {
        echo "Error updating post: " . $conn->error;
    }

    $stmt->close();
}
$conn->close();
?>