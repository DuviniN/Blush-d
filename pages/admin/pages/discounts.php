<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../index.php");
    exit();
}

require_once __DIR__ . '/../../../server/config/db.php';

// Handle new discount form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $product_id = intval($_POST['product_id']);
  $percent = floatval($_POST['discount_percent']);
  $start = $_POST['start_date'];
  $end = $_POST['end_date'];

  $stmt = $conn->prepare("INSERT INTO discounts (product_id, discount_percent, start_date, end_date) VALUES (?,?,?,?)");
  $stmt->bind_param("idss", $product_id, $percent, $start, $end);
  $stmt->execute();
  $stmt->close();
  header("Location: discounts.php?msg=Discount+added");
  exit;
}

// Handle delete
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  $conn->query("DELETE FROM discounts WHERE id=$id");
  header("Location: discounts.php?msg=Discount+deleted");
  exit;
}

// Fetch all products for dropdown
$products = $conn->query("SELECT product_id, product_name FROM product ORDER BY product_name");

// Fetch current discounts
$discounts = $conn->query("SELECT d.*, p.name AS product_name
    FROM discounts d
    JOIN product p ON d.product_id=p.product_id
    ORDER BY d.start_date DESC
");
?>
<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Discounts - Blush-D</title>
  <link rel="stylesheet" href="../assets/css/style.css?v=<?php echo time(); ?>">
</head>

<body>
  <?php include __DIR__ . '/../includes/header.php'; ?>
  <div class="container">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>

    <main class="content">
      <h1>Manage Discounts</h1>
      <?php if (isset($_GET['msg'])): ?>
        <p class="success"><?= htmlspecialchars($_GET['msg']) ?></p>
      <?php endif; ?>

      <!-- Add new discount -->
      <div class="card form-card">
        <h3>Add Discount</h3>
        <form method="post">
          <label>Product</label>
          <select name="product_id" required>
            <option value="">-- Select Product --</option>
            <?php while ($p = $products->fetch_assoc()): ?>
              <option value="<?= $p['product_id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
            <?php endwhile; ?>
          </select>

          <label>Discount Percent (%)</label>
          <input type="number" step="0.01" name="discount_percent" required>

          <label>Start Date</label>
          <input type="date" name="start_date" required>

          <label>End Date</label>
          <input type="date" name="end_date" required>

          <button class="btn" type="submit">Add Discount</button>
        </form>
      </div>

      <!-- List discounts -->
      <div class="card" style="margin-top:20px;">
        <h3>Current Discounts</h3>
        <table class="data">
          <thead>
            <tr>
              <th>ID</th>
              <th>Product</th>
              <th>Percent</th>
              <th>Start</th>
              <th>End</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($discounts && $discounts->num_rows > 0): ?>
              <?php while ($d = $discounts->fetch_assoc()): ?>
                <tr>
                  <td><?= $d['id'] ?></td>
                  <td><?= htmlspecialchars($d['product_name']) ?></td>
                  <td><?= $d['discount_percent'] ?>%</td>
                  <td><?= $d['start_date'] ?></td>
                  <td><?= $d['end_date'] ?></td>
                  <td><a class="btn small danger" href="discounts.php?delete=<?= $d['id'] ?>" onclick="return confirm('Delete this discount?')">Delete</a></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="6">No discounts found</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
  <script src="../assets/js/main.js?v=<?php echo time(); ?>"></script>
</body>

</html>