<?php
// Start session to store user login information
session_start();

// Include database connection
require 'connection.php'; // Assuming you have a file for database connection

$response = array();

// Check if form is submitted via POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve email and password from the POST request
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Query the database to check if the email exists
    $query = "SELECT * FROM admin WHERE email = '$email' LIMIT 1"; // Change 'users' to your table name
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session for the logged-in user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            
            // Return success response
            $response['status'] = 'success';
            $response['message'] = 'Login successful';
        } else {
            // Password doesn't match
            $response['status'] = 'error';
            $response['message'] = 'Incorrect password';
        }
    } else {
        // Email doesn't exist
        $response['status'] = 'error';
        $response['message'] = 'Email not found';
    }
    
    // Close database connection
    mysqli_close($conn);
    
    // Return JSON response
    echo json_encode($response);
}
?>
