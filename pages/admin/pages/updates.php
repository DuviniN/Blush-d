<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $mysqli->real_escape_string($_POST['title']);
    $body = $mysqli->real_escape_string($_POST['body']);
    $stmt = $mysqli->prepare("INSERT INTO updates (title, body, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $title, $body);
    $stmt->execute();
    $stmt->close();
    header("Location: updates.php?msg=Update+posted");
    exit;
}

// Fetch updates
$res = $mysqli->query("SELECT * FROM updates ORDER BY created_at DESC");
$updates = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Updates - Blush-D</title>
  <link rel="stylesheet" href="/Blush-d/pages/admin/assets/css/style.css">
</head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="container">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="content">
    <h1>Updates</h1>
    <?php if(isset($_GET['msg'])): ?>
      <p class="success"><?= htmlspecialchars($_GET['msg']) ?></p>
    <?php endif; ?>

    <!-- Post new update -->
    <div class="card form-card">
      <h3>Post New Update</h3>
      <form method="post">
        <label>Title</label>
        <input type="text" name="title" required>
        <label>Message</label>
        <textarea name="body" required></textarea>
        <button class="btn" type="submit">Post Update</button>
      </form>
    </div>

    <!-- Show updates -->
    <div class="card" style="margin-top:20px;">
      <h3>Recent Updates</h3>
      <ul class="updates-list">
        <?php if (empty($updates)): ?>
          <li>No updates yet.</li>
        <?php else: ?>
          <?php foreach ($updates as $u): ?>
            <li>
              <div class="update-title"><?= htmlspecialchars($u['title']) ?></div>
              <div class="update-body"><?= nl2br(htmlspecialchars($u['description'])) ?></div>
              <div class="update-date"><?= htmlspecialchars($u['created_at']) ?></div>
            </li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>
  </main>
</div>
<script src="/Blush-d/pages/admin/assets/js/main.js"></script>
</body>
</html>
