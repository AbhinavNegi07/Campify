<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';
require_once 'campground.php';

$db = new Database();
$campground = new Campground($db->conn);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register Your Campground</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin: 50px auto;
        }

        .row {
            display: flex;
        }

        .card {
            display: flex;
            flex-direction: column;
        }

        .img-card {
            background-image: url('../assets/hero/hero-1.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Toast Container */
        .toast-container {
            position: fixed;
            top: 15%;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1050;
            color: #fff;
        }

        /* Toast Styling */
        .toast {
            background-color: #dc3545 !important;
            /* Bootstrap "danger" (red) */
            color: white;
        }
    </style>
</head>

<body>

    <?php include("../components/header.php"); ?>

    <!-- Toast Notifications -->
    <?php if (isset($_SESSION['messages']) && is_array($_SESSION['messages'])): ?>
        <div class="toast-container">
            <?php foreach ($_SESSION['messages'] as $message): ?>
                <div class="toast show" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            <?= htmlspecialchars($message); ?>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['messages']); ?>
    <?php endif; ?>

    <div class="container">
        <div class="row align-items-stretch">
            <div class="col-lg-6 d-flex">
                <div class="card img-card shadow p-4 flex-fill">
                    <img style="width: 100%; height: 100%; object-fit: cover;" src="../assets/hero/hero-1.jpg" alt="">
                </div>
            </div>

            <div class="col-lg-6 d-flex">
                <div class="card shadow p-4 flex-fill">
                    <h2 class="text-center">Campground Registration</h2>

                    <form action="process_registration.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Campground Name:</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Location:</label>
                            <input type="text" name="location" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone:</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description:</label>
                            <textarea name="description" class="form-control" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="images" class="form-label">Upload Images (Max: 6)</label>
                            <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*">
                            <small class="text-muted">You can upload up to 6 images.</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include("../components/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>