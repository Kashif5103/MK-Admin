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

// Get the state ID from the GET request and ensure it is an integer
$state_id = intval($_GET['state_id']); 

// Prepare an SQL statement to fetch cities based on the provided state ID
$stmt = $conn->prepare("SELECT id, name FROM cities WHERE state_id = ?");
$stmt->bind_param("i", $state_id); // Bind the state ID parameter as an integer
$stmt->execute(); // Execute the prepared statement
$result = $stmt->get_result(); // Get the result set from the executed statement

// Initialize an empty array to store the cities' data
$cities = [];

// Loop through the result set and fetch each row as an associative array
while ($row = $result->fetch_assoc()) {
    // Add each row (city data) to the $cities array
    $cities[] = $row;
}

// Convert the $cities array to JSON format and output it
echo json_encode($cities);

// Close the prepared statement and the database connection
$stmt->close();
$conn->close();
?>
