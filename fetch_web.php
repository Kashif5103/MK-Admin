<?php
// Include database connection file
require 'connection.php';

// SQL query to fetch data from the website_info table
$sql = "SELECT id, website_name, domain_name, hosting_company, country FROM website_info";
$result = $conn->query($sql);

$websites = array(); // Initialize an empty array to store the website information

if ($result->num_rows > 0) {
    // Fetch all rows and store them in the $websites array
    while($row = $result->fetch_assoc()) {
        $websites[] = $row;
    }
} else {
    $websites = []; // Return an empty array if no data is found
}

// Close the connection
$conn->close();

// Return the data as a JSON response
header('Content-Type: application/json');
echo json_encode($websites);
?>
