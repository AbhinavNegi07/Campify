<?php
require_once '../config/database.php';
require_once 'campground.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    die("Error: Invalid campground slug.");
}

$campground_slug = $_GET['slug'];

$db = new Database();
$campground = new Campground($db->conn);

$camp = $campground->getCampgroundBySlug($campground_slug);

if (!$camp) {
    die("Error: Campground not found!");
}

$images = $campground->getCampgroundImages($camp['id']);

$logged_in_user_id = $_SESSION['user_id'] ?? null;
$owner_id = $camp['user_id'];

$is_owner = ($logged_in_user_id && $logged_in_user_id == $owner_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= htmlspecialchars($camp['name']) ?> - Campground Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .camp-details {
            max-width: 900px;
            margin: auto;
        }

        .bento-grid {
            display: grid;
            gap: 10px;
            padding: 10px;
        }

        /* Grid Layout Adjustments Based on Image Count */
        <?php if (count($images) == 1) : ?>.bento-grid {
            grid-template-columns: 1fr;
        }

        <?php elseif (count($images) == 2) : ?>.bento-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        <?php elseif (count($images) == 3) : ?>.bento-grid {
            grid-template-columns: 2fr 1fr;
            grid-template-rows: 2fr 1fr;
        }

        .bento-grid .bento-item:nth-child(1) {
            grid-column: span 1;
            grid-row: span 2;
        }

        <?php elseif (count($images) == 4) : ?>.bento-grid {
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(2, 1fr);
        }

        <?php elseif (count($images) == 5) : ?>.bento-grid {
            grid-template-columns: 2fr 1fr;
            grid-template-rows: repeat(2, 1fr);
        }

        .bento-grid .bento-item:nth-child(1) {
            grid-column: span 1;
            grid-row: span 2;
        }

        <?php else : ?>.bento-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .bento-grid .bento-item:nth-child(1) {
            grid-column: span 2;
            grid-row: span 2;
        }

        <?php endif; ?>.bento-item {
            overflow: hidden;
            border-radius: 10px;
        }

        .bento-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease-in-out;
            border-radius: 10px;
            transition: all 1s ease-in-out;
        }

        .bento-item img:hover {
            transform: scale(1.1);
        }
    </style>
</head>

<body>

    <?php include("../components/header.php"); ?>

    <div class="container mt-5 camp-details">
        <div class="camp-card">
            <?php if (!empty($_SESSION['messages'])) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php foreach ($_SESSION['messages'] as $message) {
                        echo '<p class="mb-0">' . htmlspecialchars($message) . '</p>';
                    } ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['messages']); ?>
            <?php endif; ?>

            <h2 class="text-center"><?= htmlspecialchars($camp['name']) ?></h2>
            <p class="text-muted text-center"><?= htmlspecialchars($camp['location']) ?></p>

            <!-- Bento Grid for Images -->
            <div class="bento-grid">
                <?php if (!empty($images)) : ?>
                    <?php foreach ($images as $image) : ?>
                        <div class="bento-item">
                            <?php
                            $image_filename = htmlspecialchars($image['image_path']);
                            $image_path = strpos($image_filename, "../uploads/") === 0 ? $image_filename : "../uploads/" . htmlspecialchars($camp['slug']) . "/" . $image_filename;
                            ?>
                            <img src="<?= $image_path ?>" alt="Campground Image">
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="bento-item">
                        <img src="../assets/default.jpg" alt="Default Campground Image">
                    </div>
                <?php endif; ?>
            </div>

            <hr>

            <p><strong>Email:</strong> <?= htmlspecialchars($camp['email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($camp['phone']) ?></p>
            <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($camp['description'])) ?></p>

            <?php if ($is_owner) : ?>
                <div class="d-flex justify-content-between mt-3">
                    <a href="edit_campground.php?slug=<?= urlencode($camp['slug']) ?>" class="btn btn-warning">Edit</a>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">Delete</button>
                </div>

                <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Deletion</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">Are you sure you want to delete <strong><?= htmlspecialchars($camp['name']) ?></strong>?</div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <form action="delete_campground.php" method="POST">
                                    <input type="hidden" name="slug" value="<?= htmlspecialchars($camp['slug']); ?>">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <a href="view_campgrounds.php" class="btn btn-primary mt-3">Back to Listings</a>
        </div>
    </div>

    <?php include("../components/footer.php"); ?>
</body>

</html>