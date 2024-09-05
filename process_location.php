<?php
// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

// Retrieve the location type from the POST request
$location_type = $_POST['location_type'];

// Initialize a variable for error messages
$error_message = "";

// Handle the request based on the location type
switch ($location_type) {
    case 'country':
        // Sanitize and validate the country name
        $country_name = filter_var(trim($_POST['country_name']), FILTER_SANITIZE_STRING);

        if (!empty($country_name) && strlen($country_name) >= 2 && strlen($country_name) <= 255) {
            // Prepare an SQL statement to insert a new country
            $stmt = $conn->prepare("INSERT INTO countries (name) VALUES (?)");
            $stmt->bind_param("s", $country_name);

            // Execute the query and handle success or error
            if ($stmt->execute()) {
                echo "Country added successfully!";
            } else {
                error_log("MySQL Error: " . $stmt->error);
                $error_message = "Error: Could not add country.";
            }
        } else {
            $error_message = "Country name cannot be empty and must be between 2 and 255 characters.";
        }
        break;

    case 'state':
        // Sanitize the state name and get the country ID
        $state_name = filter_var(trim($_POST['state_name']), FILTER_SANITIZE_STRING);
        $country_id = intval($_POST['country_id']);

        if (!empty($state_name) && strlen($state_name) >= 2 && strlen($state_name) <= 255 && $country_id) {
            // Prepare an SQL statement to insert a new state
            $stmt = $conn->prepare("INSERT INTO states (name, country_id) VALUES (?, ?)");
            $stmt->bind_param("si", $state_name, $country_id);

            if ($stmt->execute()) {
                echo "State added successfully!";
            } else {
                error_log("MySQL Error: " . $stmt->error);
                $error_message = "Error: Could not add state.";
            }
        } else {
            $error_message = "State name cannot be empty, must be between 2 and 255 characters, and country must be selected.";
        }
        break;

    case 'city':
        // Sanitize the city name and get the state ID
        $city_name = filter_var(trim($_POST['city_name']), FILTER_SANITIZE_STRING);
        $state_id = intval($_POST['state_id']);

        if (!empty($city_name) && strlen($city_name) >= 2 && strlen($city_name) <= 255 && $state_id) {
            // Prepare an SQL statement to insert a new city
            $stmt = $conn->prepare("INSERT INTO cities (name, state_id) VALUES (?, ?)");
            $stmt->bind_param("si", $city_name, $state_id);

            if ($stmt->execute()) {
                echo "City added successfully!";
            } else {
                error_log("MySQL Error: " . $stmt->error);
                $error_message = "Error: Could not add city.";
            }
        } else {
            $error_message = "City name cannot be empty, must be between 2 and 255 characters, and state must be selected.";
        }
        break;

    default:
        $error_message = "Invalid location type selected.";
}

// Display error message if any
if (!empty($error_message)) {
    echo $error_message;
}

// Close the prepared statement and the database connection
if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>
