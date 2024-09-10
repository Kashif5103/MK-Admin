<?php
// Start session to store user login information
session_start();

// Include database connection
require 'connection.php'; // Database connection file

$response = array();

// Check if form is submitted via POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve email and password from POST request
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query the database to check if the email exists in the admin table
    $query = "SELECT * FROM admin WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables for the logged-in user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            // Return success response
            $response['status'] = 'success';
            $response['message'] = 'Login successful';
        } else {
            // Incorrect password
            $response['status'] = 'error';
            $response['message'] = 'Incorrect password';
        }
    } else {
        // Email not found
        $response['status'] = 'error';
        $response['message'] = 'Email not found';
    }

    // Close the database connection
    mysqli_close($conn);

    // Return JSON response
    echo json_encode($response);
}
?>
