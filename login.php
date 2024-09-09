<?php
session_start(); // Start session management

// Include database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web_info";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed.']));
}

// Check if form data is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the SQL query to fetch user details
    $stmt = $conn->prepare("SELECT id, name, password FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    // Check if a user with this email exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            echo json_encode(['status' => 'success']);
        } else {
            // Password is incorrect
            echo json_encode(['status' => 'error', 'message' => 'Incorrect password.']);
        }
    } else {
        // No user found with this email
        echo json_encode(['status' => 'error', 'message' => 'No account found with that email address.']);
    }

    // Close statement and connection
    $stmt->close();
}

$conn->close();
?>
