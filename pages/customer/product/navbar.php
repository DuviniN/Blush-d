<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Navbar HTML -->
<nav class="navbar">
  <div class="logo">Blush’d Cosmetics</div>

  <!-- Category links -->
  <ul class="nav-links">
    <li><a href="../dashboard.php?cat=All" class="<?php echo (isset($_GET['cat']) && $_GET['cat']==='All') ? 'active' : ''; ?>">All</a></li>
    <li><a href="../dashboard.php?cat=Skin" class="<?php echo (isset($_GET['cat']) && $_GET['cat']==='Skin') ? 'active' : ''; ?>">Skin</a></li>
    <li><a href="../dashboard.php?cat=Hair" class="<?php echo (isset($_GET['cat']) && $_GET['cat']==='Hair') ? 'active' : ''; ?>">Hair</a></li>
    <li><a href="../dashboard.php?cat=Makeup" class="<?php echo (isset($_GET['cat']) && $_GET['cat']==='Makeup') ? 'active' : ''; ?>">Makeup</a></li>
    <li><a href="../dashboard.php?cat=Tools" class="<?php echo (isset($_GET['cat']) && $_GET['cat']==='Tools') ? 'active' : ''; ?>">Tools</a></li>
  </ul>

  <!-- User info and logout -->
  <div class="nav-user">
    <?php if (isset($_SESSION['user_name'])): ?>
        <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
        <a href="../logout.php" class="logout-btn">Logout</a>
    <?php else: ?>
        <a href="../login/login.php" class="login-btn">Login</a>
    <?php endif; ?>
  </div>
</nav>

<!-- Include review CSS/JS -->
<link rel="stylesheet" href="../review/styles.css">
<script src="../review/script.js" defer></script>

<!-- Optional: navbar styling -->
<style>
.navbar {
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:10px 20px;
  background:#fff;
  border-bottom:1px solid #eee;
  position:sticky;
  top:0;
  z-index:100;
}
.navbar .logo { font-size:22px; font-weight:bold; color:#ff6b8a; }
.nav-links { list-style:none; display:flex; gap:12px; margin:0; padding:0; }
.nav-links li a { text-decoration:none; color:#333; padding:6px 10px; border-radius:4px; }
.nav-links li a.active, .nav-links li a:hover { background:#ff6b8a; color:#fff; }
.nav-user span { margin-right:10px; font-weight:600; }
.logout-btn, .login-btn { text-decoration:none; color:#fff; background:#ff6b8a; padding:6px 10px; border-radius:4px; }
.logout-btn:hover, .login-btn:hover { opacity:0.9; }
</style>
