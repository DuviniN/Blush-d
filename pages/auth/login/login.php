<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BLUSH-D</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="../../../assets/global.css">
</head>
<body>
    <div class="login-container">
        <div class="login-form-wrapper">
            <div class="login-header">
                <h1>BLUSH-D</h1>
                <h2>Welcome Back</h2>
                <p>Sign in to your account</p>
            </div>
            
            <form class="login-form" id="loginForm">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                    <span class="error-message" id="emailError"></span>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <span class="error-message" id="passwordError"></span>
                </div>
                
                <div class="form-options">
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>
                
                <button type="submit" class="login-btn" id="loginBtn">
                    <span class="btn-text">Sign In</span>
                    <span class="btn-loading" style="display: none;">Signing In...</span>
                </button>
                
                <div class="form-footer">
                    <p>Don't have an account? <a href="../register/register.php" class="register-link">Sign up here</a></p>
                </div>
            </form>
            
        </div>
        
        <div class="login-image">
            <div class="image-overlay">
                <h3>Your Beauty Journey Continues</h3>
                <p>Access your personalized dashboard and explore our latest beauty products</p>
            </div>
        </div>
    </div>
    
    <script src="login.js"></script>
</body>
</html>
