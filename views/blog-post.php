<?php
$title = $post["title"];
$description = trim($post['excerpt'] ?? '') ?: $post['title'];
$ogType = 'article';
$canonicalUrl = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/revamp/blog/' . ($post['slug'] ?? '');
if (!empty($post['cover_image'])) {
  $ogImage = '/revamp/' . ltrim($post['cover_image'], '/');
}
$metaRobots = 'index, follow';
include 'includes/header.php';
include 'includes/navigation.php';
$jsonString = $post['tags'];

// Decode the JSON string into an array
$tags = json_decode($jsonString, true);

if (is_array($tags) && !empty($tags)) {
  // 2. Prepend '#' to each tag and join with spaces
  $formattedTags = implode('  ', array_map(fn($tag) => '#' . trim($tag), $tags));
}
?>
<main class="blog-post">
  <div class="container">
    <div class="breadCrumbs">
      <a href="../">Home</a>
      <span>//</span>
      <a href="../blog">Blog</a>
    </div>
    <div class="category">
      <p><?= htmlspecialchars($post["category"]) ?></p>
    </div>
    <div class="title">
      <h2>
        <?= htmlspecialchars($post["title"]) ?>
      </h2>
    </div>
    <div class="hashtag">
      <p><?= htmlspecialchars($formattedTags) ?></p>
    </div>
    <div class="blog-image">
      <?php if (!empty($post['cover_image'])): ?>
        <img src="/revamp/<?= htmlspecialchars($post['cover_image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
      <?php endif; ?>
    </div>
    <section class="blog-note">
      <?= $post['content'] ?>
    </section>
  </div>

  <div class="container">
    <hr />
    <div class="share">
      <div class="links">
        <p>share:</p>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($_SERVER['REQUEST_URI']) ?>" target="_blank"> <i class="fa-brands fa-facebook"></i></a>
        <a href="https://twitter.com/intent/tweet?text=<?= urlencode($post['title']) ?>&url=<?= urlencode($_SERVER['REQUEST_URI']) ?>" target="_blank"> <i class="fa-brands fa-x-twitter"></i></a>
        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode($_SERVER['REQUEST_URI']) ?>&title=<?= urlencode($post['title']) ?>" target="_blank"><i class="fa-brands fa-linkedin"></i></a>
      </div>
      <a href="/revamp/blog">
        <button>Back to blog</button>
      </a>
    </div>
  </div>
  <hr />
  <div class="container">
    <div class="related-post">
      <h3>Related Posts <i class="fa-solid fa-circle-arrow-right"></i></h3>
      <section class="posts-sections">
        <div class="container">
          <div class="post-cards">
            <div class="post-card">
              <div class="post-card-img">
                <img
                  src="https://placehold.co/600x400?text=Hello+World"
                  alt="" />
              </div>
              <div class="post-card-content">
                <div class="category">software development</div>
                <h4>Understanding the Basics of Web Development</h4>
                <div class="author-date d-flex justify-content-between">
                  <div class="author">
                    <i class="fa-regular fa-clock"></i> 3 mins read
                  </div>
                  <div class="date">
                    <i class="fa-solid fa-calendar-days"></i> 12th June,
                    2024
                  </div>
                </div>
              </div>
            </div>
            <div class="post-card">
              <div class="post-card-img">
                <img
                  src="https://placehold.co/600x400?text=Hello+World"
                  alt="" />
              </div>
              <div class="post-card-content">
                <div class="category">software development</div>
                <h4>Understanding the Basics of Web Development</h4>
                <div class="author-date d-flex justify-content-between">
                  <div class="author">
                    <i class="fa-regular fa-clock"></i> 3 mins read
                  </div>
                  <div class="date">
                    <i class="fa-solid fa-calendar-days"></i> 12th June,
                    2024
                  </div>
                </div>
              </div>
            </div>
            <div class="post-card">
              <div class="post-card-img">
                <img
                  src="https://placehold.co/600x400?text=Hello+World"
                  alt="" />
              </div>
              <div class="post-card-content">
                <div class="category">software development</div>
                <h4>Understanding the Basics of Web Development</h4>
                <div class="author-date d-flex justify-content-between">
                  <div class="author">
                    <i class="fa-regular fa-clock"></i> 3 mins read
                  </div>
                  <div class="date">
                    <i class="fa-solid fa-calendar-days"></i> 12th June,
                    2024
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</main>
<!-- 3. Trigger Highlighting -->

<?php
$articleSchema = [
  '@context' => 'https://schema.org',
  '@type' => 'BlogPosting',
  'headline' => $post['title'] ?? '',
  'description' => $description,
  'image' => !empty($post['cover_image']) ? 'https://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/revamp/' . ltrim($post['cover_image'], '/') : 'https://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/revamp/views/assets/img/profile.png',
  'author' => [
    '@type' => 'Person',
    'name' => 'Olúwàtimíléhìn'
  ],
  'publisher' => [
    '@type' => 'Person',
    'name' => 'Olúwàtimíléhìn',
    'logo' => [
      '@type' => 'ImageObject',
      'url' => 'https://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/revamp/views/assets/img/profile.png'
    ]
  ],
  'datePublished' => !empty($post['created_at']) ? date(DATE_ATOM, strtotime($post['created_at'])) : date(DATE_ATOM),
  'dateModified' => !empty($post['updated_at']) ? date(DATE_ATOM, strtotime($post['updated_at'])) : (!empty($post['created_at']) ? date(DATE_ATOM, strtotime($post['created_at'])) : date(DATE_ATOM)),
  'mainEntityOfPage' => [
    '@type' => 'WebPage',
    '@id' => $canonicalUrl
  ],
  'articleSection' => $post['category'] ?? 'Uncategorized',
  'keywords' => is_array($tags) ? implode(', ', $tags) : ''
];
?>
<script type="application/ld+json">
<?= json_encode($articleSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?>
</script>
<?php
include 'includes/footer.php';
include 'includes/script.php';
?>
<script>
  hljs.highlightAll();
</script>