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
        echo ("Register Function Called"); // Logs to PHP error log

        $sql = "INSERT INTO campgrounds (name, location, email, phone, description, image, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssi", $name, $location, $email, $phone, $description, $image, $user_id);

        if ($stmt->execute()) {
            return $this->conn->insert_id; // Returns inserted ID
        } else {
            return false;
        }
    }

    // Function to get campground details by ID (Also fetches user_id)
    // public function getCampgroundById($id)
    // {
    //     $sql = "SELECT * FROM campgrounds WHERE id = ?";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->bind_param("i", $id);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
    //     return $result->fetch_assoc(); // Returns an associative array
    // }

    public function getCampgroundById($id)
    {
        $sql = "SELECT id, name, location, email, phone, description, image, user_id FROM campgrounds WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // Returns associative array
    }

    // Function to delete a campground (Only owner can delete)
    // public function deleteCampground($id, $user_id)
    // {
    //     // Check if the campground belongs to the logged-in user
    //     $stmt = $this->conn->prepare("SELECT user_id, image FROM campgrounds WHERE id = ?");
    //     $stmt->bind_param("i", $id);
    //     $stmt->execute();
    //     $stmt->bind_result($camp_owner_id, $image);
    //     $stmt->fetch();
    //     $stmt->close();

    //     // If the logged-in user is not the owner, deny access
    //     if ($camp_owner_id !== $user_id) {
    //         return "Unauthorized access!";
    //     }

    //     // If an image exists, delete it from the uploads folder
    //     if (!empty($image) && file_exists($image)) {
    //         unlink($image);
    //     }

    //     // Now delete the campground from the database
    //     $stmt = $this->conn->prepare("DELETE FROM campgrounds WHERE id = ?");
    //     $stmt->bind_param("i", $id);
    //     $result = $stmt->execute();
    //     $stmt->close();

    //     return $result;
    // }

    public function deleteCampground($id, $user_id)
    {
        // Check if the campground belongs to the logged-in user
        $stmt = $this->conn->prepare("SELECT user_id, image FROM campgrounds WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($camp_owner_id, $image);
        $stmt->fetch();
        $stmt->close();

        // If the logged-in user is not the owner, deny access
        if ($camp_owner_id !== $user_id) {
            return false; // Unauthorized
        }

        // If an image exists, delete it from the uploads folder
        if (!empty($image) && file_exists($image)) {
            unlink($image);
        }

        // Now delete the campground from the database
        $stmt = $this->conn->prepare("DELETE FROM campgrounds WHERE id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }


    // Function to update a campground (Only owner can update)
    // public function updateCampground($id, $name, $location, $email, $phone, $description, $image, $user_id)
    // {
    //     // Check if the campground belongs to the logged-in user
    //     $stmt = $this->conn->prepare("SELECT user_id FROM campgrounds WHERE id = ?");
    //     $stmt->bind_param("i", $id);
    //     $stmt->execute();
    //     $stmt->bind_result($camp_owner_id);
    //     $stmt->fetch();
    //     $stmt->close();

    //     // If the logged-in user is not the owner, deny access
    //     if ($camp_owner_id !== $user_id) {
    //         return "Unauthorized access!";
    //     }

    //     // Update campground details
    //     $sql = "UPDATE campgrounds SET name = ?, location = ?, email = ?, phone = ?, description = ?, image = ? WHERE id = ?";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->bind_param("ssssssi", $name, $location, $email, $phone, $description, $image, $id);

    //     return $stmt->execute();
    // }

    public function updateCampground($id, $name, $location, $email, $phone, $description, $image, $user_id)
    {
        $sql = "UPDATE campgrounds SET name = ?, location = ?, email = ?, phone = ?, description = ?, image = ? WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssii", $name, $location, $email, $phone, $description, $image, $id, $user_id);

        return $stmt->execute();
    }

    // Function to check if a campground already exists
    public function campgroundExists($name, $email, $phone)
    {
        return $this->isNameTaken($name) || $this->isEmailTaken($email) || $this->isPhoneTaken($phone);
    }

    private function isNameTaken($name)
    {
        $stmt = $this->conn->prepare("SELECT id FROM campgrounds WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    private function isEmailTaken($email)
    {
        $stmt = $this->conn->prepare("SELECT id FROM campgrounds WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    private function isPhoneTaken($phone)
    {
        $stmt = $this->conn->prepare("SELECT id FROM campgrounds WHERE phone = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
}
