<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/conn.php';

use Models\admin\CreateBlog;

function createPost()
{
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $title = trim($_POST['title']);
        $slug = trim($_POST['slug']);
        $excerpt = trim($_POST['Excerpt']);
        $content = trim($_POST['content']);
        $tags = trim($_POST['tags']);
        global $connection;
        if (empty($title) || empty($slug)) {
            return false;
        }
        try {
            $createBlog = new CreateBlog($title, $slug, $excerpt, $content, $tags, $connection);
        $createBlog->getCreate();
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
        }
    }
}
