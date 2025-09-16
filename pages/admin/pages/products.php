<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

if (isset($_GET['msg'])) $msg = $_GET['msg'];
$products = $mysqli->query("SELECT p.*, c.name AS category_name FROM product p LEFT JOIN category c ON p.category_id=c.category_id ORDER BY p.product_id DESC");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Products - Blush-D</title>
  <link rel="stylesheet" href="/Blush-d/pages/admin/assets/css/style.css">
</head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="container">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>
  <main class="content">
    <h1>Manage Products</h1>
    <?php if(!empty($msg)): ?><p class="success"><?=htmlspecialchars($msg)?></p><?php endif; ?>

    <table class="data">
      <thead><tr>
        <th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Actions</th>
      </tr></thead>
      <tbody>
        <?php while($p = $products->fetch_assoc()): ?>
        <tr>
          <td><?=$p['product_id']?></td>
          <td><?=htmlspecialchars($p['name'])?></td>
          <td><?=htmlspecialchars($p['category_name'])?></td>
          <td>$<?=number_format($p['price'],2)?></td>
          <td><?=$p['stock']?></td>
          <td>
            <a class="btn small" href="edit_product.php?id=<?=$p['product_id']?>">Edit</a>
            <a class="btn small danger" href="delete_product.php?id=<?=$p['product_id']?>" onclick="return confirmDelete()">Delete</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

  </main>
</div>
<script src="/Blush-d/pages/admin/assets/js/main.js"></script>
</body>
</html>
