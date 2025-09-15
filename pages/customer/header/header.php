<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: ../login/login.php");
    exit();
}

require_once "../../../server/config/db.php";

// Get current page category for active link
$category = isset($_GET['cat']) ? $_GET['cat'] : 'All';
?>
<!-- Announcement Bar -->
<div class="announcement-bar">
  <div class="announcement-wrapper">
    <span class="announcement">🚚 Deliver within 3 days</span>
    <span class="announcement">💄 New arrivals are live now!</span>
    <span class="announcement">✨ Free shipping on orders over $50</span>
  </div>
</div>


<nav class="header-navbar">
    <div class="logo">
        <a href="../dashboard/dashboard.php">Blush’d Cosmetics</a>
    </div>

    <ul class="nav-links">
        <li><a href="../dashboard/dashboard.php?cat=All" class="<?php echo ($category==='All') ? 'active' : ''; ?>">All</a></li>
        <li><a href="../dashboard/dashboard.php?cat=Skin" class="<?php echo ($category==='Skin') ? 'active' : ''; ?>">Skin</a></li>
        <li><a href="../dashboard/dashboard.php?cat=Hair" class="<?php echo ($category==='Hair') ? 'active' : ''; ?>">Hair</a></li>
        <li><a href="../dashboard/dashboard.php?cat=Makeup" class="<?php echo ($category==='Makeup') ? 'active' : ''; ?>">Makeup</a></li>
        <li><a href="../dashboard/dashboard.php?cat=Tools" class="<?php echo ($category==='Tools') ? 'active' : ''; ?>">Tools</a></li>
    </ul>

    <div class="nav-user">
        <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
        <a href="#" class="logout-btn" id="logoutBtn">Logout</a>
    </div>
    <link rel="stylesheet" href="../header/header.css">

</nav>
<!-- Logout Confirmation Modal -->
<div class="logout-modal" id="logoutModal">
    <div class="modal">
        <h2>Confirm Logout</h2>
        <p>Are you sure you want to log out from your account?</p>
        <form method="post" action="../logout/logout.php">
            <button type="submit" name="confirm_logout" class="btn btn-confirm">Yes, Logout</button>
            <button type="button" class="btn btn-cancel" id="cancelLogout">Cancel</button>
        </form>
    </div>
</div>
<script>
const logoutBtn = document.getElementById('logoutBtn');
const logoutModal = document.getElementById('logoutModal');
const cancelLogout = document.getElementById('cancelLogout');

logoutBtn.addEventListener('click', () => {
    logoutModal.style.display = 'flex';
});

cancelLogout.addEventListener('click', () => {
    logoutModal.style.display = 'none';
});

// Click outside modal to close
window.addEventListener('click', (e) => {
    if(e.target == logoutModal) {
        logoutModal.style.display = 'none';
    }
});
</script>

