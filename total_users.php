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
    die("Connection failed: " . $conn->connect_error);
}

// Query to count the total number of users
$sql = "SELECT COUNT(*) AS total_users FROM users";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo $row['total_users'];
} else {
    echo "0";
}

// Close the database connection
$conn->close();
?>
