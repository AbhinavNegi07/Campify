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

// Fetching reviews
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
$review_count = count($reviews);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= htmlspecialchars($camp['name']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <style>
        :root {
            --primary-color: #2e6c49;
            --secondary-color: #f8f9fa;
            --accent-color: #ff7d3b;
            --text-color: #333;
            --light-text: #6c757d;
            --card-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            --border-radius: 12px;
        }

        body {
            background-color: #f5f7f9;
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.7;
        }

        /* Header area with hero image */
        .camp-hero {
            position: relative;
            height: 450px;
            background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)),
                url('../assets/default.jpg') center/cover no-repeat;
            margin-bottom: 2rem;
            color: white;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.6));
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 2rem;
        }

        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .camp-rating {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 1rem;
        }

        .camp-location {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 1rem;
        }

        /* Swiper Styles */
        .swiper {
            width: 100%;
            height: 400px;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .swiper-slide {
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: white !important;
            background: rgba(0, 0, 0, 0.3);
            width: 40px !important;
            height: 40px !important;
            border-radius: 50%;
        }

        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-size: 18px !important;
        }

        .swiper-pagination-bullet-active {
            background: var(--accent-color) !important;
        }

        /* Content styles */
        .camp-details {
            max-width: 1200px;
            margin: auto;
            padding: 0 1rem;
        }

        .camp-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .section-title {
            position: relative;
            margin-bottom: 1.5rem;
            color: var(--primary-color);
            font-weight: 600;
        }

        .section-title:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -10px;
            width: 50px;
            height: 3px;
            background: var(--accent-color);
        }

        .info-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-card {
            background: var(--secondary-color);
            padding: 1.5rem;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .info-icon {
            font-size: 1.8rem;
            color: var(--primary-color);
        }

        .description-card {
            background-color: #fff;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        /* Review Styles */
        .reviews-container {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .review-card {
            border-bottom: 1px solid #eee;
            padding: 1.5rem 0;
        }

        .review-card:last-child {
            border-bottom: none;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .review-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .rating {
            direction: ltr;
            display: flex;
            gap: 5px;
        }

        .star-label {
            font-size: 24px;
            cursor: pointer;
            color: #ccc;
        }

        .star-label i {
            transition: color 0.2s ease-in-out;
        }

        .rating input:checked~label i,
        .rating label:hover i,
        .rating label:hover~label i {
            color: gold !important;
        }

        .rating input {
            display: none;
        }

        .review-form {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #225136;
            border-color: #225136;
        }

        .btn-accent {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
        }

        .btn-accent:hover {
            background-color: #e06a2e;
            border-color: #e06a2e;
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        /* Footer */
        footer {
            background-color: var(--primary-color);
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }
    </style>
</head>

<body>
    <?php include("../components/header.php"); ?>

    <!-- Hero Section with Main Image -->
    <div class="camp-hero" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('<?= !empty($images) ? $images[0]['image_path'] : '../assets/default.jpg' ?>');">
        <div class="hero-overlay">
            <div class="hero-content">
                <h1 class="display-4 fw-bold"><?= htmlspecialchars($camp['name']) ?></h1>

                <div class="camp-location">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span><?= htmlspecialchars($camp['location']) ?></span>
                </div>

                <div class="camp-rating">
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                        <?php if ($i <= round($avg_rating)) : ?>
                            <i class="bi bi-star-fill text-warning"></i>
                        <?php else : ?>
                            <i class="bi bi-star text-warning"></i>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <span class="ms-2"><?= number_format($avg_rating, 1) ?> (<?= $review_count ?> reviews)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages/Alerts -->
    <div class="container">
        <?php if (!empty($_SESSION['messages'])) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php foreach ($_SESSION['messages'] as $message) {
                    echo '<p class="mb-0">' . htmlspecialchars($message) . '</p>';
                } ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['messages']); ?>
        <?php endif; ?>
    </div>

    <div class="container camp-details">
        <!-- Swiper Gallery -->
        <?php if (!empty($images)) : ?>
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <?php foreach ($images as $image) : ?>
                        <?php
                        $image_filename = htmlspecialchars($image['image_path']);
                        $image_path = strpos($image_filename, "../uploads/") === 0 ? $image_filename : "../uploads/" . htmlspecialchars($camp['slug']) . "/" . $image_filename;
                        ?>
                        <div class="swiper-slide">
                            <img src="<?= $image_path ?>" alt="Campground Image">
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>
        <?php endif; ?>

        <!-- Contact Information Cards -->
        <h2 class="section-title">Campground Details</h2>
        <div class="info-row">
            <div class="info-card">
                <div class="info-icon">
                    <i class="bi bi-envelope"></i>
                </div>
                <div>
                    <h5 class="mb-0">Email</h5>
                    <p class="mb-0"><?= htmlspecialchars($camp['email']) ?></p>
                </div>
            </div>
            <div class="info-card">
                <div class="info-icon">
                    <i class="bi bi-telephone"></i>
                </div>
                <div>
                    <h5 class="mb-0">Phone</h5>
                    <p class="mb-0"><?= htmlspecialchars($camp['phone']) ?></p>
                </div>
            </div>
            <div class="info-card">
                <div class="info-icon">
                    <i class="bi bi-geo-alt"></i>
                </div>
                <div>
                    <h5 class="mb-0">Location</h5>
                    <p class="mb-0"><?= htmlspecialchars($camp['location']) ?></p>
                </div>
            </div>
        </div>

        <!-- Description Section -->
        <h2 class="section-title">About This Campground</h2>
        <div class="description-card">
            <p><?= nl2br(htmlspecialchars($camp['description'])) ?></p>
        </div>

        <!-- Owner Actions -->
        <?php if ($is_owner) : ?>
            <div class="action-buttons">
                <a href="edit_campground.php?slug=<?= urlencode($camp['slug']) ?>" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit Campground
                </a>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                    <i class="bi bi-trash"></i> Delete Campground
                </button>
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

        <div class="mt-4 mb-5">
            <a href="view_campgrounds.php" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Back to Listings
            </a>
        </div>

        <!-- Reviews Section -->
        <h2 class="section-title">Reviews & Ratings</h2>

        <!-- Review Form -->
        <?php if ($logged_in_user_id) : ?>
            <div class="review-form">
                <h4>Leave a Review</h4>
                <form action="submit_review.php" method="POST" id="reviewForm">
                    <input type="hidden" name="campground_id" value="<?= $camp['id'] ?>">
                    <input type="hidden" name="slug" value="<?= htmlspecialchars($camp['slug']) ?>">

                    <!-- Star Rating Selection -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Rating:</label>
                        <div class="rating mb-2 d-flex flex-row justify-content-start">
                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>" class="d-none">
                                <label for="star<?= $i ?>" class="star-label"><i class="bi bi-star text-warning"></i></label>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <!-- Review Text -->
                    <div class="mb-3">
                        <label for="review" class="form-label fw-bold">Your Review:</label>
                        <textarea name="review" id="review" class="form-control" rows="3" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-accent">
                        <i class="bi bi-send"></i> Submit Review
                    </button>
                </form>
            </div>
        <?php else : ?>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> You must be <a href="../auth/login.php">logged in</a> to leave a review.
            </div>
        <?php endif; ?>

        <!-- Review List -->
        <div class="reviews-container">
            <?php if (!empty($reviews)) : ?>
                <?php foreach ($reviews as $review) : ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div class="review-user">
                                <div class="user-avatar">
                                    <?= strtoupper(substr($review['username'], 0, 1)) ?>
                                </div>
                                <div>
                                    <h5 class="mb-0"><?= htmlspecialchars($review['username']) ?></h5>
                                    <div class="stars">
                                        <?php
                                        $rating = intval($review['rating']);
                                        for ($i = 1; $i <= 5; $i++) {
                                            echo ($i <= $rating) ? '<i class="bi bi-star-fill text-warning"></i>' : '<i class="bi bi-star text-warning"></i>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted"><?= date("F j, Y", strtotime($review['created_at'])) ?></small>
                        </div>
                        <div class="review-content mt-2">
                            <p><?= nl2br(htmlspecialchars($review['review'])) ?></p>
                        </div>

                        <!-- Delete Review Option -->
                        <?php if ($logged_in_user_id == $review['user_id']) : ?>
                            <div class="text-end mt-2">
                                <form action="delete_review.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                    <input type="hidden" name="slug" value="<?= htmlspecialchars($camp['slug']) ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="text-center py-4">
                    <i class="bi bi-chat-square-text" style="font-size: 3rem; color: #ddd;"></i>
                    <p class="mt-3">No reviews yet. Be the first to leave one!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include("../components/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <script>
        // Initialize Swiper
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
                dynamicBullets: true
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
        });

        // Star Rating Logic
        document.querySelectorAll('.rating input').forEach(star => {
            star.addEventListener('change', function() {
                document.querySelectorAll('.star-label i').forEach(i => i.classList.replace('bi-star-fill', 'bi-star')); // Reset all
                let selectedRating = this.value;
                for (let i = 1; i <= selectedRating; i++) {
                    document.querySelector('label[for="star' + i + '"] i').classList.replace('bi-star', 'bi-star-fill');
                }
            });
        });

        // Review Form Validation
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