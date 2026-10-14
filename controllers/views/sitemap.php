<?php

require_once __DIR__ . '/../../vendor/autoload.php';

function generateSitemap()
{
    $connection = $GLOBALS['connection'] ?? null;

    // Define your site's base URL
    $baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/revamp';

    // 1. Static Pages Array
    $staticPages = [
        ['loc' => '/', 'priority' => '1.0', 'changefreq' => 'daily'],
        ['loc' => '/about', 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => '/contact', 'priority' => '0.5', 'changefreq' => 'yearly'],
        ['loc' => '/blog', 'priority' => '0.9', 'changefreq' => 'daily'],
    ];

    // 2. Fetch Dynamic Blog Posts from Database
    $dynamicPosts = [];
    if ($connection) {
        $stmt = $connection->prepare("SELECT slug, updated_at FROM blog WHERE published = 0");
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $dynamicPosts[] = [
                'loc' => '/blog/' . $row['slug'],
                'lastmod' => date('Y-m-d', strtotime($row['updated_at'])),
                'priority' => '0.7',
                'changefreq' => 'weekly'
            ];
        }
        $stmt->close();
    }

    // 3. Clear Output Buffer & Set XML Headers
    if (ob_get_length()) ob_clean();
    header("Content-Type: application/xml; charset=utf-8");

    // 4. Output XML Structure
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    // Output Static Pages
    foreach ($staticPages as $page) {
        echo '<url>';
        echo '<loc>' . htmlspecialchars($baseUrl . $page['loc']) . '</loc>';
        echo '<lastmod>' . date('Y-m-d') . '</lastmod>';
        echo '<changefreq>' . $page['changefreq'] . '</changefreq>';
        echo '<priority>' . $page['priority'] . '</priority>';
        echo '</url>';
    }

    // Output Dynamic Blog Posts
    foreach ($dynamicPosts as $post) {
        echo '<url>';
        echo '<loc>' . htmlspecialchars($baseUrl . $post['loc']) . '</loc>';
        echo '<lastmod>' . $post['lastmod'] . '</lastmod>';
        echo '<changefreq>' . $post['changefreq'] . '</changefreq>';
        echo '<priority>' . $post['priority'] . '</priority>';
        echo '</url>';
    }

    echo '</urlset>';
    exit;
}
