<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web_info";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve and validate the `id` parameter from the URL
$website_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($website_id <= 0) {
    die("Invalid website ID provided. Please go back and try again.");
}

// Ensure the website ID exists in the `website_info` table
$website_check_sql = "SELECT id FROM website_info WHERE id = $website_id";
$website_check_result = $conn->query($website_check_sql);

if ($website_check_result->num_rows === 0) {
    die("Invalid website ID: No matching record found.");
}

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $user_name = isset($_POST['user_name']) ? $conn->real_escape_string($_POST['user_name']) : '';
    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
    $password = isset($_POST['password']) ? $conn->real_escape_string($_POST['password']) : '';

    // Check if all fields are filled
    if ($user_name && $email && $password) {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if the email already exists for the same website_id
        $check_email_sql = "SELECT * FROM user_info WHERE email = '$email' AND website_id = $website_id";
        $check_email_result = $conn->query($check_email_sql);

        if ($check_email_result->num_rows > 0) {
            echo "<script>alert('This email is already registered for this website.'); window.location.href='add_user.php?id=$website_id';</script>";
        } else {
            // Insert the new user into the `user_info` table
            $insert_sql = "INSERT INTO user_info (user_name, email, password, website_id) 
                           VALUES ('$user_name', '$email', '$hashed_password', $website_id)";
            if ($conn->query($insert_sql) === TRUE) {
                // Redirect to cards.php after successful registration
                header("Location: cards.php?id=$website_id");
                exit();
            } else {
                echo "Error adding user: " . $conn->error;
            }
        }
    } else {
        echo "Required form fields are missing.";
    }
}

$conn->close();
?>
