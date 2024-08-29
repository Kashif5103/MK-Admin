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

// Prepare an SQL query to select all records from the Student table
$sql = "SELECT id, name, email, password FROM Student";
$result = $conn->query($sql); // Execute the SQL query

// Check if the query returned any rows
if ($result->num_rows > 0) {
    // Loop through each row in the result set
    while($row = $result->fetch_assoc()) {
        // Output each row as a table row (<tr>) with the respective data in table cells (<td>)
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['password']}</td>
                <td>
                    <!-- Buttons for editing and deleting the record -->
                    <button class='btn btn-edit' data-id='{$row['id']}'>Edit</button>
                    <button class='btn btn-delete' data-id='{$row['id']}'>Delete</button>
                </td>
              </tr>";
    }
} else {
    // If no records are found, display a message indicating that
    echo "<tr><td colspan='5'>No data found</td></tr>";
}

// Close the database connection
$conn->close();
?>
