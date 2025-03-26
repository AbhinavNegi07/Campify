<?php
require_once '../config/database.php';

$db = new Database();
$conn = $db->conn;

// Fetch campgrounds with only the first image
$sql = "SELECT c.*, 
               (SELECT image_path FROM campground_images ci WHERE ci.campground_id = c.id ORDER BY ci.id ASC LIMIT 1) AS first_image 
        FROM campgrounds c 
        ORDER BY c.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Registered Campgrounds</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .campground-page {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

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
            width: 280px;
            transition: transform 0.3s ease-in-out;
        }

        .campground-card:hover {
            transform: translateY(-5px);
        }

        .campground-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 5px;
        }

        .campground-card h3 {
            margin: 10px 0;
            font-size: 18px;
            color: #333;
        }

        .campground-card p {
            color: #555;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .campground-btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease-in-out;
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
    </style>
</head>

<body class="campground-page">
    <?php include("../components/header.php"); ?>

    <div class="campground-container">
        <h2>Registered Campgrounds</h2>
        <div class="campground-grid">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="campground-card">
                    <!-- Show first image, or default if no image exists -->
                    <!-- <img src="<?= htmlspecialchars($row['first_image'] ?: '../assets/default-camp.jpg'); ?>" alt="Campground Image">
                    <img src="<?= htmlspecialchars('../uploads/' . $row['slug'] . '/' . ($row['first_image'] ?: 'default-camp.jpg')); ?>"
                        alt="Campground Image"> -->
                    <?php
                    $defaultImage = '../assets/default-camp.jpg';
                    $firstImagePath = $row['first_image'];
                    $slugImagePath = '../uploads/' . $row['slug'] . '/' . basename($row['first_image']);

                    // Check if the image from the first method exists
                    if (!empty($firstImagePath) && file_exists($firstImagePath)) {
                        $imageToShow = $firstImagePath;
                    }
                    // Check if the slug-based image exists
                    elseif (!empty($row['first_image']) && file_exists($slugImagePath)) {
                        $imageToShow = $slugImagePath;
                    }
                    // Fallback to default image
                    else {
                        $imageToShow = $defaultImage;
                    }
                    ?>

                    <img src="<?= htmlspecialchars($imageToShow); ?>" alt="Campground Image">


                    <h3><?= htmlspecialchars($row['name']); ?></h3>
                    <p><strong>Location:</strong> <?= htmlspecialchars($row['location']); ?></p>

                    <a href="campground_details.php?slug=<?= urlencode($row['slug']) ?>" class="campground-btn">View Details</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php include("../components/footer.php"); ?>
</body>

</html>