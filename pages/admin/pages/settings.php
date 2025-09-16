<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Example: change admin name (in demo we won't write to DB)
    $_SESSION['admin_name'] = $_POST['admin_name'] ?? $_SESSION['admin_name'];
    $msg = 'Settings saved';
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Settings - Blush-D</title>
<link rel="stylesheet" href="/Blush-d/pages/admin/assets/css/style.css"></head><body>
<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="container">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>
  <main class="content">
    <h1>Settings</h1>
    <?php if(!empty($msg)) echo "<p class='success'>{$msg}</p>"; ?>
    <div class="card form-card">
      <form method="post">
        <label>Admin display name</label>
        <input name="admin_name" value="<?=htmlspecialchars($_SESSION['admin_name'] ?? '')?>">
        <!-- <label>Theme</label> -->
        <!-- <div> -->
          <!-- <button type="button" id="themeToggle">Toggle theme</button> -->
        <!-- </div> -->
        <button class="btn" type="submit">Save</button>
      </form>
    </div>
  </main>
</div>
<script src="/Blush-d/pages/admin/assets/js/main.js"></script>
</body></html>
