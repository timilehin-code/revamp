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

        if (empty($title) || empty($slug)) {
            logProjectError("Validation Error: Title or Slug missing. Title: '{$title}', Slug: '{$slug}'");
            echo "Failed: missing required fields.";
            return false;
        }

        $createBlog = new CreateBlog($title, $slug, $excerpt, $content, $tags, $connection);
        $success = $createBlog->create();

        if ($success) {
            header("Location: /revamp/blog");
            exit;
        } else {
            logProjectError("Database Insert Failed for post title: '{$title}'");
            echo "Failed to insert blog post.";
        }
    }
}
