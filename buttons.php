<?php
// Start session
session_start();

// Set session timeout duration (e.g., 30 minutes)
$session_timeout = 30 * 60; // 30 minutes in seconds

// Check if the user is logged in
if (isset($_SESSION['login_time'])) {
    // Check if session has expired
    if ((time() - $_SESSION['login_time']) > $session_timeout) {
        // Session has expired, destroy session and redirect to login page
        session_unset();
        session_destroy();
        header("Location: login.php?message=session_expired");
        exit();
    } else {
        // Session is still active, update login time to extend session
        $_SESSION['login_time'] = time();
    }
} else {
    // User is not logged in, redirect to login page
    header("Location: login.html");
    exit();
}

// Include database connection
include 'connection.php'; // Adjust the file path as needed

?>
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
    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">


    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Include Bootstrap CSS for styling -->
    <link rel="stylesheet" href="css/bootstrap.css">

    <!-- Page title -->

    <!-- Custom CSS for header, form, details container, and buttons -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            text-align: center;
            color: #333;
        }

        .table-responsive {
            margin-top: 20px;
        }

        .action-col {
            text-align: center;
        }

        .custom-button {
            margin-right: 20px;
        }

        .btn-edit {
            background-color: #17a2b8;
            color: white;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn-sm {
            padding: 5px 10px;
            margin-right: 5px;
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-0">
                    <img src="mk_logo%20(2).webp" alt="Logo" style="width: 60px; height: 60px;">
                </div>
                <div class="sidebar-brand-text mx-3">MK UserNest</div>
            </a>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="buttons.php">
                    <i class="fas fa-user-circle"></i> <!-- User logo icon -->
                    <span>User</span>
                </a>
            </li>
            <!-- Divider -->

            <!-- <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseForm"
                    aria-expanded="true" aria-controls="collapseForm">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Form</span>
                </a>
                <div id="collapseForm" class="collapse" aria-labelledby="headingForm" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="buttons.html">Form</a>
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInputs"
                    aria-expanded="true" aria-controls="collapseInputs">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Inputs</span>
                </a>
                <div id="collapseInputs" class="collapse" aria-labelledby="headingInputs" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="cards.html">Input</a>
                    </div>
                </div>
            </li> -->


            <!-- Nav Item - Utilities Collapse Menu -->
            <!-- <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Utilities</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Utilities:</h6>
                        <a class="collapse-item" href="utilities-color.html">Colors</a>
                        <a class="collapse-item" href="utilities-border.html">Borders</a>
                        <a class="collapse-item" href="utilities-animation.html">Animations</a>
                        <a class="collapse-item" href="utilities-other.html">Other</a>
                    </div>
                </div>
            </li> -->

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->


            <!-- Nav Item - Charts -->
            <!-- <li class="nav-item">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Charts</span></a>
            </li> -->

            <!-- Nav Item - Tables -->
            <!-- Add Location Navigation Item -->
            <div class="sidebar-heading">
                Pages
            </div>

            <!-- Nav Item - Pages Collapse Menu -->




            <!-- Nav Item - Tables -->
            <!-- Add Location Navigation Item -->
            <li class="nav-item">
                <a class="nav-link" href="login.html">
                    <i class="fas fa-sign-in-alt"></i> <!-- Login icon -->
                    <span>Login</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="register.html">
                    <i class="fas fa-user-plus"></i> <!-- Register icon -->
                    <span>Register</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 7, 2019</div>
                                        $290.29 has been deposited into your account!
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 2, 2019</div>
                                        Spending Alert: We've noticed unusually high spending for your account.
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter">7</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_1.svg" alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                            problem I've been having.</div>
                                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_2.svg" alt="...">
                                        <div class="status-indicator"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">I have the photos that you ordered last month, how
                                            would you like them sent to you?</div>
                                        <div class="small text-gray-500">Jae Chun · 1d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_3.svg" alt="...">
                                        <div class="status-indicator bg-warning"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Last month's report looks great, I am very happy with
                                            the progress so far, keep up the good work!</div>
                                        <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                            told me that people say this to all dogs, even if they aren't good...</div>
                                        <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Muhammad Kashif</span>
                                <img class="img-profile rounded-circle" src="img/M kashif.jpeg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <!-- <div class="container-fluid"> -->

                <!-- Page Heading -->
                <!-- <h1 class="h3 mb-4 text-gray-800">Buttons</h1> -->

                <!-- <div class="row"> -->

                <!-- Main container and header for the application -->
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
                                    buttons: [{
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
                                            customize: function(doc) {
                                                doc.pageMargins = [40, 40, 20, 20]; // Adjust margins
                                                doc.content[0].layout = 'lightHorizontalLines'; // Add lines between rows
                                            }
                                        },
                                        {
                                            extend: 'print',
                                            exportOptions: {
                                                columns: ':not(.action-col)' // Exclude 'Action' column
                                            },
                                            customize: function(win) {
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

                <!-- <script>
                    // Function to fetch and display user data
                    function loadUserData() {
                        fetch('fetch_web.php') // Fetch data from the PHP file
                            .then(response => response.json())
                            .then(data => {
                                const userTable = document.getElementById('user_table');
                                userTable.innerHTML = ''; // Clear existing data

                                data.forEach(website => {
                                    userTable.innerHTML += `
                                        <tr>
                                            <td>${website.id}</td>
                                            <td>
                                                <a href="cards.php?id=${website.id}">${website.website_name}</a>
                                            </td>
                                            <td>${website.domain_name}</td>
                                            <td>${website.hosting_company}</td>
                                            <td>${website.country}</td>
                                            <td>
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
                            })
                            .catch(error => console.error('There has been a problem with your fetch operation:', error));
                    }

                    // Load user data on page load
                    document.addEventListener('DOMContentLoaded', loadUserData);

                    // Function to handle the edit action
                    function editUser(userId) {
                        window.location.href = `charts.php?id=${userId}`;
                    }

                    // Function to confirm and delete user
                    function confirmDelete(userId) {
                        // Using a single confirmation popup
                        if (window.confirm('Are you sure you want to delete this website?')) {
                            deleteUser(userId);
                        }
                    }

                    // Function to handle the delete action
                    function deleteUser(userId) {
                        fetch(`del_web.php`, {
                                method: 'POST', // Use POST for PHP
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded', // Form URL encoded
                                },
                                body: `id=${userId}`, // Pass the website ID to delete
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    loadUserData(); // Reload the table after deletion
                                } else {
                                    alert('Error deleting the website.');
                                }
                            })
                            .catch(error => console.error('Error deleting the website:', error));
                    }
                </script> -->




                <a class="scroll-to-top rounded" href="#page-top">
                    <i class="fas fa-angle-up"></i>
                </a>

                <!-- Logout Modal-->
                <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">Select "Logout" below if you are ready to end your current session.
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                <a class="btn btn-primary" href="login.html">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Your Website 2024</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->
                <!-- Script for the data table -->
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>


                <!-- Bootstrap core JavaScript-->
                <!-- jQuery and Bootstrap inclusion -->
                <!-- <script src="vendor/jquery/jquery.min.js"></script> -->
                <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

                <!-- Core plugin JavaScript -->
                <!-- <script src="vendor/jquery-easing/jquery.easing.min.js"></script> -->

                <!-- Custom scripts for all pages -->
                <!-- <script src="js/sb-admin-2.min.js"></script> -->

                <!-- Custom AJAX functionality (ensure this is loaded after jQuery and Bootstrap) -->
                <!-- <script src="js/myajax.js"></script> -->

</body>

</html>