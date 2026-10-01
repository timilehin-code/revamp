<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/conn.php';
require_once __DIR__ . '/../../models/views/blogs.php';

use Models\Views\Blogs;

$connection = $GLOBALS['connection'] ?? null;

if ($connection) {
    $blogsModel = new Blogs($connection);
    $allBlogs = $blogsModel->getAllPosts();
}
