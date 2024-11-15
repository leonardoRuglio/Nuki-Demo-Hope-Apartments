<?php
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
    <!-- Meta tags and title -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smartlock Information</title>


    <!-- Matomo -->
    <script>
        var _paq = window._paq = window._paq || [];
        /* Tracker methods like "setCustomDimension" should be called before "trackPageView" */
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function() {
            var u = "https://stat.msmhost.de/";
            _paq.push(['setTrackerUrl', u + 'matomo.php']);
            _paq.push(['setSiteId', '12']);
            var d = document,
                g = d.createElement('script'),
                s = d.getElementsByTagName('script')[0];
            g.async = true;
            g.src = u + 'matomo.js';
            s.parentNode.insertBefore(g, s);
        })();
    </script>
    <!-- End Matomo Code -->
    <!-- Bootstrap CSS from jsDelivr -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Link to external custom CSS -->
    <link rel="stylesheet" href="../../public/css/styles.css">

    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="card shadow-sm border-0">
            <div class="card-header text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="card-title mb-0">Smartlock Accounts Information</h2>
                </div>
            </div>

            <div class="card-body">
                <!-- Grouped search input and buttons -->
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

                <!-- Main Table -->
                <div class="table-responsive mb-5">
                    <table class="table no-hover table-bordered align-middle">
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
                            <?php
                            // Decode the JSON data into an associative array
                            $smartlockArray = json_decode($smartlockData, true);

                            if ($smartlockArray && is_array($smartlockArray)) {
                                foreach ($smartlockArray as $item) {
                                    // Sanitize the data to prevent XSS attacks
                                    $deviceName = htmlspecialchars($item['deviceName'] ?? 'N/A', ENT_QUOTES, 'UTF-8');
                                    $userName = htmlspecialchars($item['userName'] ?? 'N/A', ENT_QUOTES, 'UTF-8');
                                    $creationDate = htmlspecialchars($item['creationDate'] ?? 'N/A', ENT_QUOTES, 'UTF-8');
                                    $allowedFromDate = htmlspecialchars($item['allowedFromDate'] ?? 'N/A', ENT_QUOTES, 'UTF-8');
                                    $allowedUntilDate = htmlspecialchars($item['allowedUntilDate'] ?? 'N/A', ENT_QUOTES, 'UTF-8');
                                    $smartlockId = htmlspecialchars($item['smartlockId'] ?? '', ENT_QUOTES, 'UTF-8');
                                    $accountUserId = htmlspecialchars($item['accountUserId'] ?? '', ENT_QUOTES, 'UTF-8');

                                    echo "<tr>";
                                    echo "<td>{$deviceName}</td>";
                                    echo "<td>{$userName}</td>";
                                    echo "<td class='text-center'>{$creationDate}</td>";
                                    echo "<td class='text-center d-none d-md-table-cell'>{$allowedFromDate}</td>";
                                    echo "<td class='text-center d-none d-md-table-cell'>{$allowedUntilDate}</td>";
                                    echo "<td class='text-center'>";
                                    // Add the "Send Code" button with data attributes
                                    echo "<button class='btn btn-primary send-code-btn' data-id='{$smartlockId}' data-account-user-id='{$accountUserId}'>Send Code</button>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>No data available.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Controls -->
                <div id="paginationControls" class="mt-3"></div>

                <!-- Table for filtered results -->
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

    <!-- Bootstrap JavaScript and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Pass PHP data to JavaScript -->
    <script>
        const smartlockData = <?php echo json_encode($smartlockArray); ?>;
    </script>

    <!-- External JS file inclusion -->
    <script src="../../public/js/Smartlock.js"></script>
    <script src="../../public/js/AuthorizationCode.js"></script>
</body>

</html>
