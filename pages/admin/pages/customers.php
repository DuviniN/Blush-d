<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$users = $mysqli->query("SELECT * FROM user ORDER BY user_id DESC");
?>
<!doctype html><html><head><meta charset="utf-8"><title>Customers - Blush-D</title>
<link rel="stylesheet" href="/Blush-d/pages/admin/assets/css/style.css"></head><body>
<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="container">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>
  <main class="content">
    <h1>Customers</h1>
    <table class="data">
      <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th></tr></thead>
      <tbody>
        <?php while($u = $users->fetch_assoc()): ?>
        <tr>
          <td><?=$u['user_id']?></td>
          <td><?=htmlspecialchars($u['first_name'].' '.$u['last_name'])?></td>
          <td><?=htmlspecialchars($u['email'])?></td>
          <td><?=htmlspecialchars($u['phone_number'])?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </main>
</div>
<script src="/Blush-d/pages/admin/assets/js/main.js"></script>
</body></html>
