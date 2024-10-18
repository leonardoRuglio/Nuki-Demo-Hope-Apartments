<?php
require_once '../controllers/DeviceSmarlockController.php';
require_once '../controllers/LoginController.php';
require_once '../controllers/User.php';
require_once '../../config/config.php';
include '../views/nav.php';  // Including the navbar here

$controller = new SmartlockDeviceController();

try {
    // Call the controller to get Smartlock device data
    $devices = $controller->fetchSmartlockDevice();
} catch (Exception $e) {
    // Error handling
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smartlock Devices</title>

    <!-- Link to Bootstrap 4.5 and Custom CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>

<body>
    <div class="container mt-5">
        <div class="card mb-4">
            <div class="card-header">
                <h2 class="card-title">Smartlock Devices</h2>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <strong>Error:</strong> <?= htmlspecialchars($error); ?>
                    </div>
                <?php else: ?>
                    <!-- Search Input and Sort Button -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" id="deviceSearch" class="form-control" placeholder="Search Devices by Name or Account ID">
                                <button class="btn btn-primary" id="searchButton" type="button">Search</button>
                                <button class="btn btn-secondary ml-2" id="sortButton" type="button">Sort Alphabetically</button>
                            </div>
                        </div>
                    </div>

                    <table class="table table-hover table-bordered table-striped" id="deviceTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Smartlock ID</th>
                                <th>Account ID</th>
                            </tr>
                        </thead>
                        <tbody id="deviceTableBody">
                            <?php foreach ($devices as $device): ?>
                                <tr>
                                    <td><?= htmlspecialchars($device->getName()); ?></td>
                                    <td><?= htmlspecialchars($device->getSmartlockId()); ?></td>
                                    <td><?= htmlspecialchars($device->getAccountId()); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Pass PHP data to JavaScript -->
    <script>
        const deviceData = <?php echo json_encode($devices); ?>;
    </script>

    <!-- Bootstrap JavaScript and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Include the custom JS for filtering and sorting -->
    <script src="../../public/js/device.js"></script>
</body>

</html>
