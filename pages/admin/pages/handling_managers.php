<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: ../../../index.php");
  exit();
}
require_once __DIR__ . '/../../../server/config/db.php';

$errors = [];
$success = "";

// Add manager
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_manager'])) {
  $first_name = trim($_POST['first_name'] ?? '');
  $last_name = trim($_POST['last_name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $password_plain = $_POST['password'] ?? '';
  $start_day = date('Y-m-d');

  if ($first_name === '' || $last_name === '' || $email === '' || $password_plain === '') {
    $errors[] = "First name, last name, email, and password are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email address.";
  } else {
    // Check email uniqueness
    $chk = $conn->prepare("SELECT user_id FROM user WHERE email = ? LIMIT 1");
    $chk->bind_param("s", $email);
    $chk->execute();
    $chk->store_result();
    if ($chk->num_rows > 0) {
      $errors[] = "A manager with that email already exists.";
      $chk->close();
    } else {
      $chk->close();
      $password_hash = password_hash($password_plain, PASSWORD_DEFAULT);
      $role = 'MANAGER';
      $ins = $conn->prepare("INSERT INTO user (first_name, last_name, email, password, phone_number, role, start_day) VALUES (?, ?, ?, ?, ?, ?, ?)");
      if (!$ins) {
        $errors[] = "Prepare failed: " . $conn->error;
      } else {
        $ins->bind_param("sssssss", $first_name, $last_name, $email, $password_hash, $phone, $role, $start_day);
        if ($ins->execute()) {
          $success = "Manager added successfully.";
        } else {
          $errors[] = "Insert failed: " . $ins->error;
        }
        $ins->close();
      }
    }
  }
}

// Delete manager (via GET)
if (isset($_GET['delete']) && filter_var($_GET['delete'], FILTER_VALIDATE_INT)) {
  $delId = (int) $_GET['delete'];
  $del = $conn->prepare("DELETE FROM user WHERE user_id = ? AND role = 'MANAGER'");
  if ($del) {
    $del->bind_param("i", $delId);
    if ($del->execute()) {
      $success = "Manager removed.";
    } else {
      $errors[] = "Delete failed: " . $del->error;
    }
    $del->close();
  } else {
    $errors[] = "Prepare failed: " . $conn->error;
  }
}

// Fetch all managers
$managers = [];
$res = $conn->query("SELECT user_id, first_name, last_name, email, phone_number, start_day FROM user WHERE role = 'MANAGER' ORDER BY user_id DESC");
if ($res) {
  while ($row = $res->fetch_assoc()) {
    $managers[] = $row;
  }
  $res->free();
} else {
  $errors[] = "Could not retrieve managers: " . $conn->error;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Manage Managers</title>
  <link rel="stylesheet" href="../assets/css/style.css?v=<?php echo time(); ?>">
</head>

<body>
  <?php include __DIR__ . '/../includes/header.php'; ?>
  <div class="container">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <main class="content">
      <h2>Manage Managers</h2>
      <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <div class="errors">
          <ul>
            <?php foreach ($errors as $e): ?>
              <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <section>
        <h3>Add Manager</h3>
        <form method="POST">
          <input type="hidden" name="add_manager" value="1">
          <label>First Name</label><br>
          <input type="text" name="first_name" required><br>
          <label>Last Name</label><br>
          <input type="text" name="last_name" required><br>
          <label>Email</label><br>
          <input type="email" name="email" required><br>
          <label>Phone</label><br>
          <input type="text" name="phone"><br>
          <label>Password</label><br>
          <input type="password" name="password" required><br><br>
          <button type="submit">Add Manager</button>
        </form>
      </section>

      <section>
        <h3>Existing Managers</h3>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Start Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($managers as $m): ?>
              <tr>
                <td><?= (int)$m['user_id'] ?></td>
                <td><?= htmlspecialchars($m['first_name']) ?></td>
                <td><?= htmlspecialchars($m['last_name']) ?></td>
                <td><?= htmlspecialchars($m['email']) ?></td>
                <td><?= htmlspecialchars($m['phone_number']) ?></td>
                <td><?= htmlspecialchars($m['start_day']) ?></td>
                <td>
                  <a href="?delete=<?= (int)$m['user_id'] ?>" onclick="return confirm('Remove this manager?')">Remove</a>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($managers)): ?>
              <tr>
                <td colspan="7">No managers found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </section>
    </main>
  </div>
</body>

</html>