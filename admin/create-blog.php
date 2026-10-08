<?php
include 'includes/header.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: /revamp/admin/login");
    exit;
}
?>
<div class="app">
    <?php
    include 'includes/sidebar.php';
    ?>
    <!-- NEW / EDIT POST VIEW -->
    <section class="view" id="view-new">
        <form action="/revamp/controllers/create-blog" method="POST" class="panel" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <div class="field">
                <label for="p-title">Post title</label>
                <input
                    type="text"
                    id="p-title"
                    name="title"
                    placeholder="e.g. Redesigning my portfolio in a weekend" />
            </div>
            <div class="field">
                <label for="p-slug">URL slug</label>
                <input
                    type="text"
                    id="p-slug"
                    class="slug"
                    name="slug"
                    placeholder="redesigning-my-portfolio" />
            </div>
            <div class="field">
                <label for="p-excerpt">Excerpt</label>
                <input
                    type="text"
                    id="p-excerpt"
                    name="excerpt"
                    placeholder="One line summary for the posts list" />
            </div>
            <div class="field">
                <label for="p-content">Content</label>
                <textarea
                    id="p-content"
                    name="content"
                    style="min-height: 160px"
                    placeholder="Write your post..."></textarea>
            </div>
            <div class="field">
                <label for="category">category</label>
                <select name="category" id="category">
                    <option value="" disabled selected>--Select a category--</option>
                    <option value="Software development">Software development</option>
                    <option value="Finance">Finance</option>
                    <option value="Life">Life</option>
                    <option value="Technology">Technology</option>
                </select>
            </div>
            <div class="field">
                <label for="p-tags">Tags (comma separated)</label>
                <input type="text" name="tags" id="p-tags" placeholder="design, process" />
            </div>
            <div class="field">
                <label for="cover_image">Cover Image</label>
                <input type="file" name="cover_image" id="cover_image" placeholder="design, process" />
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit" id="save-post">Save post</button>
            </div>
        </form>
    </section>
    </main>
</div>

<?php
include 'includes/script.php';
?>
<script>
    const easyMDE = new EasyMDE({
        element: document.getElementById('p-content'),
        placeholder: 'Write your post here... Use ```php for code blocks!',
        spellChecker: false,
        status: ['lines', 'words'], // Shows word count at bottom right
        toolbar: [
            'bold', 'italic', 'heading', '|',
            'quote', 'unordered-list', 'ordered-list', '|',
            'link', 'image', 'code', 'table', '|',
            'preview', 'side-by-side', 'fullscreen'
        ]
    });
</script>