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

// Retrieve form values sent via POST method
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password']; // Note: Consider hashing passwords before storing

// Prepare an SQL statement to check if the email already exists in the database
$checkEmail = $conn->prepare("SELECT id FROM Student WHERE email = ?");
$checkEmail->bind_param("s", $email); // Bind the email parameter to the SQL query
$checkEmail->execute(); // Execute the query
$checkEmail->store_result(); // Store the result to check the number of rows

// Check if the email already exists in the database
if ($checkEmail->num_rows > 0) {
    // If the email exists, output an error message
    echo "Error: This email is already registered!";
} else {
    // If the email does not exist, prepare an SQL statement to insert a new record
    $stmt = $conn->prepare("INSERT INTO Student (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password); // Bind the form values to the SQL query

    // Execute the SQL statement to insert the new record
    if ($stmt->execute()) {
        // If the record is inserted successfully, output a success message
        echo "New record created successfully";
    } else {
        // If there is an error inserting the record, output the error message
        echo "Error: " . $stmt->error;
    }

    // Close the statement after execution
    $stmt->close();
}

// Close the email check statement
$checkEmail->close();

// Close the database connection
$conn->close();
?>
