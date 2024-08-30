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
    die(json_encode(array("status" => "error", "message" => "Connection failed: " . $conn->connect_error)));
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    // Prepare and execute the SQL statement to fetch the user data
    $sql = "SELECT * FROM Student WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User exists, now verify the password
        $row = $result->fetch_assoc();
        if ($password === $row['password']) { // Direct comparison since password is not hashed
            // Login successful
            echo json_encode(array("status" => "success", "message" => "Login successful"));
        } else {
            // Invalid password
            echo json_encode(array("status" => "error", "message" => "Invalid password."));
        }
    } else {
        // No user found with this email
        echo json_encode(array("status" => "error", "message" => "No user found with this email address."));
    }
}

// Close the database connection
$conn->close();
?>
