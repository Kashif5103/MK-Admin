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
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $name = isset($_POST['name']) ? filter_var($_POST['name'], FILTER_SANITIZE_STRING) : null;
    $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : null;
    $phone = isset($_POST['phone']) ? filter_var($_POST['phone'], FILTER_SANITIZE_STRING) : null;
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    $country = isset($_POST['country']) ? intval($_POST['country']) : null;
    $state = isset($_POST['state']) ? intval($_POST['state']) : null;
    $city = isset($_POST['city']) ? intval($_POST['city']) : null;

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = "uploads/" . basename($image);

        // Ensure the uploads directory exists
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        // Move uploaded file to the specified path
        if (!move_uploaded_file($image_tmp, $image_path)) {
            die("Failed to upload file.");
        }
    } else {
        die("No image file uploaded or upload error.");
    }

    // Check for duplicate email
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Email already exists!";
        $stmt->close();
        $conn->close();
        exit();
    }

    $stmt->close();

    // Prepare an SQL statement for inserting user data
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, country, state, city, profile_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssiiis", $name, $email, $phone, $password, $country, $state, $city, $image_path);

    // Execute the SQL statement and handle the result
    if ($stmt->execute()) {
        header("Location: buttons.html");
        exit(); // Ensure no further code is executed after redirection
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
