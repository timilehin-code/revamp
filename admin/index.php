<?php
include '../includes/header.php';
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
  include '../includes/sidebar.php';
  ?>
  <main class="content">
    <div class="topbar">
      <div>
        <h1 id="page-title">Home Page</h1>
        <p id="page-sub">What visitors see first on your portfolio.</p>
      </div>
    </div>

    <!-- HOME VIEW -->
    <section class="view active" id="view-home">
      <form action="" method="POST" class="panel">
        <div class="field">
          <label for="h-title">Hero title</label>
          <input
            type="text"
            id="h-title"

            placeholder="e.g. Hi, I'm Timi. I build things." />
        </div>
        <div class="field">
          <label for="h-subtitle">Hero subtitle</label>
          <input
            type="text"
            id="h-subtitle"
            placeholder="e.g. Product designer & frontend developer" />
        </div>
        <div class="field">
          <label for="h-bio">About / bio</label>
          <textarea
            id="h-bio"
            placeholder="A couple of sentences about you."></textarea>
        </div>
        <div class="field">
          <label for="h-avatar">Avatar image URL</label>
          <input
            type="url"
            id="h-avatar"
            placeholder="https://example.com/avatar.jpg" />
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" id="save-home">
            Save home page
          </button>
        </div>
      </form>
    </section>


</div>

<div class="toast" id="toast"></div>

<?php
include '../includes/script.php';
?>