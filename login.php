<?php
// Start session to store user login information
session_start();

// Include database connection
include 'connection.php'; // Database connection file

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = array();

// Set session timeout duration (e.g., 30 minutes)
$session_timeout = 30 * 60; // 30 minutes in seconds

// Check if form is submitted via POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve email and password from POST request
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables for the logged-in user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['login_time'] = time(); // Set login time to the current time

            // Return success response and redirect
            $response['status'] = 'success';
            $response['message'] = 'Login successful';
            echo json_encode($response);
            header("Location: index.php"); // Redirect to the dashboard or home page
            exit();
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
    $stmt->close();
    $conn->close();
}
?>
