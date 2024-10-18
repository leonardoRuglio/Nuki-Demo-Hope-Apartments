<?php
require_once '../controllers/LoginController.php';
require_once '../controllers/User.php';
include '../views/nav.php';

$loginController = new LoginController();

if (!$loginController->isLoggedIn()) {
    exit();
}

// Create an instance of UserController
$controller = new UserController();

try {
    // Get user data as JSON
    $userDataArray = $controller->fectUserData(); // Fetch data as array
} catch (Exception $e) {
    // Log error and set a fallback response
    error_log('Error fetching data: ' . $e->getMessage());
    $userDataArray = [];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta tags and title -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>

    <!-- Bootstrap CSS from jsDelivr -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../public/css/styles.css">

    <!-- FontAwesome CSS for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-dG5J0zqJY5lOedY/o1jzGkNfG1N5lJkx5TqX6f7j3Q0uNU/bsO7WlSm+cwr6oLwRMBzJjJ1xK++V5oOglY6n8A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <div class="container mt-5">
        <!-- User Information Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h2 class="card-title mb-0">User Information</h2>
            </div>
            <div class="card-body">
                <!-- Search Input, Button, and Sort Button -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" id="userSearch" class="form-control" placeholder="Search Users by Name or Email">
                            <button class="btn btn-primary" id="searchButton" type="button"><i class="fas fa-search"></i> Search</button>
                            <button class="btn btn-secondary ms-2" id="sortButton" type="button"><i class="fas fa-sort-alpha-down"></i> Sort</button>
                        </div>
                    </div>
                </div>

                <!-- User Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered" id="userTable">
                        <thead class="table-dark">
                            <tr>
                                <th>User ID</th>
                                <th>Account ID</th>
                                <th>Email</th>
                                <th>Name</th>
                                <th>Creation Date</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            <!-- Rows will be dynamically inserted here -->
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Controls -->
                <div id="paginationControls" class="mt-3"></div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS from jsDelivr -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- FontAwesome JS for icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"
        integrity="sha512-ZyCKNn3NKs0BZrjF9dJeINYn/6A1Q2K63dxi6k7Vufz5+ynJ1YJcOvVLFqGq6fOZdYGjmmQ2FGa77T7ybX/roA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Pass PHP data to JavaScript -->
    <script>
        const userData = <?php echo json_encode($userDataArray); ?>;
    </script>
    <!-- Include the JavaScript file -->
    <script src="../../public/js/user.js"></script>
</body>

</html>
