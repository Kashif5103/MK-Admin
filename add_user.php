<?php
// Start session to access session variables
session_start();

// Include database connection
include 'connection.php'; // Adjust the file path as needed

// Check if user is logged in (for pages requiring authentication)
if (!isset($_SESSION['user_id'])) {
    // User not logged in, redirect to login page
    header("Location: login.html");
    exit();
}
ob_start(); // Start output buffering
// Retrieve and validate the `id` parameter from the URL
$website_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Debugging: Display the `id` value
// echo "Website ID from URL: " . $website_id . "<br>";

if ($website_id <= 0) {
    die("Invalid website ID provided. Please go back and try again.");
}

// Ensure the website ID exists
$website_check_sql = "SELECT id FROM website_info WHERE id = ?";
$website_check_stmt = $conn->prepare($website_check_sql);
$website_check_stmt->bind_param("i", $website_id);
$website_check_stmt->execute();
$website_check_result = $website_check_stmt->get_result();

if ($website_check_result->num_rows === 0) {
    die("Invalid website ID: No matching record found.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $user_name = isset($_POST['user_name']) ? $conn->real_escape_string($_POST['user_name']) : '';
    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($user_name && $email && $password) {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into the database
        $insert_sql = "INSERT INTO user_info (user_name, email, password, website_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sssi", $user_name, $email, $hashed_password, $website_id);

        if ($stmt->execute()) {
            ob_end_clean(); // Clear output buffer
            header("Location: cards.php?id=$website_id");
            exit();
        } else {
            echo "Error adding user: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Required form fields are missing.";
    }
}

$conn->close();
ob_end_flush(); // End output buffering
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

        /* Style for error message */
        #password-error {
            color: red;
            display: none;
            font-size: 0.9em;
        }
        #username-error {
            color: red;
            display: none;
            font-size: 0.9em;
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
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h3 class="m-0 font-weight-bold text-primary text-center">Add New User</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="user.php?id=<?php echo htmlspecialchars($website_id); ?>">
                                <div class="form-group">
                                    <label for="user_name">User Name:</label>
                                    <input type="text" class="form-control" id="user_name" name="user_name" required>
                                    <div id="username-error">User name must only contain alphabetic characters.</div>
                                </div>

                                <div class="form-group">
                                    <label for="email">User email:</label>
                                    <input type="email" class="form-control form-control-user" id="email" name="email" required>
                                    <div id="email-status"></div> <!-- This element will show the email status -->
                                </div>

                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <div id="password-error">Password must be at least 8 characters long, include an uppercase letter, and a special character.</div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Add User</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>




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
                <!-- <script>
                    $(document).ready(function() {
                        $('#email').on('blur', function() { // Trigger when the user finishes typing (on blur event)
                            var email = $(this).val();

                            if (email != '') {
                                $.ajax({
                                    url: 'email_user_info.php', // PHP file to check the email
                                    method: 'POST',
                                    data: {
                                        email: email
                                    },
                                    success: function(response) {
                                        $('#email-status').html(response);
                                    }
                                });
                            } else {
                                $('#email-status').html('');
                            }
                        });
                    });
                </script> -->
                <script>
                    document.getElementById('user_name').addEventListener('input', function() {
                        const userName = this.value;
                        const usernameError = document.getElementById('username-error');

                        // Regular expression to check for alphabetic characters only
                        const pattern = /^[A-Za-z]+$/;

                        if (!pattern.test(userName)) {
                            usernameError.style.display = 'block';
                        } else {
                            usernameError.style.display = 'none';
                        }
                    });
                </script>
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
                <!-- add the password ajax -->
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