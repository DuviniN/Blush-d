<!-- Profile Section Component -->
<div id="profile-section" class="section" style="display: none;">
    <div class="profile-container">
        <!-- Profile Hero Section -->
        <div class="profile-hero">
            <div class="profile-hero-content">
                <div class="profile-left-section">
                    <div class="profile-avatar-wrapper">
                        <div class="profile-avatar-large">
                            <div class="profile-initials" id="profileInitials">DW</div>
                            <img class="profile-image" id="profileImage" src="" alt="Profile" style="display: none;">
                        </div>
                        <button class="profile-camera-btn" onclick="document.getElementById('profileImageInput').click()">
                            <i class="fas fa-camera"></i>
                        </button>
                        <input type="file" id="profileImageInput" accept="image/*" style="display: none;">
                    </div>
                    <div class="profile-info">
                        <h1 class="profile-name-display">Duvini Weerasinghe</h1>
                        <p class="profile-title-display">Store Manager</p>
                        <div class="profile-badges">
                            <span class="profile-badge verified">
                                <i class="fas fa-check-circle"></i>
                                Verified
                            </span>
                        </div>
                        <p class="profile-quote">"Dedicated to delivering exceptional beauty experiences"</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Navigation Tabs -->
        <div class="profile-tabs">
            <button class="profile-tab active" data-tab="details" onclick="switchProfileTab('details')">
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
            <div id="details-tab" class="profile-tab-content active">
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
                                <input type="text" id="fullName" class="profile-form-input" value="Duvini Weerasinghe" readonly>
                            </div>
                            <div class="profile-form-group">
                                <label class="profile-form-label">
                                    <i class="fas fa-envelope"></i> Email
                                </label>
                                <input type="email" id="email" class="profile-form-input" value="duvini@beautyHub.com" readonly>
                            </div>
                            <div class="profile-form-group">
                                <label class="profile-form-label">
                                    <i class="fas fa-phone"></i> Phone
                                </label>
                                <input type="tel" id="phone" class="profile-form-input" value="+1 (555) 123-4567" readonly>
                            </div>
                            <div class="profile-form-group">
                                <label class="profile-form-label">
                                    <i class="fas fa-calendar"></i> Birth Date
                                </label>
                                <input type="date" id="birthDate" class="profile-form-input" value="1995-03-15" readonly>
                            </div>
                        </div>
                        <div class="profile-actions" id="personalActions" style="display: none;">
                            <button class="profile-btn profile-btn-primary" onclick="saveChanges('personal')">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <button class="profile-btn profile-btn-secondary" onclick="cancelEdit('personal')">
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
                                <input type="text" id="position" class="profile-form-input" value="Store Manager" readonly>
                            </div>
                            <div class="profile-form-group">
                                <label class="profile-form-label">
                                    <i class="fas fa-building"></i> Department
                                </label>
                                <input type="text" id="department" class="profile-form-input" value="Operations" readonly>
                            </div>
                            <div class="profile-form-group">
                                <label class="profile-form-label">
                                    <i class="fas fa-id-card"></i> Employee ID
                                </label>
                                <input type="text" id="employeeId" class="profile-form-input" value="EMP001" readonly>
                            </div>
                            <div class="profile-form-group">
                                <label class="profile-form-label">
                                    <i class="fas fa-calendar-plus"></i> Start Date
                                </label>
                                <input type="date" id="startDate" class="profile-form-input" value="2023-01-15" readonly>
                            </div>
                        </div>
                        <div class="profile-actions" id="workActions" style="display: none;">
                            <button class="profile-btn profile-btn-primary" onclick="saveChanges('work')">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <button class="profile-btn profile-btn-secondary" onclick="cancelEdit('work')">
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
                                <option>Last 30 days</option>
                                <option>Last 7 days</option>
                                <option>Today</option>
                            </select>
                        </div>
                    </div>
                    <div class="profile-activity-timeline">
                        <div class="profile-activity-item">
                            <div class="profile-activity-icon success">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="profile-activity-content">
                                <div class="profile-activity-text">Added new product "Vitamin C Serum"</div>
                                <div class="profile-activity-time">2 hours ago</div>
                            </div>
                        </div>
                        <div class="profile-activity-item">
                            <div class="profile-activity-icon warning">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="profile-activity-content">
                                <div class="profile-activity-text">Updated stock for "Moisturizing Cream"</div>
                                <div class="profile-activity-time">5 hours ago</div>
                            </div>
                        </div>
                        <div class="profile-activity-item">
                            <div class="profile-activity-icon info">
                                <i class="fas fa-download"></i>
                            </div>
                            <div class="profile-activity-content">
                                <div class="profile-activity-text">Exported inventory report</div>
                                <div class="profile-activity-time">1 day ago</div>
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
                                    <p>Update your account password for security</p>
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
                                    <p>Receive updates via email</p>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
