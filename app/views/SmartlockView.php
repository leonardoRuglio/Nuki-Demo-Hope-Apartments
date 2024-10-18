<?php
//require_once '../controllers/SendInvitationController.php';
require_once '../controllers/SmartlockController.php';
require_once '../../config/config.php';
require_once '../controllers/LoginController.php';
require_once '../controllers/User.php';
include '../views/nav.php';

$loginController = new LoginController();

if (!$loginController->isLoggedIn()) {
    header('Location: LoginView.php');
    exit();
}

$controller = new SmartlockController();
$smartlockData = $controller->fetchSmartlockDataAsJson();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smartlock Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoMZ4HBaGATK1kmiPjY3Jk6SSKwwV5lKdVfn53O3G9GtgxY"
        crossorigin="anonymous">
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-RY6n4IhZFbX60pyysuV+osx3w5sV5rxPZoxHFgPduJFc5o6eT5jV4bRHjF0ZO51UyX/5ObGkPQyzE7e3v1P0WQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="container mt-5">
        <div class="card shadow-sm border-0">
            <div class="card-header text-white ">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="card-title mb-0">Smartlock Accounts Information</h2>
                    <button id="sendAllInvitationsButton" class="btn btn-success"><i class="fas fa-envelope"></i>  (Testing) Send Invitations to All Users</button>
                </div>
            </div>

            <div class="card-body">
                <!-- Search and Filter Bar -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search by Device or User Name">
                            <button id="searchButton" class="btn btn-primary ms-2"><i class="fas fa-search"></i> Search</button>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <button id="filterButton" class="btn btn-secondary"><i class="fas fa-filter"></i> 1-7 Day Authorization</button>
                    </div>
                </div>

                <!-- Smartlock Table -->
                <div class="table-responsive mb-5">
                    <table class="table table-striped table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Device Name</th>
                                <th>Name</th>
                                <th class="text-center">Creation Date</th>
                                <th class="text-center d-none d-md-table-cell">Allowed From Date</th>
                                <th class="text-center d-none d-md-table-cell">Allowed Until Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="smartlockTableBody">
                            <!-- Rows will be dynamically inserted here -->
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Controls -->
                <div id="paginationControls" class="mt-3"></div>

                <!-- Filtered Results Section -->
                <div id="filteredResults" class="mt-5">
                    <h4 class="text-primary">1-7 Day Authorization</h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle">
                            <thead class="table-info">
                                <tr>
                                    <th>Device Name</th>
                                    <th>Name</th>
                                    <th class="text-center">Creation Date</th>
                                    <th class="text-center d-none d-md-table-cell">Allowed From Date</th>
                                    <th class="text-center d-none d-md-table-cell">Allowed Until Date</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="filteredTableBody">
                                <!-- Filtered rows will be dynamically inserted here -->
                            </tbody>
                        </table>
                        <div id="noDataMessage" style="display:none;">
                            <p class="text-center text-muted">No data found.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjtO8gRtrq3H/xO3Bbs/HtFJlV4jqjB6hq51Kx7K2P5nQ92FVhYfHC1nA3OPJSt"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"
        integrity="sha512-ZYx2uJBrIQZ8kU5Ewg/Ukf1r9+/JcAl3o/1cZxSVYfFV5IuR+Zf/pPf5CQxGr4UwUeuz9jRgQe2KdXYeIYd5PA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        const smartlockData = JSON.parse('<?php echo addslashes($smartlockData); ?>');
    </script>
    <script src="../../public/js/Smartlock.js"></script>
    <script src="../../public/js/SendInvitation.js"></script>
</body>

</html>
