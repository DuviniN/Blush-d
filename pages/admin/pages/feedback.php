<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$reviews = $mysqli->query("SELECT r.*, u.first_name, p.name AS product_name FROM review r LEFT JOIN user u ON r.user_id=u.user_id LEFT JOIN product p ON r.product_id=p.product_id ORDER BY review_date DESC");
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Feedback - Blush-D</title>
<link rel="stylesheet" href="/Blush-d/pages/admin/assets/css/style.css"></head><body>
<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="container">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>
  <main class="content">
    <h1>Customer Feedback</h1>
    <table class="data">
      <thead><tr><th>ID</th><th>Customer</th><th>Product</th><th>Rating</th><th>Comments</th><th>Date</th></tr></thead>
      <tbody>
        <?php while($r = $reviews->fetch_assoc()): ?>
        <tr>
          <td><?=$r['review_id']?></td>
          <td><?=htmlspecialchars($r['first_name'])?></td>
          <td><?=htmlspecialchars($r['product_name'])?></td>
          <td><?=$r['rating']?></td>
          <td><?=htmlspecialchars($r['comments'])?></td>
          <td><?=$r['review_date']?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </main>
</div>
<script src="/Blush-d/pages/admin/assets/js/main.js"></script>
</body></html>
