<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - BLUSH-D</title>
    <link rel="stylesheet" href="register.css">
    <link rel="stylesheet" href="../../../assets/global.css">
</head>
<body>
    <div class="register-container">
        <div class="register-form-wrapper">
            <div class="register-header">
                <h1>BLUSH-D</h1>
                <h2>Create Your Account</h2>
                <p>Join our community of beauty enthusiasts</p>
            </div>
            
            <form class="register-form" id="registerForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="firstName" required>
                        <span class="error-message" id="firstNameError"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastName" required>
                        <span class="error-message" id="lastNameError"></span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                    <span class="error-message" id="emailError"></span>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <span class="error-message" id="passwordError"></span>
                    <div class="password-requirements">
                        <small>Password must be at least 8 characters long</small>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                    <span class="error-message" id="confirmPasswordError"></span>
                </div>
                
                <button type="submit" class="register-btn" id="registerBtn">
                    <span class="btn-text">Create Account</span>
                    <span class="btn-loading" style="display: none;">Creating Account...</span>
                </button>
                
                <div class="form-footer">
                    <p>Already have an account? <a href="../login/login.php" class="login-link">Sign in here</a></p>
                </div>
            </form>
            
            <div class="success-message" id="successMessage" style="display: none;">
                <div class="success-content">
                    <h3>Registration Successful!</h3>
                    <p>Welcome to BLUSH-D! You will be redirected to your dashboard shortly.</p>
                </div>
            </div>
        </div>
        
        <div class="register-image">
            <div class="image-overlay">
                <h3>Discover Your Beauty</h3>
                <p>Join thousands of customers who trust BLUSH-D for their beauty needs</p>
            </div>
        </div>
    </div>
    
    <script src="register.js"></script>
</body>
</html>
