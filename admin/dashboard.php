<?php
require 'includes/auth.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body class="bg-light">
    <?php include 'includes/navbar.php'; ?>
    <div class="container d-flex flex-column justify-content-center align-items-center vh-100">
        <div class="card shadow-lg p-4 text-center">
            <h2 class="text-primary mb-4">Welcome, Admin</h2>
            <div class="d-grid gap-3">
                <a href="blogs.php" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Manage Blogs</a>
                <a href="manage_campgrounds.php" class="btn btn-warning"><i class="bi bi-house-door"></i> Manage Campgrounds</a>
                <a href="logout.php" class="btn btn-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>