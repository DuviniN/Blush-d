<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../index.php");
    exit();
}
require_once __DIR__ . '/../../../server/config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Example: change admin name (in demo we won't write to DB)
    $_SESSION['admin_name'] = $_POST['admin_name'] ?? $_SESSION['admin_name'];
    $msg = 'Settings saved';
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Settings - Blush-D</title>
<link rel="stylesheet" href="../assets/css/style.css?v=<?php echo time(); ?>"></head><body>
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
<script src="../assets/js/main.js?v=<?php echo time(); ?>"></script>
</body></html>
