<?php
require_once '../config/database.php';
require_once 'campground.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "Please log in to edit a campground.";
    header("Location: login.php");
    exit;
}

$db = new Database();
$campground = new Campground($db->conn);

// Validate campground ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid campground ID.");
}

$campground_id = $_GET['id'];
$campground_details = $campground->getCampgroundById($campground_id);

if (!$campground_details) {
    die("Campground not found.");
}

// Ensure logged-in user is the owner
$logged_in_user_id = $_SESSION['user_id'];
if ($campground_details['user_id'] != $logged_in_user_id) {
    die("You do not have permission to edit this campground.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $location = trim($_POST['location']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $description = trim($_POST['description']);

    // Handle image upload if a new file is provided
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $image_path = $target_file;
    } else {
        $image_path = $campground_details['image']; // Keep the existing image
    }

    // Update campground
    $updated = $campground->updateCampground($campground_id, $name, $location, $email, $phone, $description, $image_path, $logged_in_user_id);



    if ($updated) {
        $_SESSION['message'] = "Campground updated successfully!";
        header("Location: campground_details.php?id=" . $campground_id);
        exit;
    } else {
        $_SESSION['message'] = "Failed to update campground.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Campground</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <?php include("../components/header.php"); ?>

    <div class="container mt-5">
        <h2>Edit Campground</h2>

        <?php if (isset($_SESSION['message'])) : ?>
            <div class="alert alert-info"><?= $_SESSION['message']; ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Campground Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($campground_details['name']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" name="location" id="location" class="form-control" value="<?= htmlspecialchars($campground_details['location']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Contact Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($campground_details['email']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Contact Phone</label>
                <input type="text" name="phone" id="phone" class="form-control" value="<?= htmlspecialchars($campground_details['phone']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="5" required><?= htmlspecialchars($campground_details['description']) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Upload New Image (optional)</label>
                <input type="file" name="image" id="image" class="form-control">
                <img src="<?= htmlspecialchars($campground_details['image'] ?: '../assets/default.jpg') ?>" alt="Current Image" class="mt-2" style="max-width: 200px;">
            </div>

            <button type="submit" class="btn btn-success">Update Campground</button>
            <a href="campground_details.php?id=<?= $campground_id ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <?php include("../components/footer.php"); ?>
</body>

</html>