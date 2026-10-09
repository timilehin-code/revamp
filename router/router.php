<?php

use Bramus\Router\Router;

$router = new Router();

// Simple GET route
$router->get('/', function () {
    require_once __DIR__ . '/../config/conn.php';
    require_once __DIR__ . '/../views/index.php';
});




// 404 Handler
$router->set404(function () {
    header('HTTP/1.1 404 Not Found');
    require_once __DIR__ . '/../views/404.php';
});
$router->get('/home', function () {
    require_once __DIR__ . '/../config/conn.php';
    require_once __DIR__ . '/../views/index.php';
});

$router->get('/blog', function () {
    require_once __DIR__ . '/../views/blog.php';
});
$router->get('/Blog', function () {
    require_once __DIR__ . '/../views/blog.php';
});
// Route with dynamic URL parameters
$router->get('/blog/{slug}', function ($slug) {
    require_once __DIR__ . '/../config/conn.php';
    $connection = $GLOBALS['connection'] ?? null;

    if (!$connection) {
        logProjectError("Router Error: \$connection is null.");
        echo "Database connection failed.";
        return;
    }

    $blogsModel = new models\views\Blogs($connection);
    $post = $blogsModel->getBySlug($slug);

    // Render 404 page if no post matches the slug
    if (!$post) {
        header('HTTP/1.1 404 Not Found');
        require_once __DIR__ . '/../views/404.php';
        return;
    }
    $parseDown = new Parsedown();
    $parseDown->setSafeMode(true);
    $post['content'] = $parseDown->text($post['content']);
    // Load single post view and pass $post
    require_once __DIR__ . '/../views/blog-post.php';
});

$router->get('/Admin', function () {
    require_once __DIR__ . '/../admin/index.php';
});

$router->get("/guests", function () {
    require_once __DIR__ . '/../config/conn.php';
    require_once __DIR__ . '/../views/guests.php';
});
$router->post("/controllers/guests", function () {
    require_once __DIR__ . '/../config/conn.php';
    require_once __DIR__ . '/../controllers/views/guest.php';
    saveGuestNote();
});

// $router->get('/admin/register', function () {
//     require_once __DIR__ . '/../admin/registration.php';
// });

// $router->post('/controllers/register', function () {
//     require_once __DIR__ . '/../controllers/admin/authentication.php';
//     register();
// });
$router->get('/admin/login', function () {
    require_once __DIR__ . '/../config/conn.php';
    require_once __DIR__ . '/../admin/login.php';
});

$router->get('/admin/logout', function () {
    require_once __DIR__ . '/../controllers/admin/authentication.php';
    logout();
});

$router->post('/controllers/login', function () {
    require_once __DIR__ . '/../controllers/admin/authentication.php';
    login();
});

$router->get('/admin/create-blog', function () {
    require_once __DIR__ . '/../config/conn.php';
    require_once __DIR__ . '/../admin/create-blog.php';
});


$router->get('/admin/blogs/', function () {
    require_once __DIR__ . '/../admin/blogs.php';
});

// 
$router->post('/controllers/create-blog', function () {
    require_once __DIR__ . '/../../config/conn.php';

    require_once __DIR__ . '/../controllers/admin/createBlog.php';
    createPost();
});

$router->post('/mail', function () {

    require_once __DIR__ . '/../config/conn.php';

    require_once __DIR__ . '/../controllers/views/mail.php';
    sendMail();
});

// Execute the router
$router->run();
