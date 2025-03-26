<?php
class Campground
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Function to register a new campground (Linked to the logged-in user)
    public function register($name, $location, $email, $phone, $description, $image, $user_id)
    {
        error_log("Register function called");
        echo ("Register Function Called"); // Debugging log
        // Generate a unique slug
        $slug = $this->generateSlug($name);
        $sql = "INSERT INTO campgrounds (name, location, email, phone, description, image, user_id, slug) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssis", $name, $location, $email, $phone, $description, $image, $user_id, $slug);
        if ($stmt->execute()) {
            return $this->conn->insert_id; // Returns inserted ID
        } else {
            return false;
        }
    }

    // Function to generate a unique slug
    private function generateSlug($name)
    {
        // Convert name to lowercase, remove special characters, and replace spaces with dashes
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        // Ensure slug is unique by appending a number if it already exists
        $counter = 1;
        $originalSlug = $slug;
        while ($this->slugExists($slug)) {
            $slug = $originalSlug . "-" . $counter;
            $counter++;
        }
        return $slug;
    }

    // Function to check if a slug already exists
    private function slugExists($slug)
    {
        $sql = "SELECT COUNT(*) FROM campgrounds WHERE slug = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        $count = 0;
        $stmt->bind_result($count);
        $stmt->fetch();
        return $count > 0;
    }

    // Function to get campground details by Slug 
    public function getCampgroundBySlug($slug)
    {
        $stmt = $this->conn->prepare("SELECT * FROM campgrounds WHERE slug = ?");
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            die("SQL Error: " . $this->conn->error); // Debugging
        }

        $campground = $result->fetch_assoc();

        if (!$campground) {
            die("DEBUG: Campground not found for slug - " . htmlspecialchars($slug));
        }

        return $campground;
    }


    // Function to get campground slugs by campground id
    public function getSlugById($campground_id)
    {
        $stmt = $this->conn->prepare("SELECT slug FROM campgrounds WHERE id = ?");
        $stmt->bind_param("i", $campground_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $campground = $result->fetch_assoc();
        return $campground ? $campground['slug'] : null;
    }

    // Function to delete a campground (Only owner can delete)
    public function deleteCampground($slug, $user_id)
    {
        // Verify if the user owns this campground
        $query = "SELECT id FROM campgrounds WHERE slug = ? AND user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $slug, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return false; // Campground not found or not owned by user
        }

        // Delete campground images first
        $deleteImagesQuery = "DELETE FROM campground_images WHERE campground_id = (SELECT id FROM campgrounds WHERE slug = ?)";
        $stmtImages = $this->conn->prepare($deleteImagesQuery);
        $stmtImages->bind_param("s", $slug);
        $stmtImages->execute();

        // Now delete the campground
        $deleteQuery = "DELETE FROM campgrounds WHERE slug = ?";
        $stmtDelete = $this->conn->prepare($deleteQuery);
        $stmtDelete->bind_param("s", $slug);

        return $stmtDelete->execute();
    }

    // Function to update a campground (Only owner can update)
    public function updateCampground($id, $name, $location, $email, $phone, $description, $uploaded_images, $user_id)
    {
        // Fetch current campground details
        $sql = "SELECT name, slug FROM campgrounds WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($old_name, $old_slug);
        $stmt->fetch();
        $stmt->close();

        // Check if name has changed; if so, generate a new slug
        $new_slug = ($old_name !== $name) ? $this->generateSlug($name) : $old_slug;

        // Update the main campground details
        $sql = "UPDATE campgrounds SET name = ?, slug = ?, location = ?, email = ?, phone = ?, description = ? WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssii", $name, $new_slug, $location, $email, $phone, $description, $id, $user_id);
        $updated = $stmt->execute();
        $stmt->close();

        if (!$updated) {
            return false; // Stop if update failed
        }

        // Image handling: Check if new images are uploaded
        if (!empty($uploaded_images)) {
            // Delete old images from `campground_images` table
            $delete_sql = "DELETE FROM campground_images WHERE campground_id = ?";
            $delete_stmt = $this->conn->prepare($delete_sql);
            $delete_stmt->bind_param("i", $id);
            $delete_stmt->execute();
            $delete_stmt->close();

            // Insert new images
            foreach ($uploaded_images as $image) {
                $insert_sql = "INSERT INTO campground_images (campground_id, image_path) VALUES (?, ?)";
                $insert_stmt = $this->conn->prepare($insert_sql);
                $insert_stmt->bind_param("is", $id, $image);
                $insert_stmt->execute();
                $insert_stmt->close();
            }
        }

        return true; // Update successful
    }

    // Function to check if a campground already exists
    public function campgroundExists($name, $email, $phone)
    {
        return $this->isNameTaken($name) || $this->isEmailTaken($email) || $this->isPhoneTaken($phone);
    }

    public function isNameTaken($name)
    {
        $stmt = $this->conn->prepare("SELECT id FROM campgrounds WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function isEmailTaken($email)
    {
        $stmt = $this->conn->prepare("SELECT id FROM campgrounds WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function isPhoneTaken($phone)
    {
        $stmt = $this->conn->prepare("SELECT id FROM campgrounds WHERE phone = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function getCampgroundImages($campground_id)
    {
        $stmt = $this->conn->prepare("SELECT image_path FROM campground_images WHERE campground_id = ?");
        $stmt->bind_param("i", $campground_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
