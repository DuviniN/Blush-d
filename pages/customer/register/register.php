<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../../../server/config/db.php");
session_start();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $countryCode = trim($_POST['countryCode']);
    $mobile = trim($_POST['mobile']);
    $password = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, country_code, mobile, password) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) die("Prepare failed: " . $conn->error);

    $stmt->bind_param("sssss", $name, $email, $countryCode, $mobile, $hashedPassword);

    if (!$stmt->execute()) {
        // Check duplicate email
        if ($conn->errno === 1062) {
            $_SESSION['error'] = "❌ This email is already registered.";
        } else {
            $_SESSION['error'] = "❌ Error: " . $stmt->error;
        }
    } else {
        $_SESSION['success'] = "✅ Registration successful! Please log in.";
        header("Location: ../login/login.php");
        exit();
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
<title>Register | Blush’d Cosmetics</title>
<link rel="stylesheet" href="register.css">
</head>
<body>

<div class="register-container">
  <h2>Create Account</h2>
  <form id="registerForm" method="POST" action="">
    <div class="input-group">
      <label for="name">Full Name</label>
      <input type="text" id="name" name="name" placeholder="Enter your name" required>
    </div>

    <div class="input-group">
      <label for="email">Email Address</label>
      <input type="email" id="email" name="email" placeholder="Enter your email" required>
      <small class="error-message" id="emailError"></small>
    </div>

    <div class="input-group">
      <label for="mobile">Mobile Number</label>
      <div class="mobile-input">
        <select id="countryCode" name="countryCode" required>
          <option value="+94">🇱🇰 +94</option>
          <option value="+1">🇺🇸 +1</option>
          <option value="+44">🇬🇧 +44</option>
          <option value="+91">🇮🇳 +91</option>
          <option value="+61">🇦🇺 +61</option>
        </select>
        <input type="tel" id="mobile" name="mobile" placeholder="Enter mobile number" required>
      </div>
      <small class="error-message" id="mobileError"></small>
    </div>

    <div class="input-group">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Enter your password" required>
    </div>

    <div class="input-group">
      <label for="confirm-password">Confirm Password</label>
      <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm password" required>
      <small class="error-message" id="passwordError"></small>
    </div>

    <div class="input-group">
      <input type="checkbox" id="showPassword">
      <label for="showPassword">Show Password</label>
    </div>

    <button type="submit" class="btn">Register</button>
  </form>

  <div class="register-footer">
    Already have an account? <a href="../login/login.php">Login</a>
  </div>
</div>

<!-- Notification -->
<div id="notification" class="notification">
  <span id="notificationMessage"></span>
  <button id="closeNotification">&times;</button>
</div>

<script>
// --- Input Validation ---
const emailInput = document.getElementById("email");
const mobileInput = document.getElementById("mobile");
const passwordInput = document.getElementById("password");
const confirmPasswordInput = document.getElementById("confirm-password");

const emailError = document.getElementById("emailError");
const mobileError = document.getElementById("mobileError");
const passwordError = document.getElementById("passwordError");

// Email validation
emailInput.addEventListener("blur", () => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  emailError.textContent = (!emailRegex.test(emailInput.value) && emailInput.value.trim() !== "") 
                            ? "❌ Please enter a valid email address." : "";
});

// Mobile validation
mobileInput.addEventListener("blur", () => {
  const mobileRegex = /^[0-9]{7,12}$/;
  mobileError.textContent = (!mobileRegex.test(mobileInput.value) && mobileInput.value.trim() !== "") 
                            ? "❌ Mobile number must be 7–12 digits." : "";
});

// Password match validation
confirmPasswordInput.addEventListener("blur", () => {
  passwordError.textContent = (confirmPasswordInput.value !== passwordInput.value) 
                              ? "❌ Passwords do not match." : "";
});

// Show/Hide password
const showPasswordCheckbox = document.getElementById('showPassword');
showPasswordCheckbox.addEventListener('change', () => {
  const type = showPasswordCheckbox.checked ? 'text' : 'password';
  passwordInput.type = type;
  confirmPasswordInput.type = type;
});

// Form submission validation
document.getElementById("registerForm").addEventListener("submit", function(event) {
  emailInput.dispatchEvent(new Event('blur'));
  mobileInput.dispatchEvent(new Event('blur'));
  confirmPasswordInput.dispatchEvent(new Event('blur'));

  if (emailError.textContent || mobileError.textContent || passwordError.textContent) {
    event.preventDefault();
    alert("Please fix the errors before submitting.");
  }
});

// --- Notification Handling ---
window.addEventListener('DOMContentLoaded', () => {
  const notification = document.getElementById('notification');
  const notificationMessage = document.getElementById('notificationMessage');
  const closeBtn = document.getElementById('closeNotification');

  <?php
  if(isset($_SESSION['error'])) {
      $msg = addslashes($_SESSION['error']);
      echo "notificationMessage.textContent = '$msg';";
      echo "notification.style.backgroundColor = '#f44336';";
      echo "notification.style.display = 'block';";
      unset($_SESSION['error']);
  }
  ?>

  closeBtn.onclick = () => { notification.style.display = 'none'; };
  setTimeout(() => { notification.style.display = 'none'; }, 4000);
});
</script>

</body>
</html>
