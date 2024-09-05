<?php
// delete_user.php

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "catmarketing";

// Establish a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    // If the connection failed, display an error message and terminate the script
    die("Connection failed: " . $conn->connect_error);
}

// Get the user ID from the URL, or default to 0 if not set
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Prepare the SQL statement to delete a user based on their ID
$sql = "DELETE FROM users WHERE id=?";
$stmt = $conn->prepare($sql);

// Bind the user ID parameter to the SQL statement
$stmt->bind_param("i", $id);

// Execute the prepared statement
if ($stmt->execute()) {
    // If execution was successful, create a success response
    $response = array("success" => true);
} else {
    // If execution failed, create an error response with the error message
    $response = array("success" => false, "error" => $stmt->error);
}

// Close the statement and database connection
$stmt->close();
$conn->close();

// Set the Content-Type header to application/json
header('Content-Type: application/json');

// Output the JSON-encoded response
echo json_encode($response);
?>
