$(document).ready(function() {
    // Handle form submission for adding or updating records
    $('#insert_form').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        let formData = $(this).serialize(); // Serialize form data for sending via AJAX
        let id = $(this).attr('data-id'); // Get the record ID if updating
        let url = id ? 'update.php' : 'insert.php'; // Determine the URL based on action (add or update)

        // If updating, append the ID to the form data
        if (id) {
            formData += '&id=' + id;
        }

        // AJAX request to insert or update data
        $.ajax({
            url: url, // The URL for the request (insert or update)
            type: 'POST', // The HTTP method for the request
            data: formData, // The serialized form data
            success: function(response) {
                if (response.includes("Error:")) {
                    // If the response contains "Error", show an error message
                    $('#status_message').removeClass('alert-success').addClass('alert-danger').html(response).show();
                } else {
                    // If successful, show a success message
                    $('#status_message').removeClass('alert-danger').addClass('alert-success').html(response).show();
                    $('#insert_form')[0].reset(); // Reset form fields after submission
                    $('#insert_form').removeAttr('data-id'); // Remove the data-id attribute after successful submission
                    loadData(); // Reload data in the table
                }
            },
            error: function() {
                // If there's an error during the request, show an error message
                $('#status_message').removeClass('alert-success').addClass('alert-danger').html('Failed to submit data!').show();
            }
        });
    });

    // Function to load data and populate the table
    function loadData() {
        $.ajax({
            url: 'fetch.php', // The URL for fetching data
            type: 'GET', // The HTTP method for the request
            success: function(response) {
                $('#user_table').html(response); // Populate the table with the fetched data
            }
        });
    }

    // Handle click event for the Edit button
    $(document).on('click', '.btn-edit', function() {
        let id = $(this).data('id'); // Get the ID of the record to be edited

        // AJAX request to get the specific record data for editing
        $.ajax({
            url: 'get_single_record.php', // The URL for fetching a single record
            type: 'GET', // The HTTP method for the request
            data: { id: id }, // The data sent with the request (record ID)
            success: function(response) {
                let data = JSON.parse(response); // Parse the JSON response
                $('#name').val(data.name); // Populate the Name field
                $('#email').val(data.email); // Populate the Email field
                $('#password').val(data.password); // Populate the Password field
                $('#insert_form').attr('data-id', data.id); // Store the ID in the form's data-id attribute
            },
            error: function() {
                // If there's an error during fetching, show an error message
                $('#status_message').removeClass('alert-success').addClass('alert-danger').html('Failed to fetch data for editing!').show();
            }
        });
    });

    // Handle click event for the Delete button
    $(document).on('click', '.btn-delete', function() {
        let id = $(this).data('id'); // Get the ID of the record to be deleted

        // Confirm before deleting the record
        if (confirm('Are you sure you want to delete this record?')) {
            // AJAX request to delete the record
            $.ajax({
                url: 'delete.php', // The URL for deleting the record
                type: 'POST', // The HTTP method for the request
                data: { id: id }, // The data sent with the request (record ID)
                success: function(response) {
                    // If successful, show a success message
                    $('#status_message').removeClass('alert-danger').addClass('alert-success').html(response).show();
                    loadData(); // Reload data in the table
                },
                error: function() {
                    // If there's an error during deletion, show an error message
                    $('#status_message').removeClass('alert-success').addClass('alert-danger').html('Failed to delete record!').show();
                }
            });
        }
    });

    // Check email availability on blur event
    $('#email').on('blur', function() {
        var email = $(this).val().trim();

        if (email != '') {
            $.ajax({
                url: "check_email.php",
                method: "POST",
                data: { email: email },
                success: function(response) {
                    if (response.trim() == 'taken') {
                        $('#email-status').html('<span style="color: red;">✖ Email Already registered</span>');
                    } else if (response.trim() == 'available') {
                        $('#email-status').html('<span style="color: green;">✔ Email Available</span>');
                    }
                },
                error: function() {
                    $('#email-status').html('<span style="color: red;">Error checking email</span>');
                }
            });
        } else {
            $('#email-status').html('');
        }
    });

    // Handle login form submission
    $('#login_form').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        let formData = $(this).serialize(); // Serialize form data for sending via AJAX

        // AJAX request to login
        $.ajax({
            url: 'login.php', // The URL for the login request
            type: 'POST', // The HTTP method for the request
            data: formData, // The serialized form data
            dataType: 'json', // Expecting JSON response
            success: function(response) {
                if (response.status === 'success') {
                    // If login is successful, redirect to index.html
                    window.location.href = 'index.html';
                } else {
                    // If there is an error, show the error message
                    $('#status_message').removeClass('alert-success').addClass('alert-danger').html(response.message).show();
                }
            },
            error: function() {
                // If there's an error during the request, show a generic error message
                $('#status_message').removeClass('alert-success').addClass('alert-danger').html('Failed to process login request.').show();
            }
        });
    });

    // Initial load of data when the document is ready
    loadData();
});
