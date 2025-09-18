<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../index.php");
    exit();
}

$adminName = $_SESSION['first_name'] ?? 'Admin';
$adminPhoto = $_SESSION['photo'] ?? '../uploads/default.jpg';

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
    <button id="logoutBtn" class="btn small">Logout</button>
  </div>
</header>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="modal">
  <div class="modal-content">
    <h3>Confirm Logout</h3>
    <p>Are you sure you want to logout?</p>
    <div class="modal-actions">
      <button id="cancelLogout" class="btn btn-secondary">Cancel</button>
      <button id="confirmLogout" class="btn btn-danger">Logout</button>
    </div>
  </div>
</div>
