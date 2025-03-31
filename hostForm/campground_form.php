<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/database.php';
require_once 'campground.php';

$db = new Database();
$campground = new Campground($db->conn);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register Your Campground</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin: 50px auto;
        }

        .row {
            display: flex;
        }

        .card {
            display: flex;
            flex-direction: column;
        }

        .img-card {
            background-image: url('../assets/hero/hero-1.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Toast Container */
        .toast-container {
            position: fixed;
            top: 15%;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1050;
            color: #fff;
        }

        /* Toast Styling */
        .toast {
            background-color: #dc3545 !important;
            /* Bootstrap "danger" (red) */
            color: white;
        }

        /* images */
        .preview-container {
            margin-top: 10px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .preview-item {
            position: relative;
            display: inline-block;
        }

        .preview-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .delete-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255, 0, 0, 0.8);
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 14px;
            cursor: pointer;
            text-align: center;
            line-height: 18px;
        }

        .delete-btn:hover {
            background: red;
        }
    </style>
</head>

<body>

    <?php include("../components/header.php"); ?>

    <!-- Toast Notifications -->
    <?php if (isset($_SESSION['messages']) && is_array($_SESSION['messages'])): ?>
        <div class="toast-container">
            <?php foreach ($_SESSION['messages'] as $message): ?>
                <div class="toast show" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            <?= htmlspecialchars($message); ?>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['messages']); ?>
    <?php endif; ?>

    <div class="container">
        <div class="row align-items-stretch">
            <div class="col-lg-6 d-flex">
                <div class="card img-card shadow p-4 flex-fill">
                    <!-- <img style="width: 100%; height: 100%; object-fit: cover;" src="../assets/hero/hero-1.jpg" alt=""> -->
                </div>
            </div>

            <div class="col-lg-6 d-flex">
                <div class="card shadow p-4 flex-fill">
                    <h2 class="text-center">Campground Registration</h2>
                    <form action="process_registration.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Campground Name:</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Location:</label>
                            <input type="text" name="location" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone:</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description:</label>
                            <textarea name="description" class="form-control" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Price per Night (₹)</label>
                            <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" required>
                        </div>

                        <div class="mb-3">
                            <label for="images" class="form-label">Upload Images (Max: 6)</label>
                            <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*">
                            <small class="text-muted">You can upload up to 6 images.</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include("../components/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.getElementById("images");
            const previewContainer = document.createElement("div");
            previewContainer.classList.add("preview-container");
            input.parentNode.appendChild(previewContainer);

            let selectedFiles = [];

            input.addEventListener("change", function(event) {
                previewContainer.innerHTML = ""; // Clear previous previews
                const files = Array.from(event.target.files);

                if (files.length + selectedFiles.length > 6) {
                    alert("⚠️ You can only upload up to 6 images.");
                    input.value = ""; // Reset input
                    return;
                }

                selectedFiles = [...selectedFiles, ...files];

                selectedFiles.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgContainer = document.createElement("div");
                        imgContainer.classList.add("preview-item");

                        const img = document.createElement("img");
                        img.src = e.target.result;
                        img.classList.add("preview-image");

                        // Add delete button
                        const deleteBtn = document.createElement("button");
                        deleteBtn.innerHTML = "✖";
                        deleteBtn.classList.add("delete-btn");
                        deleteBtn.onclick = function() {
                            selectedFiles.splice(index, 1);
                            updateFileInput();
                        };

                        imgContainer.appendChild(img);
                        imgContainer.appendChild(deleteBtn);
                        previewContainer.appendChild(imgContainer);
                    };
                    reader.readAsDataURL(file);
                });
            });

            function updateFileInput() {
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => dataTransfer.items.add(file));
                input.files = dataTransfer.files;

                previewContainer.innerHTML = ""; // Clear and re-render preview
                selectedFiles.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgContainer = document.createElement("div");
                        imgContainer.classList.add("preview-item");

                        const img = document.createElement("img");
                        img.src = e.target.result;
                        img.classList.add("preview-image");

                        const deleteBtn = document.createElement("button");
                        deleteBtn.innerHTML = "✖";
                        deleteBtn.classList.add("delete-btn");
                        deleteBtn.onclick = function() {
                            selectedFiles.splice(index, 1);
                            updateFileInput();
                        };

                        imgContainer.appendChild(img);
                        imgContainer.appendChild(deleteBtn);
                        previewContainer.appendChild(imgContainer);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    </script>


</body>

</html>