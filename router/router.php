<?php

use Bramus\Router\Router;

$router = new Router();

// Simple GET route
$router->get('/', function () {
    require_once __DIR__ . '/../views/index.php';
});

$router->get('/home', function () {
    require_once __DIR__ . '/../views/index.php';
});

$router->get('/blog', function () {
    require_once __DIR__ . '/../views/blog.php';
});
$router->get('/index', function () {
    require_once __DIR__ . '/../views/index.php';
});
$router->get('/Blog', function () {
    require_once __DIR__ . '/../views/blog.php';
});
// Route with dynamic URL parameters
$router->get('/blog/{slug}', function ($slug) {
    $cleanSlug = htmlspecialchars($slug);
    require_once __DIR__ . '/../views/blog-post.php';
});

// 404 Handler
$router->set404(function () {
    header('HTTP/1.1 404 Not Found');
    require_once __DIR__ . '/../views/404.php';
});

// Execute the router
$router->run();
