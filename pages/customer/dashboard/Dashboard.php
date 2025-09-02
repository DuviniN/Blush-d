<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard | Blush’d Cosmetics</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
<p>This is your dashboard.</p>

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
