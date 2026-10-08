<?php
include 'includes/header.php';
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<section class="login-form">
  <div class="wrapper">
    <div class="form-header">
      <h3 class="text-center">Login</h3>
    </div>
    <form action="/revamp/controllers/login" method="POST">
      <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
      <?php
      if (isset($_SESSION['error_message']) && !empty($_SESSION['error_message'])) {
        echo '<p class="error-message text-danger text-center">' . htmlspecialchars($_SESSION['error_message']) . '</p>';
        unset($_SESSION['error_message']);
      }
      ?>
      <input type="text" class="" name="name" placeholder="name" />
      <input
        type="password"
        class=""
        name="password"
        id=""
        placeholder="password" />
      <button type="submit">Login</button>
    </form>
  </div>
</section>
<?php
include 'includes/script.php';
?>