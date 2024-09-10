<?php
session_start(); // Start the session

// Check if the user is logged in as admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.html");
    exit();
}

// Database connection settings
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "web_info"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the website ID from the query parameter
$website_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch website data from the database
$website_sql = "SELECT * FROM website_info WHERE id = $website_id";
$website_result = $conn->query($website_sql);

// Fetch user data related to the website
$user_sql = "SELECT * FROM user_info WHERE website_id = $website_id";
$user_result = $conn->query($user_sql);
?>
<?php
// Close connection
$conn->close();
?>