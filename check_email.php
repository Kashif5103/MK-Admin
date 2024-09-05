<?php
// check_email.php

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "catmarketing";

// Establish a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // Prepare a statement to check if the email exists in the admin table
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Email already exists
        echo '<span style="color: red;">Email already exists</span>';
    } else {
        // Email is available
        // echo '<span style="color: green;">Email available</span>';
    }

    $stmt->close();
}

$conn->close();
?>
