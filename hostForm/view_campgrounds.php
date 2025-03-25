<?php
require_once '../config/database.php';

$db = new Database();
$conn = $db->conn;

$sql = "SELECT * FROM campgrounds ORDER BY created_at DESC";
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
            /* margin: 20px; */
            background-color: #f8f9fa;
        }

        .campground-container {
            /* max-width: 1200px; */
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
                    <img src="<?= htmlspecialchars($row['image']); ?>" alt="Campground Image">
                    <h3><?= htmlspecialchars($row['name']); ?></h3>
                    <p><strong>Location:</strong> <?= htmlspecialchars($row['location']); ?></p>
                    <a href="campground_details.php?id=<?= $row['id']; ?>" class="campground-btn">View</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php include("../components/footer.php"); ?>
</body>

</html>