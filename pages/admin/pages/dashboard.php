<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Dashboard - Blush-D</title>
  <link rel="stylesheet" href="/Blush-d/pages/admin/assets/css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="container">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="content">
    <h1>Sales Overview</h1>

    <!-- small KPI cards -->
    <section class="kpis">
      <?php
      // Example KPI queries
      $totalSalesRes = $mysqli->query("SELECT COUNT(*) AS total_orders, SUM(total_price) AS revenue FROM `order`");
      $kpi = $totalSalesRes->fetch_assoc();
      $totalOrders = $kpi['total_orders'] ?? 0;
      $revenue = $kpi['revenue'] ? number_format($kpi['revenue'],2) : '0.00';
      ?>
      <div class="card">
        <h3>Total Orders</h3>
        <p class="big"><?=$totalOrders?></p>
      </div>
      <div class="card">
        <h3>Total Revenue</h3>
        <p class="big">$<?=$revenue?></p>
      </div>
      <div class="card">
        <h3>Products</h3>
        <?php $pcount = $mysqli->query("SELECT COUNT(*) AS c FROM product")->fetch_assoc()['c']; ?>
        <p class="big"><?=$pcount?></p>
      </div>
    </section>

    <!-- Revenue analytics placeholder -->
    <section class="analytics">
      <div class="chart-card">
        <h3>Revenue analytics (last 5 months)</h3>
        <div class="chart-container"style="width:70%;margin:auto;">
          <canvas id="salesChart" height="100"></canvas>
        </div>
      </div>

      <div class="recent-orders card">
        <h3>Recent Orders</h3>
        <table>
          <thead><tr><th>Order</th><th>Date</th><th>Total</th></tr></thead>
          <tbody>
          <?php
            $res = $mysqli->query("SELECT order_id, order_date, total_price FROM `order` ORDER BY order_date DESC LIMIT 5");
            while($r = $res->fetch_assoc()):
          ?>
            <tr>
              <td>#<?=$r['order_id']?></td>
              <td><?=htmlspecialchars($r['order_date'])?></td>
              <td>$<?=number_format($r['total_price'],2)?></td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>

    </section>

  </main>
</div>
<script src="/Blush-d/pages/admin/assets/js/main.js"></script>
<script src="/Blush-d/pages/admin/assets/js/chart.js"></script> 


</body>
</html>
