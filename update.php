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

// Check if the 'id' field is set in the POST request
if (isset($_POST['id'])) {
    // Retrieve form values sent via POST method
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare an SQL statement to update the record with the given ID
    $sql = "UPDATE Student SET name='$name', email='$email', password='$password' WHERE id=$id";

    // Execute the SQL query and check if the update was successful
    if ($conn->query($sql) === TRUE) {
        // If the record is updated successfully, output a success message
        echo "Record updated successfully!";
    } else {
        // If there is an error updating the record, output the error message
        echo "Error updating record: " . $conn->error;
    }
} else {
    // If the 'id' is not provided in the POST request, output an error message
    echo "Error: ID not provided!";
}

// Close the database connection
$conn->close();
?>
