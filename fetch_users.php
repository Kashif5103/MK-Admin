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
    // If the connection fails, output an error message and stop execution
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch user details along with the names of the country, state, and city
$query = "
    SELECT 
        users.id, 
        users.name, 
        users.email, 
        users.phone, 
       
        users.profile_image, 
        countries.name as country, 
        states.name as state, 
        cities.name as city 
    FROM users
    LEFT JOIN countries ON users.country = countries.id
    LEFT JOIN states ON users.state = states.id
    LEFT JOIN cities ON users.city = cities.id
";

// Execute the query and get the result set
$result = $conn->query($query);

// Initialize an empty array to store the users' data
$users = [];

// Loop through the result set and fetch each row as an associative array
while ($row = $result->fetch_assoc()) {
    // Add each row (user data) to the $users array
    $users[] = $row;
}

// Convert the $users array to JSON format and output it
echo json_encode($users);

// Close the database connection
$conn->close();
?>
