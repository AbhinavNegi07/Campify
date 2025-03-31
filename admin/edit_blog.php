<?php
require_once '../config/database.php';
require 'includes/auth.php'; // Ensure only admin can access

$db = new Database();
$conn = $db->conn;

// Get the blog by slug
if (isset($_GET['slug'])) {
    $slug = mysqli_real_escape_string($conn, $_GET['slug']);
    $query = "SELECT * FROM blogs WHERE slug = '$slug'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $blog = mysqli_fetch_assoc($result);
    } else {
        die("<div class='container mt-5'><div class='alert alert-danger'>Blog not found!</div></div>");
    }
} else {
    die("<div class='container mt-5'><div class='alert alert-danger'>Invalid request!</div></div>");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $newSlug = strtolower(str_replace(' ', '-', trim($title))); // Generate new slug
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);

    // Image Handling
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = __DIR__ . "/../uploads/blogs/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imagePath = $uploadDir . $imageName;
        $imageDbPath = "uploads/blogs/" . $imageName;

        if (move_uploaded_file($imageTmpName, $imagePath)) {
            $updateImage = ", image = '$imageDbPath'";
        } else {
            die("<div class='container mt-5'><div class='alert alert-danger'>Image upload failed!</div></div>");
        }
    } else {
        $updateImage = "";
    }

    // Update Query
    $updateQuery = "UPDATE blogs SET title = '$title', slug = '$newSlug', content = '$content', author = '$author' $updateImage WHERE slug = '$slug'";

    if (mysqli_query($conn, $updateQuery)) {
        echo "<div class='container mt-5'><div class='alert alert-success'>✅ Blog updated successfully!</div></div>";
        header("refresh:2;url=blogs.php"); // Redirect after 2 seconds
        exit;
    } else {
        echo "<div class='container mt-5'><div class='alert alert-danger'>❌ Error updating blog.</div></div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blog</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.tiny.cloud/1/$_ENV['TINYMCE_KEY']/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#blogContent',
            height: 400,
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount',
            toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code',
            menubar: false
        });
    </script>
</head>

<body class="bg-light">
    <?php include 'includes/navbar.php'; ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h4>Edit Blog</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" value="<?php echo $blog['title']; ?>" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Content</label>
                                <!-- <textarea name="content" class="form-control" rows="5" required><?php echo $blog['content']; ?></textarea> -->
                                <textarea id="blogContent" name="content" class="form-control"><?php echo htmlspecialchars_decode($blog['content']); ?></textarea>

                            </div>

                            <div class="mb-3">
                                <label class="form-label">Current Image</label><br>
                                <img src="../<?php echo $blog['image']; ?>" class="img-fluid rounded shadow-sm" width="200">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">New Image (optional)</label>
                                <input type="file" name="image" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Author</label>
                                <input type="text" name="author" value="<?php echo $blog['author']; ?>" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-success">Update Blog</button>
                            <a href="blogs.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>