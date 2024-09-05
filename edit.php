<?php
// edit.php

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "catmarketing";

// Establish a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the user ID from the URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user = null;

if ($id) {
    // Fetch user data with country, state, and city IDs
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

// Fetch available countries
$countries = $conn->query("SELECT id, name FROM countries");

// Fetch available states if a country is selected
$states = $user && $user['country'] ? $conn->query("SELECT id, name FROM states WHERE country_id = {$user['country']}") : null;

// Fetch available cities if a state is selected
$cities = $user && $user['state'] ? $conn->query("SELECT id, name FROM cities WHERE state_id = {$user['state']}") : null;

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h3 class="m-4 font-weight-bold text-primary">Edit User</h3>
            </div>
            <div class="card-body">
                <form action="update_user.php" method="POST" enctype="multipart/form-data">
                    <!-- Hidden field to store user ID -->
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">

                    <!-- Name field -->
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>

                    <!-- Email field -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>

                    <!-- Phone field -->
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                    </div>

                    <!-- Password field -->
                    <div class="form-group">
                        <label for="password">Password (leave empty to keep current password)</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>

                    <!-- Country dropdown -->
                    <div class="form-group">
                        <label for="country">Country</label>
                        <select class="form-control" id="country" name="country" required>
                            <option value="">Select Country</option>
                            <?php while ($country = $countries->fetch_assoc()): ?>
                                <option value="<?php echo $country['id']; ?>" <?php echo $user['country'] == $country['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($country['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- State dropdown -->
                    <div class="form-group">
                        <label for="state">State</label>
                        <select class="form-control" id="state" name="state" required>
                            <option value="">Select State</option>
                            <?php if ($states): ?>
                                <?php while ($state = $states->fetch_assoc()): ?>
                                    <option value="<?php echo $state['id']; ?>" <?php echo $user['state'] == $state['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($state['name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- City dropdown -->
                    <div class="form-group">
                        <label for="city">City</label>
                        <select class="form-control" id="city" name="city" required>
                            <option value="">Select City</option>
                            <?php if ($cities): ?>
                                <?php while ($city = $cities->fetch_assoc()): ?>
                                    <option value="<?php echo $city['id']; ?>" <?php echo $user['city'] == $city['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($city['name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Profile Image field -->
                    <div class="form-group">
                        <label for="profile_image">Profile Image</label>
                        <input type="file" class="form-control" id="profile_image" name="profile_image">
                        <?php if ($user['profile_image']): ?>
                            <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Image" style="width:100px; height:100px; margin-top:10px;">
                        <?php endif; ?>
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
