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

// Validate campground slug
if (!isset($_GET['slug'])) {
    die("Invalid campground slug.");
}

$slug = $_GET['slug'];
$campground_details = $campground->getCampgroundBySlug($slug);

if (!$campground_details) {
    die("Campground not found.");
}

$campground_id = $campground_details['id']; // Now we have the ID from the database
$old_slug = $campground_details['slug']; // Store old slug
$old_folder = "../uploads/" . $old_slug; // Old folder path

// Ensure logged-in user is the owner
$logged_in_user_id = $_SESSION['user_id'];
if ($campground_details['user_id'] != $logged_in_user_id) {
    die("You do not have permission to edit this campground.");
}

// Function to generate a slug
function generateSlug($string)
{
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $location = trim($_POST['location']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $description = trim($_POST['description']);

    // Generate new slug from updated name
    $new_slug = generateSlug($name);
    $new_folder = "../uploads/" . $new_slug; // New folder path

    // Rename the uploads folder if the name (slug) is changed
    if ($new_slug !== $old_slug && is_dir($old_folder)) {
        rename($old_folder, $new_folder);
    }

    // Prepare for image uploads
    $uploaded_images = [];
    $target_dir = $new_folder . "/";

    // Check if new images were uploaded
    if (!empty($_FILES['images']['name'][0])) {
        // Get existing images
        $existing_images = $campground->getCampgroundImages($campground_id);

        // Delete old images
        foreach ($existing_images as $image) {
            $image_path = $old_folder . "/" . basename($image['image_path']);
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        // Upload new images
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $file_name = basename($_FILES["images"]["name"][$key]);
            $target_file = $target_dir . $file_name;

            // Create the new folder if it doesn't exist
            if (!is_dir($new_folder)) {
                mkdir($new_folder, 0777, true);
            }

            if (move_uploaded_file($tmp_name, $target_file)) {
                $uploaded_images[] = $file_name;
            }
        }
    } else {
        // Keep existing images if no new ones are uploaded
        $existing_images = $campground->getCampgroundImages($campground_id);
        foreach ($existing_images as $image) {
            $uploaded_images[] = basename($image['image_path']);
        }
    }

    // Update campground details in the database, including the new slug
    $updated = $campground->updateCampground(
        $campground_id,
        $name,
        $location,
        $email,
        $phone,
        $description,
        $uploaded_images, // Updated images array
        $logged_in_user_id,
        $new_slug // Pass new slug
    );

    if ($updated) {
        $_SESSION['message'] = "Campground updated successfully!";
        header("Location: campground_details.php?slug=" . urlencode($new_slug));
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
                <label for="images" class="form-label">Upload New Images (optional, max 6)</label>
                <input type="file" name="images[]" id="images" class="form-control" multiple>

                <div class="mt-2">
                    <?php
                    $existing_images = $campground->getCampgroundImages($campground_id);

                    // Ensure slug is correct (use the updated name if changed)
                    $new_slug = generateSlug($campground_details['name']);
                    $image_folder = "../uploads/" . htmlspecialchars($new_slug) . "/";

                    if (!empty($existing_images)) {
                        foreach ($existing_images as $image) {
                            $image_filename = basename($image['image_path']); // Extract only the filename
                            $image_path = $image_folder . $image_filename;

                            // Debugging output
                            if (!file_exists($image_path)) {
                                echo '<p style="color:red;">Image not found: ' . htmlspecialchars($image_path) . '</p>';
                            } else {
                                echo '<img src="' . htmlspecialchars($image_path) . '" alt="Campground Image" class="me-2" style="max-width: 100px; max-height: 100px;">';
                            }
                        }
                    } else {
                        echo '<img src="../assets/default.jpg" alt="Default Image" class="mt-2" style="max-width: 100px;">';
                    }
                    ?>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Update Campground</button>
            <a href="campground_details.php?slug=<?= urlencode($old_slug) ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <?php include("../components/footer.php"); ?>
</body>

</html>