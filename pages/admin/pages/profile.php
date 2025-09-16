<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

// Session values (demo, not DB yet)
$adminName  = $_SESSION['admin_name'] ?? "Admin";
$adminEmail = $_SESSION['admin_email'] ?? "admin@blushd.com";
$adminPhoto = $_SESSION['admin_photo'] ?? "uploads/default.png"; // fallback

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName  = trim($_POST['name']);
    $newEmail = trim($_POST['email']);

    // Handle photo upload
    if (!empty($_FILES['photo']['name'])) {
        $targetDir  = __DIR__ . "/uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $filename   = time() . "_" . basename($_FILES["photo"]["name"]);
        $targetFile = $targetDir . $filename;

        $ext = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowed = ["jpg","jpeg","png","gif"];

        if (in_array($ext, $allowed)) {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
                $_SESSION['admin_photo'] = "uploads/" . $filename;
                $adminPhoto = $_SESSION['admin_photo'];
            }
        }
    }

    // Update session values
    $_SESSION['admin_name']  = $newName;
    $_SESSION['admin_email'] = $newEmail;

    $adminName  = $newName;
    $adminEmail = $newEmail;

    $msg = "Profile updated successfully";
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Profile - Blush-D</title>
  <link rel="stylesheet" href="/Blush-d/pages/admin/assets/css/style.css">
  <style>
    .profile-photo {
      width:100px;
      height:100px;
      border-radius:50%;
      object-fit:cover;
      margin-bottom:10px;
      border:2px solid var(--blush);
    }
  </style>
</head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="container">
  <?php include __DIR__ . '/../includes/sidebar.php'; ?>

  <main class="content">
    <h1>Admin Profile</h1>
    <?php if(!empty($msg)): ?>
      <p class="success"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>

    <div class="card form-card">
      <form method="post" enctype="multipart/form-data">
        <div style="text-align:center;">
          <img src="<?= htmlspecialchars($adminPhoto) ?>" class="profile-photo" alt="Admin Photo">
        </div>

        <label>Full Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($adminName) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($adminEmail) ?>" required>

        <label>Profile Photo</label>
        <input type="file" name="photo" accept="image/*">

        <!-- <label>Password</label> -->
        <!-- <input type="password" name="password" placeholder="(not implemented yet)" disabled> -->

        <button class="btn" type="submit">Save Changes</button> 
      </form>
    </div>
  </main>
</div>
<script src="/Blush-d/pages/admin/assets/js/main.js"></script>
</body>
</html>
