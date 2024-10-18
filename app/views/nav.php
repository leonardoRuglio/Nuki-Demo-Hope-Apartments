<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuki App</title>

    <!-- Link to CSS and Bootstrap -->
    <link rel="stylesheet" href="../../public/css/styles.css"> <!-- Your CSS file -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Bootstrap CSS -->
</head>

<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <!-- Logo y Título -->
            <a class="navbar-brand d-flex align-items-center" href="SmartlockView.php">
                <img src="../../public/imagenes/logo.jpg" alt="Logo" class="rounded-circle">
                <span class="navbar-title">Nuki App</span>
            </a>

            <!-- Menú button l -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- link nav -->
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_email'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="SmartlockView.php">Accounts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="UserView.php">Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="SmartlockDeviceView.php">Devices</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="BatteryView.php">Nuki PowerLink</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="RegisterView.php">Register</a> <!-- just for authenticated users -->
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="LoginView.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>