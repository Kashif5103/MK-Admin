<?php
// Include your database connection file
include "connection.php";
if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // Prepare the SQL statement to check if the email already exists
    $stmt = $conn->prepare("SELECT id FROM Student WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if the email is already registered
    if ($stmt->num_rows > 0) {
        echo 'taken';  // Email is already registered
    } else {
        echo 'available';  // Email is available
    }

    // Close the statement and connection
    $stmt->close();
}
$conn->close();
?>




