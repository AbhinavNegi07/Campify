<?php
require '../config/database.php';
require 'includes/auth.php'; // Ensure only admin can access

$db = new Database();
$conn = $db->conn;

if (!isset($_GET['slug'])) {
    die("<div class='container mt-5'><div class='alert alert-danger'>Blog not found.</div></div>");
}

$slug = mysqli_real_escape_string($conn, $_GET['slug']);

// Get blog image
$result = mysqli_query($conn, "SELECT image FROM blogs WHERE slug='$slug'");
$blog = mysqli_fetch_assoc($result);

if (!$blog) {
    die("<div class='container mt-5'><div class='alert alert-danger'>Blog not found.</div></div>");
}

// Delete image file
$imagePath = "../" . $blog['image'];
if (file_exists($imagePath)) {
    unlink($imagePath);
}

// Delete blog from database
$deleteQuery = "DELETE FROM blogs WHERE slug='$slug'";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Blog</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php include 'includes/navbar.php'; ?>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg p-4 text-center">
            <?php if (mysqli_query($conn, $deleteQuery)): ?>
                <div class="alert alert-success">✅ Blog deleted successfully!</div>
                <a href="blogs.php" class="btn btn-primary mt-3">Back to Blogs</a>
            <?php else: ?>
                <div class="alert alert-danger">❌ Error deleting blog.</div>
                <a href="blogs.php" class="btn btn-warning mt-3">Try Again</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>