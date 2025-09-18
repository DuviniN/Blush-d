<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../index.php");
    exit();
}
include_once __DIR__ . '/../../../server/config/db.php';

$users = $conn->query("SELECT * FROM user ORDER BY user_id DESC");
?>
<!doctype html><html><head><meta charset="utf-8"><title>Customers - Blush-D</title>
<link rel="stylesheet" href="../assets/css/style.css?v=<?php echo time(); ?>"></head><body>
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
<script src="../assets/js/main.js?v=<?php echo time(); ?>"></script>
</body></html>
