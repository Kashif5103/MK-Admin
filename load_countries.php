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

// Execute an SQL query to retrieve the country IDs and names
$result = $conn->query("SELECT id, name FROM countries");

// Initialize an empty array to store the countries' data
$countries = [];

// Loop through the result set and fetch each row as an associative array
while ($row = $result->fetch_assoc()) {
    // Add each row (country data) to the $countries array
    $countries[] = $row;
}

// Convert the $countries array to JSON format and output it
echo json_encode($countries);

// Close the database connection
$conn->close();
?>
