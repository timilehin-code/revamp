<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/conn.php';

use models\admin\CreateBlog;

function createPost()
{
    $connection = $GLOBALS['connection'] ?? null;

    if (!$connection) {
        logProjectError("Controller Error: \$connection is null or invalid.");
        echo "Failed to create post. Check logs.";
        return false;
    }

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $title   = trim($_POST['title'] ?? '');
        $slug    = trim($_POST['slug'] ?? '');
        $excerpt = trim($_POST['Excerpt'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $rawTags = trim($_POST['tags'] ?? '');
        $tagsArray = array_map('trim', explode(',', $rawTags));
        $tags = json_encode($tagsArray);
        $imagePath = '';
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['cover_image']['tmp_name'];
            $fileName    = $_FILES['cover_image']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            // Validate allowed extensions
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            if (!in_array($fileExtension, $allowedExtensions)) {
                logProjectError("Image Error: Invalid file format.");
                echo "Invalid image format. Allowed: JPG, PNG, WEBP, GIF.";
                return false;
            }

            // Target directory: revamp/uploads/blogs/
            $uploadDir = __DIR__ . '/../../uploads/blogs/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Create a unique filename to prevent overwrites
            $newFileName = $slug . '-' . time() . '.' . $fileExtension;
            $destination = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destination)) {
                // Save relative path for view rendering
                $imagePath = 'uploads/blogs/' . $newFileName;
            } else {
                logProjectError("Image Error: Failed to move uploaded file.");
                echo "Failed to upload image.";
                return false;
            }
        } else {
            logProjectError("Image Error: No image uploaded or upload error code " . $_FILES['cover_image']['error']);
            echo "Image is required.";
            return false;
        }

        if (empty($title) || empty($slug)) {
            logProjectError("Validation Error: Title or Slug missing. Title: '{$title}', Slug: '{$slug}'");
            echo "Failed: missing required fields.";
            return false;
        }

        $createBlog = new CreateBlog($title, $slug, $excerpt, $content, $tags, $imagePath, $connection);
        $success = $createBlog->create();

        if ($success) {
            header("Location: /revamp/admin");
            exit;
        } else {
            // Delete uploaded file if DB insertion failed
            if (!empty($imagePath) && file_exists(__DIR__ . '/../../' . $imagePath)) {
                unlink(__DIR__ . '/../../' . $imagePath);
            }
            logProjectError("Database Insert Failed for post title: '{$title}'");
            echo "Failed to insert blog post. Check app.log.";
        }
    }
}
