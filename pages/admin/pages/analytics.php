<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../index.php");
    exit();
}

require_once __DIR__ . '/../../../server/config/db.php';

// Example: fetch last 7 days revenue
$data = [];
$res = $conn->query("
    SELECT DATE(order_date) as d, SUM(total_price) as revenue
    FROM `order`
    GROUP BY DATE(order_date)
    ORDER BY d DESC
    LIMIT 7
");
while ($row = $res->fetch_assoc()) {
  $data[] = $row;
}
$data = array_reverse($data); // oldest first
?>
<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Analytics - Blush-D</title>
  <link rel="stylesheet" href="../assets/css/style.css?v=<?php echo time(); ?>">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
  <?php include __DIR__ . '/../includes/header.php'; ?>
  <div class="container">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>

    <main class="content">
      <h1>Analytics</h1>

      <div class="card">
        <h3>Revenue (Last 7 Days)</h3>
        <canvas id="revenueChart" height="100"></canvas>
      </div>

      <div class="card" style="margin-top:20px;">
        <h3>Top Products by Sales</h3>
        <table class="data">
          <thead>
            <tr>
              <th>Product</th>
              <th>Total Orders</th>
              <th>Revenue</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $top = $conn->query("SELECT p.product_name, COUNT(o.order_id) as orders, SUM(o.total_price) as revenue
            FROM `order` o
            JOIN order_item oi ON o.order_id=oi.order_id
            JOIN product p ON oi.product_id=p.product_id
            GROUP BY p.product_id
            ORDER BY revenue DESC
            LIMIT 5
        ");
            while ($t = $top->fetch_assoc()):
            ?>
              <tr>
                <td><?= htmlspecialchars($t['product_name']) ?></td>
                <td><?= $t['orders'] ?></td>
                <td>$<?= number_format($t['revenue'], 2) ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

    </main>
  </div>

  <script src="../assets/js/main.js?v=<?php echo time(); ?>"></script>
  <script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const chart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: <?= json_encode(array_column($data, 'd')) ?>,
        datasets: [{
          label: 'Revenue ($)',
          data: <?= json_encode(array_column($data, 'revenue')) ?>,
          fill: true,
          borderColor: '#ff7660',
          backgroundColor: 'rgba(255,118,96,0.2)',
          tension: 0.3
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>
</body>

</html>