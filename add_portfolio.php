<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection
include 'db.php';

// Handle form submission for adding a new portfolio item
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $category = $_POST['category']; // Get selected category
    $image = $_FILES['image'];

    $valid_formats = ['image/jpeg', 'image/png', 'image/gif'];  // Allowed image formats

    // Check for upload errors
    if ($image['error'] !== UPLOAD_ERR_OK) {
        $error = "File upload error: " . $image['error'];
    } elseif (!in_array($image['type'], $valid_formats)) {
        // Validate the image format
        $error = "Invalid image format. Please upload JPG, PNG, or GIF files only.";
    } else {
        // Handle image upload if valid
        $image_name = basename($image['name']);
        $image_path = 'uploads/' . $image_name;

        // Ensure the uploads directory exists
        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true);
        }

        // Move the uploaded file temporarily
        if (move_uploaded_file($image['tmp_name'], $image_path)) {
            // Resize the image
            resizeImage($image_path, 800, 600); // Resize to max width 800px and height 600px

            // Insert new portfolio item (only category and image_path)
            $sql = "INSERT INTO portfolio_items (category, image_path) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $category, $image_path);

            if ($stmt->execute()) {
                $success = "Portfolio item added successfully!";
                header("Location: admin.php"); // Redirect to admin page after successful add
                exit();
            } else {
                $error = "Error adding portfolio item: " . $conn->error;
            }
            $stmt->close();
        } else {
            $error = "Failed to upload the image.";
        }
    }

    $conn->close();
}

// Function to resize images
function resizeImage($file, $maxWidth, $maxHeight) {
    list($width, $height, $type) = getimagesize($file);

    $ratio = $width / $height;

    // Determine new dimensions
    if ($maxWidth / $maxHeight > $ratio) {
        $maxWidth = $maxHeight * $ratio;
    } else {
        $maxHeight = $maxWidth / $ratio;
    }

    $src = imagecreatefromstring(file_get_contents($file));
    $dst = imagecreatetruecolor($maxWidth, $maxHeight);

    // Resize the image
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $maxWidth, $maxHeight, $width, $height);

    // Save the resized image
    imagejpeg($dst, $file, 90); // Save as JPEG with 90% quality

    // Free up memory
    imagedestroy($src);
    imagedestroy($dst);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Portfolio Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="text-center mt-5">Add Portfolio Item</h2>

        <!-- Display success or error messages -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php elseif (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Form for adding a new portfolio item -->
        <form action="add_portfolio.php" method="POST" enctype="multipart/form-data">
            
            <div class="mb-3">
                <label for="category" class="form-label">Select Category</label>
                <select name="category" class="form-select" id="category" required>
                    <option value="">Choose a category</option>
                    <option value="Weddings">Weddings</option>
                    <option value="Portraits">Portraits</option>
                    <option value="Landscapes">Landscapes</option>
                    <option value="Events">Events</option>
                    <option value="Fashion">Fashion</option>
                    <option value="Commercial">Commercial</option>
                    <option value="Product">Product</option>
                    <option value="Travel">Travel</option>
                    <option value="Nature">Nature</option>
                    <option value="Sports">Sports</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Upload Image (JPG, PNG, GIF only)</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/jpeg, image/png, image/gif" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Add Portfolio Item</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
