<?php
// Include necessary files
require_once '../controllers/BatteryController.php';
require_once '../controllers/LoginController.php';
include 'nav.php';

// Instantiate the login controller to check the user's status
$loginController = new LoginController();

// Check if the user is logged in; if not, redirect to the login page
if (!$loginController->isLoggedIn()) {
    header('Location: LoginView.php');
    exit();
}

// Instantiate the BatteryController and fetch sorted smartlock data
$batteryController = new BatteryController();
$smartlocks = $batteryController->getSortedSmartlockData();

// Get the search term from query parameters
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Get the current page number from query parameters, default to 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Define the number of items per page
$perPage = 25;

// Calculate the starting index for the current page
$start = ($page - 1) * $perPage;

// If there's a search term, filter the smartlocks
if (!empty($searchTerm)) {
    $searchTermLower = strtolower($searchTerm);
    $smartlocks = array_filter($smartlocks, function ($smartlock) use ($searchTermLower) {
        return strpos(strtolower($smartlock['name']), $searchTermLower) !== false;
    });
}

// Get the total number of smartlocks after filtering
$totalSmartlocks = count($smartlocks);

// Slice the array to get only the items for the current page
$smartlocksPage = array_slice($smartlocks, $start, $perPage);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta tags and title -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuki Smartlocks Battery Status</title>


    <!-- Bootstrap CSS from jsDelivr -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>

<body>
    <div class="container mt-5">
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header text-white">
                <h2 class="card-title mb-0">Battery Status of Nuki Devices</h2>
            </div>
            <div class="card-body">
                <!-- Filter Bar -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <!-- Search Form -->
                        <form method="get" action="" class="d-flex">
                            <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search by Smartlock Name" value="<?= htmlspecialchars($searchTerm) ?>">
                            <button type="submit" id="searchButton" class="btn btn-primary ms-2">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </form>

                    </div>
                    <div class="col-md-6 text-end">
                        <!-- Additional Filter Button (if needed) -->
                    </div>
                </div>

                <?php if (isset($smartlocks['error'])): ?>
                    <div class="alert alert-danger text-center">
                        <?= htmlspecialchars($smartlocks['error']); ?>
                    </div>
                <?php else: ?>
                    <?php if (count($smartlocksPage) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Name</th>
                                        <th>Battery Status</th>
                                        <th>Battery Charge (%)</th>
                                    </tr>
                                </thead>
                                <tbody id="smartlockTableBody">
                                    <?php foreach ($smartlocksPage as $smartlock): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($smartlock['name']) ?></td>
                                            <td>
                                                <?php if (isset($smartlock['state']['batteryCritical']) && $smartlock['state']['batteryCritical']): ?>
                                                    <span class="badge bg-danger">Critical</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">Normal</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= isset($smartlock['state']['batteryCharge']) ? htmlspecialchars($smartlock['state']['batteryCharge']) . '%' : 'Not available' ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination controls -->
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link btn btn-primary me-2" href="?page=<?= $page - 1 ?>&search=<?= urlencode($searchTerm) ?>" aria-label="Previous">
                                            <i class="fas fa-chevron-left"></i> Previous
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($start + $perPage < $totalSmartlocks): ?>
                                    <li class="page-item">
                                        <a class="page-link btn btn-primary" href="?page=<?= $page + 1 ?>&search=<?= urlencode($searchTerm) ?>" aria-label="Next">
                                            Next <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php else: ?>
                        <div class="alert alert-info text-center">
                            No smartlocks found.
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Include JavaScript libraries -->
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-tYKxU+Y8HkVqh3oF6qP9o2EK5X0lQ2V+n23lTWUMHbZrraPF6uOMtlaJTCpDg8o5"
        crossorigin="anonymous"></script>
    <!-- FontAwesome JS for icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"
        integrity="sha512-uyXJccI1PrbU6F0R4g9cgXzpIf3n2bT9V9R1ZtQ+WlFG0YV0Qz4wZQhM7vX5xPwRq6/1dYkABmttbbFw8ad7BQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>