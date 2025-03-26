<?php
require_once '../config/database.php';
require_once 'campground.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if slug is set in URL
if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    die("Error: Invalid campground slug.");
}

$campground_slug = $_GET['slug'];

$db = new Database();
$campground = new Campground($db->conn);

// Fetch campground details using slug
$camp = $campground->getCampgroundBySlug($campground_slug);

if (!$camp) {
    die("Error: Campground not found!");
}

// Get all images for the campground
$images = $campground->getCampgroundImages($camp['id']);

// Get logged-in user ID
$logged_in_user_id = $_SESSION['user_id'] ?? null;
$owner_id = $camp['user_id'];

$is_owner = ($logged_in_user_id && $logged_in_user_id == $owner_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Campground Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /* Fix the height of the carousel and make images cover the area */
        .carousel-inner {
            height: 350px;
            /* Set a fixed height */
        }

        .carousel-item img {
            height: 100%;
            width: 100%;
            object-fit: cover;
            /* Ensures images fully cover the area without distortion */
        }

        /* Ensure the card stays at a fixed height */
        .card {
            min-height: 600px;
            /* Set a minimum height for the card */
        }
    </style>
</head>

<body>

    <?php include("../components/header.php"); ?>

    <?php
    if (isset($_SESSION['messages']) && !empty($_SESSION['messages'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php
            foreach ($_SESSION['messages'] as $message) {
                echo '<p class="mb-0">' . htmlspecialchars($message) . '</p>';
            }
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['messages']); // Clear after displaying 
        ?>
    <?php endif; ?>

    <div class="container mt-5" style="max-width:690px">
        <div class="card shadow">

            <!-- Bootstrap Carousel for Multiple Images -->
            <div id="campgroundCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php if (!empty($images)) : ?>
                        <?php foreach ($images as $index => $image) : ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <?php
                                $image_path = $image['image_path'];
                                // Remove duplicate "../uploads/{slug}/" if it exists
                                if (strpos($image_path, "../uploads/") !== false) {
                                    $image_path = str_replace("../uploads/" . $camp['slug'] . "/", "", $image_path);
                                }
                                ?>
                                <img src="../uploads/<?= htmlspecialchars($camp['slug']) ?>/<?= htmlspecialchars($image_path) ?>"
                                    class="d-block w-100" alt="Campground Image">

                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="carousel-item active">
                            <img src="../assets/default.jpg" class="d-block w-100" alt="Default Image">
                        </div>
                    <?php endif; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#campgroundCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#campgroundCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>

            <div class="card-body">
                <h2 class="card-title"><?= htmlspecialchars($camp['name']) ?></h2>
                <p><strong>Location:</strong> <?= htmlspecialchars($camp['location']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($camp['email']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($camp['phone']) ?></p>
                <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($camp['description'])) ?></p>

                <!-- Show buttons only if the user is the owner -->
                <?php if ($is_owner) : ?>
                    <a href="edit_campground.php?slug=<?= urlencode($camp['slug']) ?>" class="btn btn-warning">Edit</a>

                    <!-- Delete Button that Opens Modal -->
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                        Delete
                    </button>

                    <!-- Bootstrap Modal -->
                    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete <strong><?= htmlspecialchars($camp['name']) ?></strong>?
                                </div>
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

                <a href="view_campgrounds.php" class="btn btn-primary">Back to Listings</a>
            </div>
        </div>
    </div>

    <?php include("../components/footer.php"); ?>
</body>

</html>