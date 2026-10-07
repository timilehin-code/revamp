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
  <!-- POSTS VIEW -->
  <section class="view" id="view-posts">
    <div class="panel">
      <div id="posts-list"></div>
    </div>
  </section>

  </main>
  <?php
  include 'includes/script.php';
  ?>