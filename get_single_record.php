<?php
// Database connection details
$servername = "localhost";
$username = "root"; // replace with your MySQL username
$password = ""; // replace with your MySQL password
$dbname = "catmarketing";

// Create a connection to the MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection to the database was successful
if ($conn->connect_error) {
    // If the connection fails, output an error message and stop execution
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the 'id' parameter from the GET request
$id = $_GET['id'];

// Prepare an SQL statement to select the record with the given ID
$sql = "SELECT id, name, email, password FROM Student WHERE id = $id";
$result = $conn->query($sql); // Execute the SQL query

// Check if the query returned any rows
if ($result->num_rows > 0) {
    // If a record is found, fetch the record as an associative array
    $row = $result->fetch_assoc();
    // Convert the array to a JSON object and output it
    echo json_encode($row);
} else {
    // If no record is found, output an empty JSON array
    echo json_encode([]);
}

// Close the database connection
$conn->close();
?>
