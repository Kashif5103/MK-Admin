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

// Retrieve the 'id' parameter from the POST request
$id = $_POST['id'];

// Prepare an SQL query to delete the record with the specified ID
$sql = "DELETE FROM Student WHERE id = $id";

// Execute the query and check if the operation was successful
if ($conn->query($sql) === TRUE) {
    // If the record is deleted successfully, output a success message
    echo "Record deleted successfully!";
} else {
    // If there is an error during deletion, output an error message with details
    echo "Error deleting record: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
