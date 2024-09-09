<?php
// Include database connection
require 'connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the data from the form
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $website_name = mysqli_real_escape_string($conn, $_POST['website_name']);
    $domain_name = mysqli_real_escape_string($conn, $_POST['domain_name']);
    $hosting_company = mysqli_real_escape_string($conn, $_POST['hosting_company']);
    $country_name = mysqli_real_escape_string($conn, $_POST['country']);

    // Prepare an update query
    $query = "UPDATE website_info SET 
              website_name = '$website_name', 
              domain_name = '$domain_name', 
              hosting_company = '$hosting_company', 
              country = '$country_name' 
              WHERE id = '$id'";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        // Redirect to a success page or another page
        header("Location: buttons.html"); // Change to your desired redirect page
        exit();
    } else {
        // Error handling
        echo "Error updating record: " . mysqli_error($conn);
    }

    // Close the connection
    mysqli_close($conn);
} else {
    // Redirect if accessed directly without form submission
    header("Location: index.php"); // Redirect to the main page or another appropriate page
    exit();
}
?>
