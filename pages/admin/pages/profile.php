<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../index.php");
    exit();
}
require_once __DIR__ . '/../../../server/config/db.php';

// Get admin data from session and database
$user_id = $_SESSION['user_id'];
$adminName = ($_SESSION['first_name'] ?? '') . ' ' . ($_SESSION['last_name'] ?? '');
$adminEmail = $_SESSION['email'] ?? '';

// Fetch additional profile data from database
$stmt = $conn->prepare("SELECT first_name, last_name, email, phone_number FROM user WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

if ($userData) {
    $adminName = trim($userData['first_name'] . ' ' . $userData['last_name']);
    $adminEmail = $userData['email'];
    $adminPhone = $userData['phone_number'] ?? '';
}

$adminPhoto = $_SESSION['admin_photo'] ?? "../upload/default.jpg";

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = trim($_POST['name']);
    $newEmail = trim($_POST['email']);
    $newPhone = trim($_POST['phone'] ?? '');
    
    // Split name into first and last name
    $nameParts = explode(' ', $newName, 2);
    $firstName = $nameParts[0];
    $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

    // Handle photo upload
    $photoUpdated = false;
    if (!empty($_FILES['photo']['name'])) {
        $targetDir = __DIR__ . "/uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $filename = time() . "_" . basename($_FILES["photo"]["name"]);
        $targetFile = $targetDir . $filename;

        $ext = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowed = ["jpg","jpeg","png","gif"];

        if (in_array($ext, $allowed)) {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
                $_SESSION['admin_photo'] = "uploads/" . $filename;
                $adminPhoto = $_SESSION['admin_photo'];
                $photoUpdated = true;
            }
        }
    }

    // Update database
    $updateStmt = $conn->prepare("UPDATE user SET first_name = ?, last_name = ?, email = ?, phone_number = ? WHERE user_id = ?");
    $updateStmt->bind_param("ssssi", $firstName, $lastName, $newEmail, $newPhone, $user_id);
    
    if ($updateStmt->execute()) {
        // Update session variables
        $_SESSION['first_name'] = $firstName;
        $_SESSION['last_name'] = $lastName;
        $_SESSION['email'] = $newEmail;

        $adminName = $newName;
        $adminEmail = $newEmail;
        $adminPhone = $newPhone;

        $msg = "Profile updated successfully";
    } else {
        $msg = "Error updating profile: " . $conn->error;
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Profile - Blush-D</title>
  <link rel="stylesheet" href="../assets/css/style.css?v=<?php echo time(); ?>">
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

        <label>Phone Number</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($adminPhone ?? '') ?>">

        <label>Profile Photo</label>
        <input type="file" name="photo" accept="image/*">

        <!-- <label>Password</label> -->
        <!-- <input type="password" name="password" placeholder="(not implemented yet)" disabled> -->

        <button class="btn" type="submit">Save Changes</button> 
      </form>
    </div>
  </main>
</div>
<script src="../assets/js/main.js?v=<?php echo time(); ?>"></script>
</body>
</html>
