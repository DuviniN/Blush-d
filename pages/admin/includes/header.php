<?php
// includes/header.php
if (!isset($_SESSION)) session_start();
$adminName = $_SESSION['admin_name'] ?? 'Admin';
$adminPhoto = $_SESSION['admin_photo'] ?? 'uploads/default.png'; // fallback
?>
<header class="topbar">
  <div class="brand">Blush-D Admin</div>
  <!-- <div class="search"> -->
    <!-- <input id="searchInput" placeholder="Search product..."> -->
  <!-- </div> -->
  <div class="actions">
    <!-- <button id="themeToggle">Toggle theme</button> -->
    <span class="admin-name"><?= htmlspecialchars($adminName) ?></span>
    <img src="<?= htmlspecialchars($adminPhoto) ?>" class="topbar-photo" alt="Admin Photo">
    <a class="btn small" href="/Blush-d/pages/admin/logout.php">Logout</a>
  </div>
</header>
