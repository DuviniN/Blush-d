<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../index.php");
    exit();
}
require_once __DIR__ . '/../../../server/config/db.php';

$reviews = $conn->query("SELECT r.*, u.first_name, p.product_name AS product_name FROM review r LEFT JOIN user u ON r.user_id=u.user_id LEFT JOIN product p ON r.product_id=p.product_id ORDER BY review_date DESC");

if (!$reviews) {
    die("Query failed: " . $conn->error);
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Feedback - Blush-D</title>
<link rel="stylesheet" href="../assets/css/style.css?v=<?php echo time(); ?>"></head><body>
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
<script src="../assets/js/main.js?v=<?php echo time(); ?>"></script>
</body></html>
