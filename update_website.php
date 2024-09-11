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


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the data from the form
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $website_name = mysqli_real_escape_string($conn, $_POST['website_name']);
    $domain_name = mysqli_real_escape_string($conn, $_POST['domain_name']);
    $hosting_company = mysqli_real_escape_string($conn, $_POST['hosting_company']);
    $country_name = mysqli_real_escape_string($conn, $_POST['country']);

    // Prepare an update query
    $query = "UPDATE website_info SET 
              website_name = '$website_name', 
              domain_name = '$domain_name', 
              hosting_company = '$hosting_company', 
              country = '$country_name' 
              WHERE id = '$id'";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        // Redirect to a success page or another page
        header("Location: buttons.php"); // Change to your desired redirect page
        exit();
    } else {
        // Error handling
        echo "Error updating record: " . mysqli_error($conn);
    }

    // Close the connection
    mysqli_close($conn);
} else {
    // Redirect if accessed directly without form submission
    header("Location: index.php"); // Redirect to the main page or another appropriate page
    exit();
}
?>
