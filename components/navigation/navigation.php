<?php
session_start();

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
}

// Function to get user data
function getUserData() {
    if (isLoggedIn()) {
        return [
            'user_id' => $_SESSION['user_id'] ?? null,
            'first_name' => $_SESSION['first_name'] ?? '',
            'last_name' => $_SESSION['last_name'] ?? '',
            'email' => $_SESSION['email'] ?? '',
            'role' => $_SESSION['role'] ?? ''
        ];
    }
    return null;
}

// Function to get the correct base path
function getBasePath() {
    $currentPath = $_SERVER['REQUEST_URI'];
    $scriptName = $_SERVER['SCRIPT_NAME'];
    
    // Get the directory of the current script
    $scriptDir = dirname($scriptName);
    
    // If we're in the main directory, use relative paths
    if (strpos($scriptDir, '/Blush-d-main') !== false || basename($scriptDir) === 'Blush-d-main') {
        return '';
    }
    
    // Count directory levels to go back to root
    $levels = substr_count($scriptDir, '/') - substr_count('/Blush-d-main', '/');
    if ($levels > 0) {
        return str_repeat('../', $levels);
    }
    
    return '';
}

$userData = getUserData();
$basePath = getBasePath();
?>
<header class="main-navigation">
    <nav class="nav-container">
        <!-- Company Brand -->
        <div class="brand-section">
            <a href="<?php echo $basePath; ?>index.php" class="brand-link">
                <h1 class="company-name">BLUSH-D</h1>
            </a>
        </div>

        <!-- Auth Section -->
        <div class="auth-section">
            <?php if ($userData): ?>
                <!-- Logged in user menu -->
                <div class="user-menu">
                    <div class="user-info">
                        <span class="user-greeting">Hi, <?php echo htmlspecialchars($userData['first_name']); ?>!</span>
                        <span class="user-role"><?php echo ucfirst(strtolower($userData['role'])); ?></span>
                    </div>
                    
                    <div class="user-dropdown">
                        <button class="user-dropdown-btn" id="userDropdownBtn">
                            <div class="user-avatar">
                                <?php echo strtoupper(substr($userData['first_name'], 0, 1)); ?>
                            </div>
                            <span class="dropdown-arrow">▼</span>
                        </button>
                        
                        <div class="dropdown-menu" id="userDropdownMenu">
                            <?php 
                            $dashboardUrl = '';
                            switch (strtoupper($userData['role'])) {
                                case 'CUSTOMER':
                                    $dashboardUrl = $basePath . 'pages/customer/dashboard/dashboard.php';
                                    break;
                                case 'MANAGER':
                                    $dashboardUrl = $basePath . 'pages/manager/dashboard/dashboard.php';
                                    break;
                                case 'ADMIN':
                                    $dashboardUrl = $basePath . 'pages/admin/dashboard.php';
                                    break;
                            }
                            ?>
                            <a href="<?php echo $dashboardUrl; ?>" class="dropdown-item">
                                <span class="dropdown-icon">🏠</span>
                                Dashboard
                            </a>
                            <a href="<?php echo $basePath; ?>pages/customer/profile/profile.php" class="dropdown-item">
                                <span class="dropdown-icon">👤</span>
                                Profile
                            </a>
                            <?php if (strtoupper($userData['role']) === 'CUSTOMER'): ?>
                                <a href="<?php echo $basePath; ?>pages/customer/cart/view_cart.php" class="dropdown-item">
                                    <span class="dropdown-icon">🛒</span>
                                    Cart
                                </a>
                                <a href="<?php echo $basePath; ?>pages/customer/orders/orders.php" class="dropdown-item">
                                    <span class="dropdown-icon">📦</span>
                                    Orders
                                </a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                            <button class="dropdown-item logout-btn" id="logoutBtn">
                                <span class="dropdown-icon">🚪</span>
                                Logout
                            </button>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Guest user buttons -->
                <div class="auth-buttons">
                    <a href="<?php echo $basePath; ?>pages/auth/login/login.php" class="btn-login">Login</a>
                    <a href="<?php echo $basePath; ?>pages/auth/register/register.php" class="btn-register">Register</a>
                </div>
            <?php endif; ?>
        </div>
    </nav>
</header>

<script src="<?php echo $basePath; ?>components/navigation/navigation.js"></script>