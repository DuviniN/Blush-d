<?php
include("../../../server/config/db.php");
session_start();

$loginError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId, $fullName, $hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_name'] = $fullName;
            $_SESSION['success'] = "✅ Login successful! Welcome, $fullName";
            header("Location: ../dashboard/dashboard.php");
            exit();
        } else {
            $loginError = "❌ Incorrect password.";
        }
    } else {
        $loginError = "❌ Email not registered.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | Blush’d Cosmetics</title>
<link rel="stylesheet" href="login.css">
</head>
<body>

<div class="register-container">
  <h2>Login</h2>
  <form id="loginForm" method="POST" action="">
    <div class="input-group">
      <label for="email">Email Address</label>
      <input type="email" name="email" id="email" placeholder="Enter your email" required>
      <small class="error-message" id="emailError"></small>
    </div>

    <div class="input-group">
      <label for="password">Password</label>
      <input type="password" name="password" id="password" placeholder="Enter your password" required>
      <small class="error-message" id="passwordError"></small>
    </div>

    <div class="input-group">
        <input type="checkbox" id="showPassword">
        <label for="showPassword">Show Password</label>
    </div>

    <?php if (!empty($loginError)) { ?>
      <p class="login-error"><?php echo $loginError; ?></p>
    <?php } ?>

    <button type="submit" class="btn">Login</button>
  </form>

  <div class="register-footer">
    Don't have an account? <a href="../register/register.php">Register</a>
  </div>
</div>

<div id="notification" class="notification">
  <span id="notificationMessage"></span>
  <button id="closeNotification">&times;</button>
</div>

<script>
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");
const emailError = document.getElementById("emailError");
const passwordError = document.getElementById("passwordError");

// Email validation
emailInput.addEventListener("blur", () => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  emailError.textContent = (!emailRegex.test(emailInput.value) && emailInput.value.trim() !== "") 
                            ? "❌ Please enter a valid email address." : "";
});

// Password empty check
passwordInput.addEventListener("blur", () => {
  passwordError.textContent = passwordInput.value.trim() === "" ? "❌ Password cannot be empty." : "";
});

// Show/hide password
document.getElementById('showPassword').addEventListener('change', function(){
  passwordInput.type = this.checked ? 'text' : 'password';
});

// Notification Handling
window.addEventListener('DOMContentLoaded', () => {
  const notification = document.getElementById('notification');
  const notificationMessage = document.getElementById('notificationMessage');
  const closeBtn = document.getElementById('closeNotification');

  <?php
  if(isset($_SESSION['success'])) {
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
