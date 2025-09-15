<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: ../login/login.php");
    exit();
}

require_once "../../../server/config/db.php";

// Get category from URL (default = All)
$category = isset($_GET['cat']) ? $_GET['cat'] : 'All';

// Fetch products based on category
if ($category === 'All') {
    $sql = "SELECT p.product_id, p.product_name, p.mini_descrip, p.price, p.image_id, c.name AS category_name
            FROM Products p
            LEFT JOIN Category c ON p.category_id = c.category_id";
    $stmt = $conn->prepare($sql);
} else {
    $sql = "SELECT p.product_id, p.product_name, p.mini_descrip, p.price, p.image_id, c.name AS category_name
            FROM Products p
            LEFT JOIN Category c ON p.category_id = c.category_id
            WHERE c.name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard | Blush’d Cosmetics</title>
<link rel="stylesheet" href="dashboard.css">
</head>
<body>

<!-- Navigation -->
 <?php include "../header/header.php"; ?>
<link rel="stylesheet" href="../header/header.css">

<!-- <nav class="navbar">
  <div class="logo">Blush’d Cosmetics</div>
  <ul class="nav-links">
    <li><a href="dashboard.php?cat=All" class="<?php echo ($category==='All') ? 'active' : ''; ?>">All</a></li>
    <li><a href="dashboard.php?cat=Skin" class="<?php echo ($category==='Skin') ? 'active' : ''; ?>">Skin</a></li>
    <li><a href="dashboard.php?cat=Hair" class="<?php echo ($category==='Hair') ? 'active' : ''; ?>">Hair</a></li>
    <li><a href="dashboard.php?cat=Makeup" class="<?php echo ($category==='Makeup') ? 'active' : ''; ?>">Makeup</a></li>
    <li><a href="dashboard.php?cat=Tools" class="<?php echo ($category==='Tools') ? 'active' : ''; ?>">Tools</a></li>
  </ul>
  <div class="nav-user">
    <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
    <a href="../logout.php" class="logout-btn">Logout</a>
  </div>
</nav> -->

<h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
<p>
  <?php echo ($category === 'All') ? 'Discover our new arrivals' : 'Discover our ' . htmlspecialchars($category) . ' products'; ?>
</p>

<div class="product-grid">
<?php while($row = $result->fetch_assoc()): ?>
    <a href="../product/product.php?id=<?php echo $row['product_id']; ?>" class="product-card">
        <img src="../../../assets/products/<?php echo htmlspecialchars($row['product_id'] ?? 'default'); ?>.png" 
             alt="<?php echo htmlspecialchars($row['product_name']); ?>">
        <div class="product-info">
            <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
            <p class="mini-desc"><?php echo htmlspecialchars($row['mini_descrip'] ?? substr($row['description'],0,80) . '...'); ?></p>
            <p class="price">$<?php echo number_format($row['price'], 2); ?></p>
        </div>
    </a>
<?php endwhile; ?>
</div>

<!-- Optional notification for success messages -->
<div id="notification" class="notification">
  <span id="notificationMessage"></span>
  <button id="closeNotification">&times;</button>
</div>

<script>
window.addEventListener('DOMContentLoaded', () => {
  const notification = document.getElementById('notification');
  const notificationMessage = document.getElementById('notificationMessage');
  const closeBtn = document.getElementById('closeNotification');

  <?php
  if(isset($_SESSION['success'])){
      $msg = addslashes($_SESSION['success']);
      echo "notificationMessage.textContent = '$msg';";
      echo "notification.style.backgroundColor = '#4CAF50';";
      echo "notification.style.display = 'block';";
      unset($_SESSION['success']);
  }
  ?>

  closeBtn.onclick = () => { notification.style.display = 'none'; };
  setTimeout(() => { notification.style.display = 'none'; }, 4000);
});
</script>

</body>
</html>
