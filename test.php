<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>MK Admin 2 - Buttons</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.5/css/buttons.dataTables.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

    <!-- Custom styles -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        /* Ensure table fits the page */
        .table-responsive {
            overflow-x: auto;
        }

        /* Make buttons fit properly on smaller screens */
        .btn {
            margin: 0.2rem;
        }

        /* Adjust table width */
        table.dataTable {
            width: 100% !important;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-0 font-weight-bold text-primary text-center">Website Details</h3>
            </div>
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary mt-3" onclick="window.location.href='add_web.php';">Add Website</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="userTable" width="100%" cellspacing="0">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Website Name</th>
                                <th>Domain Name</th>
                                <th>Hosting Company</th>
                                <th>Country</th>
                                <th class="action-col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="user_table">
                            <!-- User data will be appended here dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/dataTables.buttons.min.js"></script>

    <!-- JS libraries for export buttons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.0/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.5/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.5/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.print.min.js"></script>

    <!-- Custom scripts -->
    <script src="js/sb-admin-2.min.js"></script>

    <script>
        // Function to fetch and display user data
        function loadUserData() {
            fetch('fetch_web.php') // Fetch data from PHP file
                .then(response => response.json())
                .then(data => {
                    const userTable = document.getElementById('user_table');
                    userTable.innerHTML = ''; // Clear existing data

                    data.forEach(website => {
                        userTable.innerHTML += `
                            <tr>
                                <td>${website.id}</td>
                                <td><a href="cards.php?id=${website.id}">${website.website_name}</a></td>
                                <td>${website.domain_name}</td>
                                <td>${website.hosting_company}</td>
                                <td>${website.country}</td>
                                <td class="action-col">
                                    <button class="btn btn-sm btn-edit btn-warning" onclick="editUser(${website.id})">
                                        <i class="ri-file-edit-fill"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-delete btn-danger" onclick="confirmDelete(${website.id})">
                                        <i class="ri-delete-bin-5-fill"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        `;
                    });

                    // Reinitialize DataTable after data is loaded
                    $('#userTable').DataTable().destroy(); // Destroy the previous instance
                    $('#userTable').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            {
                                extend: 'copyHtml5',
                                exportOptions: {
                                    columns: ':not(.action-col)' // Exclude 'Action' column
                                }
                            },
                            {
                                extend: 'csvHtml5',
                                exportOptions: {
                                    columns: ':not(.action-col)' // Exclude 'Action' column
                                }
                            },
                            {
                                extend: 'excelHtml5',
                                exportOptions: {
                                    columns: ':not(.action-col)' // Exclude 'Action' column
                                }
                            },
                            {
                                extend: 'pdfHtml5',
                                exportOptions: {
                                    columns: ':not(.action-col)' // Exclude 'Action' column
                                },
                                customize: function (doc) {
                                    doc.pageMargins = [20, 20, 20, 20]; // Adjust margins
                                    doc.content[0].layout = 'lightHorizontalLines'; // Add lines between rows
                                }
                            },
                            {
                                extend: 'print',
                                exportOptions: {
                                    columns: ':not(.action-col)' // Exclude 'Action' column
                                },
                                customize: function (win) {
                                    $(win.document.body).find('table').css('font-size', '10px'); // Adjust font size
                                    $(win.document.body).find('table').css('width', '100%'); // Ensure table width fits
                                    $(win.document.body).find('table').addClass('compact'); // Add compact class for better fit
                                    $(win.document.body).find('table').wrap('<div style="text-align: center;"></div>'); // Center the table
                                }
                            }
                        ],
                        // Enable searching and pagination
                        searching: true,
                        paging: true
                    });
                })
                .catch(error => console.error('There was an issue fetching data:', error));
        }

        // Load user data on page load
        document.addEventListener('DOMContentLoaded', loadUserData);

        // Function to handle edit action
        function editUser(userId) {
            window.location.href = `charts.php?id=${userId}`;
        }

        // Function to confirm and delete user
        function confirmDelete(userId) {
            if (window.confirm('Are you sure you want to delete this website?')) {
                deleteUser(userId);
            }
        }

        // Function to handle delete action
        function deleteUser(userId) {
            fetch('del_web.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${userId}`, // Pass the website ID to delete
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadUserData(); // Reload table after deletion
                } else {
                    alert('Error deleting the website.');
                }
            })
            .catch(error => console.error('Error deleting website:', error));
        }
    </script>
</body>

</html>
