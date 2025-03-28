<?php
require '../config/database.php';
require 'includes/auth.php'; // Only admin can access

$db = new Database();
$conn = $db->conn;

// Fetch all blogs
$result = mysqli_query($conn, "SELECT * FROM blogs ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Blogs</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body class="bg-light">
    <?php include 'includes/navbar.php'; ?>
    <div class="container mt-5">
        <h2 class="text-center text-primary mb-4">Manage Blogs</h2>
        <div class="text-end mb-3">
            <a href="add_blog.php" class="btn btn-success"><i class="bi bi-plus-lg"></i> Add New Blog</a>
        </div>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center bg-white shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($blog = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><img src="../<?php echo $blog['image']; ?>" alt="Blog Image" class="img-thumbnail" width="100"></td>
                                <td><?php echo $blog['title']; ?></td>
                                <td><?php echo $blog['author']; ?></td>
                                <td><?php echo $blog['created_at']; ?></td>
                                <td>
                                    <a href="edit_blog.php?slug=<?php echo $blog['slug']; ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                                    <a href="delete_blog.php?slug=<?php echo $blog['slug']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">No blogs available.</div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>