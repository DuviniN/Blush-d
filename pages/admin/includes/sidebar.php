<?php
// includes/sidebar.php
$active = basename($_SERVER['SCRIPT_NAME']);
?>
<aside class="sidebar">
  <div class="logo">Blush-D</div>
  <nav>
    <a href="/Blush-d/pages/admin/pages/dashboard.php" class="<?=strpos($active,'dashboard')!==false ? 'active':''?>">Dashboard</a>
    <a href="/Blush-d/pages/admin/pages/analytics.php" class="<?=strpos($active,'analytics')!==false ? 'active':''?>">Analytics</a>
    <a href="/Blush-d/pages/admin/pages/updates.php" class="<?=strpos($active,'updates')!==false ? 'active':''?>">Updates</a>
    <a href="/Blush-d/pages/admin/pages/customers.php" class="<?=strpos($active,'customers')!==false ? 'active':''?>">Customers</a>
    <div class="menu-title">Products</div>
    <a href="/Blush-d/pages/admin/pages/products.php" class="<?=strpos($active,'products')!==false ? 'active':''?>">Manage Products</a>
    <a href="/Blush-d/pages/admin/pages/handling_managers.php" class="<?=strpos($active,'add_product')!==false ? 'active':''?>">Handling Managers</a>
    <a href="/Blush-d/pages/admin/pages/discounts.php" class="<?=strpos($active,'discounts')!==false ? 'active':''?>">Discounts</a>
    <a href="/Blush-d/pages/admin/pages/feedback.php" class="<?=strpos($active,'feedback')!==false ? 'active':''?>">Customer Feedback</a>
    <div class="menu-title">General</div>
    <a href="/Blush-d/pages/admin/pages/settings.php" class="<?=strpos($active,'settings')!==false ? 'active':''?>">Settings</a>
    <a href="/Blush-d/pages/admin/pages/profile.php" class="<?=strpos($active,'profile')!==false ? 'active':''?>">Profile</a>
    <a href="/Blush-d/pages/admin/logout.php" class="logout">Logout</a>
  </nav>
</aside>
