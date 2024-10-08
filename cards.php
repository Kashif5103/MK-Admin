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


// Fetch website data
$sql = "SELECT * FROM website_info"; // Replace with your query to fetch web details
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>MK Admin 2 - Cards</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Custom CSS for header, form, details container, and buttons -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="file"],
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .btn-add-user {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            margin: 20px 0;
        }

        .btn-add-user:hover {
            background-color: #007bff;
        }

        .mt-4 {
            text-align: center;
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

                <div class="container mt-5">
                    <?php
                    // Database connection settings
                    $servername = "localhost";
                    $username = "root"; // Replace with your database username
                    $password = ""; // Replace with your database password
                    $dbname = "web_info"; // Replace with your database name

                    // Create connection
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Get the website ID from the query parameter
                    $website_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

                    // Ensure the ID is valid
                    if ($website_id <= 0) {
                        echo "<p>Invalid website ID.</p>";
                        exit();
                    }

                    // SQL query to get website details for the specific ID
                    $sql = "SELECT * FROM website_info WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $website_id); // "i" denotes the type of parameter, "i" for integer
                    $stmt->execute();
                    $website_result = $stmt->get_result();

                    // Check if the query was successful
                    if (!$website_result) {
                        echo "<p>Error fetching website details: " . $conn->error . "</p>";
                    } else {
                        // Check if there are any results
                        if ($website_result->num_rows > 0) {
                            // Output website data
                            $website = $website_result->fetch_assoc();
                            echo '<div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h3 class="m-0 font-weight-bold text-primary text-center">Website Details</h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Website Name:</strong> ' . htmlspecialchars($website['website_name']) . '</p>
                            <p><strong>Domain Name:</strong> ' . htmlspecialchars($website['domain_name']) . '</p>
                            <p><strong>Hosting Company:</strong> ' . htmlspecialchars($website['hosting_company']) . '</p>
                            <p><strong>Country:</strong> ' . htmlspecialchars($website['country']) . '</p>';

                            // Fetch user data related to the website
                            $user_sql = "SELECT * FROM user_info WHERE website_id = ?";
                            $user_stmt = $conn->prepare($user_sql);
                            $user_stmt->bind_param("i", $website_id);
                            $user_stmt->execute();
                            $user_result = $user_stmt->get_result();

                            // Check if the query was successful
                            if (!$user_result) {
                                echo "<p>Error fetching user details: " . $conn->error . "</p>";
                            } else {
                                // Display user data
                                echo '<div class="text-center-table">
                    <h3 class="mt-4">User Details</h3>';

                    echo '<div class="d-flex justify-content-end">
                    <a href="add_user.php?id=' . $website_id . '" class="btn btn-primary mt-3 custom-button">Add User</a>
                  </div>';
            
                                if ($user_result->num_rows > 0) {
                                    echo '<div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>User Name</th>
                                            <th>Email</th>
                                            <th>Password</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                    while ($user = $user_result->fetch_assoc()) {
                                        echo '<tr>
                                    <td>' . htmlspecialchars($user['id']) . '</td>
                                    <td>' . htmlspecialchars($user['user_name']) . '</td>
                                    <td>' . htmlspecialchars($user['email']) . '</td>
                                    <td>' . htmlspecialchars($user['password']) . '</td>
                                  </tr>';
                                    }
                                    echo '</tbody>
                              </table>
                              </div>';
                                } else {
                                    echo "<p>No user details found for website ID $website_id.</p>";
                                }
                            }

                            // Close the user statement
                            $user_stmt->close();
                        } else {
                            echo "<p>No website details found for ID $website_id.</p>";
                        }
                    }

                    // Close the statements and connection
                    $stmt->close();
                    $conn->close();
                    ?>
                </div>





                <script>
                    // JavaScript for dynamic dropdowns
                    document.addEventListener('DOMContentLoaded', function() {
                        const countrySelect = document.getElementById('country');
                        const stateSelect = document.getElementById('state');
                        const citySelect = document.getElementById('city');

                        // Load countries dynamically
                        fetch('load_countries.php')
                            .then(response => response.json())
                            .then(data => {
                                data.forEach(country => {
                                    countrySelect.innerHTML += `<option value="${country.id}">${country.name}</option>`;
                                });
                            });

                        // Load states based on selected country
                        countrySelect.addEventListener('change', function() {
                            const countryId = this.value;
                            if (countryId) {
                                fetch(`load_states.php?country_id=${countryId}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        stateSelect.innerHTML = '<option value="">Select State</option>';
                                        data.forEach(state => {
                                            stateSelect.innerHTML += `<option value="${state.id}">${state.name}</option>`;
                                        });
                                    });
                            } else {
                                stateSelect.innerHTML = '<option value="">Select State</option>';
                                citySelect.innerHTML = '<option value="">Select City</option>';
                            }
                        });

                        // Load cities based on selected state
                        stateSelect.addEventListener('change', function() {
                            const stateId = this.value;
                            if (stateId) {
                                fetch(`load_cities.php?state_id=${stateId}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        citySelect.innerHTML = '<option value="">Select City</option>';
                                        data.forEach(city => {
                                            citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
                                        });
                                    });
                            } else {
                                citySelect.innerHTML = '<option value="">Select City</option>';
                            }
                        });
                    });

                    // Client-side form validation
                    document.querySelector('form').addEventListener('submit', function(event) {
                        const phoneInput = document.getElementById('phone');
                        const phonePattern = /^[0-9]{11}$/; // Example: 10-digit phone number
                        if (!phonePattern.test(phoneInput.value)) {
                            alert('Please enter a valid 11-digit phone number.');
                            event.preventDefault();
                        }
                    });
                </script>
                <script>
                    document.getElementById('password').addEventListener('input', function() {
                        const password = this.value;
                        const passwordError = document.getElementById('password-error');

                        // Regular expression to check for the required password pattern
                        const pattern = /^(?=.*[A-Z])(?=.*[!@#$%^&*()_+{}:;.,]).{8,}$/;

                        if (!pattern.test(password)) {
                            passwordError.style.display = 'block';
                        } else {
                            passwordError.style.display = 'none';
                        }
                    });
                </script>



                <!-- End of Page Wrapper -->

                <!-- Scroll to Top Button-->
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
                <script>
                    $(document).ready(function() {
                        $("#email").on('input', function() {
                            var email = $(this).val();

                            if (email !== "") {
                                $.ajax({
                                    url: 'check_email_user.php',
                                    method: 'POST',
                                    data: {
                                        email: email
                                    },
                                    success: function(response) {
                                        if (response === "exists") {
                                            $("#email-status").text("This email is already registered.").css("color", "red");
                                            $("#email").css("border-color", "red");
                                            $("#submit").attr("disabled", true); // Disable the submit button
                                        } else {
                                            $("#email-status").text("Email is available.").css("color", "green");
                                            $("#email").css("border-color", "");
                                            $("#submit").attr("disabled", false); // Enable the submit button
                                        }
                                    }
                                });
                            } else {
                                $("#email-status").text("");
                                $("#email").css("border-color", "");
                                $("#submit").attr("disabled", false); // Enable the submit button
                            }
                        });
                    });
                </script>
                <!-- Adding the image Script code -->
                <script>
                    document.getElementById('image').addEventListener('change', function() {
                        const file = this.files[0];
                        const imageError = document.getElementById('image-error');
                        const sizeError = document.getElementById('size-error');
                        const dimensionError = document.getElementById('dimension-error');

                        // Reset error messages
                        imageError.style.display = 'none';
                        sizeError.style.display = 'none';
                        dimensionError.style.display = 'none';

                        if (file) {
                            const reader = new FileReader();

                            // Validate file size (2 MB limit)
                            const maxSize = 2 * 1024 * 1024; // 2MB
                            if (file.size > maxSize) {
                                sizeError.style.display = 'block';
                                this.value = ''; // Clear the file input
                                return;
                            }

                            // Validate file header (magic number)
                            reader.onload = function(e) {
                                const header = new Uint8Array(e.target.result).subarray(0, 4);
                                let valid = false;

                                const jpg = header[0] === 0xFF && header[1] === 0xD8 && header[2] === 0xFF;
                                const png = header[0] === 0x89 && header[1] === 0x50 && header[2] === 0x4E && header[3] === 0x47;

                                if (jpg || png) {
                                    valid = true;
                                }

                                if (!valid) {
                                    imageError.style.display = 'block';
                                    document.getElementById('image').value = ''; // Clear the file input
                                    return;
                                } else {
                                    imageError.style.display = 'none';

                                    // Validate image dimensions
                                    const img = new Image();
                                    img.src = URL.createObjectURL(file);

                                    img.onload = function() {
                                        const maxWidth = 1024; // Example standard width
                                        const maxHeight = 768; // Example standard height

                                        if (img.width > maxWidth || img.height > maxHeight) {
                                            dimensionError.style.display = 'block';
                                            document.getElementById('image').value = ''; // Clear the file input
                                        } else {
                                            dimensionError.style.display = 'none';
                                        }
                                    };
                                }
                            };

                            reader.readAsArrayBuffer(file);
                        }
                    });
                </script>
                <script src="vendor/jquery/jquery.min.js">
                </script>
                <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

                <!-- Core plugin JavaScript-->
                <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

                <!-- Custom scripts for all pages-->
                <script src="js/sb-admin-2.min.js"></script>
                <!-- Include jQuery, Bootstrap, and custom AJAX scripts -->
                <script src="js/jQuery.js"></script>
                <script src="js/bootstrap.js"></script>
                <script src="js/myajax.js"></script>

</body>

</html>