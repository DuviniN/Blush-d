<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard - Beauty Hub</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../../components/footer/footer.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="logo">
                <h2>BLUSH-D</h2>
                <p>Manager Dashboard</p>
            </div>
            
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="#" class="nav-link active" onclick="showSection('dashboard')">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="showSection('profile')">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="showSection('reports')">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reports</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="showModal('addProductModal')">
                        <i class="fas fa-plus-circle"></i>
                        <span>Add Product</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="showModal('addStockModal')">
                        <i class="fas fa-boxes"></i>
                        <span>Add Stock</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="window.location.href='../../../index.php'">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            

            <!-- Dashboard Section -->
            <div id="dashboard-section" class="section">
                <header class="header">
                     <div class="welcome">
                         <h1>Welcome back, Duvini!</h1>
                         <p>Here's what's happening with your store today</p>
                     </div>
                </header>
                <div class="dashboard-grid">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h3 class="card-title">Total Products</h3>
                                <p class="card-subtitle">All active products</p>
                            </div>
                            <div class="card-icon">
                                <i class="fas fa-box"></i>
                            </div>
                        </div>
                        <div class="stats-number">0</div>
                        <div class="stats-trend">
                            <i class="fas fa-arrow-up"></i> +2 this month
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h3 class="card-title">Low Stock Items</h3>
                                <p class="card-subtitle">Items below 20 units</p>
                            </div>
                            <div class="card-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                        <div class="stats-number">2</div>
                        <div class="stats-trend" style="color: #ef4444;">
                            <i class="fas fa-arrow-down"></i> Needs attention
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h3 class="card-title">Total Orders</h3>
                                <p class="card-subtitle">Orders this month</p>
                            </div>
                            <div class="card-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                        <div class="stats-number">4</div>
                        <div class="stats-trend">
                            <i class="fas fa-arrow-up"></i> +25% from last month
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h3 class="card-title">Revenue</h3>
                                <p class="card-subtitle">Total sales this month</p>
                            </div>
                            <div class="card-icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                        <div class="stats-number">$197</div>
                        <div class="stats-trend">
                            <i class="fas fa-arrow-up"></i> +15% from last month
                        </div>
                    </div>
                </div>

                <!-- Popular Products by Category -->
                <div class="popular-products-section">
                    <div class="popular-products-header">
                        <h3 class="popular-products-title">
                            <i class="fas fa-star"></i>
                            Popular Products by Category
                        </h3>
                        <p class="popular-products-subtitle">Best selling products in each category</p>
                    </div>
                    <div id="popular-products-list" class="popular-products-grid">
                        <!-- Sample data for design preview -->
                        <div class="popular-product-card">
                            <div class="popular-product-header">
                                <div class="popular-product-category">Skincare</div>
                                <div class="popular-product-rank">#1</div>
                            </div>
                            <div class="popular-product-image">
                                <i class="fas fa-spa"></i>
                            </div>
                            <div class="popular-product-info">
                                <div class="popular-product-name">Moisturizing Cream</div>
                                <div class="popular-product-stats">
                                    <div class="popular-product-revenue">$1,299.80</div>
                                    <div class="popular-product-sold">45 sold</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="popular-product-card">
                            <div class="popular-product-header">
                                <div class="popular-product-category">Makeup</div>
                                <div class="popular-product-rank">#1</div>
                            </div>
                            <div class="popular-product-image">
                                <i class="fas fa-palette"></i>
                            </div>
                            <div class="popular-product-info">
                                <div class="popular-product-name">Foundation Liquid</div>
                                <div class="popular-product-stats">
                                    <div class="popular-product-revenue">$955.00</div>
                                    <div class="popular-product-sold">32 sold</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="popular-product-card">
                            <div class="popular-product-header">
                                <div class="popular-product-category">Fragrances</div>
                                <div class="popular-product-rank">#1</div>
                            </div>
                            <div class="popular-product-image">
                                <i class="fas fa-wind"></i>
                            </div>
                            <div class="popular-product-info">
                                <div class="popular-product-name">Eau de Parfum</div>
                                <div class="popular-product-stats">
                                    <div class="popular-product-revenue">$749.97</div>
                                    <div class="popular-product-sold">18 sold</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="popular-product-card">
                            <div class="popular-product-header">
                                <div class="popular-product-category">Haircare</div>
                                <div class="popular-product-rank">#1</div>
                            </div>
                            <div class="popular-product-image">
                                <i class="fas fa-cut"></i>
                            </div>
                            <div class="popular-product-info">
                                <div class="popular-product-name">Argan Oil Shampoo</div>
                                <div class="popular-product-stats">
                                    <div class="popular-product-revenue">$502.00</div>
                                    <div class="popular-product-sold">28 sold</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h3 class="card-title">Quick Actions</h3>
                    <p class="card-subtitle">Manage your inventory and products</p>
                    <div style="margin-top: 20px; display: flex; gap: 15px; flex-wrap: wrap;">
                        <button class="btn btn-primary" onclick="console.log('Add Product clicked'); showModal('addProductModal')">
                            <i class="fas fa-plus"></i>
                            Add New Product
                        </button>
                        <button class="btn btn-outline" onclick="console.log('Update Stock clicked'); showModal('addStockModal')">
                            <i class="fas fa-boxes"></i>
                            Update Stock
                        </button>
                        <button class="btn btn-outline" onclick="console.log('View Reports clicked'); showSection('reports')">
                            <i class="fas fa-chart-bar"></i>
                            View Reports
                        </button>
                    </div>
                </div>
            </div>

            <!--Profile -->
            <!-- Profile Section -->
            <div id="profile-section" class="section" style="display: none;">
                <div class="profile-container">
                    <!-- Profile Hero Section -->
                    <div class="profile-hero">
                        <div class="profile-hero-content">
                            <div class="profile-left-section">
                                <div class="profile-avatar-wrapper">
                                    <div class="profile-avatar-large">
                                        <img src="https://images.unsplash.com/photo-1494790108755-2616b612b586?w=150&h=150&fit=crop&crop=face" 
                                             alt="Profile" class="profile-image" id="profileImage" style="display: block;">
                                        <div class="profile-initials" id="profileInitials" style="display: none;">DW</div>
                                    </div>
                                    <button class="profile-camera-btn" onclick="document.getElementById('profileImageInput').click()">
                                        <i class="fas fa-camera"></i>
                                    </button>
                                    <input type="file" id="profileImageInput" accept="image/*" style="display: none;">
                                </div>
                                <div class="profile-info">
                                    <h1 class="profile-name-display">Duvini Weerasinghe</h1>
                                    <p class="profile-title-display">Store Manager</p>
                                    
                                    <p class="profile-quote">"Dedicated to delivering exceptional beauty experiences"</p>
                                </div>
                            </div>
                            
                            
                        </div>
                    </div>

                    <!-- Profile Navigation Tabs -->
                    <div class="profile-tabs">
                        <button class="profile-tab" data-tab="details" onclick="switchProfileTab('details')">
                            <i class="fas fa-user-edit"></i>
                            Details
                        </button>
                        <button class="profile-tab" data-tab="activity" onclick="switchProfileTab('activity')">
                            <i class="fas fa-history"></i>
                            Activity
                        </button>
                        <button class="profile-tab" data-tab="settings" onclick="switchProfileTab('settings')">
                            <i class="fas fa-cog"></i>
                            Settings
                        </button>
                    </div>

                    <!-- Profile Content -->
                    <div class="profile-content">
                        
                        <!-- Details Tab -->
                        <div id="details-tab" class="profile-tab-content">
                            <div class="details-grid">
                                <!-- Personal Information -->
                                <div class="profile-modern-card">
                                    <div class="profile-card-header">
                                        <h3><i class="fas fa-user"></i> Personal Information</h3>
                                        <button class="profile-btn-secondary" onclick="enableEdit('personal')">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    </div>
                                    <div class="profile-form-grid">
                                        <div class="profile-form-group">
                                            <label class="profile-form-label">
                                                <i class="fas fa-user"></i> Full Name
                                            </label>
                                            <input type="text" class="profile-form-input" value="Duvini Weerasinghe" readonly id="fullName">
                                        </div>
                                        <div class="profile-form-group">
                                            <label class="profile-form-label">
                                                <i class="fas fa-envelope"></i> Email Address
                                            </label>
                                            <input type="email" class="profile-form-input" value="duvini@beautyhub.com" readonly id="email">
                                        </div>
                                        <div class="profile-form-group">
                                            <label class="profile-form-label">
                                                <i class="fas fa-phone"></i> Phone Number
                                            </label>
                                            <input type="tel" class="profile-form-input" value="+1 (555) 123-4567" readonly id="phone">
                                        </div>
                                        <div class="profile-form-group">
                                            <label class="profile-form-label">
                                                <i class="fas fa-calendar"></i> Date of Birth
                                            </label>
                                            <input type="date" class="profile-form-input" value="1995-06-15" readonly id="birthDate">
                                        </div>
                                        <div class="profile-form-group">
                                            <label class="profile-form-label">
                                                <i class="fas fa-map-marker-alt"></i> Address
                                            </label>
                                            <input type="text" class="profile-form-input" value="123 Beauty Street, New York, NY 10001" readonly id="address">
                                        </div>
                                        <div class="profile-form-group">
                                            <label class="profile-form-label">
                                                <i class="fas fa-id-card"></i> Emergency Contact
                                            </label>
                                            <input type="text" class="profile-form-input" value="+1 (555) 987-6543" readonly id="emergencyContact">
                                        </div>
                                    </div>
                                    <div class="profile-actions" id="personalActions" style="display: none;">
                                        <button class="profile-btn-primary" onclick="saveChanges('personal')">
                                            <i class="fas fa-save"></i> Save Changes
                                        </button>
                                        <button class="profile-btn-secondary" onclick="cancelEdit('personal')">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
                                    </div>
                                </div>

                                <!-- Work Information -->
                                <div class="profile-modern-card">
                                    <div class="profile-card-header">
                                        <h3><i class="fas fa-briefcase"></i> Work Information</h3>
                                        <button class="profile-btn-secondary" onclick="enableEdit('work')">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    </div>
                                    <div class="profile-form-grid">
                                        <div class="profile-form-group">
                                            <label class="profile-form-label">
                                                <i class="fas fa-user-tie"></i> Position
                                            </label>
                                            <input type="text" class="profile-form-input" value="Store Manager" readonly id="position">
                                        </div>
                                        <div class="profile-form-group">
                                            <label class="profile-form-label">
                                                <i class="fas fa-building"></i> Department
                                            </label>
                                            <input type="text" class="profile-form-input" value="Operations" readonly id="department">
                                        </div>
                                        <div class="profile-form-group">
                                            <label class="profile-form-label">
                                                <i class="fas fa-id-badge"></i> Employee ID
                                            </label>
                                            <input type="text" class="profile-form-input" value="BH2022001" readonly id="employeeId">
                                        </div>
                                        <div class="profile-form-group">
                                            <label class="profile-form-label">
                                                <i class="fas fa-calendar-alt"></i> Start Date
                                            </label>
                                            <input type="date" class="profile-form-input" value="2022-03-01" readonly id="startDate">
                                        </div>
                                        <div class="profile-form-group">
                                            <label class="profile-form-label">
                                                <i class="fas fa-clock"></i> Work Hours
                                            </label>
                                            <input type="text" class="profile-form-input" value="9:00 AM - 6:00 PM" readonly id="workHours">
                                        </div>
                                        <div class="profile-form-group">
                                            <label class="profile-form-label">
                                                <i class="fas fa-user-friends"></i> Reports To
                                            </label>
                                            <input type="text" class="profile-form-input" value="Sarah Johnson - Regional Manager" readonly id="reportsTo">
                                        </div>
                                    </div>
                                    <div class="profile-actions" id="workActions" style="display: none;">
                                        <button class="profile-btn-primary" onclick="saveChanges('work')">
                                            <i class="fas fa-save"></i> Save Changes
                                        </button>
                                        <button class="profile-btn-secondary" onclick="cancelEdit('work')">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Tab -->
                        <div id="activity-tab" class="profile-tab-content">
                            <div class="profile-modern-card">
                                <div class="profile-card-header">
                                    <h3><i class="fas fa-history"></i> Activity History</h3>
                                    <div class="activity-filters">
                                        <select class="profile-form-input" style="width: auto;">
                                            <option>All Activities</option>
                                            <option>Products</option>
                                            <option>Orders</option>
                                            <option>Reports</option>
                                            <option>Settings</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="profile-activity-timeline">
                                    <div class="profile-activity-item">
                                        <div class="profile-activity-icon success">
                                            <i class="fas fa-plus-circle"></i>
                                        </div>
                                        <div class="profile-activity-content">
                                            <div class="profile-activity-text">Added new product "Vitamin C Serum" to inventory</div>
                                            <div class="profile-activity-time">2 hours ago</div>
                                        </div>
                                    </div>
                                    <div class="profile-activity-item">
                                        <div class="profile-activity-icon warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </div>
                                        <div class="profile-activity-content">
                                            <div class="profile-activity-text">Low stock alert triggered for "Foundation Cream"</div>
                                            <div class="profile-activity-time">4 hours ago</div>
                                        </div>
                                    </div>
                                    <div class="profile-activity-item">
                                        <div class="profile-activity-icon info">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                        <div class="profile-activity-content">
                                            <div class="profile-activity-text">Generated and exported monthly sales report</div>
                                            <div class="profile-activity-time">1 day ago</div>
                                        </div>
                                    </div>
                                    <div class="profile-activity-item">
                                        <div class="profile-activity-icon success">
                                            <i class="fas fa-shopping-cart"></i>
                                        </div>
                                        <div class="profile-activity-content">
                                            <div class="profile-activity-text">Processed order #1248 for customer Alice Johnson</div>
                                            <div class="profile-activity-time">2 days ago</div>
                                        </div>
                                    </div>
                                    <div class="profile-activity-item">
                                        <div class="profile-activity-icon info">
                                            <i class="fas fa-boxes"></i>
                                        </div>
                                        <div class="profile-activity-content">
                                            <div class="profile-activity-text">Updated stock levels for 5 products</div>
                                            <div class="profile-activity-time">3 days ago</div>
                                        </div>
                                    </div>
                                    <div class="profile-activity-item">
                                        <div class="profile-activity-icon success">
                                            <i class="fas fa-user-plus"></i>
                                        </div>
                                        <div class="profile-activity-content">
                                            <div class="profile-activity-text">Added new customer account for Bob Smith</div>
                                            <div class="profile-activity-time">4 days ago</div>
                                        </div>
                                    </div>
                                    <div class="profile-activity-item">
                                        <div class="profile-activity-icon info">
                                            <i class="fas fa-cog"></i>
                                        </div>
                                        <div class="profile-activity-content">
                                            <div class="profile-activity-text">Updated profile information and preferences</div>
                                            <div class="profile-activity-time">1 week ago</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Settings Tab -->
                        <div id="settings-tab" class="profile-tab-content">
                            <div class="profile-settings-grid">
                                <!-- Security Settings -->
                                <div class="profile-modern-card">
                                    <div class="profile-card-header">
                                        <h3><i class="fas fa-shield-alt"></i> Security Settings</h3>
                                    </div>
                                    <div class="settings-list">
                                        <div class="profile-setting-item">
                                            <div class="profile-setting-info">
                                                <h4>Change Password</h4>
                                                <p>Last changed 30 days ago • Use a strong password to protect your account</p>
                                            </div>
                                            <button class="profile-btn-secondary" onclick="showModal('changePasswordModal')">
                                                <i class="fas fa-key"></i> Change
                                            </button>
                                        </div>
                                        <div class="profile-setting-item">
                                            <div class="profile-setting-info">
                                                <h4>Two-Factor Authentication</h4>
                                                <p>Add an extra layer of security to your account</p>
                                            </div>
                                            <label class="profile-toggle-switch">
                                                <input type="checkbox">
                                                <span class="profile-toggle-slider"></span>
                                            </label>
                                        </div>
                                        <div class="profile-setting-item">
                                            <div class="profile-setting-info">
                                                <h4>Login Notifications</h4>
                                                <p>Get notified when someone logs into your account</p>
                                            </div>
                                            <label class="profile-toggle-switch">
                                                <input type="checkbox" checked>
                                                <span class="profile-toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Notification Preferences -->
                                <div class="profile-modern-card">
                                    <div class="profile-card-header">
                                        <h3><i class="fas fa-bell"></i> Notifications</h3>
                                    </div>
                                    <div class="settings-list">
                                        <div class="profile-setting-item">
                                            <div class="profile-setting-info">
                                                <h4>Email Notifications</h4>
                                                <p>Receive updates and alerts via email</p>
                                            </div>
                                            <label class="profile-toggle-switch">
                                                <input type="checkbox" checked>
                                                <span class="profile-toggle-slider"></span>
                                            </label>
                                        </div>
                                        <div class="profile-setting-item">
                                            <div class="profile-setting-info">
                                                <h4>Low Stock Alerts</h4>
                                                <p>Get notified when products are running low</p>
                                            </div>
                                            <label class="profile-toggle-switch">
                                                <input type="checkbox" checked>
                                                <span class="profile-toggle-slider"></span>
                                            </label>
                                        </div>
                                        <div class="profile-setting-item">
                                            <div class="profile-setting-info">
                                                <h4>Order Notifications</h4>
                                                <p>Receive alerts for new orders and updates</p>
                                            </div>
                                            <label class="profile-toggle-switch">
                                                <input type="checkbox" checked>
                                                <span class="profile-toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Display Preferences -->
                                <div class="profile-modern-card">
                                    <div class="profile-card-header">
                                        <h3><i class="fas fa-palette"></i> Display</h3>
                                    </div>
                                    <div class="settings-list">
                                        <div class="profile-setting-item">
                                            <div class="profile-setting-info">
                                                <h4>Dark Mode</h4>
                                                <p>Switch to a darker theme for better viewing</p>
                                            </div>
                                            <label class="profile-toggle-switch">
                                                <input type="checkbox">
                                                <span class="profile-toggle-slider"></span>
                                            </label>
                                        </div>
                                        <div class="profile-setting-item">
                                            <div class="profile-setting-info">
                                                <h4>Compact View</h4>
                                                <p>Show more information in less space</p>
                                            </div>
                                            <label class="profile-toggle-switch">
                                                <input type="checkbox">
                                                <span class="profile-toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Account Actions -->
                                <div class="profile-modern-card">
                                    <div class="profile-card-header">
                                        <h3><i class="fas fa-user-cog"></i> Account</h3>
                                    </div>
                                    <div class="settings-list">
                                        <div class="profile-setting-item">
                                            <div class="profile-setting-info">
                                                <h4>Export Data</h4>
                                                <p>Download a copy of your account data</p>
                                            </div>
                                            <button class="profile-btn-secondary">
                                                <i class="fas fa-download"></i> Export
                                            </button>
                                        </div>
                                        <div class="profile-setting-item">
                                            <div class="profile-setting-info">
                                                <h4>Account Backup</h4>
                                                <p>Create a backup of your profile settings</p>
                                            </div>
                                            <button class="profile-btn-secondary">
                                                <i class="fas fa-cloud-upload-alt"></i> Backup
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Reports Section -->
            <div id="reports-section" class="section" style="display: none;">
                <div class="reports-container">
                    <!-- Reports Header -->
                    <div class="reports-header">
                        <div class="reports-title-section">
                            <h2 class="reports-title">
                                <i class="fas fa-chart-bar"></i>
                                Inventory Reports
                            </h2>
                            <p class="reports-subtitle">Manage and monitor your product inventory</p>
                        </div>
                        <div class="reports-actions">
                            <button class="btn-export" onclick="exportReport()">
                                <i class="fas fa-download"></i>
                                Export Report
                            </button>
                        </div>
                    </div>

                    <!-- Filter Section -->
                    <div class="filter-section">
                        <div class="filter-container">
                            <label for="categorySelect" class="filter-label">
                                <i class="fas fa-filter"></i>
                                Filter by Category
                            </label>
                            <select id="categorySelect" class="filter-select">
                                <option value="all">All Categories</option>
                                <option value="Skincare">Skincare</option>
                                <option value="Haircare">Haircare</option>
                                <option value="Makeup">Makeup</option>
                                <option value="Tools">Tools</option>
                            </select>
                        </div>
                    </div>

                    <!-- Inventory Table -->
                    <div class="modern-table-wrapper">
                        <div class="table-header-section">
                            <h3 class="table-title">
                                <i class="fas fa-boxes"></i>
                                Product Inventory
                            </h3>
                            <div class="table-stats">
                                <span class="stat-item">
                                    <i class="fas fa-cube"></i>
                                    <span id="totalProducts">0</span> Products
                                </span>
                                <span class="stat-item warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span id="lowStockCount">0</span> Low Stock
                                </span>
                            </div>
                        </div>
                        
                        <div class="modern-table-container">
                            <table class="modern-table">
                                <thead class="table-head">
                                    <tr>
                                        <th class="th-product">Product Name</th>
                                        <th class="th-category">Category</th>
                                        <th class="th-price">Price</th>
                                        <th class="th-stock">Stock</th>
                                        <th class="th-status">Status</th>
                                        <th class="th-actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="inventoryTableBody" class="table-body">
                                    <!-- Inventory rows will be dynamically inserted by Dashboard.js -->
                                    <tr class="loading-state">
                                        <td colspan="6" class="loading-cell">
                                            <div class="loading-content">
                                                <div class="loading-spinner">
                                                    <i class="fas fa-spinner fa-spin"></i>
                                                </div>
                                                <span class="loading-text">Loading inventory data...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Product Modal -->
    <div id="addProductModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Add New Product</h2>
                <button class="close-btn" onclick="closeModal('addProductModal')">&times;</button>
            </div>
            <form>
                <div class="form-group">
                    <label class="form-label">Product Name</label>
                    <input type="text" class="form-input" placeholder="Enter product name">
                </div>
                <div class="form-group">
                    <label class="form-label">Product Image</label>
                    <input type="file" class="form-input" accept="image/*" id="productImageInput">
                    <div class="image-preview" id="productImagePreview" style="display: none;">
                        <img id="previewImg" src="" alt="Product Preview" style="max-width: 100px; max-height: 100px; margin-top: 8px; border-radius: 4px; border: 1px solid #E75480;">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select class="form-select">
                        <option value="">Select category</option>
                        <option value="1">Skincare</option>
                        <option value="2">Makeup</option>
                        <option value="3">Tools</option>
                        <option value="4">Haircare</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-input" rows="3" placeholder="Enter product description"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Short Description</label>
                    <textarea class="form-input" rows="3" placeholder="Enter product short-description"></textarea>
                </div>
                 <div class="form-group">
                    <label class="form-label">Main Ingredients</label>
                    <textarea class="form-input" rows="3" placeholder="Enter main ingredients"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Price ($)</label>
                    <input type="number" class="form-input" step="0.01" placeholder="0.00">
                </div>
                <div class="form-group">
                    <label class="form-label">Initial Stock</label>
                    <input type="number" class="form-input" placeholder="0">
                </div>
                <div style="display: flex; gap: 15px; margin-top: 30px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i class="fas fa-plus"></i>
                        Add Product
                    </button>
                    <button type="button" class="btn btn-outline" onclick="closeModal('addProductModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Stock Modal -->
    <div id="addStockModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Update Stock</h2>
                <button class="close-btn" onclick="closeModal('addStockModal')">&times;</button>
            </div>
            <form>
                <div class="form-group">
                    <label class="form-label">Select Product</label>
                    <select class="form-select">
                        <option value="">Choose product</option>
                        <option value="1">Moisturizing Cream (Current: 50)</option>
                        <option value="2">Foundation (Current: 100)</option>
                        <option value="3">Perfume (Current: 30)</option>
                        <option value="4">Shampoo (Current: 80)</option>
                        <option value="5">Body Lotion (Current: 15) - Low Stock</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Add Stock Quantity</label>
                    <input type="number" class="form-input" placeholder="Enter quantity to add">
                </div>
                <div style="display: flex; gap: 15px; margin-top: 30px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i class="fas fa-boxes"></i>
                        Update Stock
                    </button>
                    <button type="button" class="btn btn-outline" onclick="closeModal('addStockModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div id="changePasswordModal" class="modal">
        <div class="modal-content change-password-modal">
            <div class="modal-header">
                <h2 class="modal-title">
                    <i class="fas fa-shield-alt"></i>
                    Change Password
                </h2>
                <button class="close-btn" onclick="closeModal('changePasswordModal')">&times;</button>
            </div>
            <div class="password-security-info">
                <div class="security-tips">
                    <h4><i class="fas fa-info-circle"></i> Password Requirements</h4>
                    <ul>
                        <li id="length-req" class="requirement">At least 8 characters long</li>
                        <li id="uppercase-req" class="requirement">One uppercase letter (A-Z)</li>
                        <li id="lowercase-req" class="requirement">One lowercase letter (a-z)</li>
                        <li id="number-req" class="requirement">One number (0-9)</li>
                        <li id="special-req" class="requirement">One special character (!@#$%^&*)</li>
                    </ul>
                </div>
            </div>
            <form id="changePasswordForm" onsubmit="handlePasswordChange(event)">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-lock"></i>
                        Current Password
                    </label>
                    <div class="password-input-wrapper">
                        <input type="password" id="currentPassword" class="form-input" placeholder="Enter your current password" required>
                        <button type="button" class="password-toggle" onclick="togglePasswordVisibility('currentPassword', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-feedback" id="currentPasswordFeedback"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-key"></i>
                        New Password
                    </label>
                    <div class="password-input-wrapper">
                        <input type="password" id="newPassword" class="form-input" placeholder="Enter your new password" required onkeyup="validatePassword()">
                        <button type="button" class="password-toggle" onclick="togglePasswordVisibility('newPassword', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-strength" id="passwordStrength">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <span class="strength-text" id="strengthText">Password strength</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-check-circle"></i>
                        Confirm New Password
                    </label>
                    <div class="password-input-wrapper">
                        <input type="password" id="confirmPassword" class="form-input" placeholder="Confirm your new password" required onkeyup="validatePasswordMatch()">
                        <button type="button" class="password-toggle" onclick="togglePasswordVisibility('confirmPassword', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-feedback" id="confirmPasswordFeedback"></div>
                </div>
                
                <div class="password-change-actions">
                    <button type="submit" class="btn btn-primary password-submit-btn" id="submitPasswordBtn" disabled>
                        <i class="fas fa-shield-alt"></i>
                        <span class="btn-text">Update Password</span>
                        <div class="btn-loading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i>
                            Updating...
                        </div>
                    </button>
                    <button type="button" class="btn btn-outline" onclick="closeModal('changePasswordModal')">
                        <i class="fas fa-times"></i>
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

            <!-- Footer -->
            <footer class="footer">
                <div class="footer-content">
                    &copy; 2025 Beauty Hub. All rights reserved.
                </div>
            </footer>
        </main>
    </div>

    <script src="Dashboard.js?v=<?php echo time(); ?>"></script>
    <script src="PasswordManager.js?v=<?php echo time(); ?>"></script>
    <script src="Profile.js?v=<?php echo time(); ?>"></script>
   
</body>
</html>