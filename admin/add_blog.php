<?php
require_once '../config/database.php';
require 'includes/auth.php';  // Ensure only admin can access

// Create a database instance
$db = new Database();
$conn = $db->conn;

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $slug = strtolower(str_replace(' ', '-', trim($title))); // Generate slug
    $slug = preg_replace('/[^a-z0-9-]/', '', $slug); // Remove special characters
    // $content = mysqli_real_escape_string($conn, $_POST['content']);
    $content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');

    $author = mysqli_real_escape_string($conn, $_POST['author']);

    // ✅ Define the upload directory
    $uploadDir = __DIR__ . "/../uploads/blogs/";

    // ✅ Check if the directory exists, if not create it
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Handle image upload
    $imageName = time() . "_" . basename($_FILES['image']['name']); // Prevent duplicate filenames
    $imageTmpName = $_FILES['image']['tmp_name'];
    $imagePath = $uploadDir . $imageName; // Absolute path for moving file
    $imageDbPath = "uploads/blogs/" . $imageName; // Relative path for database

    if (move_uploaded_file($imageTmpName, $imagePath)) {
        // ✅ Insert into database with relative path
        // $insertQuery = "INSERT INTO blogs (title, slug, content, image, author) 
        //                 VALUES ('$title', '$slug', '$content', '$imageDbPath', '$author')";

        $insertQuery = "INSERT INTO blogs (title, slug, content, image, author) 
                VALUES ('$title', '$slug', '$content', '$imageDbPath', '$author')";


        if (mysqli_query($conn, $insertQuery)) {
            $message = "<div class='alert alert-success' role='alert'>Blog added successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger' role='alert'>Error adding blog: " . mysqli_error($conn) . "</div>";
        }
    } else {
        $message = "<div class='alert alert-danger' role='alert'>Image upload failed.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Blog</title>
    <!-- Bootstrap CDN -->
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
                        <h4 class="text-center">Add a New Blog</h4>
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        <form method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Title:</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Content:</label>
                                <!-- <textarea name="content" class="form-control" rows="5" required></textarea> -->
                                <textarea id="blogContent" name="content"></textarea>

                            </div>

                            <div class="mb-3">
                                <label class="form-label">Image:</label>
                                <input type="file" name="image" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Author:</label>
                                <input type="text" name="author" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-success w-100">Publish Blog</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>