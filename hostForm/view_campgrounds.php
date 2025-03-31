<?php
require_once '../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = new Database();
$conn = $db->conn;

// Fetch campgrounds with ALL images, not just the first one
$sql = "SELECT c.*, GROUP_CONCAT(ci.image_path SEPARATOR '|') as all_images
FROM campgrounds c
LEFT JOIN campground_images ci ON ci.campground_id = c.id
WHERE c.status = 'approved'
GROUP BY c.id
ORDER BY c.created_at DESC";

$result = $conn->query($sql);


$query = "SELECT * FROM campgrounds WHERE status = 'approved'";

$search_location = isset($_GET['search_location']) ? trim($_GET['search_location']) : '';

if (!empty($search_location)) {
    $query .= " AND location LIKE ?";
}

$stmt = $conn->prepare($query);

if (!empty($search_location)) {
    $search_param = "%$search_location%";
    $stmt->bind_param("s", $search_param);
}

$stmt->execute();
$filter_result = $stmt->get_result();


?>

<!DOCTYPE html>
<html>

<head>
    <title>Registered Campgrounds</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Add Swiper CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.css">
    <style>
        .campground-container {
            margin: auto;
            text-align: center;
            padding: 20px;
        }

        .campground-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .campground-card {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            width: 350px;
            transition: transform 0.3s ease-in-out;
            text-align: left;
        }

        .campground-card:hover {
            transform: translateY(-5px);
        }

        /* Slider styles */
        .swiper {
            width: 100%;
            height: 240px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 5px;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: #f2681d;
            background: rgba(255, 255, 255, 0.5);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            --swiper-navigation-size: 15px;
        }

        .swiper-pagination-bullet-active {
            background-color: #f2681d;
        }

        .no-images-placeholder {
            width: 100%;
            height: 240px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f5f5f5;
            color: #888;
            font-style: italic;
            border-radius: 5px;
        }

        .campground-card h3 {
            margin: 10px 0;
            font-size: 20px;
            font-weight: 600;
            color: #333;
        }

        .campground-card p {
            color: #555;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .campground-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .location {
            display: flex;
            align-items: center;
        }

        .location svg {
            margin-right: 5px;
            color: #f2681d;
        }

        .amenities {
            display: flex;
            gap: 8px;
            margin-bottom: 15px;
        }

        .amenity-tag {
            font-size: 12px;
            background-color: #f5f5f5;
            padding: 4px 8px;
            border-radius: 20px;
            color: #555;
        }

        .campground-btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #f2681d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease-in-out;
            width: 100%;
            text-align: center;
        }

        .campground-btn:hover {
            background-color: #218838;
        }

        @media (max-width: 768px) {
            .campground-card {
                width: 100%;
                max-width: 300px;
            }
        }

        /* Banner */
        .all-camp-banner {
            background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)),
                url(../assets/blogs/blog-6.jpg);
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .all-camp-banner h4 {
            color: #fff;
            font-size: 60px;
            font-weight: 700;
        }

        .all-camp-banner h4 a {
            color: #f2681d;
        }
    </style>
</head>

<body class="campground-page">
    <?php include("../components/header.php"); ?>
    <!-- <form method="GET" action="">
        <input type="text" name="search_location" placeholder="Search by location" value="<?= isset($_GET['search_location']) ? htmlspecialchars($_GET['search_location']) : '' ?>">
        <button type="submit">Search</button>
    </form> -->


    <div class="all-camp-banner">
        <h4>All <span>Campgrounds</span></h4>
    </div>
    <?php
    $hide_section = ($filter_result->num_rows > 0) ? "display: none;" : "";
    ?>
    <div class="campground-container" style="<?= $hide_section; ?>">
        <h2>Discover Our Campgrounds</h2>
        <div class="campground-grid">
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="campground-card">
                    <?php
                    $defaultImage = '../assets/default-camp.jpg';
                    $allImages = [];

                    // Process all images
                    if (!empty($row['all_images'])) {
                        $imagesPaths = explode('|', $row['all_images']);
                        foreach ($imagesPaths as $path) {
                            if (!empty($path) && file_exists($path)) {
                                $allImages[] = $path;
                            } else {
                                // Try with slug path
                                $slugPath = '../uploads/' . $row['slug'] . '/' . basename($path);
                                if (!empty($path) && file_exists($slugPath)) {
                                    $allImages[] = $slugPath;
                                }
                            }
                        }
                    }
                    ?>

                    <?php if (count($allImages) > 0) : ?>
                        <!-- Slider main container -->
                        <div class="swiper campgroundSwiper-<?= $row['id'] ?>">
                            <!-- Additional required wrapper -->
                            <div class="swiper-wrapper">
                                <?php foreach ($allImages as $image) : ?>
                                    <div class="swiper-slide">
                                        <img src="<?= htmlspecialchars($image); ?>" alt="<?= htmlspecialchars($row['name']); ?>">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <!-- Navigation buttons -->
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <!-- Pagination -->
                            <div class="swiper-pagination"></div>
                        </div>
                    <?php else : ?>
                        <div class="no-images-placeholder">
                            <img src="<?= htmlspecialchars($defaultImage); ?>" alt="No Images Available">
                        </div>
                    <?php endif; ?>

                    <h3><?= htmlspecialchars($row['name']); ?></h3>

                    <div class="campground-info">
                        <div class="location">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                            </svg>
                            <?= htmlspecialchars($row['location']); ?>
                        </div>
                        <div class="price">
                            <?php if (!empty($row['price'])) : ?>
                                <strong>₹<?= number_format($row['price'], 2); ?></strong>/night
                            <?php else : ?>
                                <strong>Contact</strong> for prices
                            <?php endif; ?>
                        </div>
                        <!-- <p><strong>Price per Night:</strong> ₹<?= number_format($row['price'], 2) ?></p> -->

                    </div>

                    <!-- <?php if (!empty($row['description'])) : ?>
                        <p><?= substr(htmlspecialchars($row['description']), 0, 50) . '...'; ?></p>
                    <?php endif; ?> -->

                    <?php
                    // Let's simulate some amenities - in real app, these would come from the database
                    $dummyAmenities = ['Restrooms', 'Showers', 'Campfire', 'Hiking'];
                    $randomAmenities = array_slice($dummyAmenities, 0, rand(2, 4));
                    ?>
                    <div class="amenities">
                        <?php foreach ($randomAmenities as $amenity) : ?>
                            <span class="amenity-tag"><?= $amenity ?></span>
                        <?php endforeach; ?>
                    </div>

                    <a href="campground_details.php?slug=<?= urlencode($row['slug']) ?>" class="campground-btn">View Details</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php
    $hided_section = ($filter_result->num_rows > 0) ? "" : "display: none;";
    ?>
    <div style="<?= $hided_section; ?>>
        <?php
        if ($filter_result->num_rows > 0) {
            echo '<div class="campground-container">';
            while ($campground = $filter_result->fetch_assoc()) {
                $imagePath = !empty($campground['image']) ? "uploads/" . $campground['slug'] . "/" . htmlspecialchars($campground['image']) : "assets/default.jpg";

                echo '<div class="campground-card">';
                echo '<img src="' . $imagePath . '" alt="Campground Image">';
                echo '<div class="campground-details">';
                echo '<h3>' . htmlspecialchars($campground['name']) . '</h3>';
                echo '<p><strong>Location:</strong> ' . htmlspecialchars($campground['location']) . '</p>';
                echo '<p>' . htmlspecialchars(substr($campground['description'], 0, 100)) . '...</p>';
                echo '<a href="campground_details.php?slug=' . urlencode($campground['slug']) . '" class="view-btn">View Details</a>';
                echo '</div></div>';
            }
            echo '</div>';
        } else {
            echo "<p class='no-results'>No campgrounds found for this location.</p>";
        }
        ?>
    </div>

    <?php include("../components/footer.php"); ?>

    <!-- Add Swiper JS -->
    <script src=" https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js">
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize all Swiper sliders
                <?php
                // Reset the result pointer to go through all rows again
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()) :
                ?>
                    new Swiper('.campgroundSwiper-<?= $row['id'] ?>', {
                        loop: true,
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true,
                        },
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        autoplay: {
                            delay: 5000,
                            disableOnInteraction: false,
                        },
                    });
                <?php endwhile; ?>
            });
        </script>
</body>

</html>