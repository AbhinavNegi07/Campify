<?php
require_once '../config/database.php';
require_once 'campground.php';

// session_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = new Database();
$campground = new Campground($db->conn);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid campground ID.");
}

$campground_id = $_GET['id'];
$campground_details = $campground->getCampgroundById($campground_id);

if (!$campground_details) {
    die("Campground not found.");
}

// Get logged-in user ID
$logged_in_user_id = $_SESSION['user_id'] ?? null;
$owner_id = $campground_details['user_id']; // Ensure this is 'user_id', not 'owner_id'

$is_owner = ($logged_in_user_id && $logged_in_user_id == $owner_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Campground Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        /* Toast Container */
        .toast-container {
            position: absolute;
            top: 20px;
            /* Adjust as needed */
            left: 50%;
            transform: translateX(-50%);
            z-index: 1050;
            /* Higher than navbar */
            width: auto;
            max-width: 80%;
        }
    </style>
</head>

<body>

    <?php if (isset($_SESSION['messages']) && is_array($_SESSION['messages'])): ?>
        <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3">
            <?php foreach ($_SESSION['messages'] as $message): ?>
                <div class="toast show text-bg-success" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            <?= htmlspecialchars($message); ?>
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['messages']); ?>
    <?php endif; ?>

    <?php include("../components/header.php"); ?>



    <div class="container mt-5 " style="max-width:690px">
        <div class="card shadow">
            <img src="<?= htmlspecialchars($campground_details['image'] ?: '../assets/default.jpg') ?>" class="card-img-top" alt="Campground Image">
            <div class="card-body">
                <h2 class="card-title"><?= htmlspecialchars($campground_details['name']) ?></h2>
                <p><strong>Location:</strong> <?= htmlspecialchars($campground_details['location']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($campground_details['email']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($campground_details['phone']) ?></p>
                <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($campground_details['description'])) ?></p>

                <!-- Show buttons only if the user is the owner -->
                <?php if ($is_owner) : ?>
                    <a href="edit_campground.php?id=<?= $campground_id ?>" class="btn btn-warning">Edit</a>
                    <form action="delete_campground.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this campground?');">
                        <input type="hidden" name="id" value="<?= $campground_id ?>">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                <?php endif; ?>

                <a href="view_campgrounds.php" class="btn btn-primary">Back to Listings</a>
            </div>
        </div>
    </div>

    <?php include("../components/footer.php"); ?>
</body>

</html>