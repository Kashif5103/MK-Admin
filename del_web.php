<?php
// Include database connection
require 'connection.php';

// Check if the request method is POST and the ID is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    // Retrieve the website ID from the request
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    // Prepare a DELETE query
    $query = "DELETE FROM website_info WHERE id = '$id'";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        // Return success response in JSON
        echo json_encode(['success' => true]);
    } else {
        // Return error response in JSON
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }

    // Close the connection
    mysqli_close($conn);
} else {
    // Return error response if accessed without a valid ID
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>
