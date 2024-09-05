<?php
// update_user.php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "catmarketing";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$id = isset($_POST['id']) ? intval($_POST['id']) : 0; // User ID
$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING); // Sanitize name
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL); // Validate email
$phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING); // Sanitize phone
$country = intval($_POST['country']); // Get country ID
$state = intval($_POST['state']); // Get state ID
$city = intval($_POST['city']); // Get city ID
$password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null; // Hash password if provided

// Handle file upload
$image = $_FILES['profile_image']['name']; // Get uploaded image name
$image_tmp = $_FILES['profile_image']['tmp_name']; // Get temporary image file path
$image_path = $image ? "uploads/" . basename($image) : null; // Set image path if an image is uploaded

if ($image) {
    move_uploaded_file($image_tmp, $image_path); // Move uploaded file to target directory
} else {
    // Fetch the current image path if no new image is uploaded
    $stmt = $conn->prepare("SELECT profile_image FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $image_path = $user['profile_image']; // Retain current image path
    $stmt->close();
}

// Prepare the SQL statement for updating user data
if ($password) {
    // If password is provided
    $sql = "UPDATE users SET name=?, email=?, phone=?, country=?, state=?, city=?, profile_image=?, password=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $name, $email, $phone, $country, $state, $city, $image_path, $password, $id);
} else {
    // If password is not provided
    $sql = "UPDATE users SET name=?, email=?, phone=?, country=?, state=?, city=?, profile_image=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $name, $email, $phone, $country, $state, $city, $image_path, $id);
}

// Execute the statement
if ($stmt->execute()) {
    header("Location: buttons.html"); // Redirect to the user table after successful update
    exit();
} else {
    echo "Error: " . $stmt->error; // Display error if update fails
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
