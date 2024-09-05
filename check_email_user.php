<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "catmarketing";

// Establish a connection to the MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    // If the connection fails, output an error message and stop execution
    die("Connection failed: " . $conn->connect_error);
}

// Check if the email is provided via POST
if (isset($_POST['email'])) {
    $email = $conn->real_escape_string($_POST['email']);
    
    // Query the database to check if the email exists
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    // Output whether the email exists or is available
    if ($result->num_rows > 0) {
        echo "exists";
    } else {
        echo "available";
    }
}

// Close the database connection
$conn->close();
?>
