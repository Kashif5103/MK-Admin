<?php
// Start session
session_start();

// Set session timeout duration (e.g., 30 minutes)
$session_timeout = 30 * 60; // 30 minutes in seconds

// Check if the user is logged in
if (isset($_SESSION['login_time'])) {
    // Check if session has expired
    if ((time() - $_SESSION['login_time']) > $session_timeout) {
        // Session has expired, destroy session and redirect to login page
        session_unset();
        session_destroy();
        header("Location: login.php?message=session_expired");
        exit();
    } else {
        // Session is still active, update login time to extend session
        $_SESSION['login_time'] = time();
    }
} else {
    // User is not logged in, redirect to login page
    header("Location: login.html");
    exit();
}

// Include database connection
include 'connection.php'; // Adjust the file path as needed

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