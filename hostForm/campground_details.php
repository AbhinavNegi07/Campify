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


// Fetching reviews (28 March)
$reviews_query = "SELECT r.*, u.name AS username 
                  FROM reviews r 
                  JOIN users u ON r.user_id = u.id 
                  WHERE r.campground_id = ? 
                  ORDER BY r.created_at DESC";
$stmt = $db->conn->prepare($reviews_query);
$stmt->bind_param("i", $camp['id']);
$stmt->execute();
$reviews_result = $stmt->get_result();
$reviews = $reviews_result->fetch_all(MYSQLI_ASSOC);

$avg_rating_query = "SELECT AVG(rating) AS avg_rating FROM reviews WHERE campground_id = ?";
$stmt = $db->conn->prepare($avg_rating_query);
$stmt->bind_param("i", $camp['id']);
$stmt->execute();
$avg_rating_result = $stmt->get_result();
$avg_rating = $avg_rating_result->fetch_assoc()['avg_rating'] ?? 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= htmlspecialchars($camp['name']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
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

        /* Review Styles */
        .rating {
            direction: ltr;
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            gap: 5px;
            /* Spacing between stars */
        }

        .star-label {
            font-size: 24px;
            cursor: pointer;
            padding: 0 5px;
            color: #ccc;
            /* Default empty star color */
        }

        /* Default empty stars */
        .star-label i {
            transition: color 0.2s ease-in-out;
        }

        /* Highlight selected and previous stars */
        .rating input:checked~label i,
        .rating label:hover i,
        .rating label:hover~label i {
            color: gold !important;
            /* Make stars gold */
        }

        /* Hide radio buttons */
        .rating input {
            display: none;
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

    <!-- Review form (28 March) -->
    <div class="container">

        <?php if (!$logged_in_user_id) : ?>
            <p class="text-danger">You must be logged in to leave a review.</p>
        <?php else : ?>
            <form action="submit_review.php" method="POST" class="mt-3 p-3 border rounded bg-light" id="reviewForm">
                <input type="hidden" name="campground_id" value="<?= $camp['id'] ?>">
                <input type="hidden" name="slug" value="<?= htmlspecialchars($camp['slug']) ?>">

                <!-- Star Rating Selection -->
                <label class="fw-bold">Rating:</label>
                <div class="rating mb-2 d-flex flex-row justify-content-start">
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                        <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>" class="d-none">
                        <label for="star<?= $i ?>" class="star-label"><i class="bi bi-star text-warning"></i></label>
                    <?php endfor; ?>
                </div>



                <!-- Review Text -->
                <label for="review" class="fw-bold">Review:</label>
                <textarea name="review" id="review" class="form-control" rows="3" required></textarea>

                <button type="submit" class="btn btn-primary mt-2">Submit Review</button>
            </form>
        <?php endif; ?>

        <!-- Reviews Section -->
        <hr>
        <h3>Reviews & Ratings</h3>

        <?php if (!empty($reviews)) : ?>
            <?php foreach ($reviews as $review) : ?>
                <div class="review p-3 border rounded bg-light mb-3">
                    <strong><?= htmlspecialchars($review['username']) ?></strong>

                    <!-- Star Rating Display -->
                    <div class="stars my-1">
                        <?php
                        $rating = intval($review['rating']); // Convert to integer (only full stars)
                        for ($i = 1; $i <= 5; $i++) {
                            echo ($i <= $rating) ? '<i class="bi bi-star-fill text-warning"></i>' : '<i class="bi bi-star text-warning"></i>';
                        }
                        ?>
                    </div>

                    <p><?= nl2br(htmlspecialchars($review['review'])) ?></p>
                    <small class="text-muted"><?= date("F j, Y", strtotime($review['created_at'])) ?></small>

                    <!-- Show Delete Button Only if the Logged-in User Owns the Review -->
                    <?php if ($logged_in_user_id == $review['user_id']) : ?>
                        <form action="delete_review.php" method="POST" style="display:inline;">
                            <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                            <input type="hidden" name="slug" value="<?= htmlspecialchars($camp['slug']) ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="text-muted">No reviews yet. Be the first to leave one!</p>
        <?php endif; ?>

    </div>

    <!-- Overall Rating -->
    <!-- <h4 class="mt-3">
        <?php
        $avg_stars = round($avg_rating); // Round average rating
        for ($i = 1; $i <= 5; $i++) {
            echo ($i <= $avg_stars) ? '<i class="bi bi-star-fill text-warning"></i>' : '<i class="bi bi-star text-warning"></i>';
        }
        ?>
        <?= number_format($avg_rating, 1) ?> / 5
    </h4> -->
    <!-- Review sectin end -->

    <?php include("../components/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.rating input').forEach(star => {
            star.addEventListener('change', function() {
                document.querySelectorAll('.star-label i').forEach(i => i.classList.replace('bi-star-fill', 'bi-star')); // Reset all
                let selectedRating = this.value;
                for (let i = 1; i <= selectedRating; i++) {
                    document.querySelector('label[for="star' + i + '"] i').classList.replace('bi-star', 'bi-star-fill');
                }
            });
        });
    </script>
    <script>
        document.getElementById('reviewForm').addEventListener('submit', function(event) {
            let ratingSelected = document.querySelector('input[name="rating"]:checked');
            if (!ratingSelected) {
                alert("Please select a star rating before submitting.");
                event.preventDefault(); // Stop form submission
            }
        });
    </script>
</body>

</html>