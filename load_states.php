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

// Retrieve the country ID from the GET request and ensure it's an integer
$country_id = intval($_GET['country_id']); 

// Prepare an SQL statement to select states based on the country ID
$stmt = $conn->prepare("SELECT id, name FROM states WHERE country_id = ?");
$stmt->bind_param("i", $country_id); // Bind the country ID parameter to the SQL statement
$stmt->execute(); // Execute the prepared statement
$result = $stmt->get_result(); // Get the result set

// Initialize an empty array to store the states' data
$states = [];

// Loop through the result set and fetch each row as an associative array
while ($row = $result->fetch_assoc()) {
    // Add each row (state data) to the $states array
    $states[] = $row;
}

// Convert the $states array to JSON format and output it
echo json_encode($states);

// Close the prepared statement and the database connection
$stmt->close();
$conn->close();
?>
